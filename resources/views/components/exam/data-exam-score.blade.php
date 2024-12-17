@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

    @if(count($data) !== 0)
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Home</li>
                        @if(session('role') == 'admin' || session('role') == 'superadmin')
                        @else
                        <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher')}}">Scoring</a></li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">Scoring {{ $data[0]['exam_name'] }} {{ $data[0]['subject_name'] }} ({{ $data[0]['grade_name'] }} - {{ $data[0]['grade_class'] }})</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Scorings</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <form method="POST" action="{{ route('actionUpdateScoreExam') }}">
                @else
                    <form method="POST" action="{{ route('actionUpdateScoreExamTeacher') }}">
                @endif
                    @csrf
                    @method('PUT')
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Type Scoring</th>
                                <th>Deadline Scoring</th>
                                <th style="width: 25%">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $el)
                            <tr id="{{'index_grade_' . $el->id}}">
                                <td class="text-sm">{{ $loop->index + 1 }}</td>
                                <td class="text-sm">{{ $el->student_name }}</td>
                                <td class="text-sm">{{ $el->type_exam }}</td>
                                <td class="text-sm"><a>{{ \Carbon\Carbon::parse($el->date_exam)->format('l, d F Y') }}
                                </a></td>
                                <td class="project-actions text-right">
                                    <div class="input-group">
                                        <input name="exam_id" type="text" class="form-control d-none" id="exam_id" value="{{ $el->exam_id }}">
                                        <input name="subject_id" type="text" class="form-control d-none" id="subject_id" value="{{ $el->subject_id }}">
                                        <input name="grade_id" type="text" class="form-control d-none" id="grade_id" value="{{ $el->grade_id }}">
                                        <input name="teacher_id" type="text" class="form-control d-none" id="teacher_id" value="{{ $el->teacher_id }}">
                                        <input name="type_exam_id" type="text" class="form-control d-none" id="type_exam_id" value="{{ $el->type_exam_id }}">
                                        <input name="student_id[]" type="text" class="form-control d-none" id="student_id" value="{{ $el->student_id }}">
                                        <input name="score[]" type="number" class="form-control score-input" id="score" placeholder="Score" value="{{ old('score', $el->score) }}" autocomplete="off" min="0" max="100" required>
                                    </div>
                                    @if($errors->has('score'))
                                    <p style="color: red">{{ $errors->first('score') }}</p>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right" name="update_all">Update Scores</button>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    @else
        <p>Kosong</p>
    @endif
</div>

<link rel="stylesheet" href="{{ asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scoreInputs = document.querySelectorAll('.score-input');

        scoreInputs.forEach(function(scoreInput) {

            // Prevent non-numeric characters from being entered
            scoreInput.addEventListener('keypress', function(event) {
                let charCode = event.which ? event.which : event.keyCode;
                if (charCode < 48 || charCode > 57) {
                    event.preventDefault(); // Block any input that isn't a number (0-9)
                }
            });

            // Validate input on the fly
            scoreInput.addEventListener('input', function() {
                let value = parseInt(this.value, 10);

                if (value > 100) {
                    this.value = 100;
                } else if (value < 0) {
                    this.value = 0;
                }
            });

            // Ensure the value stays within the range on blur (when the input loses focus)
            scoreInput.addEventListener('blur', function() {
                let value = parseInt(this.value, 10);

                if (isNaN(value) || value > 100) {
                    this.value = 100;
                } else if (value < 0) {
                    this.value = 0;
                }
            });

        });
    });
</script>


@if(session('after_create_score'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new score in the database.'
        });
    </script>
@endif

@if(session('after_update_score'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the scores in the database.'
        });
    </script>
@endif

@endsection
