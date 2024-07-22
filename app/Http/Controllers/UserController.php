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
use App\Models\Student_relationship;

class UserController extends Controller
{

   public function login()
   {
      try {
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


         // dd(empty(Master_academic::first()));

         if(empty(Master_academic::first())){
            session()->put([
               'role' => $nameRoles->name,
               'id_user' => $user['id'],
               'name_user' => $nameUser,
            ]);        
         } else {
            $semester = Master_academic::first()->value('now_semester');
   
            session()->put([
               'role' => $nameRoles->name,
               'id_user' => $user['id'],
               'name_user' => $nameUser,
               'semester' => $semester,
            ]);        
         }

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
            $id           = Relationship::where('user_id', session('id_user'))->value('id');
            $getIdStudent = Student_relationship::where('relationship_id', $id)->value('student_id');

            session()->put([
               'studentId' => $getIdStudent,
            ]); 

            return redirect('parent/dashboard/');
         }

         // return redirect('admin/dashboard/');

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function logout(Request $request)
   {
      try {
         //code...
         Auth::logout();
         $request->session()->flash('preloader', true);
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

   public function saveStudentIdToSession(Request $request)
   {
      $studentId = $request->input('studentId');
      session()->put('studentId', $studentId);
      return response()->json(['studentId' => $studentId]);
   }

   public function changePassword(Request $request, $id)
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         $rules = $request->only('password', 'reinputPassword');
         
         $validator = Validator::make($rules, [
            'password' => 'required|min:5', 
            'reinputPassword' => 'required|min:5',
         ]);

         if($request->password !== $request->reinputPassword)
         {
            session()->flash('password.success', false);
            session()->flash('error.type.password', 'Make sure your input password is the same !!!');
            session()->flash('error.password', false);
            
            if (session('role') == 'teacher') {
               return redirect('/teacher/dashboard/detail/teacher');
            }
            else {
               return redirect()->back();
            }
         }
         
         if($validator->fails())
         {
            session()->flash('password.success', false);
            session()->flash('error.type.password', false);
            session()->flash('error.password', $validator->messages());
            
            if (session('role') == 'teacher') {
               return redirect('/teacher/dashboard/detail/teacher');
            }
            else {
               return redirect()->back();
            }
         }
         
         
         User::where('id', $id)->update([
            'password' => Hash::make($request->password),
         ]);
         
         session()->flash('password.success');
         session('error.type.password', false);
         session('error.password', false);
         if (session('role') == 'teacher') {
            return redirect('/teacher/dashboard/detail/teacher');
         }
         else {
            return redirect()->back();
         }
      } catch (Exception $th) {
         //throw $th;
         return dd($th);
      }
   }


}