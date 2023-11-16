<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\BookMail;
use App\Mail\DemoMail;
use App\Mail\FeeRegisMail;
use App\Mail\PaketMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Grade;
use App\Models\statusInvoiceMail;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

    public function send($id) {
        
        session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'status bills'
        ]);

        try {
            //code...

            $invoiceMailExist = statusInvoiceMail::where('id', $id)->first();

            if(!$invoiceMailExist) {
                DB::rollBack();
                return response()->json([
                    'code' => 404,
                    'msg' => 'Invoice mail not found.',
                ],404);
            }

            if($invoiceMailExist->status) {
                DB::rollBack();
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
                DB::rollBack();
                return response()->json([
                    'code' => 404,
                    'msg' => 'Bill not found.',
                ],404);
            }

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $billExist])->setPaper('a4', 'portrait'); 
            
            $pdfReport = null;
  
            if($billExist->installment)
            {        
                $pdfReport = app('dompdf.wrapper');
                $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $billExist])->setPaper('a4', 'portrait'); 
            }
            
            $mailData = [
                'student' => $billExist->student,
                'bill' => $billExist->type == 'Book' ? $billExist : [$billExist],
                'past_due' => $invoiceMailExist->past_due,
                'charge' => $invoiceMailExist->charge,
                'change' => $invoiceMailExist->is_change,
            ];

            $sbjPaketChange = "Tagihan Paket " . $billExist->student->name.  " berhasil diubah, pada tanggal ". date('l, d F Y');
            $sbjSppCreated = "Tagihan ". $billExist->type ." ". $billExist->student->name.  " bulan ini, ". date('F Y') ." sudah dibuat.";
            $sbjAllCreated = "Tagihan ". $billExist->type ." ". $billExist->student->name." sudah dibuat.";
            $sbjAllCreatedInstallment = "Tagihan ". $billExist->type ." ". $billExist->student->name.  " bulan ini, ". date('F Y') ." sudah dibuat.";
            $sbjPastDueOrCharge = $invoiceMailExist->charge? "Tagihan ". $billExist->type ." ". $billExist->student->name.  " terkena charge karena sudah melewati jatuh tempo" : "Tagihan ". $billExist->type ." ". $billExist->student->name.  " sudah melewati jatuh tempo";
            $sbjPaymentSuccess = "Payment " . $billExist->type . " ". $billExist->student->name ." has confirmed!";
           
            foreach($billExist->student->relationship as $relationship) {
                
                $mailData['name'] = $relationship->name;
                
                try {

                    if($billExist->type == 'Book') {
                        Mail::to($relationship->email)->send(new BookMail($mailData, $sbjAllCreated, $pdf));
                    } else if($billExist->type == 'Paket') {
                        Mail::to($relationship->email)->send(new PaketMail($mailData, $sbjAllCreated, $pdf, $pdfReport));
                    } else if($billExist->type == 'Capital Fee') {
                        Mail::to($relationship->email)->send(new FeeRegisMail($mailData, $sbjAllCreated, $pdf, $pdfReport));
                    } else {
                        Mail::to($relationship->email)->send(new SppMail($mailData, $sbjAllCreated, $pdf, $pdfReport));
                    }
                    
                } catch (Exception $err) {
                    //internet laggy
                    DB::rollBack();
                    return response()->json([
                        'code' => 408,
                        'msg' => 'Internet',
                    ],408);
                }
            }
            

            statusInvoiceMail::where('id', $id)->update(['status' => true]);


            DB::commit();

            return response()->json([
                'code' => 200,
                'msg' => 'Success sent email to parents ' . $billExist->name,
            ],200);

        } catch (Exception $err) {
            
            DB::rollBack();
            return response()->json([
                    'code' => 500,
                    'msg' => 'Internal server error.',
                ],500);
        }
        
    }
}
