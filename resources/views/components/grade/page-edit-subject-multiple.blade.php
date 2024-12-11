@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades')}}">Grade</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/edit/' . $data['grade_id'])}}">Edit {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/manageSubject/' . $data['grade_id'])}}">Manage Subject & Teacher {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $data['subject_name'] }} {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</li>
                </ol>
            </nav>
            </div>
        </div>

        <div class="row d-flex justify-content-center mt-3">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateGradeSubjectMultiTeacher', $data->teacher_subject_id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateGradeSubjectMultiTeacher', $data->teacher_subject_id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit Subject Teacher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $data->grade_id }}" class="d-none" name="grade_id" id="grade_id">
                                        <input type="text" value="{{ $data->subject_id }}" class="d-none" name="before_subject_id" id="before_subject_id">
                                        <label for="name">Subject<span style="color: red"> *</span></label>
                                        <select name="subject" id="subject" class="form-control">
                                            <option value="{{ $data->subject_id }}" selected>{{ $data->subject_name }}</option>
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="name">Main Teacher<span style="color: red"> *</span></label>
                                        <select name="main_teacher" id="teacher" class="form-control js-select2">
                                            @foreach ($dataMultiple['is_lead'] as $leadTeacher)
                                                <option value="{{ $leadTeacher['id'] }}" selected>{{ $leadTeacher['name'] }}</option>
                                            @endforeach
                                            @foreach ($teacher as $teacher)
                                                <option value="{{$teacher->id}}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('teacher')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <table class="table table-striped projects">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align:left;">For Edit Subject Group</th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                    #
                                                    </th>
                                                    <th style="width: 20%">
                                                        Teacher
                                                    </th>
                                                    <th style="width: 80%">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataMultiple['is_group'] as $el)
                                                <tr>
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $el['name'] }}
                                                    </td>
                                                    
                                                    <td class="project-actions text-left toastsDefaultSuccess">
                                                        @if (session('role') == 'superadmin' || session('role') == 'admin')
                                                            <a class="btn btn-warning btn" data-toggle="modal" data-target="#modalChangeTeacher" data-subject-id="{{ $data->subject_id }}" data-teacher-id="{{ $el['id'] }}" data-grade-id="{{ $data->grade_id }}">
                                                                <i class="fas fa-edit"></i> Change
                                                            </a>
                                                            <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteTypeSchedule" data-subject-id="{{ $data->subject_id }}" data-teacher-id="{{ $el['id'] }}" data-grade-id="{{ $data->grade_id }}">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                        
                                                <!-- Modal -->
                                                <div class="modal fade" id="modalDeleteTypeSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Delete Group Subject Teacher</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">Are you sure want to delete  {{ $el['name'] }}?</div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a class="btn btn-danger btn" id="confirmDelete">Yes delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="modalChangeTeacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Change Teacher</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                    <form method="POST" action="{{ route('actionAdminChangeGradeSubjectMultiTeacher') }}" id="formChangeTeacher">
                                                                        @csrf <!-- Pastikan CSRF token digunakan -->
                                                                        <input type="hidden" value="{{$data['subject_id']}}" name="subject_id" id="subject-id">
                                                                        <input type="hidden" value="{{$data['teacher_id']}}" name="teacher_id" id="teacher-id">
                                                                        <input type="hidden" value="{{$data['grade_id']}}" name="grade_id" id="grade-id">

                                                                        <div class="form-group row">
                                                                            <div class="col-md-12 mt-2">
                                                                                <label for="name">Change Teacher<span style="color: red"> *</span></label>
                                                                                <select name="change_teacher" id="change_teacher" class="form-control">
                                                                                    <option>--- Choose Teacher ---</option>
                                                                                    @foreach ($teacherss as $t)
                                                                                        <option value="{{$t->id}}">{{$t->id}} {{ $t->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a class="btn btn-danger btn" id="confirmChange">Yes, change</a>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="name">Add Group Teacher<span style="color: red"> *</span></label>
                                        <select name="group_teacher[]" id="teacher" class="form-control js-select2" multiple>
                                    
                                            <!-- Loop through all available teachers and display them as options -->
                                            @foreach ($teachers as $teachers)
                                                <!-- Make sure to not select teachers who are already in the 'is_group' list -->
                                                <option value="{{ $teachers->id }}" 
                                                    @if (isset($dataMultiple['is_group']) && in_array($teachers->id, array_column($dataMultiple['is_group'], 'id'))) 
                                                        disabled 
                                                    @endif>
                                                    {{ $teachers->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('teacher')}}</p>
                                        @endif
                                    </div>
                                    
                                    

                                    <div class="col-12 mt-3">
                                        <input role="button" type="submit" class="btn btn-success center col-12">
                                    </div>

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

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDeleteTypeSchedule').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var gradeId = button.data('grade-id');
            var subjectId = button.data('subject-id');
            var teacherId = button.data('teacher-id');
            var confirmDelete = document.getElementById('confirmDelete');
            confirmDelete.href = "{{ url('/' . session('role') . '/grades/subject/multiple/delete') }}" + '/' + gradeId + '/' + subjectId + '/' + teacherId;
        });
        
        $('#modalChangeTeacher').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var subjectId = button.data('subject-id'); // Extract data-subject-id
            var teacherId = button.data('teacher-id'); // Extract data-teacher-id
            var gradeId = button.data('grade-id'); // Extract data-grade-id

            // Set values in the hidden inputs inside the modal
            var modal = $(this);
            modal.find('#subject-id').val(subjectId);
            modal.find('#teacher-id').val(teacherId);
            modal.find('#grade-id').val(gradeId);
        });    
    });
    
    document.getElementById('confirmChange').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        const changeTeacher = document.getElementById("change_teacher").value;
        const form = {
            gradeId: @json($data['grade_id']),
            subjectId: @json($data['subject_id']),
            teacherId: @json($data['teacher_id']),
            changeTeacher: changeTeacher,
        };
        // const formData = new FormData(form);
        
        console.log(form);

        // Prepare options for the fetch request
        const options = {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value // CSRF token for Laravel
            },
            body: form
        };

        // Send the form data using fetch
        fetch("{{ route('actionAdminChangeGradeSubjectMultiTeacher') }}", options)
        .then(response => response.json())
        .then(data => {
            // Handle the server response
            const responseMessage = document.getElementById('responseMessage');
            if (data.success) {
                console.log('sukses');
            } else {
                console.log('gagal');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const responseMessage = document.getElementById('responseMessage');
            responseMessage.textContent = "An error occurred while processing your request.";
            responseMessage.style.color = "red";
        });
    });
</script>

@if(session('after_update_subject_teacher')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the subject teacher in the database.',
        });
    </script>
@endif

@endsection