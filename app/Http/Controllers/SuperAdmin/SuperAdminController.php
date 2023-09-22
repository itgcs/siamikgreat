<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class SuperAdminController extends Controller
{
   public function index()
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page', 'dashboard');
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}