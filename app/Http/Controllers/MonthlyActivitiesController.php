<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher_subject;
use App\Models\Grade_subject;
use App\Models\Subject_exam;
use App\Models\Exam;
use App\Models\Grade_exam;
use App\Models\MonthlyActivity;
use App\Models\Score;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MonthlyActivitiesController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'monthly activities',
            'child' => 'database monthly activities',
            ]);

            $data = MonthlyActivity::get();

            return view('components.monthlyActivities.data-monthly-activities')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'monthly activities',
            'child' => 'database monthly activities',
            ]);
            return view('components.monthlyActivities.create-monthly-activities');
            
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

            if(MonthlyActivity::where('name', $request->name)->first())
            {
                DB::rollBack();
                return redirect('/monthlyActivities/create')->withErrors([
                    'name' =>  $request->name .  ' is has been created ',
                ])->withInput($rules);
            }
                
            $post = [
                'name' => $request->name,
                'created_at'   => now(),
            ];

            session()->flash('after_create_subject');

            MonthlyActivity::create($post);

            DB::commit();
            
            return redirect('/monthlyActivities');

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
                'page' => 'subjects',
                'child' => 'database subjects',
            ]);
            
            $data = MonthlyActivity::where('id', $id)->first();
            
            return view('components.subject.edit-subject')->with('data', $data);
            
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
                'page' => 'subjects',
                'child' => 'database subjects',
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
                return redirect('/'.$role.'/subjects/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = MonthlyActivity::where('name', $request->name)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/subjects/edit/' . $id)->withErrors(['name' => ["The subject " . $request->name  ." is already created !!!"]])->withInput($rules);
            }

            MonthlyActivity::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_subject');

            return redirect('/'.$role.'/subjects');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        try {

            session()->flash('after_delete_subject');

            $getIdExam = Subject_exam::where('subject_id',$id)->value('id');

            MonthlyActivity::where('id', $id)->delete();
            Teacher_MonthlyActivity::where('subject_id', $id)->delete();
            Grade_MonthlyActivity::where('subject_id', $id)->delete();
            Subject_exam::where('subject_id',$id)->delete();

        if($getIdExam != null)
        {
            Exam::where('id', $getIdExam)->delete();
            Grade_exam::where('exam_id', $getIdExam)->delete();
            Score::where('exam_id', $getIdExam)->delete();
        }

            return redirect('/' .session('role'). '/subjects');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/subjects')->with('error', 'Terjadi kesalahan saat menghapus data subject.');
        }
    }
}
