<?php

namespace App\Exports;

use App\Models\Bill;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoicePerMonthSheet implements FromQuery, WithTitle
{
    private $month;
    private $year;

    public function __construct(int $year, $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    /**
     * @return Builder
     */
    public function query()
    {

        if($this->month == "Capital Fee") {
            return Bill
                ::query()
                ->where('type', 'Capital Fee');
        } else {
            return Bill
                ::query()
                ->where('type', 'SPP')
                ->whereYear('created_at', $this->year)
                ->whereMonth('created_at', $this->month);
        }

    }

    /**
     * @return string
     */
    public function title(): string
    {
        
        if($this->month === "Capital Fee"){

            return "Capital Fee";
        } else if($this->month === "Material Fee") {

            return "Material Fee";
        } else {
            $date = Carbon::create($this->year, $this->month);
            return "Monthly Fee " . date("F Y", strtotime($date));
        }

    }
}
