<?php

namespace App\Exports;

use App\Http\Controllers\Excel\CapFeeExcelController;
use App\Http\Controllers\Excel\MonthFeeController;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithProperties;
use PDO;

class ReportExport implements WithMultipleSheets, WithProperties
{

    protected $from, $to;
    
    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function sheets(): array
    {
        $sheets = [];

        $dateFrom = explode('/', $this->from);
        $dateTo = explode('/', $this->to);

        $getMonthFrom = (int)$dateFrom[0];
        $getYearFrom = (int)$dateFrom[1];
        
        $getMonthTo = (int)$dateTo[0];
        $getYearTo = (int)$dateTo[1];

        $capFee = new CapFeeExcelController($getYearFrom);
        $capFee = $capFee->index();

        array_push($sheets, new InvoicePerMonthSheet(array_values($capFee->data), $getYearFrom, "Capital Fee", $capFee->student_id, $capFee->grade_id));

        for($year=$getYearFrom; $year<=$getYearTo; $year++){
            
            for ($month = $getMonthFrom; ($year == $getYearTo? $month<=$getMonthTo : $month <= 12); $month++) {
                    
                $monthFee = new MonthFeeController($getYearFrom);
                $indexMonth = $monthFee->index($month);
                $data = $indexMonth->data;
                $map_grade = $indexMonth->grade_id;

                array_push($sheets, new InvoicePerMonthSheet(array_values($data), $year, $month, [], $map_grade));

            }

            $getMonthFrom = 1;
        }
            
        // array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Package"));
        // array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Material Fee"));
        
        return $sheets;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Achmad Sofyan',
            'lastModifiedBy' => 'System',
            'title'          => 'Invoices Export',
            'description'    => 'Invoices Export',
            'subject'        => 'Invoices',
            'keywords'       => 'invoices,export,spreadsheet',
            'category'       => 'Invoices',
            'manager'        => 'Donny Prasetya',
            'company'        => 'Great crystal',
        ];
    }
}
