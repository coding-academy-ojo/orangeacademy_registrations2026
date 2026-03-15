<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academy;
use App\Models\Activity;
use App\Models\Cohort;
use App\Models\Enrollment;
use App\Models\InterviewCriterion;
use App\Models\InterviewEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $academies = Academy::all();
        $activeTab = $request->query('academy', $academies->first()->id ?? 'all');
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search', '');

        if ($activeTab === 'all') {
            $criteria = collect([]);
            $totalPoints = 0;
        } else {
            $criteria = InterviewCriterion::where('academy_id', $activeTab)->get();
            $totalPoints = $criteria->sum('weight');
        }

        $baseQuery = User::where('role', 'student');

        // Search filter
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('first_name_en', 'like', "%{$search}%")
                            ->orWhere('last_name_en', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($statusFilter === 'pending') {
            if ($activeTab === 'all') {
                $students = (clone $baseQuery)
                    ->where('filtration_status', 'Accepted for Interview')
                    ->whereDoesntHave('enrollments')
                    ->with(['profile'])
                    ->get();
            } else {
                $students = (clone $baseQuery)
                    ->where('filtration_status', 'Accepted for Interview')
                    ->whereDoesntHave('enrollments', function ($q) use ($activeTab) {
                        $q->whereHas('cohort', function ($q2) use ($activeTab) {
                            $q2->where('academy_id', $activeTab);
                        });
                    })
                    ->with(['profile'])
                    ->get();
            }
        } elseif ($statusFilter === 'enrolled') {
            $students = (clone $baseQuery)
                ->whereHas('enrollments', function ($q) use ($activeTab) {
                    if ($activeTab !== 'all') {
                        $q->whereHas('cohort', function ($q2) use ($activeTab) {
                            $q2->where('academy_id', $activeTab);
                        });
                    }
                    $q->where('status', 'enrolled');
                })
                ->with([
                    'profile',
                    'enrollments' => function ($q) use ($activeTab) {
                        if ($activeTab !== 'all') {
                            $q->whereHas('cohort', function ($q2) use ($activeTab) {
                                $q2->where('academy_id', $activeTab);
                            });
                        }
                    },
                    'enrollments.cohort',
                    'enrollments.interviewEvaluation.admin'
                ])
                ->get();
        } elseif ($statusFilter === 'rejected') {
            $students = (clone $baseQuery)
                ->where(function ($q) use ($activeTab) {
                    if ($activeTab === 'all') {
                        $q->where('filtration_status', 'Rejected')
                            ->whereDoesntHave('enrollments')
                            ->orWhereHas('enrollments', function ($eq) {
                                $eq->where('status', 'rejected');
                            });
                    } else {
                        $q->where('filtration_status', 'Rejected')
                            ->whereDoesntHave('enrollments', function ($eq) use ($activeTab) {
                                $eq->whereHas('cohort', function ($q2) use ($activeTab) {
                                    $q2->where('academy_id', $activeTab);
                                });
                            })
                            ->orWhereHas('enrollments', function ($q) use ($activeTab) {
                                $q->whereHas('cohort', function ($q2) use ($activeTab) {
                                    $q2->where('academy_id', $activeTab);
                                })->where('status', 'rejected');
                            });
                    }
                })
                ->with([
                    'profile',
                    'enrollments' => function ($q) use ($activeTab) {
                        if ($activeTab !== 'all') {
                            $q->whereHas('cohort', function ($q2) use ($activeTab) {
                                $q2->where('academy_id', $activeTab);
                            });
                        }
                    },
                    'enrollments.cohort',
                    'enrollments.interviewEvaluation.admin'
                ])
                ->get();
        } else {
            if ($activeTab === 'all') {
                $students = (clone $baseQuery)
                    ->where('filtration_status', 'Accepted for Interview')
                    ->with([
                        'profile',
                        'enrollments.cohort',
                        'enrollments.interviewEvaluation.admin'
                    ])
                    ->get();
            } else {
                $students = (clone $baseQuery)
                    ->where(function ($q) use ($activeTab) {
                        $q->where('filtration_status', 'Accepted for Interview')
                            ->whereDoesntHave('enrollments')
                            ->orWhereHas('enrollments', function ($eq) use ($activeTab) {
                                $eq->whereHas('cohort', function ($q2) use ($activeTab) {
                                    $q2->where('academy_id', $activeTab);
                                });
                            });
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
            }
        }

        return view('admin.interviews.index', compact('academies', 'activeTab', 'statusFilter', 'students', 'criteria', 'totalPoints'));
    }

    public function manageCriteria(Request $request, Academy $academy)
    {
        $request->validate([
            'criteria' => 'array',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.weight' => 'required|integer|min:1|max:100',
        ]);

        $academy->interviewCriteria()->delete();

        if ($request->has('criteria')) {
            foreach ($request->criteria as $criterion) {
                InterviewCriterion::create([
                    'academy_id' => $academy->id,
                    'name' => $criterion['name'],
                    'weight' => $criterion['weight'],
                ]);
            }
        }

        return redirect()->route('admin.interviews.index', ['academy' => $academy->id])
            ->with('success', 'Interview assessment checklist updated successfully.');
    }

    public function evaluate(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,accept,reject'
        ]);

        $totalScore = array_sum($request->scores);

        InterviewEvaluation::updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'admin_id' => auth()->id(),
                'scores' => $request->scores,
                'total_score' => $totalScore,
                'notes' => $request->notes,
            ]
        );

        if ($request->action === 'accept') {
            $enrollment->update(['status' => 'enrolled']);

            Activity::log([
                'user_id' => $enrollment->user_id,
                'admin_id' => auth()->id(),
                'type' => 'admin_action',
                'action' => 'Interview passed & Enrolled',
                'title' => "Student passed interview for {$enrollment->cohort->name}",
                'description' => "Score: {$totalScore}. Student status changed to enrolled.",
            ]);
        } elseif ($request->action === 'reject') {
            $enrollment->update(['status' => 'rejected']);

            Activity::log([
                'user_id' => $enrollment->user_id,
                'admin_id' => auth()->id(),
                'type' => 'admin_action',
                'action' => 'Interview failed',
                'title' => "Student failed interview for {$enrollment->cohort->name}",
                'description' => "Score: {$totalScore}. Student status changed to rejected.",
            ]);
        }

        return back()->with('success', 'Interview evaluation saved successfully.');
    }

    public function evaluateWithoutEnrollment(Request $request, User $user)
    {
        $request->validate([
            'academy_id' => 'required|exists:academies,id',
            'cohort_id' => 'required|exists:cohorts,id',
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'action' => 'required|in:save,accept,reject'
        ]);

        $cohort = Cohort::findOrFail($request->cohort_id);
        $totalScore = array_sum($request->scores);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'cohort_id' => $request->cohort_id,
            'status' => 'pending',
        ]);

        InterviewEvaluation::create([
            'enrollment_id' => $enrollment->id,
            'admin_id' => auth()->id(),
            'scores' => $request->scores,
            'total_score' => $totalScore,
            'notes' => $request->notes,
        ]);

        if ($request->action === 'accept') {
            $enrollment->update(['status' => 'enrolled']);

            Activity::log([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'type' => 'admin_action',
                'action' => 'Interview passed & Enrolled',
                'title' => "Student passed interview for {$cohort->name}",
                'description' => "Score: {$totalScore}. Student status changed to enrolled.",
            ]);
        } elseif ($request->action === 'reject') {
            $enrollment->update(['status' => 'rejected']);

            Activity::log([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'type' => 'admin_action',
                'action' => 'Interview failed',
                'title' => "Student failed interview for {$cohort->name}",
                'description' => "Score: {$totalScore}. Student status changed to rejected.",
            ]);
        }

        return back()->with('success', 'Interview evaluation saved successfully.');
    }

    public function exportAccepted(Request $request)
    {
        $academyId = $request->query('academy', 'all');
        $academyName = 'All_Academies';
        
        if ($academyId && $academyId !== 'all') {
            $academy = Academy::find($academyId);
            if ($academy) {
                $academyName = $academy->name;
            }
        }

        $filename = 'Accepted_Students_' . preg_replace('/[^a-zA-Z0-9]/', '_', $academyName) . '_' . date('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\InterviewAcceptedExport($academyId),
            $filename
        );
    }
}
