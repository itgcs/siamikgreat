<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GradeExport implements WithMultipleSheets
{
    protected $grades;

    public function __construct($grades)
    {
        $this->grades = $grades;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->grades as $grade) {
            $sheets[] = new GradeSheetExport($grade);
        }

        return $sheets;
    }
}
