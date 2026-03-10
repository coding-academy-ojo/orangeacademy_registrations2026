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
        $users = User::with(['profile', 'enrollments.cohort.academy', 'documents'])
            ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $users->getCollection()->transform(function ($user) {
            $user->completed_steps = $this->getCompletedSteps($user);
            $user->registration_progress = $this->getRegistrationProgress($user);
            return $user;
        });

        return view('admin.activities.user-progress', compact('users'));
    }

    public function userDetail(User $user)
    {
        $user->load(['profile', 'enrollments.cohort.academy', 'documents.documentRequirement', 'answers.question', 'assessmentSubmissions.assessment']);
        
        $completedSteps = $this->getCompletedSteps($user);
        $registrationProgress = $this->getRegistrationProgress($user);
        
        $activities = Activity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.activities.user-detail', compact('user', 'completedSteps', 'registrationProgress', 'activities'));
    }

    private function getCompletedSteps($user): array
    {
        $steps = [];
        
        if ($user->email_verified_at) {
            $steps[] = 'email_verified';
        }
        
        if ($user->profile) {
            $profile = $user->profile;
            if ($profile->first_name_en && $profile->last_name_en && $profile->phone && 
                $profile->gender && $profile->date_of_birth && $profile->nationality) {
                $steps[] = 'profile';
            }
        }

        $requiredDocs = $user->documents()->whereHas('documentRequirement', function ($q) {
            $q->where('is_required', true);
        })->count();
        
        $requiredCount = \App\Models\DocumentRequirement::where('is_required', true)->count();
        
        if ($requiredDocs >= $requiredCount && $requiredCount > 0) {
            $steps[] = 'documents';
        }

        if ($user->enrollments()->exists()) {
            $steps[] = 'enrollment';
        }

        $hasAnswers = $user->answers()->exists();
        if ($hasAnswers) {
            $steps[] = 'questionnaire';
        }

        $enrollment = $user->enrollments()->first();
        if ($enrollment && $enrollment->status === 'applied') {
            $steps[] = 'submitted';
        }

        return $steps;
    }

    private function getRegistrationProgress($user): int
    {
        $completedSteps = $this->getCompletedSteps($user);
        $totalSteps = 7;
        
        return round((count($completedSteps) / $totalSteps) * 100);
    }

    public function missedData()
    {
        $users = User::with(['profile', 'documents', 'enrollments'])
            ->where('role', 'student')
            ->get()
            ->map(function ($user) {
                $user->completed_steps = $this->getCompletedSteps($user);
                $user->registration_progress = $this->getRegistrationProgress($user);
                return $user;
            })
            ->filter(function ($user) {
                return $user->registration_progress < 100;
            })
            ->sortByDesc('created_at');

        return view('admin.activities.missed-data', compact('users'));
    }

    public function show(Activity $activity)
    {
        $activity->load(['user.profile', 'admin']);
        return view('admin.activities.show', compact('activity'));
    }
}
