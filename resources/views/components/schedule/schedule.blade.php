@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Schedules</li>
                    <li class="breadcrumb-item active" aria-current="page">School</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('role') == 'superadmin' || session('role') == 'admin')
    <a type="button" data-toggle="modal" data-target="#modalAddOtherSchedule" class="btn btn-success btn">   
        <i class="fa-solid fa-calendar-plus"></i>
        </i>   
        Add Schedule
    </a>
    <a href="{{url('/' . session('role') .'/schedules/schools/manage/otherSchedule') }}" class="btn btn-warning btn">   
        <i class="fa-solid fa-pencil"></i>
        </i>   
        Manage
    </a>
    @endif

    <div class="card card-dark mt-2">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">School Events & Exam Calendar</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Modal Detail-->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Other Schedule -->
<div class="modal fade" id="modalAddOtherSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Other Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperCreateOtherSchedule')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateOtherSchedule')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-body" style="position: relative; max-height: 500px; overflow-y: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>Type Schedule</th>
                                        <th>Date</th>
                                        <th>Until</th>
                                        <th>Notes</th>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        <tr>
                                            <td>
                                                <select required name="type_schedule[]" class="form-control" id="type_schedule">
                                                    <option value="">-- TYPE SCHEDULE --</option>
                                                    @foreach($data as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input name="date[]" type="date" class="form-control" id="date" required>
                                            </td>
                                            <td>
                                                <input name="end_date[]" type="date" class="form-control" id="_end_date">
                                            </td>
                                            <td>
                                                <textarea required name="notes[]" class="form-control" id="notes" cols="10" rows="1"></textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan CSS untuk modal khusus -->
<style>
.custom-modal-dialog {
    max-width: 80%; /* Atur persentase sesuai kebutuhan Anda */
    width: auto !important; /* Untuk memastikan lebar otomatis */
}
</style>

<link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var exams = @json($exams);
        var schedules = @json($schedules);

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            events: [
                ...exams.map(exam => ({
                    title: `${exam.type_exam} - (${exam.name_exam})`,
                    start: exam.date_exam,
                    description: `<br>Teacher : ${exam.teacher_name} <br>Grade : ${exam.grade_name} - ${exam.grade_class} `,
                    color: 'lime',
                    jadwal: new Date(exam.date_exam).toLocaleDateString('id-ID', { month: 'long', day: 'numeric', year: 'numeric' }),
                    sampai: exam.end_date ? new Date(exam.end_date).toLocaleDateString('id-ID', { month: 'long', day: 'numeric', year: 'numeric' }) : null,
                })),
                ...schedules.map(schedule => {
                    let endDate = schedule.end_date ? new Date(schedule.end_date) : null;
                    if (endDate) {
                        endDate.setDate(endDate.getDate() + 1); // Menambahkan satu hari
                    }
                    return {
                        title: schedule.note,
                        start: schedule.date,
                        end: endDate ? endDate.toISOString().split('T')[0] : null,
                        description: schedule.note,
                        color: schedule.color,
                        jadwal: new Date(schedule.date).toLocaleDateString('id-ID', { month: 'long', day: 'numeric', year: 'numeric' }),
                        sampai: schedule.end_date ? new Date(schedule.end_date).toLocaleDateString('id-ID', { month: 'long', day: 'numeric', year: 'numeric' }) : null,
                    };
                }),
            ],
            eventClick: function(info) {
                document.getElementById('eventTitle').innerText = 'Event: ' + info.event.title;
                if (info.event.extendedProps.sampai === null) {
                    document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description + ' (' + info.event.extendedProps.jadwal + ')';
                }
                else {
                    document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description + ' (' + info.event.extendedProps.jadwal + ' until ' + info.event.extendedProps.sampai + ')';
                }
                var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                    keyboard: false
                });
                eventModal.show();
            }
        });
        calendar.render();
    });
</script>

<script src="{{asset('template')}}/plugins/jquery/jquery.min.js"></script>

<script>
$(document).ready(function() {
    // Function to add a new row
    function addRow() {
        var newRow = `
        <tr>
            <td>
                <select required name="type_schedule[]" class="form-control" id="type_schedule">
                    <option value="">-- TYPE SCHEDULE --</option>
                    @foreach($data as $el)
                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input name="date[]" type="date" class="form-control" id="date" required>
            </td>
            <td>
                <input name="end_date[]" type="date" class="form-control" id="_end_date">
            </td>
            <td>
                <textarea required name="notes[]" class="form-control" id="notes" cols="10" rows="1"></textarea>
            </td>
            <td>
                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
            </td>
        </tr>
        `;
        $('#scheduleTableBody').append(newRow);

        updateHapusButtons();
    }

    // Function to update the visibility of the "Hapus" buttons
    function updateHapusButtons() {
        $('#scheduleTableBody tr').each(function(index, row) {
            var hapusButton = $(row).find('.btn-hapus');
            if (index === $('#scheduleTableBody tr').length - 1) {
                hapusButton.removeClass('d-none');
            } else {
                hapusButton.addClass('d-none');
            }
        });
    }

    // Event listener for the "Tambah" button
    $('#scheduleTableBody').on('click', '.btn-tambah', function() {
        addRow();
    });

    // Event listener for the "Hapus" button
    $('#scheduleTableBody').on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        updateHapusButtons();
    });

    // Initial call to update the visibility of the "Hapus" buttons
    updateHapusButtons();
});
</script>

@if(session('after_create_otherSchedule')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully'
            text: 'Successfully created new other schedule in the database.'
        });
    </script>
@endif

@endsection
