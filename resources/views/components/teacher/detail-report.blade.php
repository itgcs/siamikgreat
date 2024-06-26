@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Score Report</li>
            </ol>
        </nav>
        </div>
    </div>

    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title">Report Score</h3>

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
                        <th>#</th>
                        <th>Name Exam</th>
                        <th>Date Exam</th>
                        <th>Grade</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Type Exam</th>
                        <th>Teacher</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td class="text-sm">{{ $loop->index + 1 }}</td>
                        <td class="text-sm"><a>{{ $el->exam_name }}</a></td>
                        <td class="text-sm"><a>{{ $el->date_exam }}</a></td>
                        <td class="text-sm">{{ $el->grade_name }} - {{ $el->grade_class }}</td>
                        <td class="text-sm">{{ $el->student_name }}</td>
                        <td class="text-sm">{{ $el->subject_name }}</td>
                        <td class="text-sm">{{ $el->type_exam }}</td>
                        <td class="text-sm">{{ $el->teacher_name }}</td>
                        <td class="text-md"><span class="badge badge-success">{{ $el->score }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{ asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@endsection
