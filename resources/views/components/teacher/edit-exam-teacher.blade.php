@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- general form elements -->
            <div class="col-md-6">
                <div>
                    <form method="POST" action={{route('actionUpdateExamTeacher', $data['dataExam']->id)}}>
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Update exam</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="name">Exam Name<span style="color: red">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                                placeholder="Enter Exam Name" value="{{ old('name') ? old('name') : $data['dataExam']->name_exam }}" autocomplete="off" required>
                                        @if($errors->has('name'))
                                                <p style="color: red">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="type_exam">Type<span style="color: red">*</span></label>
                                        <select required name="type_exam" class="form-control" id="type_exam">
                                                <option selected disabled>--- SELECT TYPE EXAM ---</option>
                                                @foreach($data['typeExam'] as $el)
                                                <option value="{{ $el->id }}" {{ $el->id == $data['dataExam']->type_exam_id ? 'selected' : '' }}>{{ $el->name }}</option>
                                                @endforeach
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
                                        <label for="semester">Semester<span style="color: red">*</span></label>
                                        <select required name="semester" class="form-control" id="semester">
                                                <option value="1" {{ $data['dataExam']['semester'] == 1 ? "selected" : "" }}>Semester 1</option>
                                                <option value="2" {{ $data['dataExam']['semester'] == 2 ? "selected" : "" }}>Semester 2</option>
                                        </select>
                                        @if($errors->has('type_exam'))
                                                <p style="color: red">{{ $errors->first('type_exam') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="teacher_id">Teacher<span style="color: red">*</span></label>
                                        <select required name="teacher_id" class="form-control" id="teacher_id">
                                                @foreach($data['teacher'] as $el)
                                                    <option value="{{ $el->id }}" {{ $el->id == $data['dataExam']->teacher_id ? 'selected' : '' }}>{{ $el->name }}</option>
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
                                        <input name="date_exam" type="date" class="form-control" id="date_exam" value="{{ $data['dataExam']->date_exam }}" required>
                                        
                                        @if($errors->has('date_exam'))
                                        <p style="color: red">{{ $errors->first('date_exam') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="materi">Materi<span style="color: red">*</span></label>
                                        <textarea required name="materi" class="form-control" id="materi" cols="10" rows="3">{{ $data['dataExam']->materi }}</textarea>
                                        
                                        @if($errors->has('materi'))
                                            <p style="color: red">{{ $errors->first('materi') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-center">
                                <input role="button" type="submit" class="btn btn-success center col-11 m-3">
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

    document.getElementById('teacher_id').addEventListener('change', function() {
        loadGradeOption(this.value)
            .then(() => loadSubjectOption(gradeSelect.value, this.value));
    });

    document.getElementById('grade_id').addEventListener('change', function() {
        loadSubjectOption(this.value, document.getElementById('teacher_id').value )
    });

    function loadGradeOption(teacherId) {
        gradeSelect.innerHTML = `<option value="" selected disabled>-- SELECT GRADE --</option>`;

        return fetch(`/get-grades/${teacherId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    // Jika data kosong, tambahkan opsi "Grade Empty"
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = 'Grade Empty';
                    gradeSelect.add(option);
                } else {
                    data.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.text = grade.name + ' - ' + grade.class;
                        if (grade.id == {{ $data['dataExam']->grade_id }}) {
                            option.selected = true;
                        }
                        gradeSelect.add(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    function loadSubjectOption(gradeId, teacherId) {
        if (!gradeId) {
            return;
        }

        subjectSelect.innerHTML = `<option value="" selected disabled>-- SELECT SUBJECT --</option>`;

        fetch(`/get-subjects/${gradeId}/${teacherId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    // Jika data kosong, tambahkan opsi "Subject Empty"
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = 'Subject Empty';
                    subjectSelect.add(option);
                } else {
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.text = subject.name_subject;
                        if (subject.id == {{ $data['dataExam']->subject_id }}) {
                            option.selected = true;
                        }
                        subjectSelect.add(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    window.onload = function() {
        loadGradeOption(document.getElementById('teacher_id').value)
            .then(() => loadSubjectOption(document.getElementById('grade_id').value, document.getElementById('teacher_id').value));
    };


</script>

@endsection
