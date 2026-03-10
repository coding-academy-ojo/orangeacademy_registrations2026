<?php

namespace App\Exports\Sheets;

use App\Models\AssessmentSubmission;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AcademyAssessmentsSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    public function query()
    {
        $query = AssessmentSubmission::with(['user.profile', 'assessment']);

        if ($this->academyId) {
            $query->whereHas('user.enrollments.cohort', function ($q) {
                $q->where('academy_id', $this->academyId);
            });
        }

        return $query;
    }

    public function title(): string
    {
        return 'Assessments Results';
    }

    public function headings(): array
    {
        return [
            'Student Email',
            'Student Name',
            'Assessment Title',
            'Assessment Type',
            'Status',
            'Score',
            'Max Score',
            'Submitted At',
            'Graded At'
        ];
    }

    public function map($submission): array
    {
        return [
            $submission->user->email ?? '-',
            $submission->user->profile ? "{$submission->user->profile->first_name_en} {$submission->user->profile->last_name_en}" : '-',
            $submission->assessment->title ?? '-',
            $submission->assessment->type ?? '-',
            ucfirst($submission->status),
            $submission->score ?? '-',
            $submission->assessment->max_score ?? '-',
            $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i') : '-',
            $submission->graded_at ? $submission->graded_at->format('Y-m-d H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF27C00'],
                ],
            ],
        ];
    }
}
