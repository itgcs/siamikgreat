@extends('layouts.admin.master')
@section('content')

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher')}}">Exam</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Create Exam</li>
               </ol>
            </nav>
         </div>
      </div>

      <div class="row d-flex justify-content-center">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div>
               <form method="POST" action="{{ route('actionCreateExamTeacher') }}">
                  @csrf
                  <div class="card card-dark">
                     <div class="card-header">
                           <h3 class="card-title">Create exam</h3>
                     </div>
                     <!-- /.card-header -->
                     <!-- form start -->
                     <div class="card-body">
                        <div class="form-group row">
                           <div class="col-md-12 d-none">
                              <label for="semester">Semester<span style="color: red">*</span></label>
                              <select required name="semester" class="form-control" id="semester">
                                 <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
                                 <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
                              </select>
                              @if($errors->has('type_exam'))
                                    <p style="color: red">{{ $errors->first('type_exam') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-6">
                              <label for="grade_id">Grade<span style="color: red">*</span></label>
                              <select required name="grade_id" class="form-control" id="grade_id">
                                    <option selected disabled>--- SELECT GRADE ---</option>
                                    @foreach($data['grade'] as $el)
                                       <option value="{{ $el->id }}">{{ $el->name }} - {{ $el->class}}</option>
                                    @endforeach
                              </select>
                              @if($errors->has('grade_id'))
                                    <p style="color: red">{{ $errors->first('grade_id') }}</p>
                              @endif
                           </div>
                           <div class="col-md-6">
                              <label for="subject_id">Subject<span style="color: red">*</span></label>
                              <select required name="subject_id" class="form-control" id="subject_id">
                                 
                              </select>
                              @if($errors->has('subject_id'))
                                    <p style="color: red">{{ $errors->first('subject_id') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="type_exam">Type<span style="color: red">*</span></label>
                              <select required name="type_exam" class="form-control" id="type_exam">
                                    <option selected disabled>--- SELECT TYPE EXAM ---</option>
                                    @foreach($data['type_exam'] as $el)
                                       <option value="{{ $el->id }}">{{ $el->name }}</option>
                                    @endforeach
                              </select>
                              @if($errors->has('type_exam'))
                                    <p style="color: red">{{ $errors->first('type_exam') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="name">Exam Name<span style="color: red">*</span></label>
                              <input name="name" type="text" class="form-control" id="name"
                                    placeholder="Enter Exam Name" value="{{ old('name') }}" autocomplete="off" required>
                              @if($errors->has('name'))
                                    <p style="color: red">{{ $errors->first('name') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row d-none">
                           <div class="col-md-12">
                              <label for="teacher_id">Teacher<span style="color: red">*</span></label>
                              <select required name="teacher_id" class="form-control" id="teacher_id">
                                    @foreach($data['teacher'] as $el)
                                       <option value="{{ $el->id}}" selected>{{ $el->name }}</option>
                                    @endforeach
                              </select>
                              @if($errors->has('teacher_id'))
                                    <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="date_exam">Date Exam<span style="color: red">*</span></label>
                              <input name="date_exam" type="date" class="form-control" id="date_exam" required>
                              
                              @if($errors->has('date_exam'))
                                 <p style="color: red">{{ $errors->first('exam') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="materi">Materi<span style="color: red">*</span></label>
                              <textarea required name="materi" class="form-control" id="materi" cols="10" rows="3"></textarea>
                              
                              @if($errors->has('materi'))
                                 <p style="color: red">{{ $errors->first('materi') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="row d-flex justify-content-center">
                           <div class="col-md-12">
                              <input role="button" type="submit" class="btn btn-success center col-12">
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
   var gradeSelect   = document.getElementById("grade_id");
   var subjectSelect = document.getElementById("subject_id");
   var teacherSelect = document.getElementById("teacher_id");
   
   document.getElementById('grade_id').addEventListener('change', function() {
      loadSubjectOptionExam(this.value, teacherSelect.value);
   });

   function loadSubjectOptionExam(gradeId, teacherId) {
   // Tambahkan opsi "-- SELECT SUBJECT --" sebagai opsi pertama
      subjectSelect.innerHTML = '<option value="" selected disabled>-- SELECT SUBJECT --</option>';

      fetch(`/get-subjects/${gradeId}/${teacherId}`)
        .then(response => response.json())
        .then(data => {
            // Tambahkan opsi baru ke select subject
            if (data.length === 0) {
                // Jika data kosong, tambahkan opsi "Subject kosong"
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Subject Empty';
                subjectSelect.add(option);
            } else {
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.text = subject.name_subject;
                    subjectSelect.add(option);
                });
            }
        })
        .catch(error => console.error(error));
   }

</script>

@endsection
