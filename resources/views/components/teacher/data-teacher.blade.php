@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
   <div class="container-fluid">
      <h2 class="text-center display-4">Teacher Search</h2>
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

      <a type="button" href="teachers/register" id="#" class="btn btn-success btn mt-5 mx-2">
         <i class="fa-solid fa-user-plus"></i>
         </i>   
         Register
      </a>

      <div class="card mt-5">
       <div class="card-header">
         <h3 class="card-title">Teacher</h3>

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
                     <th style="width: 3%">
                         #
                     </th>
                     <th>
                           Teacher Name
                     </th>
                     <th>
                           Place of birth
                     </th>
                     <th>
                           Nationality
                     </th>
                     <th>
                           Gender
                     </th>
                     <th>
                           Created at
                     </th>
                     <th style="width: 8%;" class="text-center">
                           Status
                     </th>
                     <th style="width: 20%">
                     </th>
                 </tr>
             </thead>
             <tbody>
               <tr>
                  @foreach($data as $el)
                  <tr id="{{'index_teacher_' . $el->id}}">
                     <td>{{$loop->index + 1}}</td>
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
                     <td>{{$el->place_birth}}</td>
                     <td>{{$el->nationality}}</td>
                     <td>{{$el->gender}}</td>
                     <td>{{date('d/m/Y', strtotime($el->created_at))}}</td>
                     <td><h1 class="badge badge-success">Active</h1></td>
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a class="btn btn-primary {{session('role') == 'admin'? 'btn' : 'btn-sm'}}" href="teachers/detail/{{$el->id}}">
                           <i class="fas fa-folder">
                         </i>
                         View
                      </a>
                      <a class="btn btn-info {{session('role') == 'admin'? 'btn' : 'btn-sm'}}" href="teachers/{{$el->id}}">
                         <i class="fas fa-pencil-alt">
                         </i>
                         Edit
                      </a>
                      @if(session('role') == 'superadmin')
                         <a href="javascript:void(0)" id="delete-teacher" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash">
                            </i>
                            Delete
                         </a>
                      @endif
                     </td>
                  </tr>
                  @endforeach
               </tr>
             </tbody>
         </table>
       </div>
       <!-- /.card-body -->
     </div>
    </div>
    @include('components.super.delete-teacher')
@endsection