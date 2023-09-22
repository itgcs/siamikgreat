$(document).ready(function () {

    $('#delete-student').on('click', function (event) {

        event.preventDefault();
        const value = $(this).attr('data-id');
        const name = $(this).attr('data-name');
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
               console.log('masuk')
                $.ajax({
                     headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     accepts: {
                        mycustomtype: 'application/x-some-custom-type'
                     },
                     url: `/superadmin/student/${value}`,
                     type: "delete",
                     cache: false,
                     data: {
                        "id": value,
                        "_token": token
                    },
                  //   success: function () {

                  //       //...

                  //       
                  //   }
                }).then((res) => {
                  console.log(res)
                  Swal.fire(
                     'Deleted!',
                     `${name} has been deleted.`,
                     'success',
                  )
                  
                  setTimeout(() => {
                     window.location.href = '/admin/list'
                  }, 1500)
                }).catch((err) => {
                  
                  Swal.fire({
                     icon: 'error',
                     title: 'Oops...',
                     text: 'Something went wrong!',
                     footer: '<a href="">Why do I have this issue?</a>'
                   })
                })

            }
        })
    });



});
