<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Master_academic;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Roles;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Relationship;

class UserController extends Controller
{

   public function login()
   {
      try {
         //code...
         session()->flash('preloader', true);
         Auth::logout();
         return view('layouts.login');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
   
   
   public function actionLogin(Request $request)
   {
      try {
         //code...
         session()->flash('preloader', true);
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'dashboard',
         ]);

         $credentials = $request->only('username', 'password');

         $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
         ],
         [
            'username.required' => 'input username !!!',
            'password.required' => 'input password !!!'
         ]);

         if($validator->fails())
         {
            // return dd($validator->messages());
            return redirect('/')->withErrors($validator->messages())->withInput($credentials);
         }

         $check = Auth::attempt($credentials);
         
         // dd($check);

         if(!$check)
         {
            return redirect()->back()->withErrors(['invalid' => 'invalid username/password'])->withInput($credentials);
         }

         // SET ROLE & ID USE
         $user = Auth::user();
         $nameRoles = Roles::where('id',$user->role_id)->first();
         
         if ($user->role_id == 1) 
         {
            $nameUser = "superadmin";
         }
         elseif ($user->role_id == 2) 
         {
            $nameUser = "admin";
         }
         elseif ($user->role_id == 3) 
         {
            $nameUser = Teacher::where('user_id',$user->id)->value('name');
         }
         elseif ($user->role_id == 4) 
         {
            $nameUser = Student::where('user_id',$user->id)->value('name');
         }
         elseif ($user->role_id == 5) 
         {
            $nameUser = Relationship::where('user_id',$user->id)->value('name');
         }

         $semester = Master_academic::first()->value('now_semester');

         session()->put([
            'role' => $nameRoles->name,
            'id_user' => $user['id'],
            'name_user' => $nameUser,
            'semester' => $semester,
         ]);        

         $checkRole = session('role');
         // dd($checkRole);


         if($checkRole == 'superadmin'){
            return redirect('superadmin/dashboard/');
         } 
         if($checkRole == 'admin'){
            return redirect('admin/dashboard/');
         } 
         if($checkRole == 'teacher') {
            return redirect('teacher/dashboard/');
         }
         if($checkRole == 'student') {
            return redirect('student/dashboard/');
         }
         if($checkRole == 'parent') {
            return redirect('parent/dashboard/');
         }

         // return redirect('admin/dashboard/');

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function logout()
   {
      try {
         //code...
         Auth::logout();
         session()->flash('preloader');
         // return redirect('/');
         return (object) [
            'success' => true,
         ];
      } catch (Exception $err) {
         //throw $th;
         return (object) [
            'success' => false,
         ];
      }
   }

   public function saveSemesterToSession(Request $request)
   {
      $semester = $request->input('semester');
      session()->put('semester', $semester);
      return response()->json(['semester' => $semester]);
   }
}