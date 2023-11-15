$(document).ready(() => {
    $("body").on("click", "#re-send-mail", function (event) {
        event.preventDefault();
        const value = $(this).attr("status-id");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Sending emails",
            text: "This will close if the email has been sent.",
            allowEscapeKey: false,
            allowOutsideClick: false,
            timerProgressBar: true,
            didOpen: () => {
                $('#re-send-mail').remove();
                Swal.showLoading();

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/admin/bills/status/${value}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);

                        
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            // text: err,
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Email has been sent "
                });
            },
        }).then((result) => {
            console.log('selesai');
        })
    });
});


// const showLoading = function() {
//     swal({
//       title: 'Now loading',
//       allowEscapeKey: false,
//       allowOutsideClick: false,
//       onOpen: () => {
//         swal.showLoading();
//       }
//     }).then(
//       () => {},
//       (dismiss) => {
//         if (dismiss === 'timer') {
//           console.log('closed by timer!!!!');
//           swal({ 
//             title: 'Finished!',
//             type: 'success',
//             timer: 2000,
//             showConfirmButton: false
//           })
//         }
//       }
//     )
//   };
//showLoading();

//   document.getElementById("fire")
//     .addEventListener('click', (event) => {
//       showLoading();
//     });
