@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="card card-dark mt-5">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">All Schedules Schools</h5>

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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Grade Schedules</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <div id="eventDescription"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var exams = @json($exams);
    var schedules = @json($schedules);
    var gradeSchedules = @json($gradeSchedules);
    var gradeSchedulestwo = @json($gradeSchedulestwo);
    var semester1 = @json($semester1);
    var semester2 = @json($semester2);
    var endsemester1 = @json($endsemester1);
    var endsemester2 = @json($endsemester2);

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'multiMonthYear',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },

        events: [
            ...exams.map(exam => ({
                title: `${exam.type_exam} (${exam.name_exam} - ${exam.grade_name})`,
                start: exam.date_exam,
                description: `<br>Teacher : ${exam.teacher_name} <br>Grade : ${exam.grade_name} - ${exam.grade_class}`,
                color: 'red',
                type: 'exam'
            })),

            ...schedules.filter(schedule => {
                var scheduleDate = new Date(schedule.date);
                return scheduleDate.getDay() != 0; // 0: Sunday
            }).map(schedule => ({
                title: schedule.note || 'No Note',
                start: schedule.date,
                end: schedule.end_date,
                description: schedule.note || 'No Description',
                color: getColor(schedule.type_schedule || 'default'),
                type: 'schedule'
            })),

            ...Object.keys(gradeSchedules).flatMap(key => {
                var gradeSchedule = gradeSchedules[key];
                return [{
                    title: key, // Only show the grade name
                    startRecur: semester1,
                    endRecur: endsemester1,
                    daysOfWeek: [1, 2, 3, 4, 5],  // Adjust day: 1 (Mon) -> 0 (Sun), ..., 5 (Fri) -> 4 (Thu)
                    description: key, // Use the grade name for description
                    color: colorGrades(key),
                    type: 'gradeSchedule',
                    gradeKey: key // Add gradeKey to use it later in eventClick
                }];
            }),

            ...Object.keys(gradeSchedulestwo).flatMap(key => {
                var gradeSchedule2 = gradeSchedulestwo[key];
                return [{
                    title: key, // Only show the grade name
                    startRecur: semester2,
                    endRecur: endsemester2,
                    daysOfWeek: [1, 2, 3, 4, 5],  // Adjust day: 1 (Mon) -> 0 (Sun), ..., 5 (Fri) -> 4 (Thu)
                    description: key, // Use the grade name for description
                    color: colorGrades(key),
                    type: 'gradeSchedule2',
                    gradeKey: key // Add gradeKey to use it later in eventClick
                }];
            })
        ],
        eventClick: function(info) {
            var eventType = info.event.extendedProps.type;
            if (eventType === 'exam' || eventType === 'schedule') {
                document.getElementById('eventTitle').innerText = 'Event: ' + info.event.title;
                document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description;
            }
            else if (eventType === 'semester') {
                document.getElementById('eventTitle').innerText = 'Event: ' + info.event.title;
                document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description;
            }
            else if (eventType === 'gradeSchedule') {
                var gradeKey = info.event.extendedProps.gradeKey;
                var selectedDate = info.event.start;
                var selectedDay = selectedDate.getDay(); // 0: Sunday, 1: Monday, ..., 6: Saturday

                var schedulesForGrade = gradeSchedules[gradeKey].filter(schedule => schedule.day === selectedDay);

                
                var descriptionHTML = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${schedulesForGrade.map(schedule => `
                                <tr>
                                    <td>${schedule.start_time} - ${schedule.end_time}</td>
                                    <td>${schedule.subject_name}</td>
                                    <td>${schedule.teacher_name}</td>
                                    <td>${schedule.semester}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                
                document.getElementById('eventTitle').innerText = 'Grade: ' + gradeKey + ' (' + getDayName(selectedDay) + ')';
                document.getElementById('eventDescription').innerHTML = descriptionHTML;
            }
            else if (eventType === 'gradeSchedule2') {
                var gradeKey = info.event.extendedProps.gradeKey;
                var selectedDate = info.event.start;
                var selectedDay = selectedDate.getDay(); // 0: Sunday, 1: Monday, ..., 6: Saturday

                var schedulesForGrade = gradeSchedulestwo[gradeKey].filter(schedule => schedule.day === selectedDay);

                
                var descriptionHTML = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${schedulesForGrade.map(schedule => `
                                <tr>
                                    <td>${schedule.start_time} - ${schedule.end_time}</td>
                                    <td>${schedule.subject_name}</td>
                                    <td>${schedule.teacher_name}</td>
                                    <td>${schedule.semester}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                
                document.getElementById('eventTitle').innerText = 'Grade: ' + gradeKey + ' (' + getDayName(selectedDay) + ')';
                document.getElementById('eventDescription').innerHTML = descriptionHTML;
            }
            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                keyboard: false
            });
            eventModal.show();
        }
    });
    calendar.render();

    function getColor(type_schedule) {
        switch (type_schedule ? type_schedule.toLowerCase() : '') {
            case 'event':
                return 'brown';
            case 'national day':
                return 'red';
            case 'uas':
                return 'orange';
            case 'uts':
                return 'pink';
            default:
                return 'blue';
        }
    }

    function colorGrades(key) {
        switch (key ? key.toLowerCase() : '') {
            case 'primary-1':
                return 'brown';
            case 'primary-2':
                return 'red';
            case 'primary-3':
                return 'orange';
            case 'primary-4':
                return 'pink';
            case 'primary-5':
                return 'green';
            case 'primary-6':
                return 'blue';
            case 'secondary-1':
                return 'purple';
            case 'secondary-2':
                return 'teal';
            case 'secondary-3':
                return 'yellow';
            default:
                return 'gray'; // Default color if no match is found
        }
    }


    function getDayName(dayIndex) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return days[dayIndex];
    }

});
</script>

@if(session('after_create_otherSchedule')) 
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
                title: 'Successfully created new other schedule in the database.'
            });
        }, 1500);
    </script>
@endif

@endsection
