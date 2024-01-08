<?php

namespace App\Exports;

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

        $result = [];

        $data = DB::table('students')->select('bills.id', 'grades.name as grade_name', 'grades.class as grade_class','students.name','bills.type','bills.installment','bills.created_at'
        ,'bills.deadline_invoice','bills.amount','bills.dp','bills.charge', 'bills.amount','bills.paid_date','bills.paidOf', 
        'bills.amount_installment')
        ->join('bills', 'bills.student_id', '=', 'students.id')
        ->join('grades', 'grades.id', '=', 'students.grade_id')
        ->where('bills.type', 'Capital Fee')
        ->orderBy('students.grade_id', 'asc')
        ->orderBy('students.name', 'asc')
        ->get();

        if($getYearFrom === $getYearTo) {

            // for ($month = $getMonthFrom; $month <= $getMonthTo; $month++) {
            //     if($month === $getMonthFrom) array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Capital Fee"));
            //     array_push($sheets, new InvoicePerMonthSheet($getYearFrom, $month));
            //     if($month === $getMonthTo) array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Package"));
            //     if($month === $getMonthTo) array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Material Fee"));
            // }

            foreach($data as $bill){

                $obj = (object) [
                    'no_invoice' => '#' . str_pad((string)$bill->id,8,"0", STR_PAD_LEFT),
                    'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                    'name' => $bill->name,
                    'type' => $bill->type,
                    'installment' => $bill->installment? $bill->installment . ' Installment/Month' : "Cash",
                    'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                    'deadline_invoice' => $bill->deadline_invoice,
                    'amount'=> $bill->installment? $this->currencyToIdr($bill->amount_installment) : $this->currencyToIdr($bill->amount),
                    'dp' => $bill->dp? $this->currencyToIdr($bill->dp) : "",
                    'charge' => $bill->charge? $this->currencyToIdr($bill->charge) : "",
                    'total' => $this->currencyToIdr($bill->amount),
                    'paid_date' => $bill->paid_date,
                    'status' => $bill->paidOf? "Lunas": "Belum lunas",
                ];

                array_push($result, $obj);

            }
            
            array_push($sheets, new InvoicePerMonthSheet(array_values($result), $getYearFrom, "Capital Fee"));
            
        } else {

            foreach($data as $bill){

                $obj = (object) [
                    'no_invoice' => '#' . str_pad((string)$bill->id,8,"0", STR_PAD_LEFT),
                    'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                    'name' => $bill->name,
                    'type' => $bill->type,
                    'installment' => $bill->installment? $bill->installment . ' Installment/Month' : "Cash",
                    'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                    'deadline_invoice' => $bill->deadline_invoice,
                    'amount'=> $bill->installment? $this->currencyToIdr($bill->amount_installment) : $this->currencyToIdr($bill->amount),
                    'dp' => $bill->dp? $this->currencyToIdr($bill->dp) : "",
                    'charge' => $bill->charge? $this->currencyToIdr($bill->charge) : "",
                    'total' => $this->currencyToIdr($bill->amount),
                    'paid_date' => $bill->paid_date,
                    'status' => $bill->paidOf? "Lunas": "Belum lunas",
                ];

                array_push($result, $obj);

            }

            array_push($sheets, new InvoicePerMonthSheet(array_values($result), $getYearFrom, "Capital Fee"));

            // for ($month = $getMonthFrom; $month <= 12; $month++) {
            //     if($month === $getMonthFrom) array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Capital Fee"));
            //     array_push($sheets, new InvoicePerMonthSheet($getYearFrom, $month));
            // }

            // for ($month = 1; $month <= $getMonthTo; $month++) {
            //     array_push($sheets, new InvoicePerMonthSheet($getYearFrom, $month));
            //     if($month === $getMonthTo) array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Package"));
            //     if($month === $getMonthTo) array_push($sheets, new InvoicePerMonthSheet($getYearFrom, "Material Fee"));
            // }
        }
        
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

    public function currencyToIdr(int $currency){

        return 'Rp.' . number_format($currency, 0, '', ',');
    }
}
