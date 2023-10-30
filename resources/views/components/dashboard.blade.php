@extends('layouts.admin.master')
@section('content')
   <!-- Content Header (Page header) -->
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
     <div class="container-fluid">
       <!-- Small boxes (Stat box) -->
       <div class="row">
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-info">
             <div class="inner">
               <h3>{{$data->student}}</h3>

               <p>Total Students Active</p>
             </div>
             <div class="icon">
               <i class="ion ion-bag"></i>
             </div>
             <a href="/admin/list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-success">
             <div class="inner">
               <h3>{{$data->teacher}}
                {{-- <sup style="font-size: 20px">%</sup> --}}
              </h3>

               <p>Total Teachers Active</p>
             </div>
             <div class="icon">
               <i class="ion ion-stats-bars"></i>
             </div>
             <a href="/admin/teachers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-warning">
             <div class="inner">
               <h3>{{$data->bill}}</h3>

               <p>Bills last 30 days</p>
             </div>
             <div class="icon">
               <i class="ion ion-person-add"></i>
             </div>
             <a href="/admin/bills" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-danger">
             <div class="inner">
               <h3>{{$data->pastDue}}</h3>

               <p>Bills Past Due</p>
             </div>
             <div class="icon">
               <i class="ion ion-pie-graph"></i>
             </div>
             <a href="/admin/bills?grade=all&invoice=pastdue&type=all&status=false&search=" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
       </div>
       <!-- /.row -->
       <!-- Main row -->
       <div class="row">
         <!-- Left col -->
         <section class="col-lg-7 connectedSortable">
           <!-- Custom tabs (Charts with tabs)-->
           <div class="card">
             <div class="card-header">
               <h3 class="card-title">
                 <i class="fas fa-chart-pie mr-1"></i>
                 Bills
               </h3>
               <div class="card-tools">
                 <ul class="nav nav-pills ml-auto">
                   <li class="nav-item">
                     <a class="nav-link active" href="#revenue-chart" data-toggle="tab">New</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" href="#sales-chart" data-toggle="tab">Past Due</a>
                   </li>
                 </ul>
               </div>
             </div><!-- /.card-header -->
             <div class="card-body">
               <div class="tab-content p-0">
                 <!-- Morris chart - Sales -->
                 <div class="chart tab-pane active" id="revenue-chart"
                      style="position: relative; height: 310px;">

                    
                      @if (sizeof($data->dataBill) == 0)
                  <div class="d-flex justify-content-center">

                    <h2>Data bill does not exist !!!</h2>
                  
                  </div>
                  @else
                     {{-- <h1>New Bills</h1> --}}
                     <div class="card">
                      <!-- /.card-header -->
                      <div class="card-body">
                        <ul class="todo-list" data-widget="todo-list">

                        @php
                          $currentDate = date('y-m-d');
                       @endphp 

                          @foreach ($data->dataBill as $el)
                            
                          
                          <li>
                            <!-- drag handle -->
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <!-- checkbox -->
                            <div  class="icheck-primary d-inline ml-2">
                              <span class="text-muted">[ {{date( 'd F Y',strtotime($el->deadline_invoice))}} ]</span>
                            </div>
                            <!-- todo text -->
                            <span class="text">( {{$el->type}} ) {{$el->student->name}}</span>
                            <!-- Emphasis label -->
                            

                            @if ($el->paidOf)
                              
                              <small class="badge badge-success"><i class="far fa-checklist"></i> Success</small>

                            @elseif (strtotime($el->deadline_invoice) < strtotime($currentDate))

                              <small class="badge badge-danger"><i class="far fa-clock"></i> Past Due</small>
                            @else
                              @php
                                  $date1 = date_create($currentDate);
                                  $date2 = date_create(date('y-m-d', strtotime($el->deadline_invoice)));
                                  $dateWarning = date_diff($date1, $date2);
                                  $dateDiff = $dateWarning->format('%a') == 0? 'Today' : $dateWarning->format('%a'). ' days';
                              @endphp
                              <small class="badge badge-warning"><i class="far fa-clock"></i> {{

                                  $dateDiff
                              
                              }}</small>
                            @endif
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                              <a href="/admin/bills/detail-payment/{{$el->id}}" target="_blank">
                                <i class="fas fa-link"></i>
                              </a>
                            </div>
                          </li>
                          
                          @endforeach
                        </ul>
                      </div>
                    </div>

                    @endif

                  </div>
                 <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 310px;">

                  @if (sizeof($data->dataPastDue) == 0)
                  <div class="d-flex justify-content-center">

                    <h2>Data past due does not exist !!!</h2>
                  
                  </div>
                  @else

                   {{-- <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
                   <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                      <ul class="todo-list" data-widget="todo-list">

                      @php
                        $currentDate = date('y-m-d');
                     @endphp 

                        @foreach ($data->dataPastDue as $el)
                          
                        
                        <li>
                          <!-- drag handle -->
                          <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                          </span>
                          <!-- checkbox -->
                          <div  class="icheck-primary d-inline ml-2">
                            <span class="text-muted">[ {{date( 'd F Y',strtotime($el->deadline_invoice))}} ]</span>
                          </div>
                          <!-- todo text -->
                          <span class="text">( {{$el->type}} ) {{$el->student->name}}</span>
                          <!-- Emphasis label -->
                          

                          @if ($el->paidOf)
                            
                            <small class="badge badge-success"><i class="far fa-checklist"></i> Success</small>

                          @elseif (strtotime($el->deadline_invoice) < strtotime($currentDate))

                            <small class="badge badge-danger"><i class="far fa-clock"></i> Past Due</small>
                          @else
                            @php
                                $date1 = date_create($currentDate);
                                $date2 = date_create(date('y-m-d', strtotime($el->deadline_invoice)));
                                $dateWarning = date_diff($date1, $date2);
                                $dateDiff = $dateWarning->format('%a days');
                            @endphp
                            <small class="badge badge-warning"><i class="far fa-clock"></i> {{

                                $dateDiff
                            
                            }}</small>
                          @endif
                          <!-- General tools such as edit or delete-->
                          <div class="tools">
                            <a href="/admin/bills/detail-payment/{{$el->id}}" target="_blank">
                              <i class="fas fa-link"></i>
                            </a>
                          </div>
                        </li>
                        
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  @endif
                 </div>
               </div>
             </div><!-- /.card-body -->
           </div>
           <!-- /.card -->

           

           <!-- Calendar -->
          <div class="card bg-gradient-secondary">
            <div class="card-header border-0">

              <h3 class="card-title">
                <i class="far fa-calendar-alt"></i>
                Calendar
              </h3>
              <!-- tools card -->
              <div class="card-tools">
                <!-- button with a dropdown -->
                <button type="button" class="btn btn-secondary btn-sm" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pt-0">
              <!--The calendar -->
              <div id="calendar" style="width: 100%"></div>
            </div>
            <!-- /.card-body -->
          </div>
            
           
         </section>
         <!-- /.Left col -->
         <!-- right col (We are only adding the ID to make the widgets sortable)-->
         <section class="col-lg-5 connectedSortable">

          <!-- Teacher List -->
          <div class="card bg-gradient-success">
            <div class="card-header">
              <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i>
                Teacher's
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
             <table class="table table-borderless">
               <thead>
                 <tr>
                   <th scope="col">#</th>
                   <th scope="col">Name</th>
                   <th scope="col">Place Birth</th>
                   <th scope="col">Register Date</th>
                 </tr>
               </thead>
               <tbody>

               @foreach ($data->dataTeacher as $el)
                 <tr>
                   <td scope="row">{{$loop->index+1}}</td>
                   <td>{{$el->name}}</td>
                   <td>{{$el->place_birth}}</td>
                   <td>{{date('d/m/Y', strtotime($el->created_at))}}</td>
                 </tr>
               @endforeach  
                 
               </tbody>
             </table>
            </div>
          </div>
          <!-- /.card -->
          


           <!-- Map card -->
           <div class="card bg-gradient-info">
             <div class="card-header border-0">
               <h3 class="card-title">
                 <i class="fas fa-map-marker-alt mr-1"></i>
                 Student's
               </h3>
               <!-- card tools -->
               <div class="card-tools">
                 <button type="button" class="btn btn-info btn-sm" data-card-widget="collapse" title="Collapse">
                   <i class="fas fa-minus"></i>
                 </button>
               </div>
               <!-- /.card-tools -->
             </div>
             <div class="card-body">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Place Birth</th>
                    <th scope="col">Register Date</th>
                  </tr>
                </thead>
                <tbody>

                @foreach ($data->dataStudent as $el)
                  <tr>
                    <td scope="row">{{$loop->index+1}}</td>
                    <td>{{$el->name}}</td>
                    <td>{{$el->place_birth}}</td>
                    <td>{{date('d/m/Y', strtotime($el->created_at))}}</td>
                  </tr>
                @endforeach  
                  
                </tbody>
              </table>
             </div>
             <!-- /.card-body-->
             <div class="card-footer bg-transparent">
               <div class="row">
                 <div class="col-4 text-center">
                   <div id="sparkline-1"></div>
                   <div class="text-white">Visitors</div>
                 </div>
                 <!-- ./col -->
                 <div class="col-4 text-center">
                   <div id="sparkline-2"></div>
                   <div class="text-white">Online</div>
                 </div>
                 <!-- ./col -->
                 <div class="col-4 text-center">
                   <div id="sparkline-3"></div>
                   <div class="text-white">Sales</div>
                 </div>
                 <!-- ./col -->
               </div>
               <!-- /.row -->
             </div>
           </div>
           <!-- /.card -->

           <!-- /.card -->
         </section>
         <!-- right col -->
       </div>
       <!-- /.row (main row) -->
     </div><!-- /.container-fluid -->
   </section>
 <!-- /.content-wrapper -->
@endsection
