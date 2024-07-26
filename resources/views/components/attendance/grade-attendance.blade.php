@extends('layouts.admin.master')
@section('content')

<style>
   .full-height {
      height: 60vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
   }
   .icon-wrapper i {
      font-size: 200px;
      color: #ccc;
   }
   .icon-wrapper p {
      position: absolute;
      left: 50%;
      transform: translate(-50%, 0%);
      margin: 0;
      font-size: 1.5rem;
      color: black;
      text-align: center;
   }
</style>

<!-- Content Wrapper. Contains page content -->
@if (sizeof($data['gradeTeacher']) != 0)
   <div class="container-fluid">
   <!-- <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div> -->

   <!-- START TABEL -->
         <div class="card card-dark mt-2">
               <div class="card-header"> 
                  <h3 class="card-title">Your Class</h3>
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
                              <th style="width: 10%">
                                 #
                              </th>
                              <th style="width: 15%">
                                 Class
                              </th>
                              <th style="width: 75%">
                                 Action
                              </th>
                           </tr>
                     </thead>
                     <tbody>
                        @foreach ($data['gradeTeacher'] as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                    <td>
                                       {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                       <a>
                                          {{ $el->name }} - {{ $el->class }}
                                       </a>
                                    </td>
                                    <td>
                                       <a class="btn btn-primary btn-sm"
                                          href="{{url('/' . session('role') . '/dashboard/attendance') . '/' . session('id_user') . '/' . $el->id}}">
                                          <i class="fas fa-paper-plane">
                                          </i>
                                          Attend
                                       </a>
                                       <a class="btn btn-secondary btn-sm"
                                          href="{{ route('attendance.detail.teacher', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                          <i class="fas fa-eye">
                                          </i>
                                          View
                                       </a>
                                       <a class="btn btn-warning btn-sm"
                                          href="{{url('/' . session('role') . '/dashboard/attendance/edit') . '/' . session('id_user') . '/' . $el->id}}">
                                          <i class="fas fa-pencil">
                                          </i>
                                          Edit
                                       </a>
                                    </td>
                              </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
         </div>
      <!-- END TABLE -->
   </div>
@else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
         <i class="fa-regular fa-face-sad-tear"></i>
            <p>Oops.. <br> You are not class teacher</p>
      </div>
</div>
@endif

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
   function saveSemesterToSession() {
      var semesterSelect = document.getElementById('semester');
      var selectedSemester = semesterSelect.value;
      
      // Simpan nilai semester ke dalam session
      $.ajax({
         url: '{{ route('save.semester.session') }}',
         type: 'POST',
         data: {
            semester: selectedSemester,
            _token: '{{ csrf_token() }}'
         },
         success: function(response) {
            console.log('Semester saved to session:', response.semester);
         },
         error: function(xhr, status, error) {
            console.error('Error saving semester to session:', error);
         }
      });
   }
</script>

   @if(session('after_create_attendance')) 

      <script>
           Swal.fire({
               icon: 'success',
               title: 'Successfully',
               text: 'Successfully upload attendance in the database.',
            });
      </script>

   @endif

   @if(session('data_is_empty')) 
      <script>
         Swal.fire({
               icon: 'error',
               title: 'Oops...',
               text: 'Data Attendance is empty !!!',
         });
      </script>
   @endif

@endsection
