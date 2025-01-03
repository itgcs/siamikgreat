@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
         
    <div class="row h-100 my-5">
        <div class="col-sm-12 my-auto text-center">
            <h3>The teachers you are looking for does not exist !!!</h3>
        </div>
    </div>

     @elseif (sizeof($data) == 0)

    <div class="row h-100 my-5">
        <div class="col-sm-12 my-auto text-center">
            <h3>Teacher has never been registered. Click the
                button below to register teacher's !!!</h3>
            <a role="button" href="/{{session('role')}}/teachers/register" class="btn btn-success mt-4">
                <i class="fa-solid fa-plus"></i>
                Register Teacher
            </a>
        </div>
    </div>

    @else

    
    <div class="row">
        <a type="button" href="teachers/register" id="#" class="btn btn-success btn mx-2">
           <i class="fa-solid fa-user-plus"></i>
           </i>   
           Register
        </a>
    </div>

    <div class="card card-dark mt-2">
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
                        <th>#</th>
                        <th>Teacher Name</th>
                        <th>Place of birth</th>
                        <th>Nationality</th>
                        <th>Gender</th>
                        <!-- <th>
                            Total Class
                        </th>
                        <th>
                            Total Subject
                        </th>
                        <th>
                            Total Exam
                        </th> -->
                        <th>Register at</th>
                        <th>Status</th>
                        <th>Action</th>
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

                            
                                <!-- <td class="text-center">{{ $count[$loop->index]->active_grade_count }}</td>
                                <td class="text-center">{{ $count[$loop->index]->active_subject_count }}</td>
                                <td class="text-center">{{ $count[$loop->index]->active_exam_count }}</td> -->
                                                            
                                <td>{{date('d/m/Y', strtotime($el->created_at))}}</td>
                                <td>
                                    @if($el->is_active)
                                    <h1 class="badge badge-success">Active</h1>
                                    @else
                                    <h1 class="badge badge-danger">Inactive</h1>
                                    @endif
                                    
                                </td>
                                <td class="project-actions text-left toastsDefaultSuccess">
                                    <a class="btn btn-primary btn-sm" title="View" href="teachers/detail/{{$el->unique_id}}">
                                        <i class="fas fa-eye"></i>
                                        
                                    </a>
                                    <a class="btn btn-info btn-sm" title="Edit" href="teachers/edit/{{$el->unique_id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                        
                                    </a>
                                    @if(session('role') == 'superadmin')
                                    <a class="btn btn-info btn-sm" title="Hapus" data-toggle="modal" data-target="#modalDeleteTeacher{{$el->id}}">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                    @endif
                                    @if (!$el->is_active)
                                        <a href="javascript:void(0)" id="active-teacher" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-success btn-sm">
                                        <i class="fas fa fa-check"></i>
                                        Activate
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" id="delete-teacher" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa fa-ban"></i>
                                        Deactive
                                        </a>
                                    @endif
                                </td>
                            </tr>  

                            <!-- Modal -->
                            <div class="modal fade" id="modalDeleteTeacher{{$el->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Delete teacher</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure want to delete this teacher?
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                                    <a class="btn btn-danger btn" href="{{url('/' . session('role') .'/teachers') . '/delete/' . $el->id}}">Yes delete</a>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
    </div>
    @include('components.super.delete-teacher')

    <div class="d-flex justify-content-end">
        <nav aria-label="...">
            <ul class="pagination" max-size="2">
                
                @php
                $role = session('role');
                $link= '/'.$role.'/teachers?';
                $previousLink = $link . '&page='.$data->currentPage()-1;
                $nextLink = $link . '&page='.$data->currentPage()+1;
                $firstLink = $link . '&page=1';
                $lastLink = $link . '&page=' . $data->lastPage();
                
                $arrPagination = [];
                $flag = false;
                
                if($data->lastPage() - 5 > 0){
                    
                    
                    if($data->currentPage()<=4)
                    {
                        for ($i=1; $i <= 5; $i++) { 
                            # code...
                            $temp = (object) [
                                
                                'page' => $i,
                                'link' => $link . '&page=' . $i,
                            ];
                            
                            array_push($arrPagination, $temp);
                        }
                    }
                    
                    else if($data->lastPage() - $data->currentPage() > 2)
                    {
                        $flag = true;
                        $idx = array($data->currentPage()-2,$data->currentPage()-1,$data->currentPage(),$data->currentPage()+1,$data->currentPage()+2);
                        
                        foreach ($idx as $value) {
                            
                            $temp = (object) [
                                
                                'page' => $value,
                                'link' => $link . '&page=' . $value,
                            ];
                            
                            array_push($arrPagination, $temp);
                        }
                        
                    } else {
                        
                        $arrFirst = [];
                        //ini buat yang current page sampai last page
                        
                        for($i=$data->currentPage(); $i<=$data->lastPage(); $i++){

                            $temp = (object) [
                            
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        
                        array_push($arrFirst, $temp);
                    }
                    
                    
                    $arrLast = [];
                        $diff = $data->currentPage() - (5 - sizeof($arrFirst));
                        //ini yang buat current page but decrement
                        

                        for($i=$diff; $i < $data->currentPage(); $i++){

                            $temp = (object) [
                                
                                'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];

                        
                        array_push($arrLast, $temp);
                    }
                    
                    
                    $arrPagination = array_merge($arrLast, $arrFirst);
                    }
                    
                    
                    
                } else {

                    for($i=1; $i<=$data->lastPage(); $i++)
                    {
                        $temp = (object) [
                            
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];

                        array_push($arrPagination, $temp);
                    }
                }
                
                @endphp

                <li class="mr-1 page-item {{$data->previousPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$firstLink}}" tabindex="+1">
                        << First
                    </a>
                </li>

                <li class="page-item {{$data->previousPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$previousLink}}" tabindex="-1">
                        Previous
                    </a>
                </li>

                @foreach ( $arrPagination as $el)
                
                <li class="page-item {{$el->page === $data->currentPage() ? 'active' : ''}}">
                    <a class="page-link" href="{{$el->link}}">
                        {{$el->page}}
                    </a>
                </li>

                @endforeach
                
                <li class="page-item {{$data->nextPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$nextLink}}" tabindex="+1">
                        Next
                    </a>
                </li>

                <li class="ml-1 page-item {{$data->nextPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$lastLink}}" tabindex="+1">
                        Last >>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
    
@endif

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


@if(session('after_create_teacher')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully registered the teacher in the database !!!',
        });
    </script>
@endif 


@if (session('after_update_teacher'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the teacher in the database !!!',
        });
    </script>

@endif

@if (session('after_delete_teacher'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully delete teacher in the database !!!',
        });
    </script>
@endif

@endsection