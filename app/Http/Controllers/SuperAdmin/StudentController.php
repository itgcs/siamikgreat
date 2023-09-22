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
   public function destroy($id)
   {
      try {
         //code...
      
      session()->flash('preloader', true);
      Brothers_or_sister::where('student_id', $id)->delete();
      Student_relation::where('student_id', $id)->delete();
      Student::where('id', $id)->delete();

      
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