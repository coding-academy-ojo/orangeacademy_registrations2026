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
            ->whereHas('enrollments', function ($q) {
                $q->where('status', 'enrolled');
                $q->whereHas('interviewEvaluation', function ($eq) {
                    $eq->whereNotNull('total_score');
                });
            })
            ->with(['profile', 'documents', 'enrollments.cohort.academy', 'enrollments.interviewEvaluation']);
    }

    public function headings(): array
    {
        return [
            '#',
            'ID Number',
            'First Name (EN)',
            'Second Name (EN)',
            'Third Name (EN)',
            'Last Name (EN)',
            'First Name (AR)',
            'Second Name (AR)',
            'Third Name (AR)',
            'Last Name (AR)',
            'Email',
            'Phone',
            'Gender',
            'City',
            'Academy',
            'Cohort',
            'Status',
            'Interview Score',
            'Max Score',
            'Evaluation Notes',
            'Evaluated By',
            'Enrollment Date',
            'Personal Image',
        ];
    }

    public function map($user): array
    {
        $enrollment = $user->enrollments->firstWhere('status', 'enrolled');
        $evaluation = $enrollment?->interviewEvaluation;
        $maxScore = $evaluation ? array_sum($evaluation->scores ?? []) : 0;

        $personalPhoto = $user->documents->filter(fn($d) => $d->documentRequirement && stripos($d->documentRequirement->name, 'Personal Photo') !== false)->first();
        $photoPath = $personalPhoto && $personalPhoto->file_path 
            ? 'storage/' . $personalPhoto->file_path 
            : 'N/A';

        return [
            $user->id,
            $user->profile->id_number ?? 'N/A',
            $user->profile->first_name_en ?? 'N/A',
            $user->profile->second_name_en ?? 'N/A',
            $user->profile->third_name_en ?? 'N/A',
            $user->profile->last_name_en ?? 'N/A',
            $user->profile->first_name_ar ?? 'N/A',
            $user->profile->second_name_ar ?? 'N/A',
            $user->profile->third_name_ar ?? 'N/A',
            $user->profile->last_name_ar ?? 'N/A',
            $user->email,
            $user->profile->phone ?? 'N/A',
            $user->profile->gender ?? 'N/A',
            $user->profile->city ?? 'N/A',
            $enrollment?->cohort?->academy?->name ?? 'N/A',
            $enrollment?->cohort?->name ?? 'N/A',
            'Accepted to Join',
            $evaluation?->total_score ?? 'N/A',
            $maxScore ?: 'N/A',
            $evaluation?->notes ?? 'N/A',
            $evaluation?->admin->name ?? 'N/A',
            $enrollment?->created_at?->format('Y-m-d') ?? 'N/A',
            $photoPath,
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
            'A1:W1' => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
        ];
    }
}
