@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid" >
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Schedules</li>
                    <li class="breadcrumb-item active" aria-current="page">All Schedules</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="contaiuner-fluid" style="overflow-x: auto;"> 
        <div class="card card-dark" style="width: 3900px;">
            <div class="card-header">
                <h3 class="card-title">All Schedule</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="max-height: 600px;overflow-y: auto;">
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label>Teacher: <span style="color: red"></span></label>
                            <select id="teacher-select-all" name="teacher_select_all" class="form-control">
                                <option value="" selected>-- Select Teacher --</option>
                                @foreach ($data['teacher'] as $tc)
                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Grade: <span style="color: red"></span></label>
                            <select id="grade-select-all" name="grade_select_all" class="form-control">
                                <option value="" selected>-- Select Grade --</option>
                                @foreach ($data['grades'] as $gr)
                                    <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Day: <span style="color: red"></span></label>
                            <select id="day-select-all" name="day_select_all" class="form-control">
                                <option value="">-- Select Day --</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="scheduleAll"></div>
            </div>
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

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>


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
                                        <td>${schedule.subject_name ? schedule.subject_name : schedule.note}</td>
                                        <td>${schedule.teacher_name ? schedule.teacher_name : ""}</td>
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
                                        <td>${schedule.subject_name ? schedule.subject_name : schedule.note}</td>
                                        <td>${schedule.teacher_name ? schedule.teacher_name : ""}</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherSelectAll = document.getElementById('teacher-select-all');
        const gradeSelectAll = document.getElementById('grade-select-all'); 
        const daySelectAll = document.getElementById('day-select-all');
        const scheduleTeacherAllDiv = document.getElementById('scheduleAll');

        fetchTeacherAllSchedule('null', 'null', 'null');
        
        teacherSelectAll.addEventListener('change', validateAndFetchScheduleAll);
        gradeSelectAll.addEventListener('change', validateAndFetchScheduleAll);
        daySelectAll.addEventListener('change', validateAndFetchScheduleAll);

        function validateAndFetchScheduleAll() {
            const teacher = teacherSelectAll.value || 'null';
            const grade = gradeSelectAll.value || 'null';
            const day = daySelectAll.value || 'null';

            fetchTeacherAllSchedule(teacher, grade, day);
        }

        function fetchTeacherAllSchedule(teacher, grade, day) {
            fetch(`/get-all-schedule-filter/${teacher}/${grade}/${day}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data fetched:', data); // Log data yang diambil
                    renderScheduleTableAll(data, scheduleTeacherAllDiv);
                })
                .catch(error => console.error('Error fetching schedule:', error));
        }

        function renderScheduleTableAll(data, container) {
            const dayColors = {
                "Monday": "#FFDDC1",
                "Tuesday": "#D4F1F4",
                "Wednesday": "#F7D6E0",
                "Thursday": "#D1D4E0",
                "Friday": "#FFF5E1"
            };

            const classColors = {
                "Toddler": "#FFCCCC",      // Light Pink
                "Nursery": "#CCFFCC",      // Light Green
                "Kindergarten - A": "#CCCCFF",           // Light Blue
                "Kindergarten - B": "#FFCC99",           // Light Orange
                "Primary - 1": "#FF99CC",  // Pink
                "Primary - 2": "#99CCFF",  // Sky Blue
                "Primary - 3": "#FFCC99",  // Peach
                "Primary - 4": "#99FF99",  // Light Lime
                "Primary - 5": "#FFCCFF",  // Light Magenta
                "Primary - 6": "#CCCC99",   // Light Olive
                "Secondary - 1": "#FF99FF", // Violet
                "Secondary - 2": "#FFFF99", // Light Yellow
                "Secondary - 3": "#99CCFF"  // Light Cyan
            };

            let table = '<table class="table table-bordered">';
            table += `
                <thead>
                    <tr>
                        <th style="font-size:12px;">Waktu</th>
                        <th style="font-size:12px;">Toddler</th>
                        <th style="font-size:12px;">Nursery</th>
                        <th style="font-size:12px;">KA</th>
                        <th style="font-size:12px;">KB</th>
                        <th style="font-size:12px;">Primary-1</th>
                        <th style="font-size:12px;">Primary-2</th>
                        <th style="font-size:12px;">Primary-3</th>
                        <th style="font-size:12px;">Primary-4</th>
                        <th style="font-size:12px;">Primary-5</th>
                        <th style="font-size:12px;">Primary-6</th>
                        <th style="font-size:12px;">Secondary-1</th>
                        <th style="font-size:12px;">Secondary-2</th>
                        <th style="font-size:12px;">Secondary-3</th>
                    </tr>
                </thead>
                <tbody>
            `;

            Object.keys(data).forEach(day => {
                table += `<tr style="background-color: ${dayColors[day]}"><td class="bg-yellow text-center text-bold" style="font-size:12px;" colspan="14">${day.toUpperCase()}</td></tr>`;
                const gradeSchedules = data[day];

                // Kumpulkan semua slot waktu untuk hari ini
                const timeSlots = [];
                Object.keys(gradeSchedules).forEach(grade => {
                    gradeSchedules[grade].forEach(schedule => {
                        const timeSlot = `${schedule.start_time}-${schedule.end_time}`;
                        if (!timeSlots.includes(timeSlot)) {
                            timeSlots.push(timeSlot);
                        }
                    });
                });

                timeSlots.forEach(timeSlot => {
                    table += `<tr><td class="font-bold text-xs">${timeSlot}</td>`;
                    const timeSlotSchedules = {};

                    Object.keys(gradeSchedules).forEach(grade => {
                        gradeSchedules[grade].forEach(schedule => {
                            if (`${schedule.start_time}-${schedule.end_time}` === timeSlot) {
                                const gradeName = schedule.grade_name || '';
                                const classColor = classColors[gradeName.split(' ')[0]] || '#FFFFFF';
                                timeSlotSchedules[grade] = `
                                    <div class="col p-0" style="background-color: ${classColor[grade]};">
                                        <p class="text-bold" style="font-size:12px;">${schedule.subject_name ? schedule.subject_name.toUpperCase() : ""}
                                        <br>${schedule.teacher_name}
                                        <br>${schedule.assisstant ? `${schedule.assisstant} (assisstant)` : ""}</p>
                                    </div>
                                `;

                                if (!schedule.subject_name) {
                                    timeSlotSchedules[grade] = `<p>${schedule.notes}</p>`;
                                }
                            }
                        });
                    });

                    const grades = ["Toddler", "Nursery", "Kindergarten - A", "Kindergarten - B", "Primary - 1", "Primary - 2", "Primary - 3", "Primary - 4", "Primary - 5", "Primary - 6", "Secondary - 1", "Secondary - 2", "Secondary - 3"];

                    grades.forEach(grade => {
                        table += `<td style="font-size:12px;background-color: ${classColors[grade]};">${timeSlotSchedules[grade] || ''}</td>`;
                    });

                    table += `</tr>`;
                });
            });

            table += '</tbody></table>';
            container.innerHTML = table;
        }
    });
</script>

@if(session('after_create_otherSchedule')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully'
            text: 'Succesfully created new schedule academic in the database'
        });
    </script>
@endif

@endsection
