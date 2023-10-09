<!-- Sidebar Menu -->
<nav class="mt-2">
   <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
     <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->
     <li class="nav-item menu-open">
       <a href="#" class="nav-link active">
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
           <a href="/admin/dashboard" class="nav-link {{session('page') == 'dashboard' ? 'active' : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Dashboard</p>
           </a>
         </li>
         <li class="nav-item">
           <a href="/admin/register" class="nav-link {{session('page') == 'register' ? 'active' : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Register</p>
           </a>
         </li>
         <li class="nav-item">
           <a href="/admin/list" class="nav-link {{session('page') == 'database student' ? 'active' : ''}}">
             <i class="far fa-circle nav-icon"></i>  
             {{-- <i class="fa-regular fa-database"></i> --}}
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-item menu-open">
       <a href="#" class="nav-link">
         <i class="nav-icon fa-solid fa-person-chalkboard" style="color: #f2f2f2;"></i>
         {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
         <p>
           Teachers
           <i class="fas fa-angle-left right" {{session('page') == 'database teacher' ? 'active' : ''}}></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/teachers" class="nav-link">
             <i class="far fa-circle nav-icon"></i>
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-item">
       <a href="#" class="nav-link">
         <i class="nav-icon fa-solid fa-house-flag"></i>
         <p>
           Grades
           <i class="right fas fa-angle-left"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/grades" class="nav-link">
             <i class="far fa-circle nav-icon"></i>
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-item">
       <a href="#" class="nav-link">
         <i class="nav-icon fa-regular fa-credit-card"></i>
         <p>
           Payment
           <i class="right fas fa-angle-left"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/payment-students" class="nav-link">
             <i class="far fa-circle nav-icon"></i>
             <p>Students</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-header">SUPER ADMIN ACCESS</li>
     <li class="nav-item">
       <a href="{{url('/admin/user')}}" class="nav-link">
         <i class="fa-solid fa-user-secret nav-icon"></i>
         <p>User</p>
       </a>
     </li>
     <li class="nav-item">
       <a href="{{url('/admin/user/change-password')}}" class="nav-link">
         <i class="nav-icon fas fa-solid fa-lock"></i>
         <p>Change my password</p>
       </a>
     </li>
     
     
     <li class="nav-header">EXIT</li>
     <li class="nav-item">
         <a href="/" class="nav-link">
            {{-- <i class="nav-icon far fa-circle text-warning"></i> --}}
            <i class="nav-icon fa-solid fa-right-from-bracket text-danger"></i>
            <p>Logout</p>
         </a>
     </li>
   </ul>
 </nav>
 <!-- /.sidebar-menu -->