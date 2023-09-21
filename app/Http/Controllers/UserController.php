<?php

namespace App\Http\Controllers;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

   public function login()
   {
      try {
         //code...
         Auth::logout();
         return view('layouts.login');
      } catch (Exception $err) {
         
         dd($err);
      }
   }
   
   
   public function actionLogin(Request $request)
   {
      try {
         //code...

         $credentials = $request->only('username', 'password');

         $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
         ],
         [
            'username.required' => 'please input username!!!',
            'password.required' => 'please input password!!!'
         ]);

         if($validator->fails())
         {
            return response()->json(['error' => $validator->errors()]);
         }

         $check = Auth::attempt($credentials);

         if(!$check)
         {
            return response()->json(['error' => 'invalid username/password']);
         }
         

         $user = Auth::user();

         return redirect($user->role . '/dashboard/');

      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function logout()
   {
      Auth::logout();
      return redirect('/');
   }

}