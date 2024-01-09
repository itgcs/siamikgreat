<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapFeeExcelController extends Controller
{

    protected $year;

    public function __construct(int $year) {
        $this->year = $year;
    }

    public function index(): object {

        $capFeeFormated = [];
        $grade_id = [];
        $student_id = [];

        $capFee = DB::table('students')->select('bills.id', 'grades.name as grade_name', 'grades.class as grade_class','students.name','bills.type','bills.installment','bills.created_at'
        ,'bills.deadline_invoice','bills.amount','bills.dp','bills.charge', 'bills.amount','bills.paid_date','bills.paidOf', 
        'bills.amount_installment', 'students.id as student_id', 'grades.id as grade_id')
        ->join('bills', 'bills.student_id', '=', 'students.id')
        ->join('grades', 'grades.id', '=', 'students.grade_id')
        ->where('bills.type', 'Capital Fee')
        ->orderBy('students.grade_id', 'asc')
        ->orderBy('students.name', 'asc')
        ->get();

        $s_id = null;
        $start_col = 2;
        
        $g_id = null;
        $start_g = 2;

        foreach($capFee as $idx => $bill){
    

            if (count($capFee) === $idx+1) {

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

            if(count($capFee) === $idx+1) {
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


            $obj = (object) [
                'no_invoice' => '#' . str_pad((string)$bill->id,8,"0", STR_PAD_LEFT),
                'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                'name' => $bill->name,
                'type' => $bill->type,
                'installment' => $bill->installment? $bill->installment . ' Installments/Month' : "Cash",
                'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                'deadline_invoice' => $bill->deadline_invoice,
                'total' => $this->currencyToIdr($bill->amount),
                'dp' => $bill->dp? $this->currencyToIdr($bill->dp) : "",
                'charge' => $bill->charge? $this->currencyToIdr($bill->charge) : "",
                'amount'=> $bill->installment? $this->currencyToIdr($bill->amount_installment) : $this->currencyToIdr($bill->amount),
                'paid_date' => $bill->paid_date,
                'status' => $bill->paidOf? "Lunas": "Belum lunas",
            ];

            array_push($capFeeFormated, $obj);
        }

        info(json_encode($student_id));

        return (object) [
            'data' => $capFeeFormated,
            'grade_id' => $grade_id,
            'student_id' => $student_id,
        ];
    }
    

    public function currencyToIdr(int $currency){

        return 'Rp.' . number_format($currency, 0, '', ',');
    }
}
