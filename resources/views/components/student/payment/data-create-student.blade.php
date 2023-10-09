@extends('layouts.admin.master')
@section('content')

      <!-- Content Header (Page header) -->
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
     <div class="container-fluid">
       <!-- Small boxes (Stat box) -->
       <div class="row">
         <div class="col-lg-6 col-6">
           <!-- small box -->
           <div class="small-box bg-dark">
             <div class="inner">
               <h3>SPP</h3>

               <p>Costs every month</p>
             </div>
             <div class="icon">
               <i class="fa-regular fa-calendar-plus"></i>
             </div>
             <a href="/admin/payment-students/create/{{$data->unique_id}}/SPP" class="small-box-footer">Create spp <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
         <div class="col-lg-6 col-6">
           <!-- small box -->
           <div class="small-box bg-secondary">
             <div class="inner">
               <h3>Building<sup style="font-size: 20px"></sup></h3>

               <p>Building costs</p>
             </div>
             <div class="icon">
               <i class="fa-solid fa-school-flag"></i>
             </div>
             <a href="#" class="small-box-footer">Create building cost <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
         <div class="col-lg-6 col-6">
           <!-- small box -->
           <div class="small-box bg-info">
             <div class="inner">
               <h3>Book</h3>

               <p>Book costs</p>
             </div>
             <div class="icon">
               <i class="fa-solid fa-book"></i>
             </div>
             <a href="#" class="small-box-footer">Create book cost <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
         <div class="col-lg-6 col-6">
           <!-- small box -->
           <div class="small-box bg-light">
             <div class="inner">
               <h3>Uniform</h3>

               <p>Uniform costs</p>
             </div>
             <div class="icon">
               <i class="fa-solid fa-child-dress"></i>
             </div>
             <a href="#" class="small-box-footer">Create uniform cost <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->
       </div>
       <!-- /.row -->
     </div><!-- /.container-fluid -->
   </section>
 <!-- /.content-wrapper -->

@endsection