<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
   public function index()
   {
      try {

         $data = Teacher::orderBy('id', 'desc')->get();
         return view('components.teacher.data-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }
   public function getById($id)
   {
      try {

         $data = Teacher::where('id', $id)->first();
         // return view('components.teacher.data-teacher')->with('data', $data);
         return view('components.teacher.detail-teacher')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pagePost()
   {
      try {

         $data = Teacher::orderBy('id', 'desc')->get();
         return view('components.teacher.register-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function actionPost(Request $request)
   {
      try {
         

         $credentials = $request->only(['name', 'nuptk', 'gender', 'place_birth', 'date_birth', 'nationality', 'home_address', 'religion']);

         $credentials['date_birth'] = date('Y-m-d', strtotime($credentials['date_birth']));

         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'nuptk' => 'required|string|min:16|unique:teachers',
            'gender' => 'required|string|in:Male,Female',
            'place_birth' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'home_address' => 'required|string',
            'religion' => 'required|string',
         ]);

         if($validator->fails())
         {
            return redirect('/admin/teachers/register')->withErrors($validator->messages())->withInput($credentials);
         }

         $data = Teacher::create($credentials);

         session()->flash('after_create');
         return redirect('/admin/teachers/detail/' . $data->id);
      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function editPage($id)
   {
      try {

         $data = Teacher::where('id', $id)->first();
         // return view('components.teacher.data-teacher')->with('data', $data);
         return view('components.teacher.edit-teacher')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function actionEdit(Request $request, $id)
   {
      try {
         
         $credentials = $request->only(['id', 'name', 'nuptk', 'gender', 'place_birth', 'date_birth', 'nationality', 'home_address', 'religion']);
         
         $credentials['date_birth'] = $this->handleDate($credentials['date_birth'], 'Y-m-d');

         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'nuptk' => 'required|string|min:16',
            'gender' => 'required|string|in:Male,Female',
            'place_birth' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'home_address' => 'required|string',
            'religion' => 'required|string',
         ]);
         
         $check = Teacher::where('nuptk', $request->nuptk)->first();
         
         if($check->id != $id)
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/' . $id)->withErrors(['nuptk' => 'nuptk has already been taken.'])->withInput($credentials);
         }
         
         if($validator->fails())
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/' . $id)->withErrors($validator->messages())->withInput($credentials);
         }
         
         Teacher::where('id', $id)->update($credentials);
         
         session()->flash('after_create');
         return redirect('/admin/teachers/detail/' . $id);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }



   public function handleDate($date, $format = false): string
   {

      if($format === 'Y-m-d')
      {
         
         $date_format = explode('/', $date);
      } else {
         $date_format = explode('-', $date);
      }
      return $date_format[2] . '-' . $date_format[1] . '-' . $date_format[0];
   }


   public function destroy($id)
   {
      try {
         
         if(!Teacher::where('id', $id)->first())
         {
            return response()->json([
               'message' => 'Teacher with id ' . $id . ' not found!!!',
            ]);
         }

         Teacher::where('id', $id)->delete();

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         //throw $th;
         return response()->json([
            'error' => $err,
         ]);
      }
   }
}