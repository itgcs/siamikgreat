<?php

use App\Http\Controllers\Admin\{
   AdminController,
    DashboardController,
   GradeController,
    RegisterController,
   StudentController,
   TeacherController,
   RelationController,
   ExamController,
   SubjectController,
   TypeExamController,
   TypeScheduleController,
   ScoreController,
   MajorSubjectController,
   MinorSubjectController,
   SupplementarySubjectController,
   MasterScheduleController,
   MasterAcademicController,
};

use App\Http\Controllers\EcaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\ColorScheduleController;

use App\Http\Controllers\SuperAdmin\{
   SuperAdminController,
   StudentController as SuperStudentController
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Jobs\SendEmailJob;
use App\Jobs\SendMailReminder;
use App\Livewire\Counter;
use App\Mail\SendEmailTest;
use App\Models\Grade_subject;
use App\Models\Teacher_subject;
use App\Models\Teacher_grade;
use App\Models\Schedule;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'login']);
Route::post('/login', [UserController::class, 'actionLogin'])->name('actionLogin');
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/counter', Counter::class);

// Route untuk mengambil data subject
Route::get('/get-subjects/{gradeId}', function($gradeId) {
   $subjects = Grade_subject::join('subjects', 'grade_subjects.subject_id', '=', 'subjects.id')
   ->where('grade_id', $gradeId)
   ->get();
   return response()->json($subjects);
});

// Route untuk mengambil data guru
Route::get('/get-teachers/{gradeId}/{subjectId}', function($gradeId, $subjectId) {
   $subjects = Teacher_subject::join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
   ->where('grade_id', $gradeId)
   ->where('subject_id', $subjectId)
   ->get();
   return response()->json($subjects);
});

// Route untuk mengambil data subject teacher
Route::get('/get-subjects/{gradeId}/{teacherId}', function($gradeId, $teacherId) {
   $teachers = Teacher_subject::join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
   ->where('grade_id', $gradeId)
   ->where('teacher_id', $teacherId)
   ->get();
   return response()->json($teachers);
});

// Route untuk mengambil data grade teacher
Route::get('/get-grades/{teacherId}', function($teacherId) {
   $grades = Teacher_grade::join('grades', 'teacher_grades.grade_id', '=', 'grades.id')
   ->where('teacher_id', $teacherId)
   ->get();
   return response()->json($grades);
});

