@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                  @if (session('role') == 'superadmin')
                     <form method="POST" action={{route('actionSuperAddSubjectGrade')}}>
                  @elseif (session('role') == 'admin')
                     <form method="POST" action={{route('actionAdminAddSubjectGrade')}}>
                  @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create subject grade</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        @foreach ($data['grade'] as $dg)
                                        <label for="grade_id">Grade<span style="color: red">*</span></label>
                                        <select required name="grade_id" class="form-control" id="grade_id">
                                            <option value="{{ $dg->id }}" selected>{{ $dg->name  }} - {{ $dg->class }}</option>
                                        </select>
                                        @endforeach
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('grade_id')}}</p>
                                        @endif
                                    </div>
                                    <div class="col mt-2">
                                        <table class="table table-striped table-bordered">
                                        <thead>
                                            <th style="width: 40%;">Subject</th>
                                            <th style="width: 40%;">Teacher</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="scheduleTableBody">
                                            <tr>
                                                <td>
                                                    <select required name="subject_id[]" class="form-control" id="subject_id">
                                                        <option value="" selected>  SELECT SUBJECT </option>
                                                        @foreach($data['subject'] as $el)
                                                            <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('subject_id'))
                                                    <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select required name="teacher_subject_id[]" class="form-control" id="teacher_subject_id">
                                                        <option value="" selected>  SELECT TEACHER </option>
                                                        @foreach($data['teacher'] as $el)
                                                            <option value="{{ $el->id }}">{{ $el->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('teacher_subject_id'))
                                                    <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
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
                                </div>


                              
                                <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-12 m-3">
                                </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
$(document).ready(function() {
    // Function to add a new row
    function addRow() {
        var newRow = `<tr>
            <td>
                <select required name="subject_id[]" class="form-control" id="subject_id">
                    <option value="" selected>  SELECT SUBJECT </option>
                    @foreach($data['subject'] as $el)
                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                    @endforeach
                </select>
                @if($errors->has('subject_id'))
                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                @endif
            </td>
            <td>
                <select required name="teacher_subject_id[]" class="form-control" id="teacher_subject_id">
                    <option value="" selected>  SELECT TEACHER </option>
                    @foreach($data['teacher'] as $el)
                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('teacher_subject_id'))
                <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
                @endif
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

</script>

@if(session('sweetalert'))
    <script>
        Swal.fire({
            title: '{{ session('sweetalert.title') }}',
            text: '{{ session('sweetalert.text') }}',
            icon: '{{ session('sweetalert.icon') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif



@endsection
