<?php

namespace App\Http\Controllers\Excel;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Report extends Controller
{
    public function index() {

        try {

            session()->flash('page', (object)[
                'page' => 'Report',
                'child' => 'report bills'
             ]);

            return view('components.bill.report.report-page');
            
        } catch (Exception $err) {
            return abort(500);
        }
    }


    public function export() {

        try {
            //code...

            $formatedName = time().rand(10000, 99999). '_'. date("dmY") . '_report_bill' . '.xlsx'; 

            return Excel::download(new ReportExport(2023), $formatedName, \Maatwebsite\Excel\Excel::XLSX);
        } catch (Exception $err) {
            return abort(500);
        }
    }
}
