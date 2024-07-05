@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div>

   <!-- START TABEL -->
   @if (sizeof($data['classTeacher']) != 0)
     
         <div class="card card-dark mt-2">
               <div class="card-header">
                  <h3 class="card-title">Your Class Teacher</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                           <i class="fas fa-minus"></i>
                     </button>
                  </div>
               </div>
               <div class="card-body p-0">
                  <table class="table table-striped dgtojects">
                     <thead>
                           <tr>
                              <th>
                                 No
                              </th>
                              <th style="width:15%;">
                                 Name 
                              </th>
                              <th style="width:85%;">
                              </th>
                           </tr>
                     </thead>
                     <tbody>
                     @foreach ($data['classTeacher'] as $dgt)
                        <tr id="{{ 'index_grade_' . $dgt->id }}">
                              <td>
                                 {{ $loop->index + 1 }}
                              </td>
                              <td>
                                 {{ $dgt->name }} - {{ $dgt->class }}
                              </td>
                              <td class="dgtoject-actions text-left toastsDefaultSuccess">
                                 @if (strtolower($dgt->name) === "primary")
                                    <a class="btn btn-success btn"
                                       href="{{url('teacher/dashboard/report') . '/acar/detail/' . $dgt->id}}">
                                       
                                       </i>
                                       ACAR
                                    </a>
                                    <a class="btn btn-warning btn"
                                       href="{{url('teacher/dashboard/report') . '/sooa/detail/' . $dgt->id}}">
                                       
                                       </i>
                                       SOOA
                                    </a>
                                    <a class="btn btn-warning btn"
                                       href="{{url('teacher/dashboard/report') . '/tcop/detail/' . $dgt->id}}">
                                       
                                       </i>
                                       TCOP
                                    </a>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report') . '/card/semestersatu/' . $dgt->id}}">
                                       
                                       </i>
                                       SEMESTER 1
                                    </a>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report') . '/card/semesterdua/' . $dgt->id}}">
                                       
                                       </i>
                                       SEMESTER 2
                                    </a>
                                 @elseif (strtolower($dgt->name) === "secondary")
                                    <a class="btn btn-success btn"
                                       href="{{url('teacher/dashboard/report') . '/acar/detailSec/' . $dgt->id}}">
                                       
                                       </i>
                                       ACAR
                                    </a>
                                    <a class="btn btn-warning btn"
                                       href="{{url('teacher/dashboard/report') . '/sooa/detailSec/' . $dgt->id}}">
                                       
                                       </i>
                                       SOOA
                                    </a>
                                    <a class="btn btn-warning btn"
                                       href="{{url('teacher/dashboard/report') . '/tcop/detailSec/' . $dgt->id}}">
                                       
                                       </i>
                                       TCOP
                                    </a>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report') . '/cardSec/semestersatu/' . $dgt->id}}">
                                       
                                       </i>
                                       SEMESTER 1
                                    </a>
                                    <a class="btn btn-primary btn"
                                       href="{{url('teacher/dashboard/report') . '/cardSec/semesterdua/' . $dgt->id}}">
                                       
                                       </i>
                                       SEMESTER 2
                                    </a>
                                 @elseif (strtolower($dgt->name) === "nursery")
                                 <a class="btn btn-primary btn"
                                    href="{{ url('teacher/dashboard/report/cardNursery') . '/' . $dgt->id }}">
                                    
                                    </i>
                                    SEMESTER 1
                                 </a>
                                 <a class="btn btn-primary btn"
                                    href="{{url('teacher/dashboard/report/cardNursery') . '/' . $dgt->id }}">
                                    
                                    </i>
                                    SEMESTER 2
                                 </a>
                                 @elseif (strtolower($dgt->name) === "toddler")
                                 <a class="btn btn-primary btn"
                                    href="{{ url('teacher/dashboard/report/cardToddler') . '/' . $dgt->id }}">
                                    
                                    </i>
                                    SEMESTER 1
                                 </a>
                                 <a class="btn btn-primary btn"
                                 href="{{ url('teacher/dashboard/report/cardToddler') . '/' . $dgt->id }}">
                                    
                                    </i>
                                    SEMESTER 2
                                 </a>
                                 @elseif (strtolower($dgt->name) === "kindergarten")
                                 <a class="btn btn-primary btn"
                                    href="{{ url('teacher/dashboard/report/cardKindergarten') . '/' . $dgt->id }}">
                                    
                                    </i>
                                    SEMESTER 1
                                 </a>
                                 <a class="btn btn-primary btn"
                                    href="{{url('teacher/dashboard/report/cardKindergarten') . '/' . $dgt->id }}">
                                    
                                    </i>
                                    SEMESTER 2
                                 </a>
                                 @endif
                              </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
         </div>
      
      <!-- END TABLE -->
   @else
      <p class="text-center">You don't have data grade</p>
   @endif
   <!-- END TABEL -->
</div>

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

<script>
   @if(session('swal'))
      Swal.fire({
            icon: '{{ session('swal.type') }}', // 'success', 'error', 'warning', 'info', 'question'
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}'
      });
   @endif
</script>
@endsection
