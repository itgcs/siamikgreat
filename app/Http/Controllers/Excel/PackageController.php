<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    protected $year;

    public function __construct(int $year) {
        $this->year = $year;
    }

    public function index(): object {

        $packageFormated = [];
        $grade_id = [];
        $student_id = [];
        $installment_id = [];

        $package = DB::table('students')->select('bills.id', 'grades.name as grade_name', 'grades.class as grade_class','students.name','bills.type','bills.installment','bills.created_at'
        ,'bills.deadline_invoice','bills.amount','bills.dp','bills.charge', 'bills.amount','bills.paid_date','bills.paidOf', 
        'bills.amount_installment', 'students.id as student_id', 'grades.id as grade_id')
        ->join('bills', 'bills.student_id', '=', 'students.id')
        ->join('grades', 'grades.id', '=', 'students.grade_id')
        ->where('bills.type', 'Paket')
        ->orderBy('students.grade_id', 'asc')
        ->orderBy('students.name', 'asc')
        ->get();

        $s_id = null;
        $start_col = 2;
        
        $g_id = null;
        $start_g = 2;

        $unique_b = [];
        $b_start = null;

        foreach($package as $idx => $bill){

            if (count($package) === $idx+1) {

                $bill->student_id == $s_id ?
                    array_push($student_id, [$start_col, $idx+2]) :
                   ( 
                    array_push($student_id, [$start_col, $idx+1]) &&
                    array_push($student_id, [$idx+2, $idx+2])
                   );

            } else if($s_id && $bill->student_id != $s_id) {
                array_push($student_id, [$start_col, $idx+1]);
                $start_col=$idx+2;
            }

            if(count($package) === $idx+1) {
                $bill->grade_id == $g_id ?
                    array_push($grade_id, [$start_g, $idx+2]) :
                   ( 
                    array_push($grade_id, [$start_g, $idx+1]) &&
                    array_push($grade_id, [$idx+2, $idx+2])
                   );

            } else if($g_id && $bill->grade_id != $g_id) {
                array_push($grade_id, [$start_g, $idx+1]);
                $start_g=$idx+2;
            }

            $g_id = (int)$bill->grade_id;
            $s_id = (int)$bill->student_id;


        if(!in_array($bill->id, $unique_b)){
                
            $obj = (object) [
                'no_invoice' => '#' . str_pad((string)$bill->id,8,"0", STR_PAD_LEFT),
                'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                'name' => $bill->name,
                'type' => 'Package',
                'installment' => $bill->installment? $bill->installment . ' Installments/Month' : "Cash",
                'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                'deadline_invoice' => $bill->deadline_invoice,
                'total' => $this->currencyToIdr($bill->amount),
                'amount'=> $bill->installment? $this->currencyToIdr($bill->amount_installment) : $this->currencyToIdr($bill->amount),
                'paid_date' => $bill->paid_date,
                'status' => $bill->paidOf? "Lunas": "Belum lunas",
            ];
            
            
            $bill_relation = Bill::with('bill_installments')->where('id', $bill->id)->first();
            
            if(sizeof($bill_relation->bill_installments)>0) {

                $b_start = $idx+2;
                foreach($bill_relation->bill_installments as $key => $installment) {
                    
                    $obj = (object) [
                        'no_invoice' => '#' . str_pad((string)$installment->id,8,"0", STR_PAD_LEFT),
                        'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                        'name' => $bill->name,
                        'type' => 'Package',
                        'installment' => $installment->installment? $installment->installment . ' Installments/Month' : "Cash",
                        'created_at' => date('Y-m-d', strtotime($installment->created_at)),
                        'deadline_invoice' => $installment->deadline_invoice,
                        'total' => $this->currencyToIdr($installment->amount),
                        'amount'=> $installment->installment? $this->currencyToIdr($installment->amount_installment) : $this->currencyToIdr($installment->amount),
                        'paid_date' => $installment->paid_date,
                        'status' => $installment->paidOf? "Lunas": "Belum lunas",
                    ];
                    
                    array_push($packageFormated, $obj);
                    array_push($unique_b, $installment->id);
                }
                
                array_push($installment_id, [$b_start, $b_start+$key]);
                
            } else {

                array_push($packageFormated, $obj);
                array_push($unique_b, $bill->id);

            }

            }
        }

        return (object) [
            'data' => $packageFormated,
            'grade_id' => $grade_id,
            'student_id' => $student_id,
            'installment_id' => $installment_id,
        ];
    }
    

    private function currencyToIdr(int $currency){

        return 'Rp.' . number_format($currency, 0, '', ',');
    }
}
