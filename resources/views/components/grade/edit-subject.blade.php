@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 ">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades')}}">Grade</a></li>
                <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/edit/' . $data[0]['id'])}}">Edit {{ $data[0]['name'] }} - {{ $data[0]['class'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Subject & Teacher {{ $data[0]['name'] }} - {{ $data[0]['class'] }}</li>
            </ol>
        </nav>
        </div>
    </div>

    <div class="row my-3">
    @foreach ($data as $da)
        <a type="button" href="{{ url('/' . session('role') . '/grades/manageSubject/addSubject') . '/' . $da->id }}" class="btn btn-success btn mx-2">   <i class="fa-solid fa-user-plus"></i>
        </i>   
        Add Subject & Teacher
        </a>
    @endforeach
   </div>

    <div class="card card-dark">
        <div class="card-header">
            @foreach ($data as $da)
                <h3 class="card-title">Subject Teacher {{$da->name}} - {{ $da->class }}</h3>
            @endforeach

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th >
                           #
                        </th>
                        <th style="width: 20%">
                           Grade Subject
                        </th>
                        <th style="width: 30%">
                           Subject Teacher
                        </th>
                        <th style="width: 50%">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjectGrade as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                           <a>
                                {{$el->subject_name}}
                           </a>
                        </td>
                        <td>
                            <a>
                                {{$el->teacher_name}} 
                                @if($el->is_lead) 
                                    <span class="badge badge-primary">Main Teacher</span> 
                                @elseif($el->is_group) 
                                    <span class="badge badge-warning">Member</span>
                                @else 
                                @endif
                            </a>
                        </td>
                        
                        <td class="project-actions text-left toastsDefaultSuccess">
                            @if($el->is_lead) 
                            @elseif($el->is_group) 
                            @else
                                <a class="btn btn-primary btn"
                                href="{{url('/' . session('role') .'/grades/manageSubject/teacher') . '/edit/' . $el->grade_id . '/' . $el->subject_id . '/' . $el->teacher_id}}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                                </a>
                                @if (session('role') == 'superadmin' || session('role') == 'admin')
                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteSubject-{{$el->id}}" data-subject-id="{{ $el->subject_id }}" data-teacher-id="{{ $el->teacher_id }}" data-grade-id="{{ $el->grade_id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalDeleteSubject-{{$el->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <input type="hidden" value="{{$el->id}}" name="data_id" id="data-id-{{$el->id}}">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Subject</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">Are you sure want to delete {{$el->subject_name}} ?</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a class="btn btn-danger btn" id="confirmDelete-{{$el->id}}">Yes Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
            
            @if (count($groupSubject) !== 0)
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th colspan="3" style="text-align:center;">For Edit Subject Group</th>
                        </tr>
                        <tr>
                            <th>
                            #
                            </th>
                            <th style="width: 20%">
                                Grade Subject
                            </th>
                            <th style="width: 80%">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupSubject as $gs)
                        <tr id={{'index_grade_subject_' . $gs->id}}>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                <a>
                                    {{$gs->subject_name}}
                                </a>
                            </td>
                            
                            <td class="project-actions text-left toastsDefaultSuccess">
                                <a class="btn btn-primary btn"
                                href="{{url('/' . session('role') .'/grades/manageSubject/teacher/multiple') . '/edit/' . $gs->grade_id . '/' . $gs->subject_id}}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                                </a>
                                @if (session('role') == 'superadmin' || session('role') == 'admin')
                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteGroupSubject-{{$gs->id}}" data-subject-id="{{ $gs->subject_id }}" data-grade-id="{{ $gs->grade_id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modalDeleteGroupSubject-{{$gs->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <input type="hidden" value="{{$gs->id}}" name="data_id" id="data-group-id-{{$gs->id}}">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete Subject</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">Are you sure want to delete this group {{$gs->subject_name}}?</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a class="btn btn-danger btn" id="confirmDeleteGroupSubject-{{$gs->id}}">Yes delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>

            @endif
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmDeleteButtons = document.querySelectorAll('[id^="confirmDelete-"]');
        
        confirmDeleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const id = this.id.split('-')[1];
                const dataId = document.getElementById(`data-id-${id}`).value; // Get the selected teacher from the corresponding modal

                const form = {
                    id: parseInt(dataId, 10),
                    type: "singleSubject",
                };

                // console.log(form);

                const options = {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    body: JSON.stringify(form)
                };


                // Send the form data using fetch
                fetch("{{ route('dsg') }}", options)
                    .then(response => response.json())
                    .then(data => {
                        // Handle the server response
                        if (data.success) {
                            // console.log(data.tes);
                            Swal.fire({
                                icon: 'success',
                                text: 'Data Berhasil Dihapus',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 1500 milliseconds (1.5 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                location.reload(); // Reload the page after the modal closes
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Maaf ada kesalahan',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 1500 milliseconds (1.5 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                location.reload(); // Reload the page after the modal closes
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });

            });
        });

        const confirmDeleteGroupButtons = document.querySelectorAll('[id^="confirmDeleteGroupSubject-"]');
        
        confirmDeleteGroupButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const id = this.id.split('-')[1];
                const dataId = document.getElementById(`data-group-id-${id}`).value; // Get the selected teacher from the corresponding modal

                const form = {
                    id: parseInt(dataId, 10),
                    type: "multipleSubject",
                };

                // console.log(form);

                const options = {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    body: JSON.stringify(form)
                };


                // Send the form data using fetch
                fetch("{{ route('dsg') }}", options)
                    .then(response => response.json())
                    .then(data => {
                        // Handle the server response
                        if (data.success) {
                            console.log(data.tes);
                            Swal.fire({
                                icon: 'success',
                                text: 'Data Berhasil Dihapus',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 1500 milliseconds (1.5 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                location.reload(); // Reload the page after the modal closes
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Maaf ada kesalahan',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 1500 milliseconds (1.5 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                location.reload(); // Reload the page after the modal closes
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });

            });
        });

    });
</script>

@if(session('after_add_subject_grade')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new subject grade in the database.',
        });
    </script>
@endif

@if(session('after_delete_subject_grade')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully delete subject grade in the database.',
        });
    </script>
@endif

@endsection
