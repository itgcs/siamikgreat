<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Brothers_or_sister;
use App\Models\Student;
use App\Models\Student_relation;
use Illuminate\Http\Request;
use Exception;

class StudentController extends Controller
{
   public function inactiveStudent($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'database students',
         ]);
      // session()->flash('preloader', true);
      Student::where('id', $id)->update([
         'is_active' => 0,
      ]);

      
      return response()->json([
            'success' => true,
      ]); 
      } catch (Exception $err) {
         return response()->json([
            'error' => $err
      ]);
      }
   }

}