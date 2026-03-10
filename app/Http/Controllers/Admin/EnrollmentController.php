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

        $enrollments = $query->latest()->paginate(15);
        return view('admin.enrollments.index', compact('enrollments'));
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
