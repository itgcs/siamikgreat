<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher_subject;
use App\Models\Grade_subject;
use App\Models\Subject_exam;
use App\Models\Exam;
use App\Models\Grade_exam;
use App\Models\Score;
use App\Models\Supplementary_subject;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplementarySubjectController extends Controller
{
    public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'subjects',
            'child' => 'database supplementary subjects',
            ]);

            $data = Supplementary_subject::with(['subject'])->get();
            
            return view('components.supplementarySubject.data-supplementarySubject')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'subjects',
                'child' => 'database supplementary subjects',
            ]);

            $data = Subject::get();

            return view('components.supplementarySubject.create-supplementarySubject')->with('data', $data);
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {
            $role = session('role');

            foreach($request->supplementary_subject as $ns){
                $post = [
                    'subject_id' => $ns,
                    'created_at'   => now(),
                ];
                
                Supplementary_subject::create($post);
            }

            session()->flash('after_create_supplementarySubject');

            DB::commit();
            
            return redirect('/'.  $role .'/supplementarySubjects');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function delete($id)
    {
        try {

            session()->flash('after_delete_subject');

            $getIdExam = Subject_exam::where('subject_id',$id)->value('id');

            Subject::where('id', $id)->delete();
            Teacher_subject::where('subject_id', $id)->delete();
            Grade_subject::where('subject_id', $id)->delete();
            Subject_exam::where('subject_id',$id)->delete();

        if($getIdExam != null)
        {
            Exam::where('id', $getIdExam)->delete();
            Grade_exam::where('exam_id', $getIdExam)->delete();
            Score::where('exam_id', $getIdExam)->delete();
        }

            return redirect('/superadmin/supplementarySubjects');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/superadmin/supplementarySubjects')->with('error', 'Terjadi kesalahan saat menghapus data supplementary subject.');
        }
    }
}
