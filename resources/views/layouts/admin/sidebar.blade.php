<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-orange elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link text-center">
      <img src="{{asset('/images')}}/logo-school.png"
      class="img-fluid img-thumbnail" alt="Sample image">
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-5 pb-3 mb-3 d-flex justify-content-start">
      <div class="image">
        <img src="{{asset('images/admin.png')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block brand-text" style="font-size: 1.2em;">
          {{ session('name_user') }}
        </a>
      </div>
    </div>

    <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
     <!-- Add icons to the links using the .nav-icon class
      with font-awesome or any other icon font library -->

      <!-- DASHBOARD -->
      @if (session('role') == 'superadmin')
          <li class="ml-1 nav-item">
              <a href="{{ url('/superadmin/dashboard') }}" class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                  <i class="mr-2 fa-regular fa-folder-open"></i>
                  <p>Dashboard</p>
              </a>
          </li>
      @elseif (session('role') == 'admin')
          <li class="ml-1 nav-item">
              <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                  <i class="mr-2 fa-regular fa-folder-open"></i>
                  <p>Dashboard</p>
              </a>
          </li>
      @elseif (session('role') == 'teacher')
          <li class="ml-1 nav-item">
              <a href="{{ url('/teacher/dashboard') }}" class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                  <i class="mr-2 fa-regular fa-folder-open"></i>
                  <p>Dashboard</p>
              </a>
          </li>
      @elseif (session('role') == 'student' || session('role') == 'parent')
        <li class="ml-1 nav-item">
          <a href="{{ url('/' . session('role') . '/dashboard') }}" class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
            <i class="mr-2 fa-regular fa-folder-open"></i>
            <p>Dashboard</p>
          </a>
        </li>
      @endif
      <!-- END DASHBOARD -->

      <!-- MASTER ACADEMICS -->
      @if (session('role') == 'superadmin')
          <li class="ml-1 nav-item">
              <a href="{{ url('/superadmin/masterAcademics') }}" class="nav-link {{ session('page') && session('page')->page == 'master academic' ? 'active' : '' }}">
                  <i class="mr-2 fa-solid fa-user-graduate"></i>
                  <p>Master Academic</p>
              </a>
          </li>
      @elseif (session('role') == 'admin')
          <li class="ml-1 nav-item">
              <a href="{{ url('/admin/masterAcademics') }}" class="nav-link {{ session('page') && session('page')->page == 'master academic' ? 'active' : '' }}">
                  <i class="mr-2  fa-solid fa-user-graduate"></i>
                  <p>Master Academic</p>
              </a>
          </li>
      @endif
      <!-- END MASTER ACADEMIC -->

      <!-- SCHEDULE -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
      <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'schedules' ? 'menu-open' : '') : ''}}">
        <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'schedules' ? 'active' : '') : ''}}">
        <i class="nav-icon fa-solid fa-calendar-day"></i>
          <p>
            Schedules
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
           <a href="/{{session('role')}}/schedules/all" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'all schedules' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>All Schedules</p>
           </a>
          </li>
          <li class="nav-item">
           <a href="/{{session('role')}}/schedules/schools" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules academic' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>School</p>
           </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/schedules/grades" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules grade' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Grade</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/schedules/midexams" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules mid exam' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Mid Exams</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/schedules/finalexams" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules final exam' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Final Exams</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/typeSchedules" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database type schedules' ? 'active' : '') : ''}}">
            <i class="far fa-circle nav-icon"></i>  
              <p>
                Type Schedules
              </p>
            </a>
          </li>
        </ul>
      </li>
      @elseif (session('role') == 'teacher')
      <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'schedules' ? 'menu-open' : '') : ''}}">
        <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'schedules' ? 'active' : '') : ''}}">
        <i class="nav-icon fa-solid fa-calendar-day"></i>
          <p>
            Schedules
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/schedules/all" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'all schedules' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>All Schedules</p>
            </a>
          </li>
          <li class="nav-item">
           <a href="/{{session('role')}}/dashboard/schools" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules academic' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>School</p>
           </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/schedules/grade" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules grade' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Class Teacher</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/schedules/subject" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules subject' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Subject</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/schedules/invigilater" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules invigilater' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Invigilater</p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/schedules/companion/{{ session('id_user') }}" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules assisstant' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Assisstant</p>
            </a>
          </li> -->
        </ul>
      </li>
      @elseif (session('role') == 'student' || session('role') == 'parent')
      <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'schedules' ? 'menu-open' : '') : ''}}">
        <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'schedules' ? 'active' : '') : ''}}">
        <i class="nav-icon fa-solid fa-calendar-day"></i>
          <p>
            Schedules
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
           <a href="/{{session('role')}}/dashboard/schools" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules academic' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>School</p>
           </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/schedules/grade" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules grade' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Grade</p>
            </a>
          </li>
        </ul>
      </li>
      @endif
      <!-- END SCHEDULE -->


      <!-- REPORT SCORE -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/reports/" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database reports' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-file"></i>
            <p>Reports</p>
          </a>
        </li>
      @elseif (session('role') == 'teacher')
      <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'reports' ? 'menu-open' : '') : ''}}">
        <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'reports' ? 'active' : '') : ''}}">
        <i class="nav-icon fa-solid fa-file"></i>
          <p>
            Reports
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/report/class/teacher" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'report class teacher' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Class Teacher</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/{{session('role')}}/dashboard/report/subject/teacher" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'report subject teacher' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Subject Teacher</p>
            </a>
          </li>
        </ul>
      </li>
      @endif
      <!-- END SUBJECT -->

      <!-- ATTENDENCE STUDENT -->
      @if (session('role') == 'superadmin' || session('role') == 'admin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/attendances/" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database attendance' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-paper-plane"></i>
            <p>Attendances</p>
          </a>
        </li>
      @elseif (session('role') == 'teacher')
        <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'attendance' ? 'menu-open' : '') : ''}}">
          <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'attendance' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-paper-plane"></i>
            <p>
              Attendances
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/{{ session('role') }}/dashboard/attendance/class/teacher" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'attendance class teacher' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Class Teacher</p>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a href="/{{session('role')}}/dashboard/attendance/subjectTeacher/{{ session('id_user') }}" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'attendance subject teacher' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Subject Teacher</p>
              </a>
            </li> -->
          </ul>
        </li>
      @endif
      <!-- END ATTENDENCE -->

      <!-- STUDENTS -->
      @if (session('role') == 'admin')
      <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'students' ? 'menu-open' : '') : ''}}">
        <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'students' ? 'active' : '') : ''}}">
          {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
          {{-- <i class="nav-icon fa-solid fa-house"></i>
           --}}
           <i class=" nav-icon fas fa-solid fa-users"></i>
          <p>
            Students
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          {{-- <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'dashboard' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Dashboard</p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="/admin/list" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database students' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>  
              {{-- <i class="fa-regular fa-database"></i> --}}
              <p>Data</p>
            </a>
          </li>
        </ul>
      </li>
      @elseif (session('role') == 'superadmin')
      <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'students' ? 'menu-open' : '') : ''}}">
        <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'students' ? 'active' : '') : ''}}">
          {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
          {{-- <i class="nav-icon fa-solid fa-house"></i>
           --}}
           <i class=" nav-icon fas fa-solid fa-users"></i>
          <p>
            Students
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
        <li class="nav-item">
           <a href="/superadmin/register" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'register students' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Register</p>
           </a>
         </li>
          <li class="nav-item">
            <a href="/superadmin/register/imports" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'imports' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>
              <p>Import</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/superadmin/list" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database students' ? 'active' : '') : ''}}">
              <i class="far fa-circle nav-icon"></i>  
              {{-- <i class="fa-regular fa-database"></i> --}}
              <p>Data</p>
            </a>
          </li>
        </ul>
      </li>
      @elseif (session('role') == 'parent')
      <!-- <li class="nav-item {{ session('page') && session('page')->page && session('page')->page == 'students' ? 'menu-open' : '' }}">
          <a href="/parent/dashboard/student/{{ session('id_user') }}" class="nav-link {{ session('page') && session('page')->child && session('page')->child == 'database students' ? 'active' : '' }}">
              <i class="nav-icon fas fa-solid fa-person"></i>
              <p>
                  Relationships
              </p>
          </a>
      </li> -->
      @endif
      <!-- END STUDENTS -->

      <!-- TEACHERS -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/teachers" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database teachers' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-person-chalkboard"></i>
            <p>
              Teachers
            </p>
          </a>
        </li>
      @elseif (session('role') == 'teacher')
        <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'teachers' ? 'menu-open' : '') : ''}}">
          <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'teachers' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-person-chalkboard"></i>
            {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
            <p>
              Teachers
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/teacher/dashboard/edit/teacher" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'spesifik teachers' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Edit</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/teacher/dashboard/detail/teacher" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'detail teachers' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Detail</p>
              </a>
            </li>
          </ul>
        </li>
      @endif
      <!-- END TEACHERS -->
      


      <!-- RELATIONS -->
      <!-- @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/relations" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database relations' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-person"></i>
            {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
            <p>
              Relations
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
        </li>
      @elseif (session('role') == 'student')
        <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'relations' ? 'menu-open' : '') : ''}}">
          <a href="/student/dashboard/relation/{{ session('id_user') }}" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database relations' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-person"></i>
            {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
            <p>
              Relations
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
        </li>
      @endif -->
      <!-- END RELATIONS -->
      


      <!-- GRADE -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/grades" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database grades' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-house-flag"></i>
            <p>
              Grades
            </p>
          </a>
        </li>
      @elseif (session('role') == 'teacher')
        <li class="nav-item">
          <a href="/teacher/dashboard/grade" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database teacher grades' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-house-flag"></i>
            <p>
              Grades
            </p>
          </a>
        </li>
      @endif
      <!-- END GRADE -->


      <!-- SUBJECT -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <!-- <li class="nav-item">
          <a href="/{{ session('role') }}/subjects" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database subjects' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-book"></i>
            <p>Subject</p>
          </a>
        </li> -->

        <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'subjects' ? 'menu-open' : '') : ''}}">
          <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'subjects' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-book"></i>
            <p>
              Subjects
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/{{ session('role') }}/subjects" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database subjects' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Data</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/{{ session('role') }}/majorSubjects" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database major subjects' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Major Subject</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/{{ session('role') }}/minorSubjects" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database minor subjects' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Minor Subject</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/{{ session('role') }}/supplementarySubjects" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database supplementary subjects' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Supplementary Subject</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/{{ session('role') }}/chineseHigher" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database chinese higher' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Chinese Higher</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/{{ session('role') }}/chineseLower" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database chinese lower' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Chinese Lower</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/monthlyActivities" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database monthly activities' ? 'active' : '') : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Monthly Activities</p>
              </a>
            </li>
          </ul>
        </li>
      @endif
      <!-- END SUBJECT -->

      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/eca" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database eca' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-people-group"></i>
            <p>
              Ekstra Culicular Academy
            </p>
          </a>
        </li>
      @endif


      <!-- EXAM -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/exams" class="nav-link {{ session('page') && session('page')->page == 'database exam' ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-pencil"></i>
            <p>
              Scorings
            </p>
          </a>
        </li>
      @elseif (session('role') == 'teacher') 
        <li class="nav-item">
          <a href="/{{ session('role') }}/dashboard/exam/teacher" class="nav-link {{ session('page') && session('page')->page == 'database teacher exams' ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-pencil"></i>
            <p>
              Scorings
            </p>
          </a>
        </li>
      @elseif (session('role') == 'student' || session('role') == 'parent')  
        <li class="nav-item">
          <a href="/{{ session('role') }}/dashboard/exam" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'exams' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-pencil"></i>
            <p>
              Scorings
            </p>
          </a>
        </li>
      @endif
      <!-- END EXAM -->

      <!-- SCORE IN PARENT -->
      @if (session('role') == 'parent')  
      <!-- <li class="nav-item">
          <a href="/{{ session('role') }}/dashboard/score" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'scores' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-book"></i>
            <p>
              Report Score
            </p>
          </a>
        </li> -->
      @endif

      
      <!-- TYPE EXAM -->
      @if (session('role') == 'admin' || session('role') == 'superadmin')
        <li class="nav-item">
          <a href="/{{ session('role') }}/typeExams" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database type exams' ? 'active' : '') : ''}}">
            <i class="nav-icon fa-solid fa-clipboard"></i>
            <p>
              Type Exams
            </p>
          </a>
        </li>
      @endif
      <!-- END TYPE EXAM -->

    @if (session('role') == 'superadmin')
    <li class="nav-header">AUTHENTICATION</li>
      <li class="nav-item">
        <a href="/superadmin/users" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'user' ? 'active' : '') : ''}}">
          <i class="fa-solid fa-user-secret nav-icon"></i>
          <p>User</p>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a href="{{url('/superadmin/users/change-password')}}" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'admin' ? 'active' : '') : ''}}">
          <i class="nav-icon fas fa-solid fa-lock"></i>
          <p>Change my password</p>
        </a>
      </li> -->
    </li>
    @endif
   </ul>
 </nav>

</div>
<!-- /.sidebar -->
</aside>
{{-- <script>
  $(".nav-item").click(function () {

    console.log('masuk')
        if($(".nav-item").hasClass("menu-open")){
          $(".nav-item").removeClass('menu-open');
        }
     }) 
</script> --}}