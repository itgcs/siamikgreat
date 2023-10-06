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
                    <p class="mb-0">Description</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->description? $data->description : '-'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Student</p>
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
                    <p class="mb-0">Class</p>
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

          <div class="col-4">
            <div class="card mb-4 p-4">
               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>
                     <tr>
                        <td align="left">
                           Amount:
                        </td>
                        <td align="right">
                           {{number_format($data->amount, 0, ',', '.')}}
                        </td>
                        
                     </tr>

                     
                     <tr>
                        <td align="left">
                           Discount:
                        </td>
                        <td align="right">
                           {{$data->discount}}%
                        </td>
                        
                     </tr>
                     
                  </tbody>
               </table>
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
                        <td align="left">
                           Total:
                        </td>
                        <td align="right">
                           IDR. {{number_format($total, 0, ',', '.')}}
                        </td>
                        
                     </tr>
                     
                  </tbody>
               </table>
            </div>

            <a role="button" href="#" class="btn btn-success w-100">Paid success</a>
          </div>
        </div>
      </div>
    </section>
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