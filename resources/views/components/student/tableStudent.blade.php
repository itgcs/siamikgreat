@extends('layouts.admin.master')
@section('content')
   <!-- Content Wrapper. Contains page content -->
       <div class="container-fluid">
           <h2 class="text-center display-4">Student Search</h2>
           <form class="mt-5" action="enhanced-results.html">
               <div class="row">
                   <div class="col-md-10 offset-md-1">
                       <div class="row">
                           <div class="col-6">
                               <div class="form-group">
                                   <label>Result Type:</label>
                                    <select name="studentGender" class="form-control" required>
                                        <option selected disabled value="">--- Please Select One ---</option>
                                        <option>Female</option>
                                        <option>Male</option>
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-3">
                               <div class="form-group">
                                   <label>Sort Order:</label>
                                    <select name="studentGender" class="form-control" required>
                                          <option selected disabled value="">--- Please Select One ---</option>
                                          <option>Female</option>
                                          <option>Male</option>
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-3">
                               <div class="form-group">
                                 <label>Gender<span style="color: red">*</span></label>
                                 <select name="studentGender" class="form-control">
                                     <option selected disabled value="">--- Please Select One ---</option>
                                     <option>Female</option>
                                     <option>Male</option>
                                 </select>                              
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <div class="input-group input-group-lg">
                               <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here">
                               <div class="input-group-append">
                                   <button type="submit" class="btn btn-lg btn-default">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </form >

           <div class="card mt-5">
            <div class="card-header">
              <h3 class="card-title">Student</h3>
    
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
                          <th style="width: 1%">
                              #
                          </th>
                          <th style="width: 20%">
                              Student Name
                          </th>
                          <th>
                              Place of birth
                          </th>
                          <th style="width: 10%">
                              Gender
                          </th>
                          <th>
                              Grade
                          </th>
                          <th style="width: 8%">
                              Class
                          </th>
                          <th style="width: 8%" class="text-center">
                              Status
                          </th>
                          <th style="width: 20%">
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                     @foreach ($data as $el)
                     <tr>
                        <td>
                           {{ $loop->index + 1 }}
                          </td>
                          <td>
                              <a>
                                 {{$el->name}}
                              </a>
                              <br/>
                              <small>
                                 @php
                                    
                                    $birthDate = explode("-", $el->date_birth);

                                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") 
                                    ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
                                 @endphp
                                 {{$age}} years old
                              </small>
                           </td>
                           <td>
                              {{$el->place_birth}}
                           </td>
                           <td>
                              {{$el->gender}}
                           </td>
                           <td>
                              <a>
                              Junior High School
                           </a>
                        </td>
                        <td>
                           3
                        </td>
                        <td class="project-state">
                           <h1 class="badge badge-success">Active</h1>
                        </td>
                          <td class="project-actions text-right toastsDefaultSuccess">
                             <a class="btn btn-primary" href={{url('/admin/detail') . '/' . $el->id}}>
                                <i class="fas fa-folder">
                              </i>
                              View
                           </a>
                           <a class="btn btn-info btn" href={{url('/admin/update') . '/' . $el->id}}>
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                           </a>
                           {{-- <a class="btn btn-danger btn-sm" href="#">
                              <i class="fas fa-trash">
                              </i>
                              Delete
                           </a> --}}
                        </td>
                     </tr>
                     
                     @endforeach
                  </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
       </div>
@endsection