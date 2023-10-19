@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
       <div class="container-fluid">
           <h2 class="text-center display-4">Payment Books Search</h2>
           <form class="mt-5" action="/admin/payment-books">
               <div class="row">
                   <div class="col-md-10 offset-md-1">
                       <div class="row">
                        
                        <div class="col-4">
                            <div class="form-group">

                              @php

                                 $selected = $form && $form->grade_id? $form->grade_id : null;

                              @endphp

                                <label>Grade :</label>
                                 <select name="grade_id" class="form-control">
                                            <option {{!$selected? 'selected' : ''}} value="">All Grades</option>
                                       @foreach ($grade as $el)
                                       
                                            <option {{$selected == $el->id? 'selected' : ''}} value="{{$el->id}}">{{$el->name . '-' . $el->class}}</option>
                                           
                                       @endforeach
                                 </select>
                               
                            </div>
                        </div>
                        <div class="col-4">
                               <div class="form-group">

                                 @php

                                    $selected = $form && $form->order? $form->order : 'id';

                                 @endphp

                                   <label>Sort by:</label>
                                    <select name="order" class="form-control">
                                          <option {{$selected === 'id'? 'selected' : ''}} value="id">Register</option>
                                          <option {{$selected === 'name'? 'selected' : ''}} value="name">Name</option>
                                          <option {{$selected === 'grade_id'? 'selected' : ''}} value="grade_id">Grade</option>
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-2">
                            <div class="form-group">

                                 @php
                                    
                                    $selected = $form && $form->sort ? $form->sort : 'desc';

                                 @endphp

                              <label>Sort order: <span style="color: red"></span></label>
                              <select name="sort" class="form-control">
                                  <option value="desc" {{$selected === 'desc' ? 'selected' : ''}}>Descending</option>
                                  <option value="asc" {{$selected === 'asc' ? 'selected' : ''}}>Ascending</option>
                              </select>                              
                            </div>
                        </div>
                           <div class="col-2">
                               <div class="form-group">
                                 <label>Status: <span style="color: red"></span></label>

                                 @php
                                 
                                    $selected = $form && $form->status ? $form->status : 'true';
                                    $option = $selected === 'false' ? 'true' : 'false';

                                 @endphp

                                 <select name="status" class="form-control">
                                     <option  selected value="{{$selected}}">{{$selected === 'true' ? 'Active' : 'Inactive'}}</option>
                                     <option  value="{{$option}}">{{$option === 'true' ? 'Active' : 'Inactive'}}</option>
                                 </select>                              
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <div class="input-group input-group-lg">
                               <input name="search" value="{{$form->search}}" type="search" class="form-control form-control-lg" placeholder="Type students name here">
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
                              Grade
                          </th>
                          <th style="width: 8%" class="text-center">
                              Class
                          </th>
                          <th style="width: 8%" class="text-center">
                              Total books
                          </th>
                          <th style="width: 8%" class="text-center">
                              Status
                          </th>
                          <th style="width: 25%">
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                     @foreach ($data as $el)
                     <tr id={{'index_student_' . $el->id}}>
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
                              <a>
                                 {{$el->grade->name}}
                           </a>
                        </td>
                        <td class="text-center">
                           {{$el->grade->class}}
                        </td>
                        <td class="text-center">
                            {{$el->book_count}}
                        </td>
                        <td class="project-state">
                           @if($el->is_active)
                           <h1 class="badge badge-success">Active</h1>
                           @else
                           <h1 class="badge badge-danger">Inactive</h1>
                           @endif
                        </td>
                          <td class="project-actions text-center toastsDefaultSuccess">
                             <a class="btn btn-primary btn-lg" href="/admin/payment-books/{{$el->unique_id}}">
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
         
         @include('components.super.delete-student')
@endsection