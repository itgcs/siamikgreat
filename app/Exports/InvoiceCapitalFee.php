<?php

namespace App\Exports;

use App\Models\Bill;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoiceCapitalFee implements FromQuery, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        // Bill::query()
        // ->with('student')
        // ->where('type', "Capital Fee");
    }


    public function title(): string
    {
        return "Capital Fee";
    }
}
