<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use Exception;
  
class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
      try {
         //code...
         $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ];
         
        Mail::to('tkeluarga111@gmail.com')->send(new DemoMail($mailData));
           
        return dd("Email is sent successfully.");
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
        
    }
}