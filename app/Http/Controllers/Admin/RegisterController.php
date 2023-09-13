<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Relationship;
use App\Models\Student;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
   public function index()
   {
      try {
         //code...

         return view('components.register');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function register(Request $request)
   {

      DB::beginTransaction();

      try {
         //code...


         $credentialsFather = [
            'relation' => 'father',
            'name' => $request->fatherName,
            'religion' => $request->fatherReligion,
            'place_birth' => $request->fatherPlace_birth,
            'date_birth' => $request->fatherBirth_date,
            'id_or_passport' => $request->fatherId_or_passport,
            'nationality' => $request->fatherNationality,
            'occupation' => $request->fatherOccupation,
            'company_name' => $request->fatherCompany_name,
            'company_address' => $request->fatherCompany_address,
            'phone' => $request->fatherCompany_phone,
            'home_address' => $request->fatherHome_address,
            'telephone' => $request->fatherTelephhone,
            'mobilephone' => $request->fatherMobilephone,
            'email' => $request->fatherEmail,
         ];

         $credentials = [
            'name' => $request->studentName,
            'grade' => 'SMA',
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $this->changeDateFormat($request->studentDate_birth) : '',
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp ? $this->changeDateFormat($request->studentDate_exp) : '',
         ];

         
         $createStudent = $this->createStudent($credentials);
         $createFather = $this->createRelationship($credentialsFather);
         return 'test';

         if($createStudent->status && $createFather->status)
         {
            return 'POST';
         }

         DB::rollBack();
         
      } catch (Exception $err) {
         
         DB::rollBack();
         return dd($err);
      }
   }

   private function createStudent($credentials)
   {
      $validator = Validator::make($credentials, [
         'name' => 'string|required|min:3',
         'grade' => 'string|required',
         'gender' => 'string|required',
         'religion' => 'string|required',
         'place_birth' => 'string|required',
         'date_birth' => 'date|required',
         'id_or_passport' => 'string|required|min:15|max:16|unique:students',
         'nationality' => 'string|required|min:3',
         'place_of_issue' => 'required|string',
         'date_exp' => 'date',
      ]);

      if($validator->fails())
      {
         return dd($validator->errors());
      }
      
      $student = Student::create($credentials);

      return (object) ['status' => true, 'dataStudent' => $student];
   }


   private function createRelationship($credentialsFather)
   {
      $validator = Validator::make($credentialsFather, [
         'relation' => 'required',
         'name' => 'required|string|min:3',
         'religion' => 'required|string',
         'place_birth' => 'required|string',
         'date_birth' => 'required|date',
         'id_or_passport' => 'required|string|man:15|max:16|unique:relationships',
         'nationality' => 'required|string',
         'occupation' => 'string',
         'company_name' => 'string',
         'company_address' => 'string',
         'phone' => 'string',
         'home_address' => 'required|string',
         'telephone' => 'string',
         'mobilephone' => 'required|string',
         'email' => 'required|string|unique:relationships',
      ]);

      if($validator->fails())
      {
         return dd($validator->errors());
      }

      
      $father = Relationship::create($credentialsFather);

      return (object) ['status' => true, 'dataFather' => $father];
   }

   private function changeDateFormat($date)
   {
      return date("Y-m-d", strtotime($date));
   }
}