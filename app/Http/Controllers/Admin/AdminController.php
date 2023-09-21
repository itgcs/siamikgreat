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
         session()->flash('role', 'Admin');
         session()->flash('page', 'dashboard');
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}