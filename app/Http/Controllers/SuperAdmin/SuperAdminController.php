<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Roles;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Relationship;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Exception;



class SuperAdminController extends Controller
{
   public function index()
   {

      session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
      try {
         //code...
         session()->flash('preloader', false);
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }

   public function getUser()
   {
      try {
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         //code...
         $user = Auth::user();

         $data = User::where('id', '!=', '1')->where('id', '!=', $user->id)->get();

         return view('components.super.data-user')->with('data', $data);
      
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function getById($id)
   {
      try {
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         //code...
         $user = User::where('id', $id)->first();
         
         return $user;  

      } catch (Exception $th) {
         return dd($th);
      }
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
            
            return redirect('/superadmin/users');
         }
         
         if($validator->fails())
         {
            session()->flash('password.success', false);
            session()->flash('error.type.password', false);
            session()->flash('error.password', $validator->messages());
            
            return redirect('/superadmin/users');
         }
         
         
         User::where('id', $id)->update([
            'password' => Hash::make($request->password),
         ]);
         
         session()->flash('password.success');
         session('error.type.password', false);
         session('error.password', false);
         return redirect('/superadmin/users');
      } catch (Exception $th) {
         //throw $th;
         return dd($th);
      }
   }


   public function registerUser()
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         $dataRole = DB::table('roles')->select('id', 'name')->get()->toArray();
         $dataTeacher = DB::table('teachers')->select('id', 'name', 'user_id')->get()->toArray();
         $dataStudent = Student::orderBy('name', 'asc')->get();
         $dataParent = Relationship::get();

         $data = [
            'dataRole' => $dataRole,
            'dataTeacher' => $dataTeacher,
            'dataStudent' => $dataStudent,
            'dataParent' => $dataParent,
         ];

         // dd($data);
         
         return view('components.super.register-user')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function registerUserAction(Request $request)
   {
      try {
         //code...
         session()->flash('preloader');
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => $request->role,
            'created_at' => now(),
         ];
            
         // dd($credentials);

         $validator = Validator::make($credentials, [
            'username' => 'required|unique:users|string',
            'password' => 'required|min:5|string',
         ]);
         
         if($validator->fails())
         {
            if (session('role') ==  'superadmin') {
               return redirect('/superadmin/users/register-user')->withErrors($validator->messages())->withInput($credentials);
            }
            elseif (session('role') == 'admin') {
               return redirect('/admin/users/register-user')->withErrors($validator->messages())->withInput($credentials);
            }
         }

         if($request->password !== $request->reinputPassword)
         {
            $error = [
               'password' => ['password does not match'],
               'reinputPassword' => ['password does not match']
            ];
            return redirect('/superadmin/users/register-user')->withErrors($error)->withInput($credentials);
         }

         User::create($credentials);
         $idLastUser = DB::table('users')->latest('id')->first()->id;
         // dd($idLastUser);

         if($request->teacher != null){
            Teacher::where('id', $request->teacher)->update(['user_id' => $idLastUser]);
         }else if($request->student != null){
            Student::where('id', $request->student)->update(['user_id' => $idLastUser]);
         }else if($request->parent != null){
            Relationship::where('id', $request->parent)->update(['user_id' => $idLastUser]);
         }
         
         session()->flash('register.success');
         if (session('role') == 'superadmin') {
            return redirect('/superadmin/users');
         }
         elseif (session('role') == 'admin') {
            return redirect('/admin/users');
         }
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function deleteUser($id)
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         if(!User::where('id', $id)->first())
         {
            return response()->json(['errors' => 'user id not found!']);
         }

         Teacher::where('user_id', $id)->update(['user_id' => null]);
         User::where('id', $id)->delete();

         return response()->json(['status' => 201, 
         'response' => 'Delete user success'
      ]);
      } catch (Exception $err) {
         return dd($err);
      }
   }
}