@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<h2 class="text-center display-4">Bills Search</h2>
<form class="my-5" action="/admin/bills">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Grade:</label>
                        @php

                        $selected = $form && $form->grade? $form->grade : null;

                        @endphp
                        <select name="grade" class="form-control" required>
                            <option class="text-center" {{!$selected ? 'selected' : ''}} value="all">-- All Grades --</option>
                            @foreach ($grade as $el)
                                
                                <option class="text-center" {{$selected == $el->id ? 'selected' : ''}} value="{{$el->id}}">{{$el->name . ' - '. $el->class}}</option>
                            
                            @endforeach
                            </option>
                        </select>

                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">

                        @php

                        $selected = $form && $form->invoice ? $form->invoice : 'all';

                        @endphp

                        <label>Invoice : <span style="color: red"></span></label>
                        <select name="invoice" class="form-control text-center">
                            <option {{$selected === 'all'? 'selected' : ''}} value="all" >-- All Invoice --</option>
                            <option {{$selected === '30'? 'selected' : ''}} value="30" >30 Days</option>
                            <option {{$selected === '7'? 'selected' : ''}} value="7" >7 Days</option>
                            <option {{$selected === '1'? 'selected' : ''}} value="1" >Tomorrow</option>
                            <option {{$selected === 'today'? 'selected' : ''}} value="today" >Today</option>
                            <option {{$selected === 'pastdue'? 'selected' : ''}} value="pastdue" >Past Due</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">

                        @php

                        $selected = $form && $form->type? $form->type : 'all';

                        @endphp

                        <label>Type : </label>
                        <select name="type" class="form-control text-center">
                            <option {{$selected === 'all'? 'selected' : ''}} value="all">-- All Type --</option>
                            <option {{$selected === 'Paket'? 'selected' : ''}} value="Paket">Paket</option>
                            <option {{$selected === 'Book'? 'selected' : ''}} value="Book">Book</option>
                            <option {{$selected === 'SPP'? 'selected' : ''}} value="SPP">SPP</option>
                            <option {{$selected === 'Uniform'? 'selected' : ''}} value="Uniform">Uniform</option>
                        </select>

                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>Paid: <span style="color: red"></span></label>

                        @php

                            $selected = $form->status != 'all' && $form->status ? $form->status : 'all'

                        @endphp

                        <select name="status" class="form-control text-center">
                            <option {{$selected == 'all'? 'selected' : ''}} value="all">-- All Status --</option>
                            <option {{$selected == 'true'? 'selected' : ''}} value="true">Paid</option>
                            <option {{$selected == 'false'? 'selected' : ''}} value="false">Not yet</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-lg">
                    <input name="search" value="{{$form->search}}" type="search" class="form-control form-control-lg"
                        placeholder="Type students name here">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="container-fluid mt-5">

    @if(sizeof($data) <= 0) <div class="row h-100">
        <div class="col-sm-12 my-auto text-center">
            <h3>The bills you are looking for does not exist !!!</h3>
            {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
                <i class="fa-solid fa-plus"></i>
                Create bill for student
            </a> --}}
        </div>
</div>

@else


{{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
    <i class="fa-solid fa-plus"></i>
    Create bill for student
</a> --}}

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
                    <th class="text-center" style="width: 10%">
                        Type
                    </th>
                    <th style="width: 20%">
                        Student
                    </th>
                    <th>
                        Amount
                    </th>
                    
                    <th>
                        Grade
                    </th>
                    <th style="width: 8%" class="text-center">
                        Class
                    </th>
                    <th style="width: 8%" class="text-center">
                        Paid of
                    </th>
                    <th style="width: 8%" class="text-center">
                        Invoice
                    </th>
                    <th style="width: 25%">
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $el)
                <tr id={{'index_student_' . $el->id}}>
                    <td>
                        {{ $loop->index+1 }}
                    </td>
                    <td class="text-center">
                        <a>
                            {{$el->type}}
                        </a>
                    </td>
                    <td >
                        {{$el->student->name}}
                    </td>
                     @php
                        $amount  = $el->discount ? $el->amount - $el->amount * $el->discount/100 : $el->amount

                     @endphp
                        <td>
                        IDR. 
                        {{number_format($amount, 0, ',', '.')}}
                        @if($el->discount)
                        <br><small class="text-muted">discount: {{$el->discount}}%</small>
                        @elseif ($el->installment)
                        <br><small class="text-muted">installment: {{$el->installment}} month</small>
                        @endif   
                     </td>
                        <td>
                           {{$el->student->grade->name}}
                        </td>
                        <td class="text-center">
                           {{$el->student->grade->class}}
                        </td>
                        <td class="project-state text-center">
                            @if($el->paidOf)
                            <h1 class="badge badge-success">done</h1>
                            @else
                            <h1 class="badge badge-danger">not yet</h1>
                            @endif
                        </td>
                        <td class="text-center">
                            {{date('d M Y', strtotime($el->deadline_invoice))}}
                        </td>
                        <td class="project-actions text-right toastsDefaultSuccess text-center">
                            <a class="btn btn-primary"
                                href="/admin/bills/detail-payment/{{$el->id}}">
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

@include('components.super.delete-student')

@endif
</div>

@endsection
