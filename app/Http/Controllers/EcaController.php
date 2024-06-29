<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Eca;
use App\Models\Student;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EcaController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);

            $data = Eca::get();

            // dd($data);
            
            $data = [
                'data' => $data,
            ];

            // dd($data);
            return view('components.eca.data-eca')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);
            return view('components.eca.create-eca');
            
        } catch (Exception) {
            return abort(500);
        }
    }

    public function addStudent($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);

            $data = Student::orderBy('grade_id', 'asc')->get();
            // dd($data);

            return view('components.eca.add-student')->with('data', $data);
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {

            $rules = [
                'name' => $request->name,
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string',
                ],
            );

            $role = session('role');
            
            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/eca/create')->withErrors($validator->messages())->withInput($rules);
            }
            
            if(Eca::where('name', $request->name)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/eca/create')->withErrors([
                    'name' => 'Eca ' . $request->name .  ' is has been created ',
                ])->withInput($rules);
            }
                
            $post = [
                'name' => $request->name,
                'created_at'   => now(),
            ];

            session()->flash('after_create_eca');

            Eca::create($post);

            DB::commit();
            
            return redirect('/'.  $role .'/eca');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function pageEdit($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'eca',
                'child' => 'database eca',
            ]);
            
            $data = Eca::where('id', $id)->first();
            
            return view('components.eca.edit-eca')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
            return abort(404);
        }
    }


    public function actionPut(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            session()->flash('page',  $page = (object)[
                'page' => 'eca',
                'child' => 'database eca',
            ]);

            $rules = [
                'name' => $request->name,
                'updated_at'   => now(),
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string',
                ]
            );

            $role = session('role');

            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.$role.'/eca/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = Eca::where('name', $request->name)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/eca/edit/' . $id)->withErrors(['name' => ["The eca " . $request->name  ." is already created !!!"]])->withInput($rules);
            }

            Eca::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_eca');

            return redirect('/'.$role.'/eca');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        try {

            session()->flash('after_delete_eca');

            Eca::where('id', $id)->delete();

            return redirect('/'.session('role').'/eca');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/eca')->with('error', 'Terjadi kesalahan saat menghapus data eca.');
        }
    }
}
