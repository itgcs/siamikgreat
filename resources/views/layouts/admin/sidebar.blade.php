<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/admin/dashboard" class="brand-link">
    <img src="{{asset('template')}}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Admin</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('template')}}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Super Admin</a>
      </div>
    </div>

<!-- Sidebar Menu -->
<nav class="mt-2">
   <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
     <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->
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
           <a href="/admin/dashboard" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'dashboard' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Dashboard</p>
           </a>
         </li>
         <li class="nav-item">
           <a href="/admin/register" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'register students' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Register</p>
           </a>
         </li>
         <li class="nav-item">
           <a href="/admin/list" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database students' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>  
             {{-- <i class="fa-regular fa-database"></i> --}}
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'teachers' ? 'menu-open' : '') : ''}}">
       <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'teachers' ? 'active' : '') : ''}}">
         <i class="nav-icon fa-solid fa-person-chalkboard" style="color: #f2f2f2;"></i>
         {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
         <p>
           Teachers
           <i class="fas fa-angle-left right"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/teachers" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database teachers' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'grades' ? 'menu-open' : '') : ''}}">
       <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'grades' ? 'active' : '') : ''}}">
         <i class="nav-icon fa-solid fa-house-flag"></i>
         <p>
           Grades
           <i class="right fas fa-angle-left"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/grades" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database grades' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'books' ? 'menu-open' : '') : ''}}">
       <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'books' ? 'active' : '') : ''}}">
         <i class="nav-icon fa-solid fa-book"></i>
         <p>
           Book
           <i class="right fas fa-angle-left"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/books" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database book' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Data</p>
           </a>
         </li>
       </ul>
     </li>
     
     <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'payments' ? 'menu-open' : '') : ''}}">
       <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'payments' ? 'active' : '') : ''}}">
         <i class="nav-icon fa-regular fa-credit-card"></i>
         <p>
           Payment
           <i class="right fas fa-angle-left"></i>
         </p>
       </a>
       <ul class="nav nav-treeview">
         <li class="nav-item">
           <a href="/admin/spp-students" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'spp-students' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Students</p>
           </a>
         </li>
         <li class="nav-item">
           <a href="/admin/payment-grades" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'payment-grades' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Grades</p>
           </a>
         </li>
         <li class="nav-item">
           <a href="/admin/payment-books" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'payment-books' ? 'active' : '') : ''}}">
             <i class="far fa-circle nav-icon"></i>
             <p>Book</p>
           </a>
         </li>
       </ul>
     </li>

     <li class="nav-item {{session('page') && session('page')->page? (session('page')->page == 'Bills' ? 'menu-open' : '') : ''}}">
      <a href="#" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'Bills' ? 'active' : '') : ''}}">
        <i class="nav-icon fa-solid fa-file-invoice"></i>
        <p>
          Bill
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/admin/bills/create" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'create bills' ? 'active' : '') : ''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Create</p>
          </a>
        </li>
      </ul>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/admin/bills" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'database bills' ? 'active' : '') : ''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Data</p>
          </a>
        </li>
      </ul>
    </li>

     <li class="nav-header">SUPER ADMIN ACCESS</li>
     <li class="nav-item">
       <a href="{{url('/admin/user')}}" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'user' ? 'active' : '') : ''}}">
         <i class="fa-solid fa-user-secret nav-icon"></i>
         <p>User</p>
       </a>
     </li>
     <li class="nav-item">
       <a href="{{url('/admin/user/change-password')}}" class="nav-link {{session('page') && session('page')->page? (session('page')->page == 'admin' ? 'active' : '') : ''}}">
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