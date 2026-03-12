<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $academies = \App\Models\Academy::all();
        $activeTab = $request->query('academy', $academies->first()->id ?? 0);
        $statusFilter = $request->query('status', 'all');

        // Get criteria for active academy
        $criteria = \App\Models\InterviewCriterion::where('academy_id', $activeTab)->get();

        // Get students accepted for interviews for active academy
        $students = \App\Models\User::where('role', 'student')
            ->whereHas('enrollments', function ($q) use ($activeTab) {
                $q->whereHas('cohort', function ($q2) use ($activeTab) {
                    $q2->where('academy_id', $activeTab);
                });
            })
            ->where(function($query) use ($statusFilter) {
                if ($statusFilter === 'pending') {
                    $query->where('filtration_status', 'Accepted for Interview')
                          ->whereDoesntHave('enrollments.interviewEvaluation');
                } elseif ($statusFilter === 'enrolled') {
                    $query->whereHas('enrollments', function($q) {
                        $q->where('status', 'enrolled');
                    });
                } elseif ($statusFilter === 'rejected') {
                    $query->where('filtration_status', 'Rejected')
                          ->orWhereHas('enrollments', function($q) {
                              $q->where('status', 'rejected');
                          });
                } else {
                    $query->where('filtration_status', 'Accepted for Interview')
                          ->orWhere('filtration_status', 'Rejected')
                          ->orWhereHas('enrollments', function($q) {
                              $q->whereIn('status', ['enrolled', 'rejected']);
                          });
                }
            })
            ->with([
                'profile',
                'enrollments' => function ($q) use ($activeTab) {
                    $q->whereHas('cohort', function ($q2) use ($activeTab) {
                        $q2->where('academy_id', $activeTab);
                    });
                },
                'enrollments.cohort',
                'enrollments.interviewEvaluation.admin'
            ])
            ->get();

        return view('admin.interviews.index', compact('academies', 'activeTab', 'statusFilter', 'students', 'criteria'));
    }

    public function manageCriteria(\Illuminate\Http\Request $request, \App\Models\Academy $academy)
    {
        $request->validate([
            'criteria' => 'array',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.weight' => 'required|integer|min:1|max:100',
        ]);

        // Delete old criteria and recreate to easily handle edits/removals
        $academy->interviewCriteria()->delete();

        if ($request->has('criteria')) {
            foreach ($request->criteria as $criterion) {
                \App\Models\InterviewCriterion::create([
                    'academy_id' => $academy->id,
                    'name' => $criterion['name'],
                    'weight' => $criterion['weight'],
                ]);
            }
        }

        return redirect()->route('admin.interviews.index', ['academy' => $academy->id])
            ->with('success', 'Interview assessment checklist updated successfully.');
    }

    public function evaluate(\Illuminate\Http\Request $request, \App\Models\Enrollment $enrollment)
    {
        // Require each criterion ID as a key in the scores array, value must be numeric
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,accept,reject'
        ]);

        $totalScore = array_sum($request->scores);

        \App\Models\InterviewEvaluation::updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                'scores' => $request->scores,
                'total_score' => $totalScore,
                'notes' => $request->notes,
            ]
        );

        // Auto-change status based on the admin's button click
        if ($request->action === 'accept') {
            $enrollment->update(['status' => 'enrolled']);

            \App\Models\Activity::log([
                'user_id' => $enrollment->user_id,
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                'type' => 'admin_action',
                'action' => 'Interview passed & Enrolled',
                'title' => "Student passed interview for {$enrollment->cohort->name}",
                'description' => "Score: {$totalScore}. Student status changed to enrolled.",
            ]);

        } elseif ($request->action === 'reject') {
            $enrollment->update(['status' => 'rejected']);

            \App\Models\Activity::log([
                'user_id' => $enrollment->user_id,
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                'type' => 'admin_action',
                'action' => 'Interview failed',
                'title' => "Student failed interview for {$enrollment->cohort->name}",
                'description' => "Score: {$totalScore}. Student status changed to rejected.",
            ]);
        }

        return back()->with('success', 'Interview evaluation saved successfully.');
    }
}
