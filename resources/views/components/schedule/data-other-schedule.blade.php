@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

   <div class="card card-dark mt-5">
      <div class="card-header">
         <h3 class="card-title">Schedule</h3>

         <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
         </div>
      </div>
      <div class="card-body">
         <table class="table table-striped projects">
               <thead>
                  <tr>
                     <th >
                        #
                     </th>
                     <th>
                        Type Schedule
                     </th>
                     <th>
                        Date
                     </th>
                     <th>
                        Note
                     </th>
                     <th class="text-center">
                        Action
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
                           {{$el->type_schedule}}
                        </a>
                     </td>
                     <td>
                        <a>
                           @if($el->end_date != null)
                                 ({{ $el->date }}) - ({{ $el->end_date }})
                           @else
                                 {{ $el->date }}
                           @endif
                        </a>
                     </td>
                     <td>
                        <a>
                           {{$el->note}}
                        </a>
                     </td>
                     
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a type="button" data-toggle="modal" data-target="#modalEditOtherSchedule" class="btn btn-primary btn edit-schedule-btn" data-id="{{ $el->id }}">
                           <i class="fas fa-pen"></i> Edit
                        </a>

                        <a class="btn btn-danger btn"
                           href="{{url('/' . session('role') .'/schedules/otherSchedule/delete') . '/' . $el->id}}">
                           <i class="fas fa-trash">
                           </i>
                           Delete
                        </a>
                     </td>
                  </tr>

                  @endforeach
               </tbody>
         </table>

      </div>
   </div>

   <div class="modal fade" id="modalEditOtherSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Schedule</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form method="POST">
                     @csrf
                     <div class="card card-dark">
                           <div class="card-body">
                              <div class="form-group row">
                                 <div class="col-md-12">
                                       <label for="type_schedule">Type Schedule<span style="color: red"> *</span></label>
                                       <select required name="type_schedule" class="form-control" id="type_schedule">
                                          <option value="">-- TYPE SCHEDULE --</option>
                                             @foreach($typeSchedule as $ts)
                                                <option value="{{ $ts->id }}" {{ old('type_schedule') == $ts->id ? 'selected' : '' }}>{{ $ts->name }}</option>
                                                @endforeach
                                    </select>
                                       @if($errors->has('type_schedule'))
                                          <p style="color: red">{{ $errors->first('type_schedule') }}</p>
                                       @endif
                                 </div>
                              </div>

                              <div class="form-group row">
                                 <div class="col-md-12">
                                       <label for="date">Date<span style="color: red"> *</span></label>
                                       <input name="date" type="date" class="form-control" id="date" required>
                                       @if($errors->has('date'))
                                          <p style="color: red">{{ $errors->first('date') }}</p>
                                       @endif
                                 </div>
                              </div>

                              <div class="form-group row">
                                 <div class="col-md-12">
                                       <label for="end_date">Until<span style="color: red"></span></label>
                                       <input name="end_date" type="date" class="form-control" id="_end_date">
                                       @if($errors->has('end_date'))
                                          <p style="color: red">{{ $errors->first('end_date') }}</p>
                                       @endif
                                 </div>
                              </div>

                              <div class="form-group row">
                                 <div class="col-md-12">
                                       <label for="notes">Notes<span style="color: red"> *</span></label>
                                       <textarea required name="notes" class="form-control" id="notes" cols="10" rows="1"></textarea>
                                       @if($errors->has('notes'))
                                          <p style="color: red">{{ $errors->first('notes') }}</p>
                                       @endif
                                 </div>
                              </div>
                           </div>

                           <div class="row d-flex justify-content-center">
                              <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                           </div>
                     </div>
                  </form>
               </div>
         </div>
      </div>
   </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.edit-schedule-btn').on('click', function() {
            var scheduleId = $(this).data('id');
            $.ajax({
                url: '/get-schedule/' + scheduleId,
                method: 'GET',
                success: function(response) {
                    // Isi modal dengan data yang diambil
                    $('#type_schedule').val(response.type_schedule_id);
                    $('#date').val(response.date);
                    $('#_end_date').val(response.end_date);
                    $('#notes').val(response.note);

                    // Ubah atribut action dari form
                    $('#modalEditOtherSchedule form').attr('action', '/update-schedule/' + scheduleId);
                },
                error: function(error) {
                    console.error('Error fetching schedule data:', error);
                }
            });
        });
    });
</script>



@if(session('after_update_schedule')) 
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
         title: 'Successfully updated the schedule in the database.',
   });
   }, 1500);

   
   </script>
@endif

@if(session('after_edit_schedule')) 

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
            title: 'Successfully edit schedule in the database.',
      });
      }, 1500);


   </script>

@endif

@if(session('after_delete_schedule')) 
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
         title: 'Successfully deleted the schedule in the database.',
   });
   }, 1500);

   
   </script>
@endif

@endsection
