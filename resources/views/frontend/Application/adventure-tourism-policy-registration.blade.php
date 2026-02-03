@extends('frontend.layouts2.master')
@section('title', 'Adventure Tourism form')
@push('styles')
<style>
  :root{
    --brand: #ff6600;   /* Orange color */
    --brand-dark: #e25500;
  }
  .form-icon {
        color: var(--brand);
        font-size: 1.5rem;
    }
    .form-icon{margin-right:.35rem;}
</style>
@endpush
@section('content')

      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>{{ $application_form->name ?? '' }}</h1>

          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                    <div class="row ">
                    <div class="card-header">
                            <div class="col-md-6 col-12">
                                <h4 class="m-0">Create {{ $application_form->name ?? '' }}</h4>
                            </div>

                            <!-- RIGHT SIDE BACK BUTTON -->
                            <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                                <a href="#"
                                   class="text-white fw-bold d-inline-block no-underline"
                                   style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
                                    <i class="bi bi-arrow-left me-2 mx-2"></i> Back
                                </a>
                            </div>

                        </div>
                    </div>

                  <div class="card-body">



                        <div id="formContainer">
                            @include('frontend.Application.AdventureApplications.partials.form', [
                                'application' => $application,
                                'regions' => $regions ?? collect(),
                                'districts' => $districts ?? collect(),
                                'categories' => $categories ?? []
                            ])
                        </div>


                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>
@endsection

@push('scripts')


<script>
    function get_Region_District(id) {

        const custom_url = "{{ route('frontend.get_Region_District', ['id' => ':id']) }}"
            .replace(':id', id);

        $("#district_id").html('<option value="">Loading...</option>');

        $.ajax({
            url: custom_url,
            type: 'GET',
            success: function(resp) {

                $("#district_id").html('');

                if (Array.isArray(resp) && resp.length > 0) {

                    $("#district_id").append('<option value="">Select District</option>');

                    let oldDistrict = $("#old_district").val();

                    $.each(resp, function(index, item) {

                        let selected = (oldDistrict == item.id) ? 'selected' : '';

                        $("#district_id").append(
                            `<option value="${item.id}" ${selected}>${item.name}</option>`
                        );
                    });

                } else {

                    $("#district_id").html('<option value="">No District found</option>');
                }
            }
        });
    }

    $(document).ready(function() {
        let selectedRegion = $("#region_id").val();
        if (selectedRegion) {
            get_Region_District(selectedRegion);
        }
    });
</script>
<script>
    function validateWhatsAppInput(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const currentValue = event.target.value;

        // Allow only numeric characters (0-9)
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
            return false;
        }

        // If it's the first character, ensure it's between 6-9
        if (currentValue.length === 0 && (charCode < 54 || charCode > 57)) {
            event.preventDefault();
            return false;
        }

        // Restrict to 10 digits total
        if (currentValue.length >= 10) {
            event.preventDefault();
            return false;
        }

        return true;
    }
    </script>
    <script>
        function validateName(event) {
            const charCode = event.which ? event.which : event.keyCode;


            if ((charCode >= 65 && charCode <= 90) ||
                (charCode >= 97 && charCode <= 122) ||
                (charCode === 32)) {
                return true;
            }

            event.preventDefault();
            return false;
        }
        </script>

