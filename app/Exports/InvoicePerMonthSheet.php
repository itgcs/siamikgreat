<?php

namespace App\Exports;

use App\Models\Bill;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicePerMonthSheet implements WithTitle, WithHeadings, ShouldAutoSize, FromArray, WithStyles
{
    private $month;
    private $year;
    private $array;

    public function __construct($array, int $year, $month)
    {
        $this->array = $array;
        $this->month = $month;
        $this->array  = $array;
    }

    /**
     * @return Builder
     */
    public function array(): array
    {
        return $this->array;
    }

    //  public function collection()
    //  {
    //     return $this->array;
    //  }

    // public function query()
    // { 
    //     if($this->month == "Capital Fee") {
    //         $result = [];
    //         $db = Bill::with('student')->where('type', 'Capital Fee')->get();
    //         foreach($db as $bill){

    //             $obj = (object) [
    //                 'id' => '#' . str_pad((string)$bill->id,8,"0", STR_PAD_LEFT),
    //                 'name' => $bill->name,
    //                 'type' => $bill->type,
    //                 'installment' => $bill->installment? $bill->installment . ' Installment / month' : "Cash",
    //                 'created_at' => $bill->created_at,
    //                 'deadline_invoice' => $bill->deadline_invoice,
    //                 'amount'=> $bill->installment? $bill->amount_installment : $bill->amount,
    //                 'dp' => $bill->dp? "Rp." . $bill->dp : "0",
    //                 'charge' => $bill->charge,
    //                 'total' => $bill->amount,
    //                 'paid_date' => $bill->paid_date,
    //                 'status' => $bill->paidOf? "Lunas": "Belum lunas",
    //             ];

    //             array_push($result, $obj);
    //         }

    //         // return $this($result);
    //         $this->array($result);
        
    //     } 
    //     else if($this->month == "Material Fee") {
            
    //         return Bill
    //             ::query()
    //             ->where('type', 'Book')
    //             ->whereYear('created_at', $this->year)
    //             ->orWhere('type', 'Uniform')
    //             ->whereYear('created_at', '>=', $this->year);

    //     } else if($this->month == 'Package') {
    //         return Bill
    //             ::query()
    //             ->where('type', 'Paket')
    //             ->whereYear('created_at', '>=', $this->year);
    //     } else {
    //         $bill = Bill
    //             ::query()
    //             ->where('type', 'SPP')
    //             ->whereYear('created_at', $this->year)
    //             ->whereMonth('created_at', $this->month);
    //         info('month'. $this->month . json_encode($bill));
    //         return $bill;
    //     }

    // }

    // public function map($bill): array
    // {

    //     if($this->month == 'Capital Fee') {

    //         return [
    //             $bill->id,
    //             $bill->name,
    //             $bill->type,
    //             $bill->installment,
    //             $bill->created_at,
    //             $bill->deadline_invoice,
    //             $bill->amount,
    //             $bill->dp,
    //             $bill->charge,
    //             $bill->total,
    //             $bill->paid_date,
    //             $bill->status,
    //         ];
    //     } else {
    //         return [
    //             $bill->id,
    //             $bill->name,
    //             $bill->type,
    //             $bill->installment,
    //             $bill->created_at,
    //             $bill->deadline_invoice,
    //             $bill->amount,
    //             $bill->dp,
    //             $bill->charge,
    //             $bill->paid_date,
    //             $bill->status,
    //         ];
    //     }

    // }
                                                                               
    /**
     * @return string
     */

    public function headings(): array
    {
        return [
            'No Invoice',
            'Grades',
            'Student name',
            'Type',
            'Installment/Month',
            'Date created',
            'Date past due',
            'Amount',
            'Done Payment',
            'Charge',
            'Total',
            'Paid date',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {

        // $sheet->mergeCells([2,2,2,4]);
        // $sheet->mergeCells([2,5,2,7]);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            'M'  => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        if(is_numeric($this->month)) {
            $date = Carbon::create($this->year, $this->month);
            return "Monthly Fee " . date("F y", strtotime($date));
        } else {
            return $this->month;
        }
    }
}
