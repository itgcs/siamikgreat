<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   
   public function index()
   {
      try {
         //code...

         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}