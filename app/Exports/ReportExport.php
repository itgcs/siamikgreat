<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{

    protected $year;
    
    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];

        for ($month = 1; $month <= 12; $month++) {
            array_push($sheets, new InvoicePerMonthSheet($this->year, $month));
            // $sheets[] = new InvoicePerMonthSheet($this->year, $month);
        }

        return $sheets;
    }
}
