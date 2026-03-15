<?php

namespace App\Exports\Sheets;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Academy;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AcademyStatsSheet implements FromArray, WithTitle, WithHeadings, WithStyles
{
    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    public function array(): array
    {
        $rows = [];

        $baseQuery = User::where('role', 'student');
        if ($this->academyId) {
            $baseQuery->whereHas('enrollments.cohort', function ($q) {
                $q->where('academy_id', $this->academyId);
            });
        }
        
        $userIds = $baseQuery->pluck('id')->toArray();
        $totalStudents = count($userIds);
        $academyName = $this->academyId ? (Academy::find($this->academyId)->name ?? 'Unknown') : 'All Academies';

        $rows[] = ['📊 ORANGE CODING ACADEMY - ANALYTICS REPORT', '', ''];
        $rows[] = ['Academy:', $academyName, ''];
        $rows[] = ['Report Date:', date('Y-m-d H:i:s'), ''];
        $rows[] = ['', '', ''];

        $rows[] = ['📈 OVERVIEW', '', ''];
        $rows[] = ['Total Registered Students', $totalStudents, ''];
        $rows[] = ['', '', ''];

        $rows[] = ['👥 GENDER DEMOGRAPHICS', '', ''];
        
        $genderStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('gender')
            ->selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        $maleCount = $genderStats['male'] ?? 0;
        $femaleCount = $genderStats['female'] ?? 0;
        $totalGender = $maleCount + $femaleCount;
        
        $rows[] = ['Male', $maleCount, $totalGender > 0 ? round(($maleCount / $totalGender) * 100, 1) . '%' : '0%'];
        $rows[] = ['Female', $femaleCount, $totalGender > 0 ? round(($femaleCount / $totalGender) * 100, 1) . '%' : '0%'];
        $rows[] = ['', '', ''];

        $rows[] = ['📋 REGISTRATION STATUS', '', ''];
        
        $enrollQuery = Enrollment::whereIn('user_id', $userIds);
        if ($this->academyId) {
            $enrollQuery->whereHas('cohort', function ($q) {
                $q->where('academy_id', $this->academyId);
            });
        }
        
        $statusStats = $enrollQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $appliedCount = $statusStats['applied'] ?? 0;
        $pendingCount = $statusStats['pending'] ?? 0;
        $acceptedCount = $statusStats['accepted'] ?? 0;
        $enrolledCount = $statusStats['enrolled'] ?? 0;
        $rejectedCount = $statusStats['rejected'] ?? 0;
        $totalEnrolled = $appliedCount + $pendingCount + $acceptedCount + $enrolledCount + $rejectedCount;

        $rows[] = ['Applied', $appliedCount, $totalEnrolled > 0 ? round(($appliedCount / $totalEnrolled) * 100, 1) . '%' : '0%'];
        $rows[] = ['Pending Interview', $pendingCount, $totalEnrolled > 0 ? round(($pendingCount / $totalEnrolled) * 100, 1) . '%' : '0%'];
        $rows[] = ['Accepted for Interview', $acceptedCount, $totalEnrolled > 0 ? round(($acceptedCount / $totalEnrolled) * 100, 1) . '%' : '0%'];
        $rows[] = ['Enrolled', $enrolledCount, $totalEnrolled > 0 ? round(($enrolledCount / $totalEnrolled) * 100, 1) . '%' : '0%'];
        $rows[] = ['Rejected', $rejectedCount, $totalEnrolled > 0 ? round(($rejectedCount / $totalEnrolled) * 100, 1) . '%' : '0%'];
        $rows[] = ['', '', ''];

        $rows[] = ['🎓 EDUCATION BREAKDOWN', '', ''];
        
        $eduStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('education_level')
            ->selectRaw('education_level, count(*) as count')
            ->groupBy('education_level')
            ->pluck('count', 'education_level')
            ->toArray();

        $totalEdu = array_sum($eduStats);
        foreach ($eduStats as $edu => $count) {
            $rows[] = [ucfirst($edu), $count, $totalEdu > 0 ? round(($count / $totalEdu) * 100, 1) . '%' : '0%'];
        }
        $rows[] = ['', '', ''];

        $rows[] = ['🏛️ TOP UNIVERSITIES', '', ''];
        
        $uniStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('university')
            ->where('university', '!=', '')
            ->selectRaw('university, count(*) as count')
            ->groupBy('university')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        $totalUni = $uniStats->sum('count');
        $rank = 1;
        foreach ($uniStats as $uni) {
            $rows[] = ['#' . $rank . ' ' . $uni->university, $uni->count, $totalUni > 0 ? round(($uni->count / $totalUni) * 100, 1) . '%' : '0%'];
            $rank++;
        }
        $rows[] = ['', '', ''];

        $rows[] = ['🏘️ TOP NEIGHBORHOODS', '', ''];
        
        $nbStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('neighborhood')
            ->where('neighborhood', '!=', '')
            ->selectRaw('neighborhood, count(*) as count')
            ->groupBy('neighborhood')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        $totalNb = $nbStats->sum('count');
        $rank = 1;
        foreach ($nbStats as $nb) {
            $rows[] = ['#' . $rank . ' ' . $nb->neighborhood, $nb->count, $totalNb > 0 ? round(($nb->count / $totalNb) * 100, 1) . '%' : '0%'];
            $rank++;
        }
        $rows[] = ['', '', ''];

        $rows[] = ['🌆 TOP CITIES', '', ''];
        
        $cityStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->selectRaw('city, count(*) as count')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        $totalCity = $cityStats->sum('count');
        $rank = 1;
        foreach ($cityStats as $city) {
            $rows[] = ['#' . $rank . ' ' . $city->city, $city->count, $totalCity > 0 ? round(($city->count / $totalCity) * 100, 1) . '%' : '0%'];
            $rank++;
        }
        $rows[] = ['', '', ''];

        $rows[] = ['🎓 GRADUATION STATUS', '', ''];
        
        $graduatedCount = \App\Models\Profile::whereIn('user_id', $userIds)
            ->where('is_graduated', 1)
            ->count();
        
        $notGraduatedCount = $totalStudents - $graduatedCount;

        $rows[] = ['Graduated', $graduatedCount, $totalStudents > 0 ? round(($graduatedCount / $totalStudents) * 100, 1) . '%' : '0%'];
        $rows[] = ['Not Graduated', $notGraduatedCount, $totalStudents > 0 ? round(($notGraduatedCount / $totalStudents) * 100, 1) . '%' : '0%'];

        return $rows;
    }

    public function headings(): array
    {
        return ['Metric', 'Count', 'Percentage'];
    }

    public function title(): string
    {
        return '📊 Statistics & Insights';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:C1');
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF7900'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            'A' => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFF7900']],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF5F5F5'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'A' => ['width' => 40],
            'B' => ['width' => 15],
            'C' => ['width' => 15],
        ];
    }
}
