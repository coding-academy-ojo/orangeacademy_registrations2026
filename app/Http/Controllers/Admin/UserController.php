<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Academy;
use App\Models\Cohort;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['profile', 'enrollments.cohort.academy', 'coursatCertificates', 'documents']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('first_name_en', 'like', "%{$search}%")
                            ->orWhere('last_name_en', 'like', "%{$search}%")
                            ->orWhere('first_name_ar', 'like', "%{$search}%")
                            ->orWhere('last_name_ar', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        if ($request->filtration_status) {
            $query->where('filtration_status', $request->filtration_status);
        }

        if ($request->gender) {
            $query->whereHas('profile', function ($pq) use ($request) {
                $pq->where('gender', $request->gender);
            });
        }

        if ($request->academy_id) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('enrollments.cohort.academy', function ($aq) use ($request) {
                    $aq->where('id', $request->academy_id);
                })
                ->orWhere(function ($q2) use ($request) {
                    // Also include users accepted for interview without enrollment
                    $q2->where('filtration_status', 'Accepted for Interview')
                        ->whereDoesntHave('enrollments');
                });
            });
        }

        if ($request->cohort_id) {
            $query->whereHas('enrollments', function ($eq) use ($request) {
                $eq->where('cohort_id', $request->cohort_id);
            });
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $academies = Academy::orderBy('name')->get();
        $cohorts = Cohort::orderBy('name')->get();

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users', 'academies', 'cohorts'));
    }

    public function show(User $user)
    {
        $user->load(['profile', 'documents', 'coursatCertificates', 'enrollments.cohort.academy', 'assessmentSubmissions.assessment', 'answers.question']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', ($user->is_active ? 'Activated' : 'Deactivated') . ' successfully.');
    }

    public function updateFiltration(Request $request, User $user)
    {
        $request->validate([
            'filtration_status' => 'nullable|string|in:Accepted for Interview,Rejected',
        ]);

        $user->update(['filtration_status' => $request->filtration_status]);

        return back()->with('success', 'Filtration status updated successfully.');
    }
}
