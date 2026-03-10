<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\AcademyDataSheet;
use App\Exports\Sheets\AcademyStatsSheet;

class AcademyExport implements WithMultipleSheets
{
    use Exportable;

    protected $academyId;

    public function __construct($academyId = null)
    {
        $this->academyId = $academyId;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AcademyDataSheet($this->academyId);
        $sheets[] = new AcademyStatsSheet($this->academyId);

        return $sheets;
    }
}
