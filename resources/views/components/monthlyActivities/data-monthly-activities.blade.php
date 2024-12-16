@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item">Monthly Activities</li>
                  <li class="breadcrumb-item active" aria-current="page">Data</li>
               </ol>
            </nav>
      </div>
   </div>  

   <div class="row">
      <a type="button" class="btn btn-success btn mx-2" data-toggle="modal" data-target="#addMonthlyActivities">   
         <i class="fa-solid fa-book"></i> 
         Add Monthly Activity
      </a>

      {{-- ADD --}}
      <div class="modal fade" id="addMonthlyActivities" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" >Add Data Monthly Activities</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="col-md-12">
                  <label for="major_subject">Major Subject<span style="color: red">*</span></label>
                  <select required name="major_subject[]" class="js-select2 form-control" id="major_subject" multiple="multiple">
                          <option value="" >--- SELECT MAJOR SUBJECT ---</option>
                          @foreach($subjects as $subject)
                              <option value="{{ $subject->id }}">{{ $subject->name_subject }}</option>
                          @endforeach
                  </select>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
               <a class="btn btn-danger btn" id="confirmChange-{{$el->id}}">Change</a>
            </div>
         </div>
      </div>
   </div>


    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Monthly Activity</h3>

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
                        <th style="width: 15%">
                           Monthly Activity
                        </th>
                        <th style="width: 80%">
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                     @if (count($data) !== 0)
                        @foreach ($data as $el)
                           <tr id={{'index_grade_' . $el->id}}>
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td>{{$el->name}}</td>
                                 
                                 <td class="project-actions text-left toastsDefaultSuccess">
                                    <a class="btn btn-warning btn" data-toggle="modal" data-target="#editMonthlyActivities-{{$el->id}}">
                                       <i class="fas fa-pencil-alt">
                                       </i>
                                       Edit
                                    </a>
                                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#deleteMonthlyActivities-{{$el->id}}">
                                       <i class="fas fa-trash"></i>
                                       Delete
                                    </a>
                                    @endif
                                 </td>
                           </tr>

                           <!-- Modal -->
                           {{-- EDIT --}}
                           <div class="modal fade" id="editMonthlyActivities-{{$el->id}}" tabindex="-1" aria-labelledby="exampleModalCenterTitle" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Change Data Monthly Activities</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <div class="modal-body">
                                    Monthly Activities
                                    <input name="class" type="text" class="form-control" id="change-name-{{$el->id}}" placeholder="" value="{{$el->name}}">
                                    <input type="hidden" value="{{$el->id}}" name="data_id" id="data-id-{{$el->id}}">
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                                    <a class="btn btn-danger btn" id="confirmChange-{{$el->id}}">Change</a>
                                 </div>
                              </div>
                           </div>

                           {{-- DELETE --}}
                           <div class="modal fade" id="deleteMonthlyActivities-{{$el->id}}" tabindex="-1" aria-labelledby="exampleModalCenterTitle"  role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button>
                                 </div>
                                 <div class="modal-body">
                                    Are you sure want to delete {{$el->name}}?
                                 </div>
                                 <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                                 <a class="btn btn-danger btn"  href="{{url('/' . session('role') .'/monthlyActivities') . '/delete/' . $el->id}}">Yes delete</a>
                                 </div>
                              </div>
                           </div>
                           
                           
                        @endforeach
                     @else
                        
                     @endif
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
   @if(session('after_create_subject')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new subject in the database.',
        });
      </script>
   @endif

   @if(session('after_update_subject')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the subject in the database.'
         });
      </script>
   @endif

   @if(session('after_delete_subject')) 
      <script>
            Swal.fire({
              icon: 'success',
              title: 'Successfully',
              text: 'Successfully deleted subject in the database.',
        });
      </script>
  @endif

  {{-- ACTION DELETE & UPDATE --}}
   <script>
   const confirmChangeButtons = document.querySelectorAll('[id^="confirmChange-"]');

   confirmChangeButtons.forEach(button => {
      button.addEventListener('click', function(event) {
         const id = this.id.split('-')[1]; // Get the ID from the button's ID
         const changeName = document.getElementById(`change-name-${id}`).value; // Get the selected teacher from the corresponding modal
         const dataId = document.getElementById(`data-id-${id}`).value; // Get the selected teacher from the corresponding modal

         // console.log(changeName);

         const form = {
               id: parseInt(dataId, 10),
               change_name: changeName,
         };

         // console.log(form);
         // // Prepare options for the fetch request
         const options = {
               method: 'PUT',
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                  'Content-Type': 'application/json' // Set the content type to JSON
               },
               body: JSON.stringify(form) // Convert the form object to a JSON string
         };

         // Send the form data using fetch
         fetch("{{ route('actionUpdateMonthly') }}", options)
               .then(response => response.json())
               .then(data => {
                  // Handle the server response
                  if (data.success) {
                     Swal.fire({
                           icon: 'success',
                           text: 'Data Berhasil Diubah',
                           showConfirmButton: false, // Hide the confirm button
                           timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
                           timerProgressBar: true // Optional: show a progress bar
                     }).then(() => {
                           // Optionally, you can still perform actions after the modal closes
                           location.reload();
                     });

                  } else {
                     Swal.fire({
                           icon: 'error',
                           text: 'Maaf ada kesalahan',
                           showConfirmButton: false, // Hide the confirm button
                           timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
                           timerProgressBar: true // Optional: show a progress bar
                     }).then(() => {
                           // Optionally, you can still perform actions after the modal closes
                           location.reload();
                     });
                  }
               })
               .catch(error => {
                  console.error('Fetch error:', error);
               });
      });
   });   
   </script>
@endsection
