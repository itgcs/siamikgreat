<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Grade;
use App\Models\statusInvoiceMail;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusMailSend extends Controller
{
    public function index(Request $request)
    {

        session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'status bills'
        ]);

        try {

            $grade = Grade::orderBy('id', 'asc')->get();

            $form = (object) [
                'type' => $request->type && $request->type !== 'all'? $request->type : null,
                'invoice' => $request->invoice && $request->invoice !== 'all'? $request->invoice : null,
                'status' => $request->status && $request->status !== 'all'? $request->status : null,
                'search' => $request->search? $request->search : null,
                'page' => $request->page? $request->page : null,
             ];

             if( $form->search && is_numeric($form->search) ) {

                $dataModel = new statusInvoiceMail;
                $data = $dataModel->with(['bill']);

                $data = $data->whereRelation('bill', 'id', '=', $form->search);

                $data = $data->orderBy('updated_at', 'desc')->paginate(15);

             } else if($form->page || $request->type && $request->invoice && $request->status) {

                $dataModel = new statusInvoiceMail;
                $data = $dataModel->with(['bill']);

                if($form->type)
                {
                    if($form->type == 'others'){

                        $data = $data->whereRelation('bill', 'type', '<>', 'spp')
                        ->whereRelation('bill', 'type', '<>', 'capital fee')
                        ->whereRelation('bill', 'type', '<>', 'paket')
                        ->whereRelation('bill', 'type', '<>', 'book')
                        ->whereRelation('bill', 'type', '<>', 'uniform');
                        
                    } else {
                        
                        $data = $data->whereRelation('bill', 'type', '=', $form->type);
                    }
                }

                if($form->invoice) 
                {
                    if($form->invoice == 'create') {
                        $data = $data
                        ->where('past_due', false)
                        ->where('charge', false);
                    } else if($form->invoice == 'charge') {
                        $data = $data
                        ->where('past_due', false)
                        ->where('charge', true);
                    } else if($form->invoice == 'past_due') {
                        $data = $data
                        ->where('past_due', true)
                        ->where('charge', false);
                    }
                }

                if($form->status)
                {
                    $condition = $form->status == 'true'? true : false;
                    $data = $data->where('status', $condition);
                }

                $data = $data->orderBy('updated_at', 'desc')->paginate(15);
                
            } else {
                $data = statusInvoiceMail::with(['bill' => function($query) {
                    $query->with('student');
                }])->orderBy('updated_at', 'desc')->paginate(15);
            }

            return view('components.bill.status.data-status-bill')
            ->with('grade', $grade)
            ->with('form', $form)
            ->with('data', $data);
            
        } catch (Exception $err) {
            
            return dd($err);
        }
    }


    public function view($status_id)
    {

        session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'status bills'
        ]);

        try {
            //code...

            $data = statusInvoiceMail::with([
                'bill' => function($query) {
                    $query->with('student');
                }
            ])->where('id', $status_id)->first();

            return view('components.bill.status.detail-status-bill')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function sendEmailNotification($status_id)
    {
        DB::beginTransaction();

        session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'status bills'
        ]);

        try {
            //code...

            $invoiceMailExist = statusInvoiceMail::where('id', $status_id)->first();

            if(!$invoiceMailExist) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'Invoice mail not found.',
                ],404);
            }

            if($invoiceMailExist->status) {
                return response()->json([
                    'code' => 400,
                    'msg' => 'Invoice mail is already been sent so it can`t be resent.',
                ],400);
            }

            $billExist = Bill::with(['student' => function ($query) {
                $query->with(['relationship', 'grade']);
            }, 'bill_collection', 'bill_installments'])
            ->where('id', $invoiceMailExist->bill_id)
            ->first();

            if(!$billExist) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'Bill not found.',
                ],404);
            }

            $pdf = app('dompdf.wrapper');
                  $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $billExist])->setPaper('a4', 'portrait'); 
  
            $pdfReport = null;
  
                 if($billExist->installment){
                    
                    $pdfReport = app('dompdf.wrapper');
                    $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $billExist])->setPaper('a4', 'portrait'); 
            }

            $mailData = [
                'student' => $billExist->student,
                'bill' => $billExist->type == 'Book' ? $billExist : [$billExist],
                'past_due' => $invoiceMailExist->past_due,
                'charge' => $invoiceMailExist->charge,
                'change' => false,
            ];

            $sbjPaketChange = "Tagihan Paket " . $billExist->student->name.  " berhasil diubah, pada tanggal ". date('l, d F Y');
            $sbjSppCreated = "Tagihan ". $billExist->type ." ". $billExist->student->name.  " bulan ini, ". date('F Y') ." sudah dibuat.";
            $sbjAllCreated = "Tagihan ". $billExist->type ." ". $$billExist->student->name. " sudah dibuat.";
            $sbjAllCreatedInstallment = "Tagihan ". $billExist->type ." ". $billExist->student->name.  " bulan ini, ". date('F Y') ." sudah dibuat.";
            $sbjPastDueOrCharge = $invoiceMailExist->charge? "Tagihan ". $billExist->type ." ". $billExist->student->name.  " terkena charge karena sudah melewati jatuh tempo" : "Tagihan ". $billExist->type ." ". $billExist->student->name.  " sudah melewati jatuh tempo";
            $sbjPaymentSuccess = "Payment " . $billExist->type . " ". $billExist->student->name ." has confirmed!";

            

            foreach($billExist->student->relationship as $relationship) {
                
                try {
                    


                } catch (Exception) {
                    //internet laggy
                    return response()->json([
                        'code' => 408,
                        'msg' => 'Internet',
                    ],408);
                }


            }


            return $billExist;

        } catch (Exception) {
            
            return response()->json([
                    'code' => 500,
                    'msg' => 'Internal server error.',
                ],500);
        }
    }
}
