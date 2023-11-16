<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class CreatePdfBill extends Controller
{
    public function invoice($bill_id)
    {
        $pdfBill = Bill::with(['student' => function ($query) {
            $query->with('grade');
         }, 'bill_installments', 'bill_collection'])
         ->where('id', $bill_id)
         ->first();

          
        $pdf = app('dompdf.wrapper');
        return $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 
    }
}
