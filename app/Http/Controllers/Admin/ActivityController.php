<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['user.profile', 'admin'])->orderBy('created_at', 'desc');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('email', 'like', "%{$request->search}%");
                    });
            });
        }

        $activities = $query->paginate(30);
        $types = Activity::getRegistrationTypes();

        return view('admin.activities.index', compact('activities', 'types'));
    }

    public function userProgress(Request $request)
    {
        $requiredCount = \App\Models\DocumentRequirement::where('is_required', true)->count();

        $users = User::with(['profile', 'enrollments.cohort.academy'])
            ->withCount([
                'documents as required_docs_count' => function ($q) {
                    $q->whereHas('documentRequirement', fn($dq) => $dq->where('is_required', true));
                },
                'enrollments',
                'coursatCertificates as coursat_count',
                'answers'
            ])
            ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $users->getCollection()->transform(function ($user) use ($requiredCount) {
            $user->completed_steps = $this->getCompletedStepsOptimized($user, $requiredCount);
            $user->registration_progress = $this->getRegistrationProgress($user);
            return $user;
        });

        return view('admin.activities.user-progress', compact('users'));
    }

    public function userDetail(User $user)
    {
        $requiredCount = \App\Models\DocumentRequirement::where('is_required', true)->count();
        
        $user->load(['profile', 'enrollments.cohort.academy', 'documents.documentRequirement', 'coursatCertificates', 'answers.question', 'assessmentSubmissions.assessment']);
        $user->loadCount([
            'documents as required_docs_count' => function ($q) {
                $q->whereHas('documentRequirement', fn($dq) => $dq->where('is_required', true));
            },
            'enrollments',
            'coursatCertificates as coursat_count',
            'answers'
        ]);

        $completedSteps = $this->getCompletedStepsOptimized($user, $requiredCount);
        $registrationProgress = $this->getRegistrationProgress($user);

        $activities = Activity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.activities.user-detail', compact('user', 'completedSteps', 'registrationProgress', 'activities'));
    }

    private function getCompletedStepsOptimized($user, $requiredCount): array
    {
        $steps = [];

        if ($user->email_verified_at) {
            $steps[] = 'email_verified';
        }

        if ($user->profile) {
            $p = $user->profile;
            if ($p->first_name_en && $p->last_name_en && $p->phone && $p->gender && $p->date_of_birth && $p->nationality) {
                $steps[] = 'profile';
            }
        }

        // Use pre-loaded count
        if (isset($user->required_docs_count) ? $user->required_docs_count >= $requiredCount : $user->documents()->whereHas('documentRequirement', fn($q) => $q->where('is_required', true))->count() >= $requiredCount) {
            if ($requiredCount > 0) $steps[] = 'documents';
        }

        if (isset($user->enrollments_count) ? $user->enrollments_count > 0 : $user->enrollments()->exists()) {
            $steps[] = 'enrollment';
        }

        if (isset($user->coursat_count) ? $user->coursat_count >= 3 : $user->coursatCertificates()->count() >= 3) {
            $steps[] = 'coursat';
        }

        if (isset($user->answers_count) ? $user->answers_count > 0 : $user->answers()->exists()) {
            $steps[] = 'questionnaire';
        }

        $enrollment = $user->enrollments->first() ?? ($user->relationLoaded('enrollments') ? null : $user->enrollments()->first());
        if ($enrollment && $enrollment->status === 'applied') {
            $steps[] = 'submitted';
        }

        return $steps;
    }

    // Keep original for back-compat or unused routes, but mark as internal
    private function getCompletedSteps($user): array
    {
        $requiredCount = \App\Models\DocumentRequirement::where('is_required', true)->count();
        return $this->getCompletedStepsOptimized($user, $requiredCount);
    }

    private function getRegistrationProgress($user): int
    {
        $completedSteps = $user->completed_steps ?? $this->getCompletedSteps($user);
        $totalSteps = 7;

        return round((count($completedSteps) / $totalSteps) * 100);
    }

    public function missedData()
    {
        $requiredCount = \App\Models\DocumentRequirement::where('is_required', true)->count();

        $allUsers = User::with(['profile'])
            ->withCount([
                'documents as required_docs_count' => function ($q) {
                    $q->whereHas('documentRequirement', fn($dq) => $dq->where('is_required', true));
                },
                'enrollments',
                'coursatCertificates as coursat_count',
                'answers'
            ])
            ->where('role', 'student')
            ->get()
            ->map(function ($user) use ($requiredCount) {
                $user->completed_steps = $this->getCompletedStepsOptimized($user, $requiredCount);
                $user->registration_progress = $this->getRegistrationProgress($user);
                return $user;
            })
            ->filter(function ($user) {
                return $user->registration_progress < 100;
            })
            ->sortByDesc('created_at');

        // Calculate stats before pagination
        $stats = [
            'not_started' => $allUsers->filter(fn($u) => $u->registration_progress < 20)->count(),
            'in_progress' => $allUsers->filter(fn($u) => $u->registration_progress >= 20)->count(), // All here are < 100 because of the primary filter
            'total' => $allUsers->count(),
        ];

        // Manual pagination
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20;
        $currentItems = $allUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems, 
            $allUsers->count(), 
            $perPage, 
            $currentPage, 
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('admin.activities.missed-data', compact('users', 'stats'));
    }

    public function show(Activity $activity)
    {
        $activity->load(['user.profile', 'admin']);
        return view('admin.activities.show', compact('activity'));
    }
}
