@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    @if(session('role') == 'admin')
                        <li class="breadcrumb-item"><a href="{{url('/admin/masterAcademics')}}">Master Academic</a></li>
                    @elseif (session('role') == 'teacher')
                        <li class="breadcrumb-item"><a href="{{url('/teacher/masterAcademics')}}">Master Academic</a></li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
    
    <a type="button" href="{{ url('/' . session('role') . '/masterAcademics/create') }}" class="btn btn-success  mr-2">
        <i class="fas fa-calendar-plus"></i>
        Set master academic   
    </a>
    <a type="button" href="{{url('/' . session('role') .'/masterAcademics') . '/edit'}}" class="btn btn-warning">
        <i class="fas fa-solid fa-pencil"></i>
        Edit
    </a>

    <div class="col-12 p-0 mt-1"> 
        <label for="master_academic">Choose Master Academics<span style="color: red"></span></label>
        <select name="master_academic" id="master_academic" class="form-control" onchange="changeMasterAcademic(this.value)">
            <option value="">-- SELECT MASTER ACADEMIC --</option>
            @foreach ($masterAcademic as $ma)
                <option value="{{ $ma->id }}" {{ $data['academic_year'] == $ma->academic_year ? 'selected' : '' }}>
                    Academic Year {{ $ma->academic_year }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Master Academics</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                @if(!empty($data))
                <tbody>
                        <tr>
                            <td>
                                Academic Year
                            </td>
                            <td>
                                <a>{{$data['academic_year']}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Semester 1
                            </td>
                            <td>
                                <a>{{ \Carbon\Carbon::parse($data['semester1'])->format('d F Y') }}  until  {{ \Carbon\Carbon::parse($data['end_semester1'])->format('d F Y') }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Semester 2
                            </td>
                            <td>
                                <a>{{ \Carbon\Carbon::parse($data['semester2'])->format('d F Y') }}  until  {{ \Carbon\Carbon::parse($data['end_semester2'])->format('d F Y') }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Periode
                            </td>
                            <td>
                                <a>Semester {{ $data['now_semester'] }}</a>
                            </td>
                        </tr>
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

<script>
    function changeMasterAcademic(selectedId) {
        if (selectedId) {
            fetch(`/changeMasterAcademic/${selectedId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Reload page after successful change
                } else {
                    alert('Failed to update Master Academic. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating Master Academic.');
            });
        }
    }
</script>

@if(session('after_create_masterAcademic'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new master academic in the database.'
        });
    </script>
@endif

@if(session('after_update_masterAcademic'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the master academic in the database.'
        });
    </script>
@endif

@if(session('after_delete_masterAcademic'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted the type schedule in the database.'
        });
    </script>
@endif

@endsection
