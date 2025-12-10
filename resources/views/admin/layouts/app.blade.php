<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maharashtra Tourism | @yield('title')</title>
    <meta name="description"
        content="Official Maharashtra Tourism landing page – discover destinations, experiences, and plan your trip." />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!--favicon-->
    {{-- <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png"> --}}
    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png"
        sizes="32x32" type="image/png">

    <!-- loader-->

    <link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/assets/js/pace.min.js') }}"></script>


    <!--plugins-->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/plugins/metismenu/metisMenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/plugins/metismenu/mm-vertical.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/summernote/summernote-bs4.css')}}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Main CSS -->
    <link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <!-- Sass Compiled CSS (make sure they are compiled into public/sass or public/css) -->
    <link rel="stylesheet" href="assets/css/extra-icons.css">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="{{ asset('backend/sass/main.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/sass/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/sass/blue-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/sass/semi-dark.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/sass/bordered-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/sass/responsive.css') }}" rel="stylesheet">



    @stack('styles')
</head>

<body>

    {{-- Header --}}
    @include('admin.partials.navbar')

    {{-- <div class="layout"> --}}
    @include('admin.partials.sidebar')
    {{-- <div class="content-wrap">
     <main> --}}
    @yield('content')
    {{-- </main> --}}

    {{-- Footer --}}
    @include('admin.partials.footer')
    {{-- </div> --}}
    </div>

    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/peity/jquery.peity.min.js') }}"></script>
    <script src="{{asset('frontend/backend/assets/modules/summernote/summernote-bs4.js')}}"></script>
    <script>
        $(".data-attributes span").peity("donut");
    </script>

    <!-- Main JS -->
    <script src="{{ asset('backend/assets/js/main.js') }}"></script>
    <script src="{{ asset('backend/assets/js/dashboard1.js') }}"></script>
    <script src="{{ asset('backend/assets/js/dashboard2.js') }}"></script>


    <script src="{{ asset('backend/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
  $("html").attr("data-bs-theme", "dark");
});

    </script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200,        // editor height
                tabsize: 2
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            var table = $('#example').DataTable({
                dom: 'Bflrtip',
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-primary text-white',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn btn-secondary text-white',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-success text-white',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn btn-danger text-white',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn btn-warning text-white',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },
                ]

            });
            $('#export-print').on('click', () => table.button('.buttons-print').trigger());
            $('#export-csv').on('click', () => table.button('.buttons-csv').trigger());
            $('#export-excel').on('click', () => table.button('.buttons-excel').trigger());
            $('#export-pdf').on('click', () => table.button('.buttons-pdf').trigger());
            $('#export-copy').on('click', () => table.button('.buttons-copy').trigger());
        });
    </script>
    <script>
        $(document).ready(function() {

            var table = $('#example2').DataTable({
                lengthChange: false,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-outline-secondary',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn btn-outline-secondary',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-outline-secondary',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn btn-outline-secondary',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },

                    {
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn btn-outline-secondary',
                        exportOptions: {
                            columns: ':not(.no_action)'
                        }
                    },
                ]
            });
            table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>

$(document).ready(function () {
    $('body').on('click', '.deletebutton', function (e) {
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

    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options.progressBar = true;
        toastr.options.closeButton = true;

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}")
            @endforeach
        @endif
    </script>
    <script>
        new PerfectScrollbar(".user-list")
    </script>
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error");
        </script>
    @endif

    @if (session('warning'))
        <script>
            toastr.warning("{{ session('warning') }}", "Warning");
        </script>
    @endif

    @if (session('info'))
        <script>
            toastr.info("{{ session('info') }}", "Info");
        </script>
    @endif
    @stack('scripts')

</body>

</html>
