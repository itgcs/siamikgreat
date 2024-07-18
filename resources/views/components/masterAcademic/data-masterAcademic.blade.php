@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    
    <a type="button" href="{{ url('/' . session('role') . '/masterAcademics/create') }}" class="btn btn-success  mt-5 mr-2">
        Set master academic   
    </a>
    <a type="button" href="{{url('/' . session('role') .'/masterAcademics') . '/edit'}}" class="btn btn-warning mt-5">
        Edit
    </a>

    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Master Academics</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                @if(!empty($data))
                <tbody>
                        @foreach ($data as $el)
                        <tr>
                            <td>
                                Academic Year
                            </td>
                            <td>
                                <a>{{$el->academic_year}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Semester 1
                            </td>
                            <td>
                                <a>{{ \Carbon\Carbon::parse($el->semester1)->format('d F Y') }}  until  {{ \Carbon\Carbon::parse($el->end_semester1)->format('d F Y') }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Semester 2
                            </td>
                            <td>
                                <a>{{ \Carbon\Carbon::parse($el->semester2)->format('d F Y') }}  until  {{ \Carbon\Carbon::parse($el->end_semester2)->format('d F Y') }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Periode
                            </td>
                            <td>
                                <a>Semester {{ $el->now_semester }}</a>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modalDeletemasterAcademic" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete master schedule</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure want to delete this master schedule?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a class="btn btn-danger btn" href="{{url('/' . session('role') .'/masterAcademics') . '/delete/' . $el->id}}">Yes delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                @else
                    <p class="font-md text-bold text-center mt-2">Data kosong</p>
                @endif
            </table>
        </div>
    </div>
    
    <h5>Export Data :</h5>
    <a type="button" href="{{ url('/' . session('role') . '/export/excel') }}" class="btn btn-success mr-2">
        <i class="fa-regular fa-file-excel"></i>
        Excel
    </a>
    <a type="button" href="{{ url('/' . session('role') . '/export/pdf') }}" class="btn btn-success mr-2">
        <i class="fa-regular fa-file-pdf"></i>
        PDF
    </a>
    

</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_create_masterAcademic'))
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        setTimeout(() => {
            Toast.fire({
                icon: 'success',
                title: 'Successfully created new master academic in the database.'
            });
        }, 1500);
    </script>
@endif

@if(session('after_update_masterAcademic'))
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        setTimeout(() => {
            Toast.fire({
                icon: 'success',
                title: 'Successfully updated the master academic in the database.'
            });
        }, 1500);
    </script>
@endif

@if(session('after_delete_masterAcademic'))
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        setTimeout(() => {
            Toast.fire({
                icon: 'success',
                title: 'Successfully deleted the type schedule in the database.'
            });
        }, 1500);
    </script>
@endif

@endsection
