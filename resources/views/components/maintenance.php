@extends('layouts.admin.master')
@section('content')

<style>
  .full-height {
    height: 60vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }
  .icon-wrapper i {
    font-size: 200px;
    color: #ccc;
  }
  .icon-wrapper p {
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0%);
    margin: 0;
    font-size: 1.5rem;
    color: black;
    text-align: center;
   }
</style>

<!-- Main content -->
<div class="container-fluid full-height">
  <div class="icon-wrapper">
      <i class="fa-regular fa-face-sad-tear"></i>
      <a href="/teacher/dashboard" class="btn btn-primary mt-3"><p>Oops.. <br> Under Maintenance</p></a>
  </div>
</div>
<!-- /.content-wrapper -->
@endsection
