<?php

namespace App\Exports\Sheets;

use App\Models\User;
use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AcademyDocumentsSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    public function query()
    {
        $query = Document::with(['user.profile', 'documentRequirement']);

        if ($this->academyId) {
            $query->whereHas('user.enrollments.cohort', function ($q) {
                $q->where('academy_id', $this->academyId);
            });
        }

        return $query;
    }

    public function title(): string
    {
        return 'Documents Status';
    }

    public function headings(): array
    {
        return [
            'Student Email',
            'Student Name',
            'Document Name',
            'Status',
            'Uploaded At',
            'Verified At',
            'Rejection Reason'
        ];
    }

    public function map($document): array
    {
        return [
            $document->user->email ?? '-',
            $document->user->profile ? "{$document->user->profile->first_name_en} {$document->user->profile->last_name_en}" : '-',
            $document->documentRequirement->name ?? '-',
            $document->is_verified ? 'Verified' : ($document->rejection_reason ? 'Rejected' : 'Pending'),
            $document->created_at->format('Y-m-d H:i'),
            $document->is_verified ? $document->updated_at->format('Y-m-d H:i') : '-',
            $document->rejection_reason ?? '-'
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
