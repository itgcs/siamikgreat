@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/list')}}">Teacher</a></li>
                <li class="breadcrumb-item active" aria-current="page">Teacher Profile</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-body text-center">
                <img src="{{asset('images')}}/user_{{strtolower($data->gender)}}.png" alt="avatar"
                  class="rounded-circle img-fluid" style="width: 150px;">
                <h5 class="my-3">{{$data->name}}</h5>
                <p class="text-muted mb-1">
                                    
                     {{(date("md", date("U", mktime(0, 0, 0, 
                     explode("-", $data->date_birth)[2], 
                     explode("-", $data->date_birth)[1], 
                     explode("-", $data->date_birth)[0]))) > date("md") 
                     ? ((date("Y")-explode("-", $data->date_birth)[0])-1)
                     :(date("Y")-explode("-", $data->date_birth)[0]))
                     }} years old

                </p>
                <p class="text-muted mb-4">{{$data->home_address}}</p>
                {{-- <div class="d-flex justify-content-center mb-2">
                  <button type="button" class="btn btn-primary">Follow</button>
                  <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                </div> --}}
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Fullname</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Id or Passport</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->nuptk}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Gender</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->gender}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Religion</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->religion}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Place Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->place_birth}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Date Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{date("d/m/Y", strtotime($data->date_birth))}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Nationality</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->nationality}}</p>
                  </div>
                </div>
                <hr>
              </div>
            </div>

            
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