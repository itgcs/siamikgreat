@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="card card-dark mt-5">
        <div class="card-header">
            <h3 class="card-title">Exams</h3>

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
                        <th>
                           #
                        </th>
                        <th >
                          Type Exam
                        </th>
                        <th >
                           Name Exam
                        </th>
                        <th >
                          Date Exam
                        </th>
                        <th>
                           Grade
                        </th>
                        <th>
                           Subject
                        </th>
                        <th>
                           Teacher
                        </th>
                        <th>
                           Status
                        </th>
                        <th style="width: 15%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                           <a>
                              {{$el->type_exam}}
                           </a>
                        </td>
                        <td>
                           <a>
                              {{$el->name_exam}}
                           </a>
                        </td>
                        <td>
                           <a>
                              {{$el->date_exam}}
                           </a>
                           <br>
                           @php
                              $currentDate = now(); // Tanggal saat ini
                              $dateExam = $el->date_exam; // Tanggal exam dari data

                              // Hitung selisih antara tanggal exam dengan tanggal saat ini
                              $diff = strtotime($dateExam) - strtotime($currentDate);
                              $days = floor($diff / (60 * 60 * 24)); // Konversi detik ke hari
                           @endphp
                           <small class="text-muted mb-0"><span class="badge badge-danger">{{$days}} days again</span></small>
                        </td>
                        <td>
                           {{$el->grade_name}} - {{ $el->grade_class }}
                        </td>
                        <td>
                           {{$el->subject_name}}
                        </td>
                        <td>
                           {{$el->teacher_name}}
                        </td>
                        <td>
                           @if($el->is_active)
                           <span class="badge badge-success"> Active </span>
                           @else
                           <span class="badge badge-success"> Inactive </span>
                           @endif
                        </td>
                        <td class="project-actions text-right toastsDefaultSuccess">
                           <a class="btn btn-primary btn" href="{{ url(session('role') . '/dashboard/exam') . '/detail/' . $el->id }}">
                              <i class="fas fa-folder">
                              </i>
                              View
                           </a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_grade')) 

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
              title: 'Successfully created new grade in the database.',
        });
        }, 1500);


      </script>

  @endif


  
  
  @if(session('after_update_grade')) 
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
            title: 'Successfully updated the grade in the database.',
      });
      }, 1500);

    
    </script>
   @endif

  @if(session('levelup'))
  
   @php
      $gradePromotion = session('levelup')
   @endphp
      <script>

      var grade = "{{ $gradePromotion }}"
     
      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
  
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully level up students from grade ' + grade,
      });
      }, 1500);

    
    </script>
   @endif

   @if(session('graduate'))
  
      @php
         $gradePromotion = session('graduate')
      @endphp

      <script>

      var grade = "{{ $gradePromotion }}"
     
      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
  
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully graduate students from grade ' + grade,
      });
      }, 1500);

    
    </script>
   @endif


@endsection
