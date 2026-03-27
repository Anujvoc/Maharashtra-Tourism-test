
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">

    <title>@yield('title', 'Dashboard')</title>

    <meta name="description" content="Maharashtra Tourism - Designed &amp; Developed by Vocman India Private Limited">

    <meta name="author" content="pixelcave">

    <meta name="robots" content="index, follow">



    <!-- Open Graph Meta 
 -->

    <meta property="og:title" content="Maharashtra Tourism - Designed &amp; Developed by Vocman India Private Limited">

    <meta property="og:site_name" content="Maharashtra Tourism">

    <meta property="og:description" content="Maharashtra Tourism - Designed &amp; Developed by Vocman India Private Limited">

    <meta property="og:type" content="website">

    <meta property="og:url" content="">

    <meta property="og:image" content="">

    <link rel="shortcut icon" href="{{ asset('backend/mah-logo-300x277.png') }}">

    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">

    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/dashmix.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">

    <script src="{{ asset('assets/js/setTheme.js') }}"></script>

    <style>
/* Required label star */
.form-label.required::after {
    content: " *";
    color: red;
    font-weight: 600;
}
</style>

</head>

<body>

    <div id="page-loader" class="d-flex flex-column justify-content-center align-items-center show" style="height:100vh; background: #f0f0f0;">

        <img src="{{ asset('backend/mah-logo-300x277.png') }}" alt="Maharashtra Tourism" style="width:120px; height:120px; margin-bottom:20px;">

        <!-- <h2 style="font-weight:bold; font-size:2rem; letter-spacing:2px; color:#222;">

            Maharashtra Tourism

        </h2> -->

    </div>



    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        @include('backend.layouts.sideheader')

        @include('backend.layouts.navbar')

        @include('backend.layouts.header')

             <main id="main-container" class="container-fluid  px-4" style="
    max-width: 100% !important;
    width: 100%;
">

            @yield('content')

        </main>



        @include('backend.layouts.footer')

    </div>

    <!-- Core JS -->

    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/chart.js/chart.umd.js') }}"></script>

    <script src="{{ asset('assets/js/dashmix.app.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/be_pages_dashboard.min.js') }}"></script>



    <!-- DataTables JS Plugins -->

    <script src="{{ asset('assets/js/plugins/datatables/dataTables.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>



    <script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Page JS Code -->

    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>

    <script>

    $(document).ready(function () {

        console.log(window.document.cookie);

    $(document).on('change', '.filestyle', function (event) {

        const input = event.target;

        const file = input.files[0];

        if (file) {

            const reader = new FileReader();

            reader.onload = function (e) {

                $(input).closest('.card-body').find('.img-thumbnail').attr('src', e.target.result);

            };

            reader.readAsDataURL(file);

        }

    });

});





$(document).ready(function () {

    $('body').on('click', '.alldeletebutton', function (e) {

        e.preventDefault();



        var url = $(this).attr('href');



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

                $.ajax({

                    url: url,

                    type: 'DELETE',

                    data: {

                        _token: '{{ csrf_token() }}'

                    },

                    success: function(response) {

                        if (response.status == 'success') {

                            Swal.fire({

                                title: 'Deleted!',

                                text: response.message,

                                icon: 'success'

                            }).then(() => {

                                window.location.reload();

                            });

                        } else if (response.status == 'error') {

                            Swal.fire({

                                title: 'Cannot delete',

                                text: response.message,

                                icon: 'error'

                            });

                        }

                    },

                    error: function(xhr) {

                        Swal.fire({

                            title: 'Error!',

                            text: 'Something went wrong while deleting.',

                            icon: 'error'

                        });

                    }

                });

            }

        });

    });

});







$(document).ready(function() {

    $('.img-thumb').on('click', function() {

        var imgSrc = $(this).attr('src');

        var windowWidth = 600;

        var windowHeight = 500;

        var left = (screen.width / 2) - (windowWidth / 2);

        var top = (screen.height / 2) - (windowHeight / 2);

        var newWindow = window.open('', '_blank', 'width=' + windowWidth + ',height=' + windowHeight + ',left=' + left + ',top=' + top);

        newWindow.document.write('<html><head><title>Image</title></head><body style="margin:0;display:flex;justify-content:center;align-items:center;height:100vh;"><img src="' + imgSrc + '" style="max-width:100%;max-height:100%;"></body></html>');

    });

});





</script>



<script>

    $(document).ready(function(){

    $('body').on('click', '.change-status', function(){

        let isChecked = $(this).is(':checked');

        let id = $(this).data('id');



        $.ajax({



            method: 'PUT',

            headers: { // Set the headers here

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },

            data: {

                status: isChecked, // Send the boolean state directly

                id: id,

            },

            success: function(data){

                toastr.success(data.message); // Notify the user of success

            },

            error: function(xhr, status, error){

                console.log(error);

                toastr.error('Error updating status.'); // Notify the user of error

            }

        });

    });

});



    </script>





    @stack('scripts')

</body>



</html>

