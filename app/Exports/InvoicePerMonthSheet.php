<?php

namespace App\Exports;

use App\Models\Bill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoicePerMonthSheet implements FromQuery, WithTitle
{
    private $month;
    private $year;

    public function __construct(int $year, int $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Bill
            ::query()
            ->where('type', 'SPP')
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if($this->month >= 10){

            return '>=10 ' . $this->month;
        } else {

            return '<10 ' . (int)"0".$this->month;
        }
    }
}
