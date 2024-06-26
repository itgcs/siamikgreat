<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master_academic;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MasterAcademicController extends Controller
{
    Public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'master academic',
            'child' => 'master academic',
            ]);

            $data = Master_academic::get();

            // dd($data);
            return view('components.masterAcademic.data-masterAcademic')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'master schedules',
            'child' => 'master schedules',
            ]);
            return view('components.masterAcademic.create-masterAcademic');
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {                
            $post = [
                'academic_year' => $request->academic_year,
                'semester1' => $request->semester1,
                'end_semester1' => $request->end_semester1,
                'semester2' => $request->semester2,
                'end_semester2' => $request->end_semester2,
                'now_semester' => $request->now_semester,
                'created_at' => now(),
            ];

            $role = session('role');

            session()->flash('after_create_masterAcademic');

            Master_academic::create($post);

            DB::commit();
            
            return redirect('/'.  $role .'/masterAcademics');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }
    
    public function pageEdit()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'master academic',
                'child' => 'master academic',
            ]);
            
            $data = Master_academic::first();
            
            return view('components.masterAcademic.edit-masterAcademic')->with('data', $data);
            
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
                'page' => 'master schedules',
                'child' => 'master schedules',
            ]);

            $rules = [
                'academic_year' => $request->academic_year,
                'semester1' => $request->semester1,
                'end_semester1' => $request->end_semester1,
                'semester2' => $request->semester2,
                'end_semester2' => $request->end_semester2,
                'now_semester' => $request->now_semester,
                'updated_at' => now(),
            ];

            $role = session('role');

            Master_academic::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_masterAcademic');

            return redirect('/'.$role.'/masterAcademics');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        session()->flash('after_delete_typeSchedule');

        Master_academic::where('id', $id)->delete();

        return redirect('/superadmin/masterAcademics');
    }
}
