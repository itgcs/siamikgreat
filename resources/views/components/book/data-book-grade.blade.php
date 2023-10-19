@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">


    @if (sizeof($data) <= 0 )

    <div class="row h-100">
        <div class="col-sm-12 my-auto text-center">
            <h6>Book has never been created. Click the
                button below to get started !!!</h6>
            <a role="button" href="/admin/books/create" class="btn btn-success mt-4">
                <i class="fa-solid fa-plus"></i>
                Create Book
            </a>
        </div>
    </div>


    @else    


    <h2 class="text-center display-4">Book Search</h2>
           <form class="mt-5" action="/admin/books">
               <div class="row">
                   <div class="col-md-10 offset-md-2">
                       <div class="row">
                           <div class="col-3">
                               <div class="form-group">
                                   <label>Grade :</label>
                                   @php
                                       
                                       $selected = $form && $form->grade_id ? $form->grade_id : 'all';

                                    @endphp
                                    <select name="grade_id" class="form-control form-control-lg ">
                                        <option {{$selected === 'all' ? 'selected' : ''}} value="">none</option>

                                        @foreach ($grades as $grade)
                                        <option {{$selected == $grade->id ? 'selected' : ''}} value="{{$grade->id}}">{{$grade->name}} - {{$grade->class}}</option>
                                        @endforeach
                                    </select>
                                  
                               </div>
                           </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label>Book name :</label>
                                <div class="input-group input-group-lg">
                                    <input name="book_name" value="{{$form && $form->book_name? $form->book_name : ""}}" type="search" class="form-control form-control-lg" placeholder="Search book name">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                           
                       </div>
                   </div>
               </div>
           </form >


    <a role="button" href="/admin/books/create" class="btn btn-success mt-4">
        <i class="fa-solid fa-plus"></i>
        Create Book
    </a>

    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Book</h3>

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
                        <th style="width: 25%">
                           Book name
                        </th>
                        <th style="width: 25%">
                           Grade
                        </th>
                        <th style="width: 25%">
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
                                 {{$el->name}}
                           </a>
                        </td>
                        <td>
                           @if ($el->grade)
                              
                              <a  a href="/admin/grades/{{$el->grade->id}}">
                                    {{$el->grade->name}} - {{$el->grade->class}}
                              </a>
                           @else 
                              <h6 style="color: red;">{{'Unknown'}}</h6>
                           @endif
                        </td>
                        <td>
                           {{$el->active_student_count}}
                        </td>
                        
                        <td class="project-actions text-right toastsDefaultSuccess text-center">
                           <a class="btn btn-primary btn"
                              href="{{url('/admin/books/') . '/detail/' . $el->id}}">
                              <i class="fas fa-folder">
                              </i>
                              View
                           </a>
                           <a class="btn btn-info btn"
                              href="{{url('/admin/books/') . '/edit/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                           </a>
                        </td>
                    </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        <!-- /.card-body -->
        </div>
    @endif
</div>


@endsection
