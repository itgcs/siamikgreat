@extends('layouts.admin.master')
@section('content')

@php
  $currentDate = now(); // Tanggal saat ini
  $dateExam = $data->date_exam; // Tanggal ujian dari data

  // Buat objek DateTime dari tanggal saat ini dan tanggal ujian
  $currentDateTime = new DateTime($currentDate);
  $dateExamDateTime = new DateTime($dateExam);

  // Hitung selisih antara kedua tanggal
  $interval = $currentDateTime->diff($dateExamDateTime);

  // Ambil jumlah hari dari selisih tersebut
  $days = $interval->days;

  // Jika tanggal ujian lebih kecil dari tanggal saat ini, buat selisih menjadi negatif
  if ($dateExamDateTime < $currentDateTime) {
      $days = -$days;
  } else if ($dateExamDateTime > $currentDateTime && $days == 0) {
      // Jika tanggal ujian di masa depan dan selisih kurang dari 1 hari, anggap 1 hari
      $days = 1;
  }
@endphp

<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="{{ url('/' . session('role') . '/dashboard/exam') }}">Exam</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Exam</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Name Exam</p>
              </div>
              <div class="col-sm-10">
                <p class="text-muted mb-0">: {{$data->name_exam}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Type Exam</p>
              </div>
              <div class="col-sm-10">
                <p class="text-muted mb-0">: {{$data->type_exam}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Date Exam</p>
              </div>
              <div class="col-sm-10">
                <p class="text-muted mb-0">; {{$data->date_exam}}</p>
                  @if ($data->is_active)
                    <small class="text-muted mb-0">Days until exam:  <span class="badge badge-danger">{{$days}} days</span></small>
                  @else
                  @endif
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Grade</p>
              </div>
              <div class="col-sm-10">
                <p class="text-muted mb-0">: {{$data->grade_name}} - {{$data->grade_class}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Subject</p>
              </div>
              <div class="col-sm-10">
                <p class="text-muted mb-0">: {{$data->subject_name}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Materi</p>
              </div>
              <div class="col-sm-10">
                <p class="text-muted mb-0">: {{$data->materi}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-2">
                <p class="mb-0">Status</p>
              </div>
              <div class="col-sm-10">
                  <p class="text-muted mb-0">
                    @if($data->is_active)
                      : <span class="badge badge-success">Active</span>
                    @else
                      : <span class="badge badge-danger">Inactive</span>
                    @endif
                  </p>
              </div>
            </div>
          </div>
        </div>

        
      </div>
    </div>
  </div>
</section>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_create_teacher')) 
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfully',
      text: 'Successfully registered the teacher in the database !!!',
    });
  </script>
@endif 


@if (session('after_update_teacher'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfully',
      text: 'Successfully updated the teacher in the database !!!',
    });
  </script>
@endif

@endsection