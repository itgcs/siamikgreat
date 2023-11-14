<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\statusInvoiceMail;
use Exception;
use Illuminate\Http\Request;

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
}
