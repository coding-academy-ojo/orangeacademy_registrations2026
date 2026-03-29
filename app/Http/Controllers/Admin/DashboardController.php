<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Enrollment, AssessmentSubmission, Document, Activity};

class DashboardController extends Controller
{
    public function exportExcel(\Illuminate\Http\Request $request)
    {
        $academyId = $request->query('academy');
        $filename = 'students_export_' . date('Y-m-d_H-i') . '.xlsx';

        if ($academyId) {
            $academy = \App\Models\Academy::find($academyId);
            if ($academy) {
                $filename = 'export_' . \Illuminate\Support\Str::slug($academy->name) . '_' . date('Y-m-d_A') . '.xlsx';
            }
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AcademyExport($academyId), $filename);
    }

    public function exportPdf(\Illuminate\Http\Request $request)
    {
        $academyId = $request->query('academy');

        if ($academyId) {
            $academy = \App\Models\Academy::findOrFail($academyId);
            $academies = collect([$academy]);
        } else {
            $academies = \App\Models\Academy::all();
        }

        $academyReports = [];

        foreach ($academies as $academy) {
            $enrollmentsInAcademy = Enrollment::whereHas('cohort', function ($q) use ($academy) {
                $q->where('academy_id', $academy->id);
            })->get();

            $userIdsInAcademy = $enrollmentsInAcademy->pluck('user_id')->unique()->toArray();
            $totalStudents = count($userIdsInAcademy);

            $genderStats = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('gender')
                ->selectRaw('gender, count(*) as count')
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();

            $educationStats = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('education_level')
                ->selectRaw('education_level, count(*) as count')
                ->groupBy('education_level')
                ->pluck('count', 'education_level')
                ->toArray();

            $universityStats = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('university')
                ->where('university', '!=', '')
                ->selectRaw('university, count(*) as count')
                ->groupBy('university')
                ->pluck('count', 'university')
                ->toArray();

            $neighborhoodStats = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('neighborhood')
                ->where('neighborhood', '!=', '')
                ->selectRaw('neighborhood, count(*) as count')
                ->groupBy('neighborhood')
                ->pluck('count', 'neighborhood')
                ->toArray();

            $cohortStats = Enrollment::whereHas('cohort', function ($q) use ($academy) {
                $q->where('academy_id', $academy->id);
            })
                ->join('cohorts', 'enrollments.cohort_id', '=', 'cohorts.id')
                ->selectRaw('cohorts.name as cohort_name, enrollments.status, count(*) as count')
                ->groupBy('cohorts.name', 'enrollments.status')
                ->get();

            $cohortData = [];
            foreach ($cohortStats as $data) {
                $cName = $data->cohort_name;
                $status = $data->status;
                $count = $data->count;

                if (!isset($cohortData[$cName])) {
                    $cohortData[$cName] = [
                        'total' => 0,
                        'applied' => 0,
                        'accepted' => 0,
                        'rejected' => 0,
                        'enrolled' => 0,
                        'graduated' => 0,
                        'dropped' => 0
                    ];
                }
                $cohortData[$cName][$status] = $count;
                $cohortData[$cName]['total'] += $count;
            }

            $studentsList = User::whereIn('id', $userIdsInAcademy)
                ->with(['profile', 'enrollments.cohort'])
                ->get()
                ->map(function ($user) {
                    $enrollment = $user->enrollments->first();
                    return [
                        'name' => $user->profile ? $user->profile->first_name_en . ' ' . $user->profile->last_name_en : $user->email,
                        'email' => $user->email,
                        'gender' => $user->profile->gender ?? 'N/A',
                        'education' => $user->profile->education_level ?? 'N/A',
                        'university' => $user->profile->university ?? 'N/A',
                        'cohort' => $enrollment ? $enrollment->cohort->name : 'N/A',
                        'status' => $enrollment ? $enrollment->status : 'N/A',
                    ];
                });

            $academyReports[] = [
                'academy' => $academy,
                'totalStudents' => $totalStudents,
                'genderStats' => $genderStats,
                'educationStats' => $educationStats,
                'universityStats' => $universityStats,
                'neighborhoodStats' => $neighborhoodStats,
                'cohortData' => $cohortData,
                'studentsList' => $studentsList,
            ];
        }

        return view('admin.reports.academy-pdf', [
            'academyReports' => $academyReports,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
            'isSingleAcademy' => (bool) $academyId
        ]);
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $cohortId = $request->query('cohort_id');
        $cohort = $cohortId ? \App\Models\Cohort::find($cohortId) : null;
        
        $allCohorts = \App\Models\Cohort::with('academy')->orderBy('name', 'desc')->get();

        // Base query for filtering by cohort
        $cohortFilter = $cohort ? function ($q) use ($cohort) {
            $q->where('cohort_id', $cohort->id);
        } : null;

        $stats = [
            'total_users' => $cohort 
                ? Enrollment::where('cohort_id', $cohort->id)->distinct()->count('user_id')
                : User::count(),
            'pending_enrollments' => Enrollment::when($cohort, fn($q) => $q->where('cohort_id', $cohort->id))->where('status', 'applied')->count(),
            'accepted_enrollments' => Enrollment::when($cohort, fn($q) => $q->where('cohort_id', $cohort->id))->where('status', 'accepted')->count(),
            'pending_assessments' => $cohort 
                ? AssessmentSubmission::whereIn('user_id', $userIdsInCohort ?? [])->where('status', 'submitted')->count()
                : AssessmentSubmission::where('status', 'submitted')->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_enrollments' => Enrollment::with(['user.profile', 'cohort'])->when($cohort, fn($q) => $q->where('cohort_id', $cohort->id))->latest()->take(5)->get(),
        ];

        // Chart Data
        $userIdsInCohort = $cohort ? Enrollment::where('cohort_id', $cohort->id)->pluck('user_id')->toArray() : null;

        $genderStats = \App\Models\Profile::selectRaw('gender, count(*) as count')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        $enrollmentStats = Enrollment::when($cohort, fn($q) => $q->where('cohort_id', $cohort->id))
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        if ($cohort) {
            $academyStats = [$cohort->academy->name ?? 'N/A' => count($userIdsInCohort)];
        } else {
            $academyStats = Enrollment::join('cohorts', 'enrollments.cohort_id', '=', 'cohorts.id')
                ->join('academies', 'cohorts.academy_id', '=', 'academies.id')
                ->selectRaw('academies.name as academy, count(enrollments.id) as count')
                ->groupBy('academies.name')
                ->pluck('count', 'academy')
                ->toArray();
        }

        // Education Level Stats
        $educationStats = \App\Models\Profile::selectRaw('education_level, count(*) as count')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))
            ->whereNotNull('education_level')
            ->groupBy('education_level')
            ->pluck('count', 'education_level')
            ->toArray();

        // Country Stats
        $countryStats = \App\Models\Profile::selectRaw('country, count(*) as count')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))
            ->whereNotNull('country')
            ->groupBy('country')
            ->pluck('count', 'country')
            ->toArray();

        // City Stats
        $cityStats = \App\Models\Profile::selectRaw('city, count(*) as count')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))
            ->whereNotNull('city')
            ->groupBy('city')
            ->pluck('count', 'city')
            ->toArray();

        // Field of Study Stats
        $fieldOfStudyStats = \App\Models\Profile::selectRaw('field_of_study, count(*) as count')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))
            ->whereNotNull('field_of_study')
            ->groupBy('field_of_study')
            ->pluck('count', 'field_of_study')
            ->toArray();

        // University Stats
        $universityStats = \App\Models\Profile::selectRaw('university, count(*) as count')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))
            ->whereNotNull('university')
            ->where('university', '!=', '')
            ->groupBy('university')
            ->pluck('count', 'university')
            ->toArray();

        // Age Analysis (18-35)
        $ageStatsQuery = \App\Models\Profile::whereNotNull('date_of_birth')
            ->when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort));
        $ageStats = $ageStatsQuery->selectRaw('
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) as "18-20",
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) as "21-25",
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) as "26-30",
                SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) as "31-35"
            ')
            ->first()
            ->toArray();

        // Graduated Chart Data (University Graduates enrolled in Academies)
        $graduatedStatsQuery = Enrollment::join('cohorts', 'enrollments.cohort_id', '=', 'cohorts.id')
            ->join('academies', 'cohorts.academy_id', '=', 'academies.id')
            ->join('profiles', 'enrollments.user_id', '=', 'profiles.user_id')
            ->where('profiles.is_graduated', 1)
            ->whereNotNull('profiles.graduation_year');
        
        if ($cohort) {
            $graduatedStatsQuery->where('enrollments.cohort_id', $cohort->id);
        }
        
        $graduatedStats = $graduatedStatsQuery
            ->selectRaw('academies.name as academy, count(*) as count')
            ->groupBy('academies.name')
            ->pluck('count', 'academy')
            ->toArray();

        $totalGraduated = array_sum($graduatedStats);
        $totalAge1835 = array_sum($ageStats);

        // Missing Data Stats
        $missingDataStats = [
            'no_education' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where(fn($q) => $q->whereNull('education_level')->orWhere('education_level', ''))->count(),
            'no_country' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where(fn($q) => $q->whereNull('country')->orWhere('country', ''))->count(),
            'no_city' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where(fn($q) => $q->whereNull('city')->orWhere('city', ''))->count(),
            'no_field_of_study' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where(fn($q) => $q->whereNull('field_of_study')->orWhere('field_of_study', ''))->count(),
            'no_nationality' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where(fn($q) => $q->whereNull('nationality')->orWhere('nationality', ''))->count(),
        ];
        $academyLocations = \App\Models\Academy::whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->pluck('location')
            ->toArray();

        // Clean and normalize the location names
        $targetCities = array_values(array_unique(array_filter(array_map(function ($city) {
            return ucfirst(strtolower(trim($city)));
        }, $academyLocations))));
        $cityDemographicsQuery = \App\Models\Profile::whereIn('city', collect($targetCities)->map(fn($c) => strtolower($c))->toArray())
            ->orWhereIn('city', $targetCities);
        
        if ($cohort && $userIdsInCohort) {
            $cityDemographicsQuery->whereIn('user_id', $userIdsInCohort);
        }
        
        $cityDemographics = $cityDemographicsQuery
            ->selectRaw('city, count(*) as count')
            ->groupBy('city')
            ->pluck('count', 'city')
            ->toArray();

        // Normalize city keys to sentence case to avoid case mismatch
        $normalizedCityDemographics = [];
        foreach ($cityDemographics as $city => $count) {
            $normalizedCityDemographics[ucfirst(strtolower($city))] = $count;
        }

        // Detailed Academy Demographics
        $academyDetailedStats = [];
        $academies = \App\Models\Academy::all();

        foreach ($academies as $academy) {
            $academyName = $academy->name;

            $enrollmentQuery = \App\Models\Enrollment::whereHas('cohort', function ($q) use ($academy) {
                $q->where('academy_id', $academy->id);
            });
            if ($cohort) {
                $enrollmentQuery->where('cohort_id', $cohort->id);
            }
            $enrollmentsInAcademy = $enrollmentQuery->get();

            $userIdsInAcademy = $enrollmentsInAcademy->pluck('user_id')->unique()->toArray();
            $totalRegistrations = count($userIdsInAcademy);

            $genderStatsArray = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('gender')
                ->selectRaw('gender, count(*) as count')
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();

            $educationStatsArray = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('education_level')
                ->selectRaw('education_level, count(*) as count')
                ->groupBy('education_level')
                ->pluck('count', 'education_level')
                ->toArray();

            $ageStatsRaw = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('date_of_birth')
                ->selectRaw('
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 20 THEN 1 ELSE 0 END) as "18-20",
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) as "21-25",
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) as "26-30",
                    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 35 THEN 1 ELSE 0 END) as "31-35"
                ')
                ->first();

            $ageStatsArray = [
                '18-20' => (int) ($ageStatsRaw['18-20'] ?? 0),
                '21-25' => (int) ($ageStatsRaw['21-25'] ?? 0),
                '26-30' => (int) ($ageStatsRaw['26-30'] ?? 0),
                '31-35' => (int) ($ageStatsRaw['31-35'] ?? 0),
            ];

            $graduatedCount = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->where('is_graduated', 1)
                ->whereNotNull('graduation_year')
                ->count();

            // University stats for this academy
            $universityStatsArray = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('university')
                ->selectRaw('university, count(*) as count')
                ->groupBy('university')
                ->orderByDesc('count')
                ->limit(5)
                ->pluck('count', 'university')
                ->toArray();

            // Neighborhood stats for this academy
            $neighborhoodStatsArray = \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)
                ->whereNotNull('neighborhood')
                ->selectRaw('neighborhood, count(*) as count')
                ->groupBy('neighborhood')
                ->orderByDesc('count')
                ->limit(5)
                ->pluck('count', 'neighborhood')
                ->toArray();

            $cohortStatsArray = [];
            if ($totalRegistrations > 0) {
                $cohortsQuery = \App\Models\Enrollment::whereHas('cohort', function ($q) use ($academy) {
                    $q->where('academy_id', $academy->id);
                });
                if ($cohort) {
                    $cohortsQuery->where('cohort_id', $cohort->id);
                }
                $cohortsData = $cohortsQuery
                    ->join('cohorts', 'enrollments.cohort_id', '=', 'cohorts.id')
                    ->selectRaw('cohorts.name as cohort_name, enrollments.status, count(*) as count')
                    ->groupBy('cohorts.name', 'enrollments.status')
                    ->get();

                foreach ($cohortsData as $data) {
                    $cName = $data->cohort_name;
                    $status = $data->status;
                    $count = $data->count;

                    if (!isset($cohortStatsArray[$cName])) {
                        $cohortStatsArray[$cName] = [
                            'total' => 0,
                            'applied' => 0,
                            'accepted' => 0,
                            'rejected' => 0,
                            'enrolled' => 0,
                            'graduated' => 0,
                            'dropped' => 0,
                        ];
                    }
                    if (isset($cohortStatsArray[$cName][$status])) {
                        $cohortStatsArray[$cName][$status] = $count;
                    }
                    $cohortStatsArray[$cName]['total'] += $count;
                }
            }
            $academyDetailedStats[$academyName] = [
                'total' => $totalRegistrations,
                'gender' => $genderStatsArray,
                'education' => $educationStatsArray,
                'age' => $ageStatsArray,
                'graduated_count' => $graduatedCount,
                'universities' => $universityStatsArray,
                'neighborhoods' => $neighborhoodStatsArray,
                'cohorts' => $cohortStatsArray,
                'phone_stats' => [
                    'orange' => \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)->where('phone', 'like', '077%')->count(),
                    'zain' => \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)->where('phone', 'like', '079%')->count(),
                    'umniah' => \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)->where('phone', 'like', '078%')->count(),
                ],
                'health_stats' => [
                    'accessibility' => \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)->where('has_accessibility_needs', 1)->count(),
                    'illness' => \App\Models\Profile::whereIn('user_id', $userIdsInAcademy)->where('has_illness', 1)->count(),
                ],
            ];
        }

        // Academy map data for interactive map (location -> academies in that location)
        $academyMapData = [];
        foreach ($academies as $academy) {
            $loc = ucfirst(strtolower(trim($academy->location ?? '')));
            if (!$loc)
                continue;
            if (!isset($academyMapData[$loc])) {
                $academyMapData[$loc] = [];
            }
            $academyMapData[$loc][] = [
                'name' => $academy->name,
                'location' => $academy->location,
                'cohorts_count' => $academy->cohorts()->count(),
                'students_count' => $academyDetailedStats[$academy->name]['total'] ?? 0,
                'cohort_names' => $academy->cohorts()->pluck('name')->toArray(),
            ];
        }

        // ── Activity & User Progress Summary ──────────────────────────────
        $recentActivities = Activity::with(['user.profile', 'admin'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $activityTypeCounts = Activity::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $todayActivityCount = Activity::whereDate('created_at', today())->count();

        // Phone company stats
        $phoneStats = [
            'orange' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where('phone', 'like', '077%')->count(),
            'zain' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where('phone', 'like', '079%')->count(),
            'umniah' => \App\Models\Profile::when($cohort, fn($q) => $q->whereIn('user_id', $userIdsInCohort))->where('phone', 'like', '078%')->count(),
        ];

        // Phone stats per academy
        $academyPhoneStats = [];
        foreach ($academies as $academy) {
            $enrollmentQuery = \App\Models\Enrollment::whereHas('cohort', function ($q) use ($academy) {
                $q->where('academy_id', $academy->id);
            });
            if ($cohort) {
                $enrollmentQuery->where('cohort_id', $cohort->id);
            }
            $userIds = $enrollmentQuery->pluck('user_id')->unique();

            $academyPhoneStats[$academy->name] = [
                'orange' => \App\Models\Profile::whereIn('user_id', $userIds)->where('phone', 'like', '077%')->count(),
                'zain' => \App\Models\Profile::whereIn('user_id', $userIds)->where('phone', 'like', '079%')->count(),
                'umniah' => \App\Models\Profile::whereIn('user_id', $userIds)->where('phone', 'like', '078%')->count(),
            ];
        }

        // Progress stats
        $progressCounts = ['not_started' => 0, 'in_progress' => 0, 'completed' => 0];
        $requiredDocCount = \App\Models\DocumentRequirement::where('is_required', true)->count();

        /** @var \App\Models\User[] $allStudentList */
        $allStudentList = User::with(['profile', 'enrollments', 'documents.documentRequirement', 'answers'])->get();

        foreach ($allStudentList as $student) {
            $completedCount = 0;
            if ($student->email_verified_at)
                $completedCount++;
            if ($student->profile && $student->profile->first_name_en)
                $completedCount++;
            $uploadedRequired = $student->documents->filter(fn($d) => optional($d->documentRequirement)->is_required)->count();
            if ($requiredDocCount > 0 && $uploadedRequired >= $requiredDocCount)
                $completedCount++;
            if ($student->enrollments->count() > 0)
                $completedCount++;
            if ($student->answers->count() > 0)
                $completedCount++;
            $firstEnrollment = $student->enrollments->first();
            if ($firstEnrollment && $firstEnrollment->status === 'applied')
                $completedCount++;

            $progress = round(($completedCount / 6) * 100);
            if ($progress === 0)
                $progressCounts['not_started']++;
            elseif ($progress >= 100)
                $progressCounts['completed']++;
            else
                $progressCounts['in_progress']++;
        }

        return view('admin.dashboard', array_merge($stats, compact('genderStats', 'enrollmentStats', 'academyStats', 'normalizedCityDemographics', 'academyDetailedStats', 'academyMapData', 'recentActivities', 'activityTypeCounts', 'todayActivityCount', 'progressCounts', 'educationStats', 'countryStats', 'cityStats', 'fieldOfStudyStats', 'universityStats', 'missingDataStats', 'ageStats', 'graduatedStats', 'totalGraduated', 'totalAge1835', 'phoneStats', 'academyPhoneStats', 'allCohorts', 'cohortId', 'cohort')));
    }
}
