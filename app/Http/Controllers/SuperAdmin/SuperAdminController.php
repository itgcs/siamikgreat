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

         return 'Ini adalah halaman super admin';
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
}