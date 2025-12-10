<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Maharashtra Tourism</title>
  <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/fontawesome/css/all.min.css')}}">

  <!-- External Link -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/jqvmap/dist/jqvmap.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/weather-icon/css/weather-icons.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/weather-icon/css/weather-icons-wind.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/summernote/summernote-bs4.css')}}">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/css/bootstrap-iconpicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/bootstrap-daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/css/components.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/modules/prism/prism.css')}}">  <!-- CSS Libraries -->


  {{-- @if($settings->layout === 'RTL')
  <link rel="stylesheet" href="{{asset('frontend/backend/assets/css/rtl.css')}}">
  @endif --}}

  {{-- <script>
    const USER = {
        id: "{{ auth()->user()->id }}",
        name: "{{ auth()->user()->nmae }}",
        image: "{{ asset(auth()->user()->image) }}"
    }
    const PUSHER = {
        key: "{{ $pusherSetting->pusher_key }}",
        cluster: "{{ $pusherSetting->pusher_cluster }}"
    }
  </script> --}}

    @vite(['resources/js/app.js'])
    {{-- @vite(['resources/js/app.js', 'resources/js/admin.js']) --}}
    <style>
        :root{
          --brand:#ff6a00; --brand-dark:#d45400; --ink:#152238; --muted:#6c757d; --bg:#fffaf5;
        }
        .required::after {
        content: " *";
        color: #dc3545;
    }
    </style>

@stack('styles')
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

        <!-- Navbar Content -->
            @include('frontend.layouts2.navbar')
        <!-- Navbar Content End-->

        <!-- sidebar Content -->
             @include('frontend.layouts2.sidebar')
        <!-- sidebar Content -->

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>

    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{asset('frontend/backend/assets/modules/jquery.min.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/popper.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/tooltip.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/moment.min.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/js/stisla.js')}}"></script>

  <!-- JS Libraies -->
  <script src="{{asset('frontend/backend/assets/modules/simple-weather/jquery.simpleWeather.min.js')}}"></script>
  {{-- <script src="{{asset('backend/assets/modules/chart.min.js')}}"></script> --}}
  <script src="{{asset('frontend/backend/assets/modules/jqvmap/dist/jquery.vmap.min.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/summernote/summernote-bs4.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/chocolat/dist/js/jquery.chocolat.min.js')}}"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{asset('frontend/backend/assets/js/bootstrap-iconpicker.bundle.min.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/modules/select2/dist/js/select2.full.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="{{asset('frontend/backend/assets/js/page/bootstrap-modal.js')}}"></script>
  <!-- Page Specific JS File -->
  {{-- <script src="{{asset('backend/assets/js/page/index-0.js')}}"></script> --}}

  <!-- Template JS File -->
  <script src="{{asset('frontend/backend/assets/js/scripts.js')}}"></script>
  <script src="{{asset('frontend/backend/assets/js/custom.js')}}"></script>
  <script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    }
</script>

  <script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{$error}}")
        @endforeach
    @endif
  </script>
  <script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif
</script>
<script>
    function get_Region_District(id) {

        if (!id) {
            $(".district_id").html('<option value="">Select District</option>');
            return;
        }

        const custom_url = "{{ route('frontend.get_Region_District', ['id' => ':id']) }}"
            .replace(':id', id);

        $(".district_id").html('<option value="">Loading...</option>');

        $.ajax({
            url: custom_url,
            type: 'GET',
            dataType: 'json',
            success: function(resp) {

                $(".district_id").html('');

                if (Array.isArray(resp) && resp.length > 0) {

                    $(".district_id").append('<option value="">Select District</option>');

                    // yahan id="old_district" hai → #old_district use karo
                    let oldDistrict = $("#old_district").val();

                    $.each(resp, function(index, item) {

                        let selected = (oldDistrict == item.id) ? 'selected' : '';

                        $(".district_id").append(
                            `<option value="${item.id}" ${selected}>${item.name}</option>`
                        );
                    });

                } else {
                    $(".district_id").html('<option value="">No District found</option>');
                }
            },
            error: function() {
                $(".district_id").html('<option value="">Error loading districts</option>');
            }
        });
    }

    $(document).ready(function() {
        // yahan bhi id="region_id" hai → #region_id use karo
        let selectedRegion = $("#region_id").val();
        if (selectedRegion) {
            get_Region_District(selectedRegion);
        }
    });
</script>

<script>
    function get_Region_District123(id) {

        const custom_url = "{{ route('frontend.get_Region_District', ['id' => ':id']) }}"
            .replace(':id', id);

        $(".district_id").html('<option value="">Loading...</option>');

        $.ajax({
            url: custom_url,
            type: 'GET',
            success: function(resp) {

                $(".district_id").html('');

                if (Array.isArray(resp) && resp.length > 0) {

                    $(".district_id").append('<option value="">Select District</option>');

                    let oldDistrict = $(".old_district").val();

                    $.each(resp, function(index, item) {

                        let selected = (oldDistrict == item.id) ? 'selected' : '';

                        $(".district_id").append(
                            `<option value="${item.id}" ${selected}>${item.name}</option>`
                        );
                    });

                } else {

                    $(".district_id").html('<option value="">No District found</option>');
                }
            }
        });
    }

    $(document).ready(function() {
        let selectedRegion = $(".region_id").val();
        if (selectedRegion) {
            get_Region_District(selectedRegion);
        }
    });
</script>

  <!-- Dynamic Delete alart -->
  <script>
    // Auto attach to all file inputs with class 'file-preview-input'
    document.querySelectorAll('.file-preview-input').forEach(input => {
      input.addEventListener('change', function() {
        const previewId = this.id + 'Preview';
        const previewBox = document.getElementById(previewId);
        previewBox.innerHTML = ''; // clear old previews

        Array.from(this.files).forEach(file => {
          const reader = new FileReader();

          reader.onload = e => {
            // For images
            if (file.type.startsWith('image/')) {
              const item = document.createElement('div');
              item.classList.add('preview-item');

              item.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <button type="button" class="btn btn-danger btn-sm remove-btn">&times;</button>
              `;

              // Add remove event
              item.querySelector('.remove-btn').addEventListener('click', () => {
                item.remove();
                // If all removed, clear input
                if (!previewBox.querySelectorAll('.preview-item').length) input.value = '';
              });

              previewBox.appendChild(item);
            } else {
              // For PDFs or others
              const link = document.createElement('a');
              link.textContent = file.name;
              link.href = '#';
              link.className = 'badge bg-secondary text-decoration-none';
              previewBox.appendChild(link);
            }
          };

          reader.readAsDataURL(file);
        });
      });
    });

    // Manually clear image preview (for existing uploaded images)
    function removeImage(inputId, previewId) {
      document.getElementById(inputId).value = '';
      document.getElementById(previewId).innerHTML = '';
    }
  </script>


  <script>
    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('body').on('click', '.delete-item', function(event){
            event.preventDefault();

            let deleteUrl = $(this).attr('href');

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
                        type: 'DELETE',
                        url: deleteUrl,

                        success: function(data){

                            if(data.status == 'success'){
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                )
                                window.location.reload();
                            }else if (data.status == 'error'){
                                Swal.fire(
                                    'Cant Delete',
                                    data.message,
                                    'error'
                                )
                            }
                        },
                        error: function(xhr, status, error){
                            console.log(error);
                        }
                    })
                }
            })
        })

    })
  </script>


  @stack('scripts')
</body>
</html>
