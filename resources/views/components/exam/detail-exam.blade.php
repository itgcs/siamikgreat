@extends('layouts.admin.master')
@section('content')
@php
$currentDate = now(); // Tanggal saat ini
$dateExam = $data->date_exam; // Tanggal exam dari data

// Hitung selisih antara tanggal exam dengan tanggal saat ini
$diff = strtotime($dateExam) - strtotime($currentDate);
$days = floor($diff / (60 * 60 * 24)); // Konversi detik ke hari
@endphp

   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/exams')}}">Exams</a></li>
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
                  <div class="col-sm-4">
                    <p class="mb-0">Name Exam</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->name_exam}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Type Exam</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->type_exam}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Date Exam</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->date_exam}}</p>
                    <small class="text-muted mb-0">Days until exam:  <span class="badge badge-danger">{{$days}} days</span></small>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Grade</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->grade_name}} - {{$data->grade_class}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Subject</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->subject_name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Teacher</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->teacher_name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Materi</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->materi}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Status</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        @if($data->is_active)
                           <h1 class="badge badge-success">Active</h1>
                        @else
                           <h1 class="badge badge-danger">Inactive</h1>
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
    @if(session('after_create_teacher')) 
    
      {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    
      <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
      <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
           title: 'Successfully registered the teacher in the database !!!',
        });
        }, 1500);


      </script>
        
    @endif 


    @if (session('after_update_teacher'))
      
    <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
         title: 'Successfully updated the teacher in the database !!!',
      });
      }, 1500);


    </script>

    @endif

@endsection