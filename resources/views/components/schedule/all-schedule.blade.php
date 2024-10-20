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
        <!-- All Schedule -->
        <div class="card card-dark" style="width: 1400px;">
            <div class="card-header">
                <h3 class="card-title">Schedule Class</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="">
                <div class="row" style="max-width:1400px;">
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

        <!-- Schedule Mid Exam -->
        <!-- <div class="card card-dark" style="width: 3900px;">
            <div class="card-header">
                <h3 class="card-title">Schedule Mid Exam</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="max-height: 600px;overflow-y: auto;">
                <div class="row" style="max-width:1200px;">
                    <div class="col-2">
                        <div class="form-group">
                            <label>Invigilater: <span style="color: red"></span></label>
                            <select id="mid-teacher-select-all" name="mid-teacher_select_all" class="form-control">
                                <option value="" selected>-- Select Invigilater --</option>
                                @foreach ($data['teacher'] as $tc)
                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Grade: <span style="color: red"></span></label>
                            <select id="mid-grade-select-all" name="mid-grade_select_all" class="form-control">
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
                            <select id="mid-day-select-all" name="mid-day_select_all" class="form-control">
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
                <div id="scheduleMidExam"></div>
            </div>
        </div> -->

        <!-- Schedule Final Exam -->
        <!-- <div class="card card-dark" style="width: 3900px;">
            <div class="card-header">
                <h3 class="card-title">Schedule Final Exam</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="max-height: 600px;overflow-y: auto;">
                <div class="row" style="max-width:1200px;">
                    <div class="col-2">
                        <div class="form-group">
                            <label>Invigilater: <span style="color: red"></span></label>
                            <select id="final-teacher-select-all" name="final-teacher_select_all" class="form-control">
                                <option value="" selected>-- Select Invigilater --</option>
                                @foreach ($data['teacher'] as $tc)
                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Grade: <span style="color: red"></span></label>
                            <select id="final-grade-select-all" name="final-grade_select_all" class="form-control">
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
                            <select id="final-day-select-all" name="final-day_select_all" class="form-control">
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
                <div id="scheduleFinalExam"></div>
            </div>
        </div> -->
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

<!-- All Schedule Class -->
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
                table += `<tr style="background-color: ${dayColors[day]}"><td class="bg-black text-white text-center text-bold" style="font-size:12px;" colspan="14">${day.toUpperCase()}</td></tr>`;
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
                        
                                if (!timeSlotSchedules[grade]) {
                                    timeSlotSchedules[grade] = '';
                                }
                                
                                timeSlotSchedules[grade] += `
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

<!-- Schedule Mid Exam -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherSelectAll = document.getElementById('mid-teacher-select-all');
        const gradeSelectAll = document.getElementById('mid-grade-select-all'); 
        const daySelectAll = document.getElementById('mid-day-select-all');
        const scheduleTeacherAllDiv = document.getElementById('scheduleMidExam');

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
            fetch(`/get-all-schedulemidexam-filter/${teacher}/${grade}/${day}`)
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
                                        <br>${schedule.notes ? `Room : ${schedule.notes}` : ""}
                                        </p>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherSelectAll = document.getElementById('final-teacher-select-all');
        const gradeSelectAll = document.getElementById('final-grade-select-all'); 
        const daySelectAll = document.getElementById('final-day-select-all');
        const scheduleTeacherAllDiv = document.getElementById('scheduleFinalExam');

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
            fetch(`/get-all-schedulefinalexam-filter/${teacher}/${grade}/${day}`)
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
                                        <br>${schedule.notes ? `Room : ${schedule.notes}` : ""}
                                        </p>
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

@endsection
