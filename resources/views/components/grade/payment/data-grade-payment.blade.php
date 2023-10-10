@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Grades</h3>

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
                        <th style="width: 10%">
                           #
                        </th>
                        <th style="width: 25%">
                           Grades
                        </th>
                        <th>
                           SPP
                        </th>
                        <th>
                            Bundle
                        </th>
                        <th>
                            Book
                        </th>
                        <th>
                            Uniform
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
                                {{$el->name . ' - ' . $el->class}}
                           </a>
                        </td>
                        <td>
                           <a>
                                @if ($el->spp)
                                    <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                                @else
                                <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                                @endif
                           </a>
                        </td>
                        <td>
                           <a>
                            @if ($el->bundle)
                            <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                            @else
                            <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                            @endif
                           </a>
                        </td>
                        <td>
                           <a>
                            @if ($el->book)
                            <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                            @else
                            <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                            @endif
                           </a>
                        </td>
                        <td>
                           <a>
                            @if ($el->uniform)
                            <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                            @else
                            <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                            @endif
                           </a>
                        </td>
                        
                        
                        <td class="project-actions text-center toastsDefaultSuccess">
                           
                           <a class="btn btn-info btn-xl"
                              href="{{url('/admin/payment-grades') . '/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                              <i class="fa-solid fa-file-invoice fa-lg" style="margin-right: 10px;"></i>
                              </i>
                              Data payment
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

@endsection
