<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AcademyDataSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    public function query()
    {
        $query = User::with(['profile', 'enrollments.cohort.academy', 'documents.documentRequirement', 'answers'])
            ->where('role', 'student');

        if ($this->academyId) {
            $query->whereHas('enrollments.cohort.academy', function ($q) {
                $q->where('id', $this->academyId);
            });
        }

        return $query;
    }

    public function title(): string
    {
        return 'Students Data';
    }

    public function headings(): array
    {
        return [
            'ID',
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
            'ID Number',
            'Gender',
            'Date of Birth',
            'Nationality',
            'Country',
            'City',
            'Neighborhood',
            'Education Level',
            'University',
            'Major',
            'Graduation Year',
            'Graduated',
            'Academy',
            'Cohort',
            'Enrollment Status',
            'Registration Progress',
            'Has Profile',
            'Documents Uploaded',
            'Questionnaire Done',
            'Registered At',
            'Last Updated'
        ];
    }

    public function map($user): array
    {
        $profile = $user->profile;
        $enrollment = $user->enrollments->first();

        $completedCount = 0;
        if ($user->email_verified_at)
            $completedCount++;
        if ($profile && $profile->first_name_en)
            $completedCount++;

        $requiredDocCount = \App\Models\DocumentRequirement::where('is_required', true)->count();
        $uploadedDocs = $user->documents->filter(fn($d) => optional($d->documentRequirement)->is_required)->count();
        if ($requiredDocCount > 0 && $uploadedDocs >= $requiredDocCount)
            $completedCount++;

        if ($user->enrollments->count() > 0)
            $completedCount++;
        if ($user->answers->count() > 0)
            $completedCount++;
        if ($enrollment && $enrollment->status === 'applied')
            $completedCount++;

        $progress = round(($completedCount / 6) * 100);

        return [
            $user->id,
            $profile->first_name_en ?? '-',
            $profile->second_name_en ?? '-',
            $profile->third_name_en ?? '-',
            $profile->last_name_en ?? '-',
            $profile->first_name_ar ?? '-',
            $profile->second_name_ar ?? '-',
            $profile->third_name_ar ?? '-',
            $profile->last_name_ar ?? '-',
            $user->email,
            $profile->phone ?? '-',
            $profile->id_number ?? '-',
            ucfirst($profile->gender ?? '-'),
            $profile->date_of_birth ?? '-',
            $profile->nationality ?? '-',
            $profile->country ?? '-',
            $profile->city ?? '-',
            $profile->neighborhood ?? '-',
            $profile->education_level ?? '-',
            $profile->university ?? '-',
            $profile->major ?? '-',
            $profile->graduation_year ?? '-',
            $profile && $profile->is_graduated ? 'Yes' : 'No',
            $enrollment->cohort->academy->name ?? '-',
            $enrollment->cohort->name ?? '-',
            ucfirst($enrollment->status ?? 'Not Enrolled'),
            "{$progress}%",
            $profile ? 'Yes' : 'No',
            $user->documents->count() . " / $requiredDocCount",
            $user->answers->count() > 0 ? 'Yes' : 'No',
            $user->created_at->format('Y-m-d H:i'),
            $user->updated_at->format('Y-m-d H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF27C00'], // Tech Academy Orange theme
                ],
            ],
        ];
    }
}
