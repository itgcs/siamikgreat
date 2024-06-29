@extends('layouts.admin.master')
@section('content')

<section class="content">
    @if (session('role') == 'superadmin')
        <form method="POST" action={{route('actionSuperCreateMidExam')}}>
    @elseif (session('role') == 'admin')
        <form method="POST" action={{route('actionAdminCreateMidExam')}}>
    @endif
    @csrf

    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div style="overflow-x: auto;">
                    <div class="card card-dark" style="width:1340px;">
                        <div class="card-header">
                            <h3 class="card-title">Create schedule mid exam</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label for="semester">Semester<span style="color: red"> *</span></label>
                                    <select required name="semester" class="form-control">
                                        <option value="">-- Select Semester -- </option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>

                                    @if($errors->has('semester'))
                                    <p style="color: red">{{ $errors->first('semester') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-2">
                                    <label for="type_schedule">Type Schedule<span style="color: red"> *</span></label>
                                    <select required name="type_schedule" class="form-control" id="type_schedule">
                                        @foreach($data['typeSchedule'] as $el)
                                        <option value="{{ $el->id }}" selected>{{ $el->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('type_schedule'))
                                    <p style="color: red">{{ $errors->first('type_schedule') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-2">
                                    <label for="grade_id">Grade<span style="color: red"> *</span></label>
                                    <select required name="grade_id" class="form-control" id="grade_id">
                                        @foreach($data['grade'] as $el)
                                        <option value="{{ $el->id }}" selected>{{ $el->name }} - {{ $el->class}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('grade_id'))
                                    <p style="color: red">{{ $errors->first('grade_id') }}</p>
                                    @endif
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="date">Date<span style="color: red"> *</span></label>
                                    <input name="date" type="date" class="form-control" id="date" required>
                                    @if($errors->has('date'))
                                        <p style="color: red">{{ $errors->first('date') }}</p>
                                    @endif
                                </div>
    
                                <div class="col-md-2">
                                    <label for="end_date">Until<span style="color: red"></span></label>
                                    <input name="end_date" type="date" class="form-control" id="_end_date">
                                    @if($errors->has('end_date'))
                                        <p style="color: red">{{ $errors->first('end_date') }}</p>
                                    @endif
                                </div>
                            </div>


                            <table class="table table-striped table-bordered" style="width: 1300px">
                                <thead>
                                    <th style="width: 14%;">Subject</th>
                                    <th style="width: 18%;">Teacher</th>
                                    <th style="width: 18%;">Teacher Companion</th>
                                    <th style="width: 8%;">Days</th>
                                    <th style="width: 8%;">Start Time</th>
                                    <th style="width: 8%;">End Time</th>
                                    <th style="width: 12%;">Notes</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="scheduleTableBody">
                                    <tr>
                                        <td>
                                            <select name="subject_id[]" class="form-control" id="subject_id"></select>
                                            @if($errors->has('subject_id'))
                                            <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <select name="teacher_id[]" class="form-control" id="teacher_id"> 
                                               <option value="" selected >-- SELECT TEACHERS --</option>
                                            </select>
                                            @if($errors->has('teacher_id'))
                                            <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <select name="teacher_companion[]" class="form-control" id="teacher_companion">
                                                <option value="" selected>-- Teacher Companion --</option>
                                                @foreach($data['teacher'] as $dt)
                                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('teacher_companion'))
                                            <p style="color: red">{{ $errors->first('teacher_companion') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <select required name="day[]" class="form-control">
                                                <option value="" class="text-xs">Day</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                            </select>

                                            @if($errors->has('day'))
                                            <p style="color: red">{{ $errors->first('day') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="start_time" name="start_time[]">
                                            @if($errors->has('start_time'))
                                            <p style="color: red">{{ $errors->first('start_time') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="end_time" name="end_time[]">
                                            @if($errors->has('end_time'))
                                            <p style="color: red">{{ $errors->first('end_time') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <textarea name="notes[]" class="form-control" id="notes" cols="10" rows="1"></textarea>
                                            @if($errors->has('notes'))
                                            <p style="color: red">{{ $errors->first('notes') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(document).ready(function() {
    // Function to add a new row
    function addRow() {
        var newRow = `<tr>
            <td>
                <select name="subject_id[]" class="form-control subject_id">
                    <option value="" selected > Select Subject</option>
                </select>
            </td>
            <td>
                <select name="teacher_id[]" class="form-control teacher_id">
                    <option value="" selected > Select Teacher </option>
                </select>
            </td>
            <td>
                <select name="teacher_companion[]" class="form-control">
                    <option value="" selected > Teacher Companion </option>
                    @foreach($data['teacher'] as $dt)
                    <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select required name="day[]" class="form-control">
                    <option value="" class="text-xs">Day</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                </select>
            </td>
            <td>
                <input type="time" class="form-control" name="start_time[]">
            </td>
            <td>
                <input type="time" class="form-control" name="end_time[]">
            </td>
            <td>
                <textarea name="notes[]" class="form-control" cols="10" rows="1"></textarea>
            </td>
            <td>
                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1" title="Hapus Baris"><i class="fa fa-times"></i></button>
            </td>
        </tr>`;
        $('#scheduleTableBody').append(newRow);

        // Call the function to populate subject and teacher options for the new row
        const newSubjectSelect = $('#scheduleTableBody tr:last .subject_id');
        const newTeacherSelect = $('#scheduleTableBody tr:last .teacher_id');

        loadSubjectOptionExam($('#grade_id').val(), newSubjectSelect);
        newSubjectSelect.change(function() {
            loadTeacherOption($('#grade_id').val(), $(this).val(), newTeacherSelect);
        });

        updateHapusButtons();
    }

    // Function to update the visibility of the "Hapus" buttons
    function updateHapusButtons() {
        $('#scheduleTableBody tr').each(function(index, row) {
            var hapusButton = $(row).find('.btn-hapus');
            if (index === $('#scheduleTableBody tr').length - 1) {
                hapusButton.removeClass('d-none');
            } else {
                hapusButton.addClass('d-none');
            }
        });
    }

    // Event listener for the "Tambah" button
    $('#scheduleTableBody').on('click', '.btn-tambah', function() {
        addRow();
    });

    // Event listener for the "Hapus" button
    $('#scheduleTableBody').on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        updateHapusButtons();
    });

    // Initial call to update the visibility of the "Hapus" buttons
    updateHapusButtons();
});

function loadSubjectOptionExam(gradeId, subjectSelect) {
    // Clear existing options and add the default option
    subjectSelect.html('<option value="" selected >Subject</option>');

    fetch(`/get-subjects/${gradeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                // If no subjects, add "Subject Empty" option
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Subject Empty';
                subjectSelect.append(option);
            } else {
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.text = subject.name_subject;
                    subjectSelect.append(option);
                });
            }
        })
        .catch(error => console.error(error));
}

function loadTeacherOption(gradeId, subjectId, teacherSelect) {
    // Clear existing options and add the default option
    teacherSelect.html('<option value="" selected >-- SELECT TEACHERS --</option>');

    fetch(`/get-teachers/${gradeId}/${subjectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                // If no teachers, add "Teacher Empty" option
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Teacher Empty';
                teacherSelect.append(option);
            } else {
                data.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.text = teacher.name;
                    teacherSelect.append(option);
                });
            }
        })
        .catch(error => console.error(error));
}

// Call loadSubjectOptionExam if grade_id is already selected
window.onload = function() {
    const gradeSelect = document.getElementById('grade_id');
    const subjectSelect = document.getElementById('subject_id');

    if (gradeSelect.value) {
        loadSubjectOptionExam(gradeSelect.value, $(subjectSelect));
    }

    $(subjectSelect).change(function() {
        const teacherSelect = document.getElementById('teacher_id');
        loadTeacherOption(gradeSelect.value, $(this).val(), $(teacherSelect));
    });
};

</script>

@if(session('after_create_midexam_schedule')) 

   <script>

      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
   
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully created new schedule in the database.',
      });
      }, 1500);


   </script>

@endif

@endsection
