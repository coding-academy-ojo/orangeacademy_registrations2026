<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InterviewAcceptedExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    public function query()
    {
        return \App\Models\User::where('role', 'student')
            ->whereHas('enrollments', function ($q) {
                if ($this->academyId && $this->academyId !== 'all') {
                    $q->whereHas('cohort', function ($q2) {
                        $q2->where('academy_id', $this->academyId);
                    });
                }
                $q->where('status', 'enrolled');
            })
            ->with(['profile', 'enrollments.cohort.academy', 'enrollments.interviewEvaluation']);
    }

    public function headings(): array
    {
        return [
            '#',
            'Full Name',
            'Email',
            'Phone',
            'ID Number',
            'Gender',
            'City',
            'Academy',
            'Cohort',
            'Interview Score',
            'Max Score',
            'Evaluation Notes',
            'Evaluated By',
            'Enrollment Date',
        ];
    }

    public function map($user): array
    {
        $enrollment = $user->enrollments->firstWhere('status', 'enrolled');
        $evaluation = $enrollment?->interviewEvaluation;
        $maxScore = $evaluation ? array_sum($evaluation->scores ?? []) : 0;

        return [
            $user->id,
            $user->profile->first_name_en . ' ' . $user->profile->last_name_en ?? 'N/A',
            $user->email,
            $user->profile->phone ?? 'N/A',
            $user->profile->id_number ?? 'N/A',
            $user->profile->gender ?? 'N/A',
            $user->profile->city ?? 'N/A',
            $enrollment?->cohort?->academy?->name ?? 'N/A',
            $enrollment?->cohort?->name ?? 'N/A',
            $evaluation?->total_score ?? 'N/A',
            $maxScore ?: 'N/A',
            $evaluation?->notes ?? 'N/A',
            $evaluation?->admin->name ?? 'N/A',
            $enrollment?->created_at?->format('Y-m-d') ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FF6B35'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'A1:N1' => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
        ];
    }
}
