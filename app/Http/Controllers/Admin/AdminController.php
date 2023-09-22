<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   public function index()
   {
      try {
         //code...
         session()->flash('page', 'dashboard');
         session()->flash('preloader', true);
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}