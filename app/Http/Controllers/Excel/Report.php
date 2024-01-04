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

            return Excel::download(new ReportExport, 'teacher.xlsx');
        } catch (Exception $err) {
            return abort(500);
        }
    }
}
