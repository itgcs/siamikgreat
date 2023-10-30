@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/bills')}}">Bill</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Payment</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">

          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Unique ID</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">#{{$data->id}}</p>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Student Name</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{-- @if($data->is_active)
                           <h1 class="badge badge-success">Active</h1>
                        @else
                           <h1 class="badge badge-danger">Inactive</h1>
                        @endif --}}
                        {{$data->student->name}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Type</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->type}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Subject</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->subject? $data->subject : '-'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Grade</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{$data->student->grade->name}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Class</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{$data->student->grade->class}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Invoice</p>
                  </div>
                  @php
                     $currentDate = date('y-m-d');
                  @endphp  
                  <div class="col-sm-8">
                     <div class="mb-0">

                     <p class="text-muted">
                           {{date('d/m/Y', strtotime($data->deadline_invoice))}}
                     </p>
                     @if ($data->paidOf)
                        <span class="badge badge-pill badge-success"> Paid </span>
                     @elseif (strtotime($data->deadline_invoice) < strtotime($currentDate))
                        <span class="badge badge-pill badge-danger"> Past Due </span>
                     @else
                        <span class="badge badge-pill badge-warning"> {{$invoice == 0? 'Today' : $invoice . ' Days'}}</span>
                     @endif
                  </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Created</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{date('d/m/Y', strtotime($data->created_at))}}
                     </p>
                  </div>
                </div>
                <hr>
              </div>
            </div>

            
          </div>

          <div class="col-4 p-1">
            <div class="card mb-4 p-4">
               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>

                  @php
                     $totalInstallment = $data->amount * $data->installment;
                  @endphp


                     @if (sizeof($data->bill_collection))

                     @foreach ($data->bill_collection as $el)

                        <tr>
                           <td align="left" class="p-1" style="width:65%;">
                              {{$el->name}} :
                           </td>
                           <td align="right" class="p-1">
                              IDR. {{number_format($el->amount, 0, ',', '.')}}
                           </td>

                        </tr>
                        
                     @endforeach
                        
                     @else
                     
                     <tr>
                        <td align="left" class="p-1" style="width:65%;">
                              Amount:
                           </td>
                           <td align="right">
                              IDR. {{number_format($data->installment? $totalInstallment : $data->amount, 0, ',', '.')}}
                           </td>
                           
                        </tr>


                     @if ($data->installment)
                        
                        <tr>
                           <td align="left" class="p-1" style="width:65%;">
                              Installment/months : 
                           </td>
                           <td align="right">
                              {{ $data->installment }}
                           </td>

                        </tr>

                     @endif

                     @if ($data->discount)

                        <tr>
                           <td align="left" class="p-1" style="width:65%;">
                              Discount:
                           </td>
                           <td align="right">
                              {{$data->discount ? $data->discount : 0}}%
                           </td>

                        </tr>
                     @endif
                     @endif
                     
                  </tbody>
               </table>

               @if ($data->bill_collection && $data->installment && $data->type === 'Book') 

               <hr>


               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>

                  <tbody>
                     <tr>
                        <td align="left" class="p-1" style="width:65%;">Total amount :</td>
                        <td align="right">IDR. {{$data->amount * $data->installment}}</td>
                     </tr>
                     <tr>
                        <td align="left" class="p-1" style="width:65%;">Installment/months :</td>
                        <td align="right">{{$data->installment}}</td>
                     </tr>
                  </tbody>
               </table>
                  
               @endif

               <hr>

               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>
                     @php 
                        $total = $data->discount ? $data->amount - $data->amount * $data->discount/100 : $data->amount;   
                     @endphp
                     <tr>
                        <td align="left" class="p-1" style="width:65%;">
                           Total :
                        </td>
                        <td align="right">
                           IDR. {{number_format($total, 0, ',', '.')}}
                        </td>
                        
                     </tr>
                     
                  </tbody>
               </table>
            </div>
            @if (!$data->paidOf)
               @if (strtolower($data->type) == 'paket' && !$data->installment)
               <a href="/admin/bills/change-paket/{{$data->student->unique_id}}/{{$data->id}}" class="btn btn-info w-100 mb-2" id="change-paket">Change Paket</a>
               <a href="/admin/bills/intallment-paket/{{$data->id}}" class="btn btn-secondary w-100 mb-2" id="change-paket">Installment Paket</a>
               @endif
               @if(strtolower($data->type) == 'book')
                  <a href="javascript:void(0)" id="update-status-book" data-id="{{ $data->id }}" data-name="{{ $data->student->name }}" data-student-id="{{ $data->id }}" class="btn btn-success w-100">Paid book success</a>
               @else
                  <a href="javascript:void(0)" id="update-status" data-id="{{ $data->id }}" data-name="{{ $data->student->name }}" data-subject="{{ $data->subject }}" class="btn btn-success w-100">Paid success</a>
               @endif
            @endif
          </div>
        </div>
      </div>
    </section>

    @includeIf('components.super.update-paid')

    @if(session('after_create')) 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
         title: 'Data has been saved !!!',
   });
   }, 1500);


   </script>
        
    @endif

@endsection