<script>
    $(function () {
        // ---------- File preview helper ----------
        function handleFileInput($input, $previewContainer, $img, $pdf, $pdfLink, $filename, $existing) {
            function resetPreview() {
                $previewContainer.hide();
                $img.hide().attr('src', '');
                $pdf.hide();
                $pdfLink.attr('href', '').text('');
                $filename.text('');
                if ($existing.length) $existing.show();
            }

            resetPreview();

            $input.on('change', function () {
                const file = this.files && this.files[0];
                if (!file) { resetPreview(); return; }
                if ($existing.length) $existing.hide();

                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    Swal.fire('File too large', 'File must be less than 5 MB', 'warning');
                    this.value = '';
                    resetPreview();
                    return;
                }

                $filename.text(file.name);
                $previewContainer.show();

                if (file.type && file.type.startsWith('image/')) {
                    $pdf.hide();
                    $img.show();
                    const reader = new FileReader();
                    reader.onload = function (e) { $img.attr('src', e.target.result); };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                    $img.hide().attr('src', '');
                    $pdf.show();
                    const url = URL.createObjectURL(file);
                    $pdfLink.attr('href', url).text('Open PDF (' + file.name + ')');
                } else {
                    $img.hide(); $pdf.hide();
                }
            });
        }

        // initialize previews
        handleFileInput(
            $('#pan_file'),
            $('#pan_preview_container'),
            $('#pan_preview_img'),
            $('#pan_preview_pdf'),
            $('#pan_preview_pdf_link'),
            $('#pan_filename'),
            $('#pan_existing')
        );

        handleFileInput(
            $('#aadhar_file'),
            $('#aadhar_preview_container'),
            $('#aadhar_preview_img'),
            $('#aadhar_preview_pdf'),
            $('#aadhar_preview_pdf_link'),
            $('#aadhar_filename'),
            $('#aadhar_existing')
        );

        // ---------- jQuery Validate custom methods ----------
        $.validator.addMethod("acceptExt", function (value, element, param) {
            if (element.files.length === 0) return false;
            var ext = element.files[0].name.split('.').pop().toLowerCase();
            var allowed = (param || '').replace(/\s/g, '').split(',');
            // allowed like ".pdf,.jpg,.jpeg,.png"
            for (var i=0;i<allowed.length;i++){
                if (allowed[i].replace(/^\./,'') === ext) return true;
            }
            return false;
        }, "Please upload a file with a valid extension.");

        $.validator.addMethod("filesize", function (value, element, param) {
            if (element.files.length === 0) return false;
            var sizeKB = element.files[0].size / 1024;
            return sizeKB <= param;
        }, "File size must be less than {0} KB.");

        // ---------- Validation config ----------
        const validator = $("#adventureForm").validate({
            ignore: [],
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function (el) { $(el).addClass("is-invalid"); },
            unhighlight: function (el) { $(el).removeClass("is-invalid"); },
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").length) error.insertAfter(element.parent());
                else if (element.attr("type") === "file") error.insertAfter(element);
                else error.insertAfter(element);
            },
            rules: {
                email: { required: true, email: true },
                mobile: { required: true, minlength: 10, maxlength: 10 ,pattern: /^[6-9]\d{9}$/},
                whatsapp: { required: false, minlength: 10, maxlength: 10 ,pattern: /^[6-9]\d{9}$/},
                applicant_type: { required: true },
                applicant_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 255,
                    pattern: /^[A-Za-z\s]+$/
                },
                company_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 255,
                    pattern: /^[A-Za-z\s]+$/
                },
                applicant_address: { required: true, minlength: 5 },
                region_id: { required: true },
                district_id: { required: true },
                adventure_category: { required: true },
                activity_name: { required: true, minlength: 2 },

                // file rules: required on both create & edit per your instruction
                pan_file: {
                    required: function() { return true; },
                    acceptExt: ".pdf,.jpg,.jpeg,.png",
                    filesize: 5120 // KB
                },
                aadhar_file: {
                    required: function() { return true; },
                    acceptExt: ".pdf,.jpg,.jpeg,.png",
                    filesize: 5120
                }
            },
            messages: {
                email: { required: "Please enter email", email: "Invalid email" },
                pan_file: { required: "Please upload PAN file", acceptExt: "Allowed: pdf,jpg,png", filesize: "Max 5 MB" },
                aadhar_file: { required: "Please upload Aadhar file", acceptExt: "Allowed: pdf,jpg,png", filesize: "Max 5 MB" }
            },
            submitHandler: function (form) {
                // AJAX submit with SweetAlert
                const $form = $(form);
                const formData = new FormData(form);
                const $btn = $form.find('button[type="submit"]').prop('disabled', true);

                $.ajax({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success(res) {
                        Swal.fire('Success', res.message || 'Saved', 'success').then(() => {
                            // redirect to index if route exists, otherwise reload
                            var idxRoute = "{{ route('applications.index') }}";
                            if (idxRoute && idxRoute !== '') window.location.href = idxRoute;
                            else location.reload();
                        });
                    },
                    error(xhr) {
                        $btn.prop('disabled', false);
                        if (xhr.status === 422) {
                            const json = xhr.responseJSON || {};
                            let errors = json.errors || xhr.responseJSON.errors || {};
                            let html = '';
                            $.each(errors, function (k, v) { html += v.join('<br>') + '<br>'; });
                            Swal.fire({ title: 'Validation error', html: html || 'Please check form', icon: 'error' });
                            // attach feedback to fields
                            $.each(errors, function (field, messages) {
                                const el = $('[name="'+field+'"]');
                                if (el.length) {
                                    el.addClass('is-invalid');
                                    if (!el.next('.invalid-feedback').length) el.after('<div class="invalid-feedback">'+messages.join(', ')+'</div>');
                                    else el.next('.invalid-feedback').html(messages.join(', '));
                                }
                            });
                        } else {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        }
                    }
                });

                return false;
            }
        });

    });
    </script>
@endpush
