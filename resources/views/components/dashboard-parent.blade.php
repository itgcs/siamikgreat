@extends('layouts.admin.master')
@section('content')
  <!-- Content Header (Page header) -->
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
       
      <div class="form-group row">
          <div class="col-md-12">
            <select required name="studentId" class="form-control" id="studentId" onchange="saveStudentId()">
                <option value="">-- Your Relation -- </option>
                @foreach ($data['totalRelation'] as $dtr)
                  <option value="{{ $dtr->student_id }}" {{ session('studentId') == $dtr->student_id ? "selected" : "" }}> {{ $dtr->student_name }} ({{ $dtr->grade_name }} - {{ $dtr->grade_class }})</option>
                @endforeach
            </select>
          </div>
      </div>

      <div class="row">
        <!-- STUDENT ACTIVE -->
        <div class="col-lg-12 col-12">
            <!-- small box -->
            <div class="small-box bg-info">

              <div class="inner">
                <h4>Detail Relationship</h4>
                <div>
                  <table>
                    <tr>
                      <td>Student Name </td>
                      <td> : {{ $data['detailStudent']->student_name }}</td>
                    </tr>
                    <tr>
                      <td>Grade </td>
                      <td> : {{ $data['detailStudent']->grade_name }} - {{ $data['detailStudent']->grade_class }}</td>
                    </tr>
                    @foreach($data['eca'] as $de)
                    <tr>
                      <td>Ekstra Culicular {{ $loop->index+1 }}</td>
                      <td> : {{ $de->eca_name }}</td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
            </div>
        </div>
        <!-- ./col -->
       </div>
       
      <div class="row">
         <!-- GRADE ACTIVE -->
        <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-success">
             <div class="inner">
               <h3>{{ $data['totalAbsent'] }}</h3>
               <p>Total Absent Student</p>
             </div>
             <div class="icon">
               <i class="fa-solid fa-chalkboard-user"></i>
             </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->

         <!-- SUBJECT ACTIVE -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-warning">
             <div class="inner">
               <h3>{{$data['totalSubject']}}
               {{-- <sup style="font-size: 20px">%</sup> --}}
               </h3>

               <p>Total Subject Active</p>
              </div>
              <div class="icon">
                {{-- <i class="ion ion-person-add"></i> --}}
                <i class="fa-solid fa-receipt"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->

         <!-- EXAM ACTIVE -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-danger">
             <div class="inner">
               <h3>{{ $data['totalExam']}}</h3>

               <p>Total Exam</p>
             </div>
             <div class="icon">
               <i class="fa-solid fa-pencil"></i>
             </div>
             
             <a href="/parent/dashboard/exam" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
             
           </div>
         </div>
         <!-- ./col -->
       </div>

       <!-- /.row -->
       <!-- Main row -->
       <div class="row">
         <!-- Left col -->
         <section class="col-lg-7 connectedSortable">
           
          <!-- Custom tabs (Charts with tabs) List Exam-->
          <div class="card bg-danger">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-calendar-xmark mr-1"></i>
                  List Exam
              </h3>
              <!-- card tools -->
              <div class="card-tools">
                <button type="button" class="btn btn-danger btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content p-0">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane active" id="revenue-chart"
                     style="position: relative; height: full;">
                     <div>
                       <!-- /.card-header -->
                       <div>
                         <ul class="todo-list bg-danger" data-widget="todo-list">
    
                          @php
                           $currentDate = date('y-m-d');
                          @endphp 
    
                          @if (count($data['exam']) !== 0)
                            @foreach ($data['exam'] as $el)
                            <li>
                              <!-- drag handle -->
                              <span class="handle">
                                  <i class="fas fa-ellipsis-v"></i>
                                  <i class="fas fa-ellipsis-v"></i>
                              </span>
                              <!-- checkbox -->
                              <div class="icheck-primary d-inline ml-2">
                                  <span class="text-muted">[{{ date('d F Y', strtotime($el->date_exam)) }}]</span>
                              </div>
                              <!-- todo text -->
                              <span class="text text-sm">( {{$el->type_exam_name}} ) ({{ $el->subject }}) {{$el->name_exam}} </span>
                              
                              <div class="tools">
                                <a class="btn" id="view" data-id="{{ $el->id }}">
                                    <i class="fas fa-search"></i>
                                </a>
                              </div>
                            </li>
                            @endforeach
                          @else
                            <li>No active assessments</li>
                          @endif
                          </ul>
                       </div>
                     </div>
                 </div>
              </div>
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->
       
         </section>
         <!-- /.Left col -->

         <!-- right col (We are only adding the ID to make the widgets sortable)-->
         <section class="col-lg-5 connectedSortable">        
          <!-- Subject List -->
          <div class="card bg-warning">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-receipt mr-1"></i>
                Subject's
              </h3>
              <!-- card tools -->
              <div class="card-tools">
                <button type="button" class="btn btn-warning btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            
            <div class="card-body">
             <table class="table table-borderless">      
              @if(sizeof($data['dataStudent']->subject) != 0)
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($data['dataStudent']->subject as $el)
                  <tr>
                    <td scope="row">{{$loop->index+1}}</td>
                    <td>{{$el->name_subject}}</td>
                  </tr>
                @endforeach  
              @else
                <p>Teacher dont have data subject !!!</p>
              @endif
                 
               </tbody>
             </table>
            </div>
          </div>
         </section>
         <!-- right col -->
       </div>
       <!-- /.row (main row) -->
     </div><!-- /.container-fluid -->
   </section>
 <!-- /.content-wrapper -->

<script>
  function saveStudentId() {
    var studentIdSelect = document.getElementById('studentId');
    var selectedStudentId = studentIdSelect.value;
    
    // Simpan nilai semester ke dalam session
    $.ajax({
        url: '{{ route('save.student.session') }}',
        type: 'POST',
        data: {
          studentId: selectedStudentId,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          console.log('Student ID saved to session:', response.studentId);
          window.location.href = '/parent/dashboard/';
        },
        error: function(xhr, status, error) {
          console.error('Error saving semester to session:', error);
        }
    });
  }
</script>
@endsection
