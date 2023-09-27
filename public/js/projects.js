$(document).ready(function () {

    $('body').on('click', '#delete-student', function (event) {

        event.preventDefault();
        const value = $(this).attr('data-id');
        const name = $(this).attr('data-name');
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: 'Are you sure want to delete ' + name + '?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    accepts: {
                        mycustomtype: 'application/x-some-custom-type'
                    },
                    url: `/admin/student/${value}`,
                    type: "PATCH",
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

                  //   setTimeout(() => {
                  //       window.location.href = '/admin/list'
                  //   }, 1500)

                  $(`#index_student_${value}`).remove();

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

   
   $('body').on('click', '#delete-user', function (event) {

        event.preventDefault();
        const value = $(this).attr('data-id');
        const name = $(this).attr('data-name');
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: 'Are you sure want to delete ' + name + '?' ,
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
                    url: `/admin/user/${value}`,
                    type: "delete",
                    cache: false,
                    data: {
                        "id": value,
                        "_token": token
                    },
                }).then((res) => {
                    console.log(res)
                    Swal.fire(
                        'Deleted!',
                        `${name} has been deleted.`,
                        'success',
                    )

                  //   setTimeout(() => {
                  //       window.location.href = '/admin/user'
                  //   }, 1500)

                  $(`#index_user_${value}`).remove();
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

    $('body').on('click', '#delete-teacher', function (event) {

      event.preventDefault();
      const value = $(this).attr('data-id');
      const name = $(this).attr('data-name');
      let token = $("meta[name='csrf-token']").attr("content");
      Swal.fire({
          title: 'Are you sure want to delete ' + name + '?' ,
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
                  url: `/admin/teachers/${value}`,
                  type: "delete",
                  cache: false,
                  data: {
                      "id": value,
                      "_token": token
                  },
              }).then((res) => {
                  console.log(res)
                  Swal.fire(
                      'Deleted!',
                      `${name} has been deleted.`,
                      'success',
                  )

                //   setTimeout(() => {
                //       window.location.href = '/admin/user'
                //   }, 1500)

                $(`#index_teacher_${value}`).remove();
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
