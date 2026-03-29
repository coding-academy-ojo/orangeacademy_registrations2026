<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['user.profile', 'cohort.academy']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->academy_id) {
            $query->whereHas('cohort', function ($q) use ($request) {
                $q->where('academy_id', $request->academy_id);
            });
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user.profile', function ($pq) use ($search) {
                    $pq->where('first_name_en', 'like', "%{$search}%")
                      ->orWhere('last_name_en', 'like', "%{$search}%")
                      ->orWhere('first_name_ar', 'like', "%{$search}%")
                      ->orWhere('last_name_ar', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%"); // Added phone search
                })->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('email', 'like', "%{$search}%");
                });
            });
        }

        $enrollments = $query->latest()->paginate(25);
        $academies = \App\Models\Academy::all();
        
        return view('admin.enrollments.index', compact('enrollments', 'academies'));
    }

    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate(['status' => 'required|in:applied,accepted,rejected,enrolled,graduated,dropped']);
        $enrollment->update([
            'status' => $request->status,
            'enrolled_at' => $request->status === 'enrolled' ? now() : $enrollment->enrolled_at,
        ]);
        return back()->with('success', 'Status updated successfully.');
    }
}
