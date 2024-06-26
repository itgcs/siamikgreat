@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    @if (!empty($data))
        <div class="card card-dark mt-2">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $data['grade_name'] }} - {{ $data['grade_class'] }}</h5>

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
    @else
        <p>Data Kosong</p>
    @endif
</div>

<!-- Modal Detail Schedule -->
<div class="modal fade " id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Schedule Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>    
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        dayHeaderFormat: { weekday: 'short' },
        slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridiem: 'short' },
        slotMinTime: '07:00:00',
        slotMaxTime: '18:00:00',
        hiddenDays: [0, 6],
        events: [
            @foreach($gradeSchedule as $schedule)
            @php
                $event = [
                    'title' => $schedule->note == "" ? $schedule->subject_name : $schedule->note,
                    'startRecur' => \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d'),
                    'daysOfWeek' => [$schedule->day],
                    'startTime' => $schedule->start_time,
                    'endTime' => $schedule->end_time,
                    'description' => '',
                    'color' => 'blue'
                ];

                switch (strtolower($schedule->note)) {
                    case 'break':
                        $event['description'] = 'BREAK';
                        $event['color'] = 'red';
                        break;
                    case 'eca':
                        $event['description'] = 'ECA';
                        $event['color'] = 'bluesky';
                        break;
                    case 'advisory session by ct':
                        $event['description'] = 'Advisory Session by CT';
                        $event['color'] = 'orange';
                        break;
                    case 'general assembly':
                        $event['description'] = 'General Assembly';
                        $event['color'] = 'pink';
                        break;
                    case 'morning reading':
                        $event['description'] = 'Morning Reading';
                        $event['color'] = 'orange';
                        break;
                    default:
                        $event['description'] = "Teacher: {$schedule->teacher_name}<br>Grade: {$schedule->grade_name} - {$schedule->grade_class}";
                }

                echo json_encode($event) . ',';
            @endphp
            @endforeach
        ],
    });
    calendar.render();
});

</script>

@endsection