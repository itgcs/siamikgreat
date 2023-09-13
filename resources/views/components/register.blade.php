@extends('layouts.admin.master')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{ route('actionRegister') }}>
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Student</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="studentName">Name</label>
                                    <input name="studentName" type="text" class="form-control" id="studentName"
                                        placeholder="Enter name" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="studentGender" class="form-control" required>
                                                <option selected disabled value="">--- Please Select One ---</option>
                                                <option>Female</option>
                                                <option>Male</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <label>Date of Birth</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input name="studentDate_birth" type="text"
                                                class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                                data-target="#reservationdate" 
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                required
                                                />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="studentPlace_birth">Place of Birth</label>
                                        <input name="studentPlace_birth" type="text" class="form-control"
                                            id="studentPlace_birth" placeholder="Enter city" required>
                                    </div>
                                    <div class="col-md-6">

                                        <label for="studentNationality">Nationality</label>
                                        <input name="studentNationality" type="text" class="form-control"
                                            id="studentNationality" placeholder="Enter nationality" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="studentId_or_passport">ID/Passport Number</label>
                                        <input name="studentId_or_passport" type="text" class="form-control"
                                            id="studentId_or_passport" placeholder="Enter ID/Passport" required>
                                    </div>
                                    <div class="col-md-6">

                                        <label for="studentPlace_of_issue">Place of issue</label>
                                        <input name="studentPlace_of_issue" type="text" class="form-control"
                                            id="studentPlace_of_issue" placeholder="Enter place">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Religion</label>
                                            <select name="studentReligion" class="form-control" required>
                                                <option selected disabled value="">--- Please Select One ---</option>
                                                <option>Islam</option>
                                                <option>Protestant Christianity</option>
                                                <option>Catholic Christianity</option>
                                                <option>Hinduism</option>
                                                <option>Buddhism</option>
                                                <option>Confucianism</option>
                                                <option>Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <label>Date of Expiry</label>
                                        <div class="input-group date" id="reservationdateStudentDateExp"
                                            data-target-input="nearest">
                                            <input name="studentDate_exp" type="text"
                                                class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                                data-target="#reservationdateStudentDateExp" 
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                />
                                            <div class="input-group-append" data-target="#reservationdateStudentDateExp"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body students -->

                        <div class="card card-warning">
                           <div class="card-header">
                               <h3 class="card-title">Father's</h3>
                           </div>
                           <!-- /.card-header -->
                           <!-- form start -->
                           <div class="card-body">
                               <div class="form-group row">
                                 <div class="col-md-6">
                                   <label for="fatherName">Name</label>
                                   <input name="fatherName" type="text" class="form-control" id="fatherName"
                                       placeholder="Enter father's name" required>
                                 </div>

                                 <div class="col-md-6">

                                    <label for="fatherId_or_passport">ID / Passport Number</label>
                                    <input name="fatherId_or_passport" type="text" class="form-control"
                                        id="fatherId_or_passport" placeholder="Enter father's ID/Passport" required>
                                 </div>
                               </div>
                               <div class="form-group row">
                                   <div class="col-md-6">
                                       <label for="fatherOccupation">Occupation</label>
                                    <input name="fatherOccupation" type="text" class="form-control"
                                        id="fatherOccupation" placeholder="Enter father's occupation">
                                   </div>
                                   <div class="col-md-6">


                                        <label>Religion</label>
                                           <select name="fatherReligion" class="form-control" required>
                                               <option selected disabled value="">--- Please Select One ---</option>
                                               <option>Islam</option>
                                               <option>Protestant Christianity</option>
                                               <option>Catholic Christianity</option>
                                               <option>Hinduism</option>
                                               <option>Buddhism</option>
                                               <option>Confucianism</option>
                                               <option>Others</option>
                                           </select>
                                 </div>
                               </div>

                               <div class="form-group row">


                                 <div class="col-md-4">

                                       
                                    <label for="fatherPlace_birth">Place of Birth</label>
                                    <input name="fatherPlace_birth" type="text" class="form-control"
                                        id="fatherPlace_birth" placeholder="Enter city" required>

                                 </div>

                                 <div class="col-md-4">
                                    <label>Date of Birth</label>
                                    <div class="input-group date" id="reservationFatherBirthDate"
                                        data-target-input="nearest">
                                        <input name="fatherBirth_date" type="text"
                                            class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                            data-target="#reservationFatherBirthDate" 
                                            data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                            required/>
                                        <div class="input-group-append" data-target="#reservationFatherBirthDate"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="col-md-4">

                                       
                                    <label for="fatherNationality">Nationality</label>
                                    <input name="fatherNationality" type="text" class="form-control"
                                        id="fatherNationality" placeholder="Enter nationality" required>

                                    </div>
                                 </div>
                               <br>
                               <br>

                               <div class="form-group">
                                    <label for="fatherCompany_name">Company Name</label>
                                    <input name="fatherCompany_name" type="text" class="form-control" id="fatherCompany_name"
                                        placeholder="Enter company name">
                                </div>

                               <div class="form-group row">
                                   <div class="col-md-6">
                                       <label for="fatherCompany_address">Company Address</label>
                                       <input name="fatherCompany_address" type="text" class="form-control"
                                           id="fatherCompany_address" placeholder="Enter company address">
                                   </div>
                                   <div class="col-md-6">

                                       <label for="fatherCompany_phone">Company Phone</label>
                                       <input name="fatherCompany_phone" type="text" class="form-control"
                                           id="fatherCompany_phone" placeholder="Enter company phone">
                                   </div>
                               </div> 

                               <br>
                               <br>

                               <div class="form-group">
                                 <label for="fatherHome_address">Home Address</label>
                                 <input name="fatherHome_address" type="text" class="form-control" id="fatherHome_address"
                                     placeholder="Enter name" required>
                              </div>
                               <div class="form-group row">
                                   <div class="col-md-4">
                                       <label for="fatherTelephhone">Telephone</label>
                                       <input name="fatherTelephhone" type="text" class="form-control"
                                           id="fatherTelephhone" placeholder="Enter telephone">
                                   </div>
                                   
                                   <div class="col-md-4">
                                    <label for="fatherMobilephone">Mobilephone</label>
                                    <input name="fatherMobilephone" type="text" class="form-control"
                                    id="fatherMobilephone" placeholder="Enter mobilephone" required>
                                 </div>
                                 
                                 <div class="col-md-4">
                                      <label for="fatherMobilephone">Mobilephone</label>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                         <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                       </div>
                                       <input name="fatherEmail" type="email" class="form-control" placeholder="Enter father's email" required>
                                     </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <!-- /.card-body father -->


                       <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Mother's</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="form-group row">
                              <div class="col-md-6">
                                <label for="fatherName">Name</label>
                                <input name="fatherName" type="text" class="form-control" id="fatherName"
                                    placeholder="Enter father's name" required>
                              </div>

                              <div class="col-md-6">

                                 <label for="fatherId_or_passport">ID / Passport Number</label>
                                 <input name="fatherId_or_passport" type="text" class="form-control"
                                     id="fatherId_or_passport" placeholder="Enter father's ID/Passport" required>
                              </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="fatherOccupation">Occupation</label>
                                 <input name="fatherOccupation" type="text" class="form-control"
                                     id="fatherOccupation" placeholder="Enter father's occupation">
                                </div>
                                <div class="col-md-6">


                                     <label>Religion</label>
                                        <select name="fatherReligion" class="form-control" required>
                                            <option selected disabled value="">--- Please Select One ---</option>
                                            <option>Islam</option>
                                            <option>Protestant Christianity</option>
                                            <option>Catholic Christianity</option>
                                            <option>Hinduism</option>
                                            <option>Buddhism</option>
                                            <option>Confucianism</option>
                                            <option>Others</option>
                                        </select>
                              </div>
                            </div>

                            <div class="form-group row">


                              <div class="col-md-4">

                                    
                                 <label for="fatherPlace_birth">Place of Birth</label>
                                 <input name="fatherPlace_birth" type="text" class="form-control"
                                     id="fatherPlace_birth" placeholder="Enter city" required>

                              </div>

                              <div class="col-md-4">
                                 <label>Date of Birth</label>
                                 <div class="input-group date" id="reservationFatherBirthDate"
                                     data-target-input="nearest">
                                     <input name="fatherBirth_date" type="text"
                                         class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                         data-target="#reservationFatherBirthDate" 
                                         data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                         required/>
                                     <div class="input-group-append" data-target="#reservationFatherBirthDate"
                                         data-toggle="datetimepicker">
                                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                     </div>
                                 </div>
                                 </div>

                                 <div class="col-md-4">

                                    
                                 <label for="fatherNationality">Nationality</label>
                                 <input name="fatherNationality" type="text" class="form-control"
                                     id="fatherNationality" placeholder="Enter nationality" required>

                                 </div>
                              </div>
                            <br>
                            <br>

                            <div class="form-group">
                                 <label for="fatherCompany_name">Company Name</label>
                                 <input name="fatherCompany_name" type="text" class="form-control" id="fatherCompany_name"
                                     placeholder="Enter company name">
                             </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="fatherCompany_address">Company Address</label>
                                    <input name="fatherCompany_address" type="text" class="form-control"
                                        id="fatherCompany_address" placeholder="Enter company address">
                                </div>
                                <div class="col-md-6">

                                    <label for="fatherCompany_phone">Company Phone</label>
                                    <input name="fatherCompany_phone" type="text" class="form-control"
                                        id="fatherCompany_phone" placeholder="Enter company phone">
                                </div>
                            </div> 

                            <br>
                            <br>

                            <div class="form-group">
                              <label for="fatherHome_address">Home Address</label>
                              <input name="fatherHome_address" type="text" class="form-control" id="fatherHome_address"
                                  placeholder="Enter name" required>
                           </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="fatherTelephhone">Telephone</label>
                                    <input name="fatherTelephhone" type="text" class="form-control"
                                        id="fatherTelephhone" placeholder="Enter telephone">
                                </div>
                                
                                <div class="col-md-4">
                                 <label for="fatherMobilephone">Mobilephone</label>
                                 <input name="fatherMobilephone" type="text" class="form-control"
                                 id="fatherMobilephone" placeholder="Enter mobilephone" required>
                              </div>
                              
                              <div class="col-md-4">
                                   <label for="fatherMobilephone">Mobilephone</label>
                                 <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input name="fatherEmail" type="email" class="form-control" placeholder="Enter father's email" required>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body Mother -->
                        
                        <div class="d-flex justify-content-center my-5">
                            <button type="submit" class="col-11 btn btn-primary">Register Now</button>
                        </div>
                    </form>

                </div>
                <!-- /.card -->

                <!-- general form elements -->
            </div>
            <!--/.col (right) -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
