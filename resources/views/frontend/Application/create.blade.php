@extends('frontend.layouts2.master')
@section('title', 'Caravan Registration Application')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- <style>
        body {
            background: #fff7f0;
        }

        .card {
            border-radius: 18px;
            border: none;
        }

        .section-title {
            font-weight: 700;
            color: #ff6600;
            margin-top: 30px;
            font-size: 1.4rem;
            border-left: 5px solid #ff6600;
            padding-left: 10px;
        }

        .input-group-text {
            background: #ffe3cc;
            border: 1px solid #ffb380;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #ffb380;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, .25);
            border-color: #ff6600;
        }

        .btn-primary {
            background: #ff6600;
            border: none;
        }

        .btn-primary:hover {
            background: #e65c00;
        }

        .btn-outline-secondary:hover {
            background: #ffe3cc;
        }

        .preview-box {
            background: #fff0e0;
            border-radius: 12px;
        }

        .file-label {
            font-weight: 600;
        }
    </style> --}}
@endpush

@section('content')
    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <a href="{{ url('/frontend/caravan-registrations') }}" class="btn btn-secondary mb-3 float-end">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h3 class="text-center mb-4 fw-bold" style="color:#ff6600;">Caravan Registration</h3>

                <p class="text-muted small mb-4">
                    Department of Tourism has introduced a new policy to promote Caravan Tourism in Maharashtra. Applicants
                    intending to get licensed with the state government may apply using this form.<br>
                    <b>Payment Gateway:</b> To complete the payment, visit: https://gras.mahakosh.gov.in
                </p>

                <form method="POST" action="{{ route('frontend.caravan-registrations.store') }}"
                    enctype="multipart/form-data" id="caravanForm">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Id <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-envelope" style="color:#ff6600;"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-phone" style="color:#ff6600;"></i>
                                </span>
                                <input type="tel" name="mobile" id="mobile" maxlength="15" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Name of Applicant / Authorized Person <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-user" style="color:#ff6600;"></i>
                                </span>
                                <input type="text" name="applicant_name" id="applicant_name" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Full Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-location-dot" style="color:#ff6600;"></i>
                                </span>
                                <textarea name="address" id="address" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>

                        <!-- Select Region -->
                        <div class="col-md-6">
                            <label for="region_id" class="form-label">Select Region <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-map" style="color:#ff6600;"></i></span>
                                <select id="region_id" name="region_id"
                                    class="form-control {{ $errors->has('region_id') ? 'is-invalid' : '' }}"
                                    onchange="get_Region_District(this.value)" required>
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $r)
                                        <option value="{{ $r->id }}"
                                            {{ old('region_id', $application->region_id ?? '') == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('region_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Select District -->
                        <div class="col-md-6">
                            <label for="district_id" class="form-label">Select District <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-map-pin"
                                        style="color:#ff6600;"></i></span>
                                <select id="district_id" name="district_id"
                                    class="form-control {{ $errors->has('district_id') ? 'is-invalid' : '' }}" required>
                                    <option value="">Select District</option>
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" id="old_district"
                            value="{{ old('district_id', $application->district_id ?? '') }}">

                        <!-- Applicant Type -->
                        <div class="col-md-6">
                            <label class="form-label">Select Applicant Type <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"
                                        style="color:#ff6600;"></i></span>
                                <select class="form-control" name="applicant_type" id="applicant_type" required>
                                    <option value="">Select</option>
                                    @foreach ($enterprises as $enterprise)
                                        <option value="{{ $enterprise->id }}">{{ $enterprise->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact No. <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"
                                        style="color:#ff6600;"></i></span>
                                <input type="tel" name="emergency_contact" id="emergency_contact"
                                    class="form-control" required>
                            </div>
                        </div>

                        <!-- Caravan Type -->
                        <div class="col-12">
                            <label class="form-label">Type of Caravan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-caravan"
                                        style="color:#ff6600;"></i></span>
                                <select name="caravan_type_id" id="caravan_type_id" class="form-select" required>
                                    <option value="">-- Select Caravan Type --</option>
                                    @foreach ($caravanTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Prior Experience -->
                        <div class="col-12">
                            <label class="form-label">Any Prior Experience in tourism business?</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-briefcase"
                                        style="color:#ff6600;"></i></span>
                                <textarea name="prior_experience" id="prior_experience" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <!-- Vehicle Registration Number -->
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Registration Number <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-id-card"
                                        style="color:#ff6600;"></i></span>
                                <input type="text" name="vehicle_reg_no" id="vehicle_reg_no" class="form-control"
                                    required>
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div class="col-md-6">
                            <label class="form-label">How many people can your caravan accommodate?</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-users"
                                        style="color:#ff6600;"></i></span>
                                <input type="number" name="capacity" id="capacity" class="form-control"
                                    min="1">
                            </div>
                        </div>

                        <!-- Beds -->
                        <div class="col-md-6">
                            <label class="form-label">Number of beds in your caravan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-bed"
                                        style="color:#ff6600;"></i></span>
                                <input type="number" name="beds" id="beds" class="form-control"
                                    min="1">
                            </div>
                        </div>

                        <!-- Engine No -->
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Engine Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-gears"
                                        style="color:#ff6600;"></i></span>
                                <input type="text" name="engine_no" id="engine_no" class="form-control">
                            </div>
                        </div>

                        <!-- Chassis No -->
                        <div class="col-md-6">
                            <label class="form-label">Vehicle Chassis Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-truck-pickup"
                                        style="color:#ff6600;"></i></span>
                                <input type="text" name="chassis_no" id="chassis_no" class="form-control">
                            </div>
                        </div>

                        <hr class="mt-4">
                        <h5>Facilities / Amenities Available</h5>

                        <div class="col-12">
                            <div class="row">
                                @foreach ($amenities as $amenity)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenities[]"
                                                value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}">
                                            <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                                {{ $amenity->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr class="mt-4">
                        <h5>Optional Features</h5>

                        <div class="col-12">
                            <div class="row">
                                @foreach ($optionalFeatures as $feature)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="optional_features[]"
                                                value="{{ $feature->id }}" id="feature_{{ $feature->id }}">
                                            <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Routes -->
                        <div class="col-12">
                            <label class="form-label">List all routes / circuits covered <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-route"
                                        style="color:#ff6600;"></i></span>
                                <textarea name="routes" id="routes" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                        <hr class="mt-4">

                        <h5>Upload Documents (Max 200 kB each)</h5>

                        @php
                            $docs = [
                                'registration_fee_challan' => 'Registration Fee Challan',
                                'vehicle_reg_card' => 'Vehicle Registration Card Document',
                                'vehicle_insurance' => 'Vehicle Insurance',
                                'declaration_form' => 'Declaration Form',
                                'aadhar_card' => 'Aadhar Card',
                                'pan_card' => 'PAN Card',
                                'vehicle_purchase_copy' => 'Vehicle Purchase Copy',
                                'company_proof' => 'Proof of Company Documents / Partnership Deed / LLP Deed',
                            ];
                        @endphp

                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:30%">Document Name</th>
                                    <th style="width:30%">Upload</th>
                                    <th style="width:20%">Preview</th>
                                    <th style="width:20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($docs as $key => $label)
                                    <tr>
                                        <td class="align-middle">{{ $label }} <span class="text-danger">*</span></td>
                                        <td class="align-middle">
                                            <input type="file" name="{{ $key }}" id="{{ $key }}"
                                                   class="form-control file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                        </td>
                                        <td class="align-middle">
                                            <div id="preview_{{ $key }}"
                                                 class="preview-box border p-2 text-center position-relative"
                                                 style="height:110px; overflow:hidden; cursor:pointer;"></div>
                                                <span class="text-muted small">No file uploaded</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <!-- Remove button moved to Action column -->
                                            <button type="button" class="btn btn-sm btn-danger mt-2 d-none remove-btn"
                                                    data-target="{{ $key }}">
                                                <i class="fa fa-trash"></i> Remove
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <!-- ===================== MODAL POPUP ====================== -->
                        <div class="modal fade" id="previewModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body text-center" id="modalContent"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="previewSubmitBtn">Save</button>
                    </div>



                    <!-- ===================== FORM PREVIEW MODAL ===================== -->
                    <div class="modal fade" id="formPreviewModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Preview Your Application</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body" id="formPreviewBody" style="max-height: 70vh; overflow-y: auto;">
                                    <!-- Preview content will be injected dynamically -->
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Submit</button>
                                </div>

                            </div>
                        </div>
                    </div>











                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#caravanForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    applicant_name: {
                        required: true,
                        minlength: 3
                    },
                    address: {
                        required: true,
                        minlength: 10
                    },
                    region_id: {
                        required: true
                    },
                    district_id: {
                        required: true
                    },
                    applicant_type: {
                        required: true
                    },
                    emergency_contact: {
                        required: true,
                        digits: true,
                        minlength: 10
                    },
                    caravan_type_id: {
                        required: true
                    },
                    vehicle_reg_no: {
                        required: true,
                        minlength: 3
                    },
                    capacity: {
                        min: 1,
                        digits: true
                    },
                    beds: {
                        min: 1,
                        digits: true
                    },
                    routes: {
                        required: true,
                        minlength: 10
                    },
                    registration_fee_challan: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    vehicle_reg_card: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    vehicle_insurance: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    declaration_form: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    aadhar_card: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    pan_card: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    vehicle_purchase_copy: {
                        required: true,
                        accept: "image/*,application/pdf"
                    },
                    company_proof: {
                        required: true,
                        accept: "image/*,application/pdf"
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    mobile: {
                        required: "Please enter your mobile number",
                        digits: "Please enter only digits",
                        minlength: "Mobile number must be at least 10 digits",
                        maxlength: "Mobile number cannot exceed 15 digits"
                    },
                    applicant_name: {
                        required: "Please enter applicant name",
                        minlength: "Name must be at least 3 characters"
                    },
                    address: {
                        required: "Please enter your address",
                        minlength: "Address must be at least 10 characters"
                    },
                    region_id: "Please select a region",
                    district_id: "Please select a district",
                    applicant_type: "Please select applicant type",
                    emergency_contact: {
                        required: "Please enter emergency contact number",
                        digits: "Please enter only digits",
                        minlength: "Contact number must be at least 10 digits"
                    },
                    caravan_type_id: "Please select caravan type",
                    vehicle_reg_no: {
                        required: "Please enter vehicle registration number",
                        minlength: "Registration number must be at least 3 characters"
                    },
                    routes: {
                        required: "Please enter routes/circuits information",
                        minlength: "Routes description must be at least 10 characters"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group, .col-md-6, .col-12').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>






@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

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
        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function() {
                let file = this.files[0];
                let previewBox = document.getElementById('preview_' + this.id);
                let removeBtn = document.querySelector(`[data-target="${this.id}"]`);

                if (!file) return;

                // ===================== FILE SIZE CHECK (200 kB) =====================
                if (file.size > 200 * 1024) {
                    alert("File size must be less than 200 kB.");
                    this.value = "";
                    previewBox.innerHTML =
                        `<span class="text-danger small">File too large (max 200 kB)</span>`;
                    removeBtn.classList.add("d-none");
                    previewBox.removeAttribute("data-type");
                    previewBox.removeAttribute("data-src");
                    return;
                }
                // ====================================================================

                previewBox.innerHTML = "";
                removeBtn.classList.remove("d-none");

                let fileType = file.type;

                if (fileType.includes("image")) {
                    let img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = "100%";
                    img.style.maxHeight = "100px";
                    previewBox.appendChild(img);

                    previewBox.dataset.type = "image";
                    previewBox.dataset.src = img.src;

                } else if (fileType.includes("pdf")) {
                    previewBox.innerHTML = `
                <i class="fa-solid fa-file-pdf fa-3x text-danger"></i>
                <div class="small mt-1">${file.name}</div>
            `;
                    previewBox.dataset.type = "pdf";
                    previewBox.dataset.src = URL.createObjectURL(file);

                } else {
                    previewBox.innerHTML = `<span class="text-danger small">Unsupported file</span>`;
                }
            });
        });


        // ===================== POPUP PREVIEW =====================

        document.querySelectorAll('.preview-box').forEach(box => {
            box.addEventListener('click', function() {
                let type = this.dataset.type;
                let src = this.dataset.src;

                if (!src) return;

                let modalContent = document.getElementById("modalContent");
                modalContent.innerHTML = "";

                if (type === "image") {
                    modalContent.innerHTML = `<img src="${src}" class="img-fluid rounded">`;
                } else if (type === "pdf") {
                    modalContent.innerHTML = `
                <iframe src="${src}" width="100%" height="600px" style="border:none;"></iframe>
            `;
                }

                let modal = new bootstrap.Modal(document.getElementById('previewModal'));
                modal.show();
            });
        });


        // ===================== REMOVE FILE =====================

        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let key = this.dataset.target;

                let input = document.getElementById(key);
                let previewBox = document.getElementById('preview_' + key);

                input.value = "";
                previewBox.innerHTML = `<span class="text-muted small">No file uploaded</span>`;
                previewBox.removeAttribute("data-type");
                previewBox.removeAttribute("data-src");

                this.classList.add("d-none");
            });
        });
    </script>

    <script>
document.getElementById('previewSubmitBtn').addEventListener('click', function(e) {
    // Validate form
    if (!$("#caravanForm").valid()) return;

    let previewHTML = `<table class="table table-bordered">`;

    // ----- Text Inputs, Selects, Textareas -----
    document.querySelectorAll('#caravanForm input:not([type=file]):not([type=checkbox]):not([type=radio]), #caravanForm select, #caravanForm textarea')
        .forEach(field => {
            let label = field.closest('.col-md-6, .col-12')?.querySelector('.form-label');
            if (!label) return;

            previewHTML += `
                <tr>
                    <th style="width:35%">${label.innerText}</th>
                    <td>${field.value || '--'}</td>
                </tr>
            `;
        });

    // ----- Checkboxes -----
    document.querySelectorAll('#caravanForm input[type=checkbox]').forEach(field => {
        let label = field.closest('.form-check')?.querySelector('label');
        if (!label) return;

        previewHTML += `
            <tr>
                <th style="width:35%">${label.innerText}</th>
                <td>${field.checked ? 'Yes' : 'No'}</td>
            </tr>
        `;
    });

    // ----- Radio Buttons -----
    document.querySelectorAll('#caravanForm input[type=radio]').forEach(group => {
        if (!group.name) return;
        let checkedRadio = document.querySelector(`#caravanForm input[name="${group.name}"]:checked`);
        let label = group.closest('.form-check')?.querySelector('label');
        if (!label || checkedRadio) return;
        previewHTML += `
            <tr>
                <th style="width:35%">${label.innerText}</th>
                <td>${checkedRadio ? checkedRadio.value : '--'}</td>
            </tr>
        `;
    });

    // ----- File Inputs -----
    previewHTML += `<tr><th colspan="2">Uploaded Files</th></tr>`;
    document.querySelectorAll('#caravanForm input[type=file]').forEach(fileInput => {
        let label = fileInput.closest('tr')?.querySelector('td:first-child')?.innerText || fileInput.name;
        let file = fileInput.files[0];

        previewHTML += `<tr><th>${label}</th><td>`;

        if (!file) {
            previewHTML += `<span class="text-danger">Not Uploaded</span>`;
        } else {
            let fileURL = URL.createObjectURL(file);
            if (file.type.includes('image')) {
                previewHTML += `<img src="${fileURL}" style="max-width:150px; max-height:150px;" class="img-thumbnail mb-2"><div><strong>${file.name}</strong></div>`;
            } else if (file.type.includes('pdf')) {
                previewHTML += `<i class="fa-solid fa-file-pdf fa-3x text-danger"></i>
                                <div class="mt-1"><strong>${file.name}</strong></div>
                                <a href="${fileURL}" target="_blank" class="btn btn-sm btn-primary mt-2">Open PDF</a>`;
            } else {
                previewHTML += `<span class="text-danger">Unsupported File</span>`;
            }
        }

        previewHTML += `</td></tr>`;
    });

    previewHTML += `</table>`;

    document.getElementById('formPreviewBody').innerHTML = previewHTML;

    new bootstrap.Modal(document.getElementById('formPreviewModal')).show();
});

// ----- Final Submit -----
document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
    document.getElementById('caravanForm').submit();
});
</script>

@endpush
