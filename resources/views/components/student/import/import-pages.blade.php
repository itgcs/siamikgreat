@extends('layouts.admin.master')
@section('content')

<section class="content row">
    <div class="container-fluid my-5 flex justify-content-center">
        <div class="row flex justify-content-center">
            <!-- left column -->
            <div class="col-md-9">
                        <div class="card card-secondary">
                              <div class="card-header">
                                  <h3 class="card-title my-auto">Import student</h3>
                              </div>
                              <!-- /.card-header -->
                              <!-- form start -->
                              <form action="{{route('import.register')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                    <div class="file-upload">
                                      <button class="download-template-btn" type="button" onclick="downloadTemplate()">
                                        Download Template
                                      </button>

                                      <div class="image-upload-wrap">
                                        <input type="file" name="import_student" class="file-upload-input" onchange="readURL(this);" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        <div class="drag-text">
                                          <h3>Drag and drop a file or select add Excel</h3>
                                        </div>
                                      </div>

                                      <div class="file-upload-content">
                                        <h4 class="file-upload-image"></h4>
                                        <div class="image-title-wrap">
                                          <button type="button" onclick="removeUpload()" class="remove-image"><i class="fa-solid fa-trash fa-2xl" style="margin-bottom: 1em;"></i> <br> Remove <span class="image-title">Excel</span></button>
                                          <button type="submit" role="button" onclick="upload()" class="upload-image"><i class="fa-solid fa-cloud-arrow-up fa-2xl fa-bounce" style="margin-bottom: 1em;"></i> <br> Post <span class="image-title">Excel</span></button>
                                        </div>
                                      </div>

                                    </div>
                                    {{-- <input type="file" name="import_student">
                                    <input type="button" type="submit" value="submit" class="btn btn-success"> --}}
                                </div>
                              </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script
			  {{-- src="https://code.jquery.com/jquery-3.7.1.min.js" --}}
              src="{{asset('js/jquery-3.7.1.min.js')}}"
			  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
			  crossorigin="anonymous">
    </script>


    <script>

      function downloadTemplate() {
        
        Swal.fire({
                    title: "Downloading Template",
                    text: "This will close when the download is complete.",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        window.location = `/admin/register/templates/students`;
                        $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                        "content"
                                    ),
                                },
                                accepts: {
                                    mycustomtype: "application/x-some-custom-type",
                                },
                                url: `/admin/register/templates/students`,
                                type: "GET",
                                cache: false,
                            })
                            .then((res) => {

                              Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Your file has been saved",
                                showConfirmButton: false,
                                timer: 1500
                              });


                              console.log(res);

                               

                            })
                            .catch((err) => {
                                console.log(err);
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: err.msg,
                                    text: 'Make sure your internet remains stable!',
                                    // footer: '<a href="">Why do I have this issue?</a>',
                                });

                                $('#re-send-mail').show();

                            });
                    },
                })
        

      };


      function upload() {

      }

      function readURL(input) {
        
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $(".image-upload-wrap").hide();
                  // $(".file-upload-image").attr("src", e.target.result);
                  $(".file-upload-image").html(input.files[0].name);
                  $(".file-upload-content").show();
                  // $(".image-title").html(input.files[0].name);
              };
            
              reader.readAsDataURL(input.files[0]);
          } else {
              removeUpload();
          }
      }

      function removeUpload() {
          $(".file-upload-input").replaceWith($(".file-upload-input").clone());
          $(".file-upload-content").hide();
          $(".image-upload-wrap").show();
      
          $(".file-upload-wrap").bind("dragover", function () {
              $(".image-upload-wrap").addClass("image-dropping");
          });
        
          $("image-upload-wrap").bind("dragleave", function () {
              $(".image-upload-wrap").removeClass("image-dropping");
          });
      }


    </script>
    <script src="{{asset("/js/file-upload.js")}}"></script>

    @if (session('import_status') && session('import_status')['code'] == 200)
        <script>
          function succcessImport () {
            console.log('success');
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Your data has been saved",
              showConfirmButton: false,
              timer: 1500
            });
            
            succcessImport();
          }
        </script>
    @elseif (session('import_status'))
        <script>

          const msg = echo"session('import_status')['msg'];";

          function failedImport () {
            console.log('failed');
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: msg,
              footer: '<a href="#">Why do I have this issue?</a>'
            });
          }
          failedImport();
        </script>
    @endif
</section>



@endsection