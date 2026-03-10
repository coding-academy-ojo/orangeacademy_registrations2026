<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\AcademyDataSheet;
use App\Exports\Sheets\AcademyStatsSheet;
use App\Exports\Sheets\AcademyDocumentsSheet;
use App\Exports\Sheets\AcademyAssessmentsSheet;

class DetailedAcademyExport implements WithMultipleSheets
{
    use Exportable;

    protected $academyId;
    protected $academyName;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
        if ($academyId) {
            $academy = \App\Models\Academy::find($academyId);
            $this->academyName = $academy ? $academy->name : 'Academy';
        } else {
            $this->academyName = 'All Academies';
        }
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AcademyDataSheet($this->academyId);
        $sheets[] = new AcademyStatsSheet($this->academyId);
        $sheets[] = new AcademyDocumentsSheet($this->academyId);
        $sheets[] = new AcademyAssessmentsSheet($this->academyId);

        return $sheets;
    }

    public function getFileName(): string
    {
        return 'report_' . \Illuminate\Support\Str::slug($this->academyName) . '_' . date('Y-m-d') . '.xlsx';
    }
}
