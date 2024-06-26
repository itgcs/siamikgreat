@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
      <div>
         <p>Date    : {{date('d-m-Y') }}</p>
         <p>Grade   : {{ $data['nameGrade'] }}</p>
         <p>Teacher : {{ $data['nameTeacher'] }}</p>
      </div>
   <!-- START TABEL -->
      <div class="card card-dark mt-5">
            <div class="card-header"> 
               <h3 class="card-title">Student</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                     <i class="fas fa-minus"></i>
                  </button>
               </div>
            </div>
            <div class="card-body p-0">
               <form method="POST" action="{{ route('actionUpdateAttendanceStudent') }}">
                  @csrf
                  @method('POST')
                  <table class="table table-striped projects">
                     <thead>
                           <tr>
                              <th>#</th>
                              <th style="width: 20%">Student</th>
                              <th >Attendance</th>
                              <th style="width: 20%"> 
                                 <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
                                    <option value="">-- Semester -- </option>
                                    <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
                                    <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
                                 </select>
                              </th>
                           </tr>
                     </thead>
                     <tbody>
                           @if (sizeof($data['student']) != 0)

                           @foreach ($data['student'] as $el)
                              @php
                                 $student[$el->id] = [
                                       'name' => $el->name,
                                       'present' => false,
                                       'alpha' => false,
                                       'sick' => false,
                                       'permission' => false,
                                       'late' => false,
                                       'latest' => 0,
                                       'comment' => '',
                                 ];
                              @endphp
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td><a>{{ $el->name }}</a></td>
                                 <td colspan="2">
                                       <div class="input-group">
                                          <input name="date" type="date" class="form-control d-none" id="date" value="{{ date('Y-m-d') }}">
                                          <input name="grade_id" type="text" class="form-control d-none" id="grade_id" value="{{ $data['gradeId'] }}">
                                          <input name="teacher_id" type="text" class="form-control d-none" id="teacher_id" value="{{ $data['teacherId'] }}">
                                       </div>
                                       <div class="d-flex align-items-center">
                                          <div class="form-check me-2">
                                             <input id="present{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="present" id="present">
                                             <label class="form-check-label" for="present">
                                                   Present
                                             </label>
                                          </div>
                                          <div class="form-check me-2 mx-2">
                                             <input id="alpha{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="alpha" id="absent">
                                             <label class="form-check-label" for="absent">
                                                   Alpha
                                             </label>
                                          </div>
                                          <div class="form-check me-2">
                                             <input id="sick{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="sick" id="sick">
                                             <label class="form-check-label" for="sick">
                                                   Sick
                                             </label>
                                          </div>
                                          <div class="form-check me-2 mx-2">
                                             <input id="permission{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="permission" id="permission">
                                             <label class="form-check-label" for="permission">
                                                   Permission
                                             </label>
                                          </div>
                                          <div class="form-check me-2 ">
                                             <input id="late{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="late" id="late">
                                             <label class="form-check-label" for="permission">
                                                   Late
                                             </label>
                                          </div>
                                          <div class="flex-grow-1 comment-container mx-2">
                                             <input id="latest{{ $loop->index + 1 }}" name="latest[{{ $el->id }}]" type="number" class="form-control comment-type" placeholder="times late (minute)">
                                          </div>
                                          <div class="flex-grow-1 comment-container">
                                             <input id="comment{{ $loop->index + 1 }}" name="comment[{{ $el->id }}]" type="text" class="form-control comment-type" placeholder="information">
                                          </div>
                                       </div>
                                 </td>
                              </tr>
                           @endforeach

                           @else
                              <tr>
                                 <td colspan="7" class="text-center">No student in this grade!!!</td>
                              </tr>
                           @endif
                     </tbody>
                  </table>
                  <div class="card-footer">
                     <div class="d-flex align-items-center float-right">
                        <button type="button" class="btn btn-info mr-2" id="present_all_btn">Present All</button>
                        <button type="submit" class="btn btn-success" name="present_attend">Present Attend</button>
                     </div>
                  </div>
               </form>
            </div>
      </div>
   <!-- END TABLE -->
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      let checkboxes = document.querySelectorAll('.absence-type');
      let presentAllBtn = document.getElementById('present_all_btn');

      
      presentAllBtn.addEventListener('click', function() {
         checkboxes.forEach(function(checkbox) {
            if (checkbox.id.startsWith('present')) {
               checkbox.checked = true;
            } else {
               checkbox.checked = false;
               let currentRow = checkbox.closest('tr');
               let commentInput = currentRow.querySelector('.comment-container input');
               commentInput.value = ''; // Reset comment value
            }
         });
      });

      checkboxes.forEach(function(checkbox) {
         checkbox.addEventListener('change', function() {
            let currentRow = this.closest('tr');
            let checkboxesInRow = currentRow.querySelectorAll('.absence-type');

            checkboxesInRow.forEach(function(cb) {
               if (cb !== checkbox) {
                  cb.checked = false;
               }
            });
         });
      });
   });
</script>


   @if(session('failed_attend')) 

      <script>

        var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
        });
      
        setTimeout(() => {
           Toast.fire({
              icon: 'error',
              title: 'Attendance already recorded for this day.',
        });
        }, 1500);


      </script>

   @endif

@endsection
