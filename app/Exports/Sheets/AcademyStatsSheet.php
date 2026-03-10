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

        // 1. Overview
        $rows[] = ['--- OVERVIEW ---', ''];
        $academyName = $this->academyId ? (Academy::find($this->academyId)->name ?? 'Unknown') : 'All Academies';
        $rows[] = ['Academy', $academyName];

        $baseQuery = User::where('role', 'student');
        if ($this->academyId) {
            $baseQuery->whereHas('enrollments.cohort', function ($q) {
                $q->where('academy_id', $this->academyId);
            });
        }
        $totalStudents = $baseQuery->count();
        $rows[] = ['Total Registered Students', $totalStudents];
        $rows[] = ['', ''];

        // 2. Gender Demographics
        $rows[] = ['--- GENDER BREAKDOWN ---', ''];
        $userIds = $baseQuery->pluck('id')->toArray();
        $genderStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('gender')
            ->selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        foreach ($genderStats as $gender => $count) {
            $rows[] = [ucfirst($gender), $count];
        }
        $rows[] = ['', ''];

        // 3. Education Breakdown
        $rows[] = ['--- EDUCATION BREAKDOWN ---', ''];
        $eduStats = \App\Models\Profile::whereIn('user_id', $userIds)
            ->whereNotNull('education_level')
            ->selectRaw('education_level, count(*) as count')
            ->groupBy('education_level')
            ->pluck('count', 'education_level');

        foreach ($eduStats as $edu => $count) {
            $rows[] = [ucfirst($edu), $count];
        }
        $rows[] = ['', ''];

        // 4. Cohort Registration Statuses
        $rows[] = ['--- REGISTRATION STATUSES ---', ''];
        $enrollQuery = Enrollment::whereIn('user_id', $userIds);
        if ($this->academyId) {
            $enrollQuery->whereHas('cohort', function ($q) {
                $q->where('academy_id', $this->academyId);
            });
        }
        $statusStats = $enrollQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        foreach ($statusStats as $status => $count) {
            $rows[] = [ucfirst($status), $count];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Metric',
            'Value'
        ];
    }

    public function title(): string
    {
        return 'Statistics & Insights';
    }

    public function styles(Worksheet $sheet)
    {
        // Auto-size Metric column
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        return [
            // Dark header with orange coloring
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF27C00'],
                ],
            ],
            // Section Headers (Bold row)
            'A2:A100' => [
                'font' => ['bold' => true],
            ],
        ];
    }
}