// Route untuk mengambil data schedule teacher
Route::get('/get-schedule/{day}/{startTime}/{endTime}', function($day, $startTime, $endTime) {
   $teacher = request('teacher');
   $grade = request('grade');

   $query = Schedule::where('day', $day)
       ->where('start_time', '>=', '08:00')
       ->where('end_time', '<', '14:00')
       ->where('note', '=', NULL)
       ->where('start_time', '!=', $startTime)
       ->where('end_time', '!=', $endTime)
       ->leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
       ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
       ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
       ->select(
           'schedules.*',
           'teachers.id as teacher_id',
           'teachers.name as teacher_name',
           'grades.id as grade_id',
           'grades.name as grade_name',
           'grades.class as grade_class',
           'subjects.id as subject_id',
           'subjects.name_subject as subject_name'
       );

   if ($teacher) {
       $query->where('teachers.id', $teacher);
   }

   if ($grade) {
       $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-companion/{day}/{startTime}/{endTime}', function($day, $startTime, $endTime) {
   $teacher = request('teacher');
   $grade = request('grade');

   $query = Schedule::where('day', $day)
       ->where('start_time', '>=', '08:00')
       ->where('end_time', '<', '14:00')
       ->where('note', '=', NULL)
       ->where('start_time', '!=', $startTime)
       ->where('end_time', '!=', $endTime)
       ->leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
       ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
       ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
       ->select(
           'schedules.*',
           'teachers.id as teacher_id',
           'teachers.name as teacher_companion',
           'grades.id as grade_id',
           'grades.name as grade_name',
           'grades.class as grade_class',
           'subjects.id as subject_id',
           'subjects.name_subject as subject_name'
       );

   if ($teacher) {
       $query->where('teachers.id', $teacher);
   }

   if ($grade) {
       $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-filter/{teacher}/{grade}', function($teacher, $grade) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
       ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
       ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
       ->select(
           'schedules.*',
           'teachers.id as teacher_id',
           'teachers.name as teacher_name',
           'grades.id as grade_id',
           'grades.name as grade_name',
           'grades.class as grade_class',
           'subjects.id as subject_id',
           'subjects.name_subject as subject_name'
       );

   if ($teacher !== 'null') {
       $query->where('teachers.id', $teacher);
   }

   if ($grade !== 'null') {
       $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-companion-filter/{teacher}/{grade}', function($teacher, $grade) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
       ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
       ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
       ->select(
           'schedules.*',
           'teachers.id as teacher_id',
           'teachers.name as teacher_companion',
           'grades.id as grade_id',
           'grades.name as grade_name',
           'grades.class as grade_class',
           'subjects.id as subject_id',
           'subjects.name_subject as subject_name'
       );

   if ($teacher !== 'null') {
       $query->where('teachers.id', $teacher);
   }

   if ($grade !== 'null') {
       $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-edit/{day}/{teacher}/{grade}', function($day, $teacher, $grade) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
       ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
       ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
       ->where('schedules.day', '=', $day)
       ->select(
           'schedules.*',
           'teachers.id as teacher_id',
           'teachers.name as teacher_name',
           'grades.id as grade_id',
           'grades.name as grade_name',
           'grades.class as grade_class',
           'subjects.id as subject_id',
           'subjects.name_subject as subject_name'
       );

   if ($teacher !== 'null') {
       $query->where('teachers.id', $teacher);
   }

   if ($grade !== 'null') {
       $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-companion-edit/{day}/{teacher}/{grade}', function($day, $teacher, $grade) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
       ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
       ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
       ->where('schedules.day', '=', $day)
       ->select(
           'schedules.*',
           'teachers.id as teacher_id',
           'teachers.name as teacher_companion',
           'grades.id as grade_id',
           'grades.name as grade_name',
           'grades.class as grade_class',
           'subjects.id as subject_id',
           'subjects.name_subject as subject_name'
       );

   if ($teacher !== 'null') {
       $query->where('teachers.id', $teacher);
   }

   if ($grade !== 'null') {
       $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});


// Route untuk mengambil data other schedule
Route::get('/get-schedule/{id}', function($id){
   $schedule = Schedule::where('schedules.id', $id)->first();

   return response()->json($schedule);
});

// Route untuk mengupdate other schedule
Route::post('/update-schedule/{id}', [ScheduleController::class, 'actionUpdateOtherSchedule'])->name('update.otherSchedule');

// Route untuk save semester kedalam session
Route::post('/save-semester-session', [UserController::class, 'saveSemesterToSession'])->name('save.semester.session');

// Route untuk menyimpan substitute teacher
Route::post('/subtitute-teacher', [ScheduleController::class, 'subtituteTeacher'])->name('subtitute.teacher');


Route::middleware(['auth.login', 'role:superadmin'])->prefix('/superadmin')->group(function () {

   Route::prefix('/teachers')->group(function () {
      Route::get('/', [TeacherController::class, 'index']);
      Route::post('/', [TeacherController::class, 'actionPost'])->name('actionSuperadminRegisterTeacher');
      Route::put('/{id}', [TeacherController::class, 'actionEdit'])->name('actionSuperUpdateTeacher');
      Route::get('/register', [TeacherController::class, 'pagePost']);
      Route::get('/{id}', [TeacherController::class, 'editPage']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::get('/delete/{id}', [TeacherController::class, 'delete'])->name('delete-teacher');
      Route::get('/teachers/{teacherId}/{gradeId}/{subjectId}', [TeacherController::class, 'deleteGradeSubject'])->name('deleteGradeSubject');
   });
   
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
   });

   Route::prefix('/user')->group(function () {
      Route::get('/change-password', [AdminController::class, 'changeMyPassword']);
      Route::put('/change-password', [AdminController::class, 'actionChangeMyPassword']);
   });
   
   Route::prefix('/detail')->group(function () {
      Route::get('/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/users')->group(function () {
      Route::get('/', [SuperAdminController::class, 'getUser']);
      Route::get('/register-user', [SuperAdminController::class, 'registerUser']);
      Route::get('/{id}', [SuperAdminController::class, 'getById']);
      Route::post('/register-action', [SuperAdminController::class, 'registerUserAction']);
      Route::put('/change-password',[SuperAdminController::class, 'changePassword'])->name('user.editPassword');
      Route::delete('{id}', [SuperAdminController::class, 'deleteUser']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegisterSuper');
      Route::get('/imports', [Import::class, 'index']);
      Route::post('/imports', [Import::class, 'upload'])->name('import.register_super');
      Route::get('/templates/students', [Import::class, 'downloadTemplate']);
   });

   Route::prefix('/list')->group(function () {
      Route::get('/', [StudentController::class, 'index']);
      Route::get('/detail/{id}', [StudentController::class, 'detail']);
   }); 

   Route::prefix('/update')->group(function () {
      Route::put('/{id}', [StudentController::class, 'actionEdit'])->name('student.update_super');
      Route::get('/{id}', [StudentController::class, 'edit']);
   });

   Route::prefix('/relations')->group(function () {
      Route::get('/', [RelationController::class, 'index']);
      Route::get('/detail/{id}', [RelationController::class, 'getById']);
      Route::get('/edit/{id}', [RelationController::class, 'editPage']);
      Route::put('/edit/{id}', [RelationController::class, 'actionEdit'])->name('actionUpdateRelation');
   });

   Route::prefix('/grades')->group(function () {
      Route::get('/', [GradeController::class, 'index']);
      Route::get('/create', [GradeController::class, 'pageCreate']);
      Route::get('/{id}', [GradeController::class, 'detailGrade']);
      Route::get('/edit/{id}', [GradeController::class, 'pageEdit']);
      Route::get('/manageSubject/{id}', [GradeController::class, 'pageEditSubject']);
      Route::get('/manageSubject/teacher/edit/{id}/{subjectId}/{teacherId}', [GradeController::class, 'pageEditSubjectTeacher']);
      Route::put('manageSubject/{id}', [GradeController::class, 'actionPutSubjectTeacher'])->name('actionAdminUpdateGradeSubjectTeacher');
      Route::post('/', [GradeController::class, 'actionPost'])->name('actionSuperCreateGrade');
      Route::put('/{id}', [GradeController::class, 'actionPut'])->name('actionSuperUpdateGrade');
      Route::get('/delete/{id}', [GradeController::class, 'delete'])->name('delete-grade');
   });

   Route::prefix('/exams')->group(function () {
      Route::get('/', [ExamController::class, 'index']);
      Route::get('/create', [ExamController::class, 'pageCreate']);
      Route::get('/{id}', [ExamController::class, 'getById']);
      Route::get('/edit/{id}', [ExamController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [ExamController::class, 'pagePDF']);
      Route::post('/', [ExamController::class, 'actionPost'])->name('actionSuperCreateExam');
      Route::put('/{id}', [ExamController::class, 'actionPut'])->name('actionSuperUpdateExam');
      Route::get('/done/{id}', [ExamController::class, 'doneExam'])->name('doneExam');
   });

   Route::prefix('/student')->group(function () {
      Route::get('/re-registration/{student_id}', [SuperStudentController::class, 'pageReRegis']);
      Route::patch('/{id}', [SuperStudentController::class, 'inactiveStudent']);
      Route::patch('/activate/{student_id}', [SuperStudentController::class, 'activateStudent']);
      Route::patch('/re-registration/{student_id}', [SuperStudentController::class, 'actionReRegis'])->name('action.re-regis');
   });

   Route::prefix('/subjects')->group(function () {
      Route::get('/', [SubjectController::class, 'index']);
      Route::get('/create', [SubjectController::class, 'pageCreate']);
      Route::get('/edit/{id}', [SubjectController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [SubjectController::class, 'pagePDF']);
      Route::post('/', [SubjectController::class, 'actionPost'])->name('actionSuperCreateSubject');
      Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('delete-subject');
   });

   Route::prefix('/majorSubjects')->group(function () {
      Route::get('/', [MajorSubjectController::class, 'index']);
      Route::get('/create', [MajorSubjectController::class, 'pageCreate']);
      Route::post('/', [MajorSubjectController::class, 'actionPost'])->name('actionSuperCreateMajorSubject');
      Route::get('/delete/{id}', [MajorSubjectController::class, 'delete'])->name('delete-majorsubject');
   });

   Route::prefix('/minorSubjects')->group(function () {
      Route::get('/', [MinorSubjectController::class, 'index']);
      Route::get('/create', [MinorSubjectController::class, 'pageCreate']);
      Route::post('/', [MinorSubjectController::class, 'actionPost'])->name('actionSuperCreateMinorSubject');
      Route::get('/delete/{id}', [MinorSubjectController::class, 'delete'])->name('delete-minorsubject');
   });

   Route::prefix('/supplementarySubjects')->group(function () {
      Route::get('/', [SupplementarySubjectController::class, 'index']);
      Route::get('/create', [SupplementarySubjectController::class, 'pageCreate']);
      Route::post('/', [SupplementarySubjectController::class, 'actionPost'])->name('actionSuperCreateSupplementarySubject');
      Route::put('/{id}', [SupplementarySubjectController::class, 'actionPut'])->name('actionSuperUpdateSupplementarySubject');
      Route::get('/delete/{id}', [SupplementarySubjectController::class, 'delete'])->name('delete-supplementarysubject');
   });
   
   Route::prefix('/typeExams')->group(function () {
      Route::get('/', [TypeExamController::class, 'index']);
      Route::get('/create', [TypeExamController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeExamController::class, 'pageEdit']);
      Route::post('/', [TypeExamController::class, 'actionPost'])->name('actionSuperCreateTypeExam');
      Route::put('/{id}', [TypeExamController::class, 'actionPut'])->name('actionSuperUpdateTypeExam');
      Route::get('/delete/{id}', [TypeExamController::class, 'delete']);
   });

   Route::prefix('/reports')->group(function () {
      Route::get('/', [ReportController::class, 'index']);
      Route::get('detail/{id}', [ReportController::class, 'detailSubjectClass']);
      Route::get('detailSec/{id}', [ReportController::class, 'detailSubjectClassSec']);
      Route::get('detailSubject/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudent']);
      Route::get('detailSubjectSec/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentSec']);

      Route::post('/scoringMajorPrimary', [ScoringController::class, 'actionPostMajorPrimary'])->name('actionPostScoringMajorPrimary');
      Route::post('/scoringMinorPrimary', [ScoringController::class, 'actionPostMinorPrimary'])->name('actionPostScoringMinorPrimary');
      Route::post('/scoringSecondary', [ScoringController::class, 'actionPostSecondary'])->name('actionPostScoringSecondary');
      
      
      Route::get('acar/detail/{id}', [ReportController::class, 'acarPrimary']);
      Route::post('/acarPrimary', [ScoringController::class, 'actionPostAcarPrimary'])->name('actionPostScoringAcarPrimary');
      Route::post('/acarSecondary', [ScoringController::class, 'actionPostAcarSecondary'])->name('actionPostScoringAcarSecondary');
      Route::get('acar/detailSec/{id}', [ReportController::class, 'acarSecondary']);

      Route::get('sooa/detail/{id}', [ReportController::class, 'sooaPrimary']);
      Route::get('sooa/detailSec/{id}', [ReportController::class, 'sooaSecondary']);
      Route::post('/sooaPrimary', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionPostScoringSooaPrimary');
      Route::post('/sooaSecondary', [ScoringController::class, 'actionPostSooaSecondary'])->name('actionPostScoringSooaSecondary');
      Route::post('/updateSooaPrimary/{id}', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionUpdateSooaPrimary');
      
      Route::get('tcop/detail/{id}', [ReportController::class, 'tcopPrimary']);

      Route::get('semestersatu/detail/{id}', [ReportController::class, 'cardSemester1']);
      Route::get('semesterdua/detail/{id}', [ReportController::class, 'cardSemester1']);
   });

   Route::prefix('/schedules')->group(function () {
      Route::get('/all', [ScheduleController::class, 'allScheduleSchools']);
      Route::get('/schools', [ScheduleController::class, 'scheduleSchools']);
      Route::get('/grades', [ScheduleController::class, 'scheduleGrades']);
      Route::get('/manage/{id}', [ScheduleController::class, 'managePage']);  
      Route::get('/schools/manage/otherSchedule', [ScheduleController::class, 'manageOtherSchedulePage']);  
      Route::get('grade/create/{id}', [ScheduleController::class, 'create']);
      Route::post('/scheduleGrade', [ScheduleController::class, 'actionCreate'])->name('actionSuperCreateSchedule');
      Route::post('schedules/schools', [ScheduleController::class, 'actionCreateOther'])->name('actionSuperCreateOtherSchedule');
      Route::get('detail/{id}', [ScheduleController::class, 'detail']);
      Route::get('edit/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPage']);
      Route::put('/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeSchedule'])->name('actionSuperEditSchedule');
      Route::put('subtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeScheduleSubtitute'])->name('actionSuperEditScheduleSubtitute');

      Route::get('/delete/{id}', [ScheduleController::class, 'delete']);
      Route::get('/deleteSubtitute/{id}', [ScheduleController::class, 'deleteSubtitute']);
      Route::get('/otherSchedule/delete/{id}', [ScheduleController::class, 'deleteOtherSchedule']);
   });

   Route::prefix('/typeSchedules')->group(function () {
      Route::get('/', [TypeScheduleController::class, 'index']);
      Route::get('/create', [TypeScheduleController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeScheduleController::class, 'pageEdit']);
      Route::post('/', [TypeScheduleController::class, 'actionPost'])->name('actionSuperCreateTypeSchedule');
      Route::put('/{id}', [TypeScheduleController::class, 'actionPut'])->name('actionSuperUpdateTypeSchedule');
      Route::get('/delete/{id}', [TypeScheduleController::class, 'delete']);
   });

   Route::prefix('/masterSchedules')->group(function () {
      Route::get('/', [MasterScheduleController::class, 'index']);
      Route::get('/create', [MasterScheduleController::class, 'pageCreate']);
      Route::get('/edit/{id}', [MasterScheduleController::class, 'pageEdit']);
      Route::post('/', [MasterScheduleController::class, 'actionPost'])->name('actionSuperCreateMasterSchedule');
      Route::put('/{id}', [MasterScheduleController::class, 'actionPut'])->name('actionSuperUpdateMasterSchedule');
      Route::get('/delete/{id}', [MasterScheduleController::class, 'delete']);
   });

   Route::prefix('/masterAcademics')->group(function () {
      Route::get('/', [MasterAcademicController::class, 'index']);
      Route::get('/create', [MasterAcademicController::class, 'pageCreate']);
      Route::get('/edit', [MasterAcademicController::class, 'pageEdit']);
      Route::post('/', [MasterAcademicController::class, 'actionPost'])->name('actionSuperCreateMasterAcademic');
      Route::put('/{id}', [MasterAcademicController::class, 'actionPut'])->name('actionSuperUpdateMasterAcademic');
      Route::get('/delete/{id}', [MasterAcademicController::class, 'delete']);
   });

   Route::prefix('/attendances')->group(function () {
      Route::get('/', [AttendanceController::class, 'index']);
      Route::get('/subject/{id}', [AttendanceController::class, 'subject']);
      Route::get('/subject/student/{gradeId}/{subjectId}', [AttendanceController::class, 'detailAttend']);
      Route::post('/postScoreAttendance', [ScoringController::class, 'actionPostScoreAttendance'])->name('actionPostScoringAttendance');
   });

   Route::prefix('/reportCard')->group(function () {
      Route::get('/', [Pdf::class, 'index']);
   });

});

Route::middleware(['auth.login', 'role:admin'])->prefix('/admin')->group(function () {

   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
   });

   Route::prefix('/user')->group(function () {

      Route::get('/change-password', [AdminController::class, 'changeMyPassword']);
      Route::put('/change-password', [AdminController::class, 'actionChangeMyPassword']);
   });
   
   Route::prefix('/detail')->group(function () {
      Route::get('/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/users')->group(function () {
      Route::get('/', [SuperAdminController::class, 'getUser']);
      Route::get('/register-user', [SuperAdminController::class, 'registerUser']);
      Route::get('/{id}', [SuperAdminController::class, 'getById']);
      Route::post('/register-action', [SuperAdminController::class, 'registerUserAction']);
      Route::put('/change-password',[SuperAdminController::class, 'changePassword'])->name('user.editPassword');
      Route::delete('{id}', [SuperAdminController::class, 'deleteUser']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegisterAdmin');Route::get('/imports', [Import::class, 'index']);
      Route::post('/imports', [Import::class, 'upload'])->name('import.register_admin');
      Route::get('/templates/students', [Import::class, 'downloadTemplate']);
   });

   Route::prefix('/list')->group(function () {
      Route::get('/', [StudentController::class, 'index']);
      Route::get('/detail/{id}', [StudentController::class, 'detail']);
   }); 

   Route::prefix('/update')->group(function () {
      Route::put('/{id}', [StudentController::class, 'actionEdit'])->name('student.update_admin');
      Route::get('/{id}', [StudentController::class, 'edit']);
   });

   Route::prefix('/teachers')->group(function () {
      Route::get('/', [TeacherController::class, 'index']);
      Route::post('/', [TeacherController::class, 'actionPost'])->name('actionAdminRegisterTeacher');
      Route::put('/{id}', [TeacherController::class, 'actionEdit'])->name('actionAdminUpdateTeacher');
      Route::get('/register', [TeacherController::class, 'pagePost']);
      Route::get('/{id}', [TeacherController::class, 'editPage']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
   });

   Route::prefix('/relations')->group(function () {
      Route::get('/', [RelationController::class, 'index']);
      Route::get('/detail/{id}', [RelationController::class, 'getById']);
      Route::get('/edit/{id}', [RelationController::class, 'editPage']);
      Route::put('/edit/{id}', [RelationController::class, 'actionEdit'])->name('actionUpdateRelation');
   });

   Route::prefix('/grades')->group(function () {
      Route::get('/', [GradeController::class, 'index']);
      Route::get('/create', [GradeController::class, 'pageCreate']);
      Route::get('/{id}', [GradeController::class, 'detailGrade']);
      Route::get('/edit/{id}', [GradeController::class, 'pageEdit']);
      Route::get('/manageSubject/{id}', [GradeController::class, 'pageEditSubject']);
      Route::get('/manageSubject/teacher/edit/{id}/{subjectId}/{teacherId}', [GradeController::class, 'pageEditSubjectTeacher']);
      Route::put('manageSubject/{id}', [GradeController::class, 'actionPutSubjectTeacher'])->name('actionAdminUpdateGradeSubjectTeacher');
      Route::post('/', [GradeController::class, 'actionPost'])->name('actionAdminCreateGrade');
      Route::put('/{id}', [GradeController::class, 'actionPut'])->name('actionAdminUpdateGrade');
   });

   Route::prefix('/exams')->group(function () {
      Route::get('/', [ExamController::class, 'index']);
      Route::get('/create', [ExamController::class, 'pageCreate']);
      Route::get('/{id}', [ExamController::class, 'getById']);
      Route::get('/edit/{id}', [ExamController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [ExamController::class, 'pagePDF']);
      Route::post('/', [ExamController::class, 'actionPost'])->name('actionAdminCreateExam');
      Route::put('/{id}', [ExamController::class, 'actionPut'])->name('actionAdminUpdateExam');
   });

   Route::prefix('/student')->group(function () {
      Route::get('/re-registration/{student_id}', [SuperStudentController::class, 'pageReRegis']);
      Route::patch('/{id}', [SuperStudentController::class, 'inactiveStudent']);
      Route::patch('/activate/{student_id}', [SuperStudentController::class, 'activateStudent']);
      Route::patch('/re-registration/{student_id}', [SuperStudentController::class, 'actionReRegis'])->name('action.re-regis');
   });

   Route::prefix('/subjects')->group(function () {
      Route::get('/', [SubjectController::class, 'index']);
      Route::get('/create', [SubjectController::class, 'pageCreate']);
      Route::get('/edit/{id}', [SubjectController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [SubjectController::class, 'pagePDF']);
      Route::post('/', [SubjectController::class, 'actionPost'])->name('actionAdminCreateSubject');
      Route::put('/{id}', [SubjectController::class, 'actionPut'])->name('actionAdminUpdateSubject');
      Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('delete-subject');
   });

   Route::prefix('/reports')->group(function () {
      Route::get('/', [ReportController::class, 'index']);
      Route::get('detail/{id}', [ReportController::class, 'detailSubjectClass']);
      Route::get('detailSec/{id}', [ReportController::class, 'detailSubjectClassSec']);
      Route::get('detailSubject/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudent']);
      Route::get('detailSubjectSec/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentSec']);

      Route::post('scoringMajorPrimary', [ScoringController::class, 'actionPostMajorPrimary'])->name('actionAdminPostScoringMajorPrimary');
      Route::post('scoringMinorPrimary', [ScoringController::class, 'actionPostMinorPrimary'])->name('actionAdminPostScoringMinorPrimary');
      Route::post('scoringSecondary', [ScoringController::class, 'actionPostSecondary'])->name('actionAdminPostScoringSecondary');
      
      
      Route::get('acar/detail/{id}', [ReportController::class, 'acarPrimary']);
      Route::post('acarPrimary', [ScoringController::class, 'actionPostAcarPrimary'])->name('actionAdminPostScoringAcarPrimary');
      Route::post('acarSecondary', [ScoringController::class, 'actionPostAcarSecondary'])->name('actionAdminPostScoringAcarSecondary');
      Route::get('acar/detailSec/{id}', [ReportController::class, 'acarSecondary']);


      Route::get('sooa/detail/{id}', [ReportController::class, 'sooaPrimary']);
      Route::get('sooa/detailSec/{id}', [ReportController::class, 'sooaSecondary']);
      Route::post('sooaPrimary', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionAdminPostScoringSooaPrimary');
      Route::post('sooaSecondary', [ScoringController::class, 'actionPostSooaSecondary'])->name('actionAdminPostScoringSooaSecondary');
      Route::post('updateSooaPrimary/{id}', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionAdminUpdateSooaPrimary');

      Route::get('tcop/detail/{id}', [ReportController::class, 'tcopPrimary']);

      Route::get('acar/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'acarDecline']); // Sudah termasuk acar primary dan secondary
      Route::get('sooa/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'sooaPrimaryDecline']);
      Route::get('scoring/decline/{gradeId}/{teacherId}/{subjectId}/{semester}', [ReportController::class, 'scoringDecline']);
      Route::get('reportCard/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'reportCardDecline']);

      Route::get('semestersatu/detail/{id}', [ReportController::class, 'cardSemester1']);
      Route::get('semesterdua/detail/{id}', [ReportController::class, 'cardSemester2']);
   });

   Route::prefix('/schedules')->group(function () {
      Route::get('/all', [ScheduleController::class, 'allScheduleSchools']);
      Route::get('/schools', [ScheduleController::class, 'scheduleSchools']);
      
      Route::get('/grade/create/{id}', [ScheduleController::class, 'create']);
      Route::post('/scheduleGrade', [ScheduleController::class, 'actionCreate'])->name('actionAdminCreateSchedule');
      Route::get('/grades', [ScheduleController::class, 'scheduleGrades']);
      Route::get('/manage/{id}', [ScheduleController::class, 'managePage']);  
      Route::get('/detail/{id}', [ScheduleController::class, 'detail']);
      Route::get('/edit/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPage']);
      Route::put('/schedule/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeSchedule'])->name('actionAdminEditSchedule');
      Route::get('/delete/{id}', [ScheduleController::class, 'delete']);
      
      Route::get('/midexam/create/{id}', [ScheduleController::class, 'createMidExam']);
      Route::post('/midExam', [ScheduleController::class, 'actionCreateMidExam'])->name('actionAdminCreateMidExam');
      Route::get('/midexams', [ScheduleController::class, 'scheduleMidExams']);
      Route::get('/manage/midexam/{id}', [ScheduleController::class, 'managePageMidExam']);
      Route::get('/detail/midexam/{id}', [ScheduleController::class, 'detailMidExam']);
      Route::get('/edit/midexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageMidExam']);
      Route::put('/midexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateMidExam'])->name('actionAdminEditMidExam');
      Route::get('/delete/midexam/{id}', [ScheduleController::class, 'deleteMidExam']);
      
      Route::get('/finalexam/create/{id}', [ScheduleController::class, 'createFinalExam']);
      Route::post('/finalExam', [ScheduleController::class, 'actionCreateFinalExam'])->name('actionAdminCreateFinalExam');
      Route::get('/finalexams', [ScheduleController::class, 'scheduleFinalExams']);
      Route::get('/manage/finalexam/{id}', [ScheduleController::class, 'managePageFinalExam']);
      Route::get('/detail/finalexam/{id}', [ScheduleController::class, 'detailFinalExam']);
      Route::get('/edit/finalexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageFinalExam']);
      Route::put('/finalexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateFinalExam'])->name('actionAdminEditFinalExam');
      Route::get('/delete/finalexam/{id}', [ScheduleController::class, 'deleteFinalExam']);

      Route::post('/schedules/schools', [ScheduleController::class, 'actionCreateOther'])->name('actionAdminCreateOtherSchedule');

      Route::get('/schools/manage/otherSchedule', [ScheduleController::class, 'manageOtherSchedulePage']);  
      Route::get('/editSubtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageSubtitute']);
      Route::put('/subtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeScheduleSubtitute'])->name('actionAdminEditScheduleSubtitute');

      Route::get('/deleteSubtitute/{id}', [ScheduleController::class, 'deleteSubtitute']);
      Route::get('/otherSchedule/delete/{id}', [ScheduleController::class, 'deleteOtherSchedule']);
   });

   Route::prefix('/typeExams')->group(function () {
      Route::get('/', [TypeExamController::class, 'index']);
      Route::get('/create', [TypeExamController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeExamController::class, 'pageEdit']);
      Route::post('/', [TypeExamController::class, 'actionPost'])->name('actionAdminCreateTypeExam');
      Route::put('/{id}', [TypeExamController::class, 'actionPut'])->name('actionAdminUpdateTypeExam');
      Route::get('/delete/{id}', [TypeExamController::class, 'delete']);
   });

   Route::prefix('/typeSchedules')->group(function () {
      Route::get('/', [TypeScheduleController::class, 'index']);
      Route::get('/create', [TypeScheduleController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeScheduleController::class, 'pageEdit']);
      Route::post('/', [TypeScheduleController::class, 'actionPost'])->name('actionAdminCreateTypeSchedule');
      Route::put('/{id}', [TypeScheduleController::class, 'actionPut'])->name('actionAdminUpdateTypeSchedule');
      Route::get('/delete/{id}', [TypeScheduleController::class, 'delete']);
   });

   Route::prefix('/masterSchedules')->group(function () {
      Route::get('/', [MasterScheduleController::class, 'index']);
      Route::get('/create', [MasterScheduleController::class, 'pageCreate']);
      Route::get('/edit/{id}', [MasterScheduleController::class, 'pageEdit']);
      Route::post('/', [MasterScheduleController::class, 'actionPost'])->name('actionAdminCreateMasterSchedule');
      Route::put('/{id}', [MasterScheduleController::class, 'actionPut'])->name('actionAdminUpdateMasterSchedule');
      Route::get('/delete/{id}', [MasterScheduleController::class, 'delete']);
   });

   Route::prefix('/masterAcademics')->group(function () {
      Route::get('/', [MasterAcademicController::class, 'index']);
      Route::get('/create', [MasterAcademicController::class, 'pageCreate']);
      Route::get('/edit', [MasterAcademicController::class, 'pageEdit']);
      Route::post('/', [MasterAcademicController::class, 'actionPost'])->name('actionAdminCreateMasterAcademic');
      Route::put('/{id}', [MasterAcademicController::class, 'actionPut'])->name('actionAdminUpdateMasterAcademic');
      Route::get('/delete/{id}', [MasterAcademicController::class, 'delete']);
   });

   Route::prefix('/attendances')->group(function () {
      Route::get('/', [AttendanceController::class, 'index']);
      Route::get('/subject/{id}', [AttendanceController::class, 'subject']);
      Route::get('/subject/student/{gradeId}/{subjectId}', [AttendanceController::class, 'detailAttend']);
      Route::post('/postScoreAttendance', [ScoringController::class, 'actionPostScoreAttendance'])->name('actionAdminPostScoringAttendance');

      Route::get('/teacher/grade/subject', [AttendanceController::class, 'detailAttendance'])->name('attendance.detail');
   });

   Route::prefix('/majorSubjects')->group(function () {
      Route::get('/', [MajorSubjectController::class, 'index']);
      Route::get('/create', [MajorSubjectController::class, 'pageCreate']);
      Route::post('/', [MajorSubjectController::class, 'actionPost'])->name('actionAdminCreateMajorSubject');
      Route::get('/delete/{id}', [MajorSubjectController::class, 'delete'])->name('delete-majorsubject');
   });

   Route::prefix('/minorSubjects')->group(function () {
      Route::get('/', [MinorSubjectController::class, 'index']);
      Route::get('/create', [MinorSubjectController::class, 'pageCreate']);
      Route::post('/', [MinorSubjectController::class, 'actionPost'])->name('actionAdminCreateMinorSubject');
      Route::get('/delete/{id}', [MinorSubjectController::class, 'delete'])->name('delete-minorsubject');
   });

   Route::prefix('/supplementarySubjects')->group(function () {
      Route::get('/', [SupplementarySubjectController::class, 'index']);
      Route::get('/create', [SupplementarySubjectController::class, 'pageCreate']);
      Route::post('/', [SupplementarySubjectController::class, 'actionPost'])->name('actionAdminCreateSupplementarySubject');
      Route::put('/{id}', [SupplementarySubjectController::class, 'actionPut'])->name('actionAdminUpdateSupplementarySubject');
      Route::get('/delete/{id}', [SupplementarySubjectController::class, 'delete'])->name('delete-supplementarysubject');
   });

   Route::prefix('/eca')->group(function () {
      Route::get('/', [EcaController::class, 'index']);
      Route::get('/create', [EcaController::class, 'pageCreate']);
      Route::get('/add/{id}', [EcaController::class, 'addStudent']);
      Route::post('/addStudent', [EcaController::class, 'actionAddStudent'])->name('actionAdminAddStudent');
      Route::post('/', [EcaController::class, 'actionPost'])->name('actionAdminCreateEca');
      Route::put('/{id}', [EcaController::class, 'actionPut'])->name('actionAdminUpdateEca');
      Route::get('/delete/{id}', [EcaController::class, 'delete'])->name('delete-eca');
   });
});

Route::middleware(['auth.login', 'role:teacher'])->prefix('/teacher')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::get('/edit/{id}', [TeacherController::class, 'editPage']);
      Route::put('/edit/{id}', [TeacherController::class, 'actionEdit'])->name('actionUpdateSelfTeacher');
      

      Route::get('attendance/{id}', [AttendanceController::class, 'attendTeacher']);
      Route::get('attendance/gradeTeacher/{id}', [AttendanceController::class, 'gradeTeacher']);
      Route::get('attendance/subjectTeacher/{id}', [AttendanceController::class, 'subjectTeacher']);
      Route::get('attendance/{id}/{gradeId}', [AttendanceController::class, 'detail'])->name('attendanceSubject');
      Route::get('attendance/teacher/grade/subject', [AttendanceController::class, 'detailAttendTeacher'])->name('attendance.detail.teacher');
      Route::get('attendance/view/student/{id}/{gradeId}/{subjectId}', [AttendanceController::class, 'detailViewAttendTeacher']);
      Route::post('/', [AttendanceController::class, 'postAttendance'])->name('actionUpdateAttendanceStudent');
      Route::post('/postScoreAttendance', [ScoringController::class, 'actionPostScoreAttendance'])->name('actionTeacherPostScoringAttendance');


      Route::get('/grade/{id}', [GradeController::class, 'teacherGrade']);
      
      Route::get('/exam/{id}', [ExamController::class, 'teacherExam'])->name('teacher.dashboard.exam');
      Route::get('exam/create/{id}', [ExamController::class, 'createTeacherExam']);
      Route::post('/exam', [ExamController::class, 'actionPost'])->name('actionCreateExamTeacher');
      Route::get('exam/detail/{id}', [ExamController::class, 'getById']);
      Route::get('exam/edit/{id}', [ExamController::class, 'pageEdit']);
      Route::put('exam/edit/{id}', [ExamController::class, 'actionPut'])->name('actionUpdateExamTeacher');
      Route::put('/{id}', [ScoreController::class, 'doneExam'])->name('doneExam');

      Route::get('exam/score/{id}', [ScoreController::class, 'score']);
      Route::put('/', [ScoreController::class, 'actionUpdateScore'])->name('actionUpdateScoreExamTeacher');
      
      Route::get('report/{id}', [ReportController::class, 'teacherReport']);
      Route::get('report/detail/{id}', [ReportController::class, 'detail']);
      Route::get('report/classTeacher/{id}', [ReportController::class, 'classTeacher']);
      Route::get('report/subjectTeacher/{id}', [ReportController::class, 'subjectTeacher']);
      Route::get('report/detailSubjectPrimary/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentTeacher']);
      Route::get('report/detailSubjectSecondary/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentSecTeacher']);
      
      Route::post('/scoringMajorPrimary', [ScoringController::class, 'actionPostMajorPrimary'])->name('actionTeacherPostScoringMajorPrimary');
      Route::post('/scoringMinorPrimary', [ScoringController::class, 'actionPostMinorPrimary'])->name('actionTeacherPostScoringMinorPrimary');
      Route::post('/scoringSecondary', [ScoringController::class, 'actionPostSecondary'])->name('actionTeacherPostScoringSecondary');

      Route::get('report/acar/detail/{id}', [ReportController::class, 'acarPrimary']);
      Route::post('report/acarPrimary', [ScoringController::class, 'actionPostAcarPrimary'])->name('actionTeacherPostScoringAcarPrimary');
      Route::post('report/acarSecondary', [ScoringController::class, 'actionPostAcarSecondary'])->name('actionTeacherPostScoringAcarSecondary');
      Route::get('report/acar/detailSec/{id}', [ReportController::class, 'acarSecondary']);

      Route::get('report/sooa/detail/{id}', [ReportController::class, 'sooaPrimary']);
      Route::get('report/sooa/detailSec/{id}', [ReportController::class, 'sooaSecondary']);
      Route::post('report/sooaPrimary', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionTeacherPostScoringSooaPrimary');
      Route::post('report/sooaSecondary', [ScoringController::class, 'actionPostSooaSecondary'])->name('actionTeacherPostScoringSooaSecondary');
      Route::post('report/updateSooaPrimary/{id}', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionUpdateSooaPrimary');
      
      Route::get('report/card/semestersatu/{id}', [ReportController::class, 'cardSemester1']);
      Route::get('report/card/semesterdua/{id}', [ReportController::class, 'cardSemester2']);
      Route::get('report/cardSec/semestersatu/{id}', [ReportController::class, 'cardSemester1Sec']);
      Route::get('report/cardSec/semesterdua/{id}', [ReportController::class, 'cardSemester2Sec']);
      

      Route::get('report/tcop/detail/{id}', [ReportController::class, 'tcopPrimary']);
      Route::get('report/tcop/detailSec/{id}', [ReportController::class, 'tcopSecondary']);

      Route::get('report/semester1/print/{id}', [ReportController::class, 'downloadPDFSemester1']);
      Route::post('report/reportCard1', [ScoringController::class, 'actionPostReportCard1'])->name('actionTeacherPostReportCard1');
      Route::get('report/semester2/print/{id}', [ReportController::class, 'downloadPDFSemester2']);
      Route::post('report/reportCard2', [ScoringController::class, 'actionPostReportCard2'])->name('actionTeacherPostReportCard2');

   
      Route::get('schedules/grade/{id}', [ScheduleController::class, 'scheduleGradeTeacher']);
      Route::get('schedules/gradeOther/{id}', [ScheduleController::class, 'scheduleGradeTeacherOther']);
      Route::get('schedules/subject/{id}', [ScheduleController::class, 'scheduleSubjectTeacher']);
      Route::get('schedules/companion/{id}', [ScheduleController::class, 'scheduleCompanionTeacher']);
      Route::get('schools/{id}', [ScheduleController::class, 'scheduleTeacherSchools']);
      Route::get('schedules/detail/{teacherId}/{gradeId}', [ScheduleController::class, 'detailScheduleTeacher']);
   });
});

Route::middleware(['auth.login', 'role:student'])->prefix('/student')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::get('/grade/{id}', [GradeController::class, 'studentGrade']);
      Route::get('/exam/{id}', [ExamController::class, 'gradeExam'])->name('student.dashboard.exam');
      Route::get('exam/detail/{id}', [ExamController::class, 'getById']);
      Route::get('relation/{id}', [RelationController::class, 'getById']);

      Route::get('/schools/{gradeId}', [ScheduleController::class, 'scheduleStudentSchools']);
      Route::get('schedules/grade', [ScheduleController::class, 'scheduleStudent']);
   });
});

Route::middleware(['auth.login', 'role:parent'])->prefix('/parent')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::get('/grade/{id}', [GradeController::class, 'studentGrade']);
      Route::get('/exam', [ExamController::class, 'gradeExam'])->name('student.dashboard.exam');
      Route::get('exam/detail/{id}', [ExamController::class, 'getById']);
      Route::get('relation/{id}', [RelationController::class, 'getById']);
      Route::get('/score', [ReportController::class, 'detail']);

      Route::get('/schools', [ScheduleController::class, 'scheduleStudentSchools']);
      Route::get('schedules/grade', [ScheduleController::class, 'scheduleStudent']);
   });
});
