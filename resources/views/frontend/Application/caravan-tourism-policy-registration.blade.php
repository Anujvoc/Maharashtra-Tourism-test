@extends('frontend.layouts2.master')
@section('title', 'Agro-tourism Center Registration Form')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  :root{
    --brand: #ff6600;   /* Orange color */
    --brand-dark: #e25500;
  }
  .form-icon {
        color: var(--brand);
        font-size: 1.2rem;
        margin-right:.35rem;
  }
  .required::after {
    content: " *";
    color: #dc3545;
    margin-left: 0.15rem;
    font-weight: 600;
  }
  a.no-underline { text-decoration: none !important; }
  a.no-underline:hover { text-decoration: none !important; }

  .file-preview {
    margin-top: 8px;
    padding: 8px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background: #f8f9fa;
    min-height: 40px;
  }
  .file-preview img {
    max-height: 120px;
    max-width: 100%;
    display: block;
  }
  .file-preview a {
    display: inline-block;
    margin-top: 5px;
  }

  /* full screen loading overlay */
  .loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    color: white;
  }
  .loading-spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid var(--brand);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 0.8s linear infinite;
    margin-bottom: 15px;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
@endpush

@section('content')

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <p>Submitting your application, please wait...</p>
</div>

<!-- Main Content -->
<section class="section">
    <div class="section-header">
        <h1>{{ $application_form->name ?? 'Agro-tourism Center Registration' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="card-header w-100 d-flex flex-wrap justify-content-between align-items-center">
                            <div class="col-md-6 col-12">
                                <h3 class="mb-3">Agro Tourism Registration</h3>
                            </div>

                            <!-- RIGHT SIDE BACK BUTTON -->
                            <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                                <a href="{{ route('agriculture-registrations.index') }}"
                                   class="text-white fw-bold d-inline-block no-underline"
                                   style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
                                    <i class="bi bi-arrow-left me-2 mx-2"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container py-4">

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form method="POST"
                            action="{{ route('frontend.caravan-registrations.store') }}"
                            enctype="multipart/form-data"
                            id="caravanForm">
                          @csrf

                          <div class="row g-3">
                              <div class="col-md-6">
                                  <label class="form-label required">Email Id</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-envelope" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="email" name="email" id="email" class="form-control" required>
                                  </div>
                              </div>

                              <div class="col-md-6">
                                  <label class="form-label required">Mobile No.</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-phone" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="tel" name="mobile" id="mobile" maxlength="15" class="form-control" required>
                                  </div>
                              </div>

                              <div class="col-md-6">
                                  <label class="form-label required">Name of Applicant / Authorized Person</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-user" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="text" name="applicant_name" id="applicant_name" class="form-control" required>
                                  </div>
                              </div>

                              <div class="col-12">
                                  <label class="form-label required">Full Address</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-location-dot" style="color:#ff6600;"></i>
                                      </span>
                                      <textarea name="address" id="address" class="form-control" rows="2" required></textarea>
                                  </div>
                              </div>

                              <!-- Select Region -->
                              <div class="col-md-6">
                                  <label for="region_id" class="form-label required">Select Region</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-map" style="color:#ff6600;"></i>
                                      </span>
                                      <select id="region_id" name="region_id"
                                          class="form-control region_id {{ $errors->has('region_id') ? 'is-invalid' : '' }}"
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
                                  <label for="district_id" class="form-label required">Select District</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-map-pin" style="color:#ff6600;"></i>
                                      </span>
                                      <select id="district_id" name="district_id"
                                          class="form-control district_id {{ $errors->has('district_id') ? 'is-invalid' : '' }}"
                                          required>
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
                                  <label class="form-label required">Select Applicant Type</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-user" style="color:#ff6600;"></i>
                                      </span>
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
                                  <label class="form-label required">Emergency Contact No.</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-phone" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="tel" name="emergency_contact" id="emergency_contact"
                                          class="form-control" required>
                                  </div>
                              </div>

                              <!-- Caravan Type -->
                              <div class="col-12">
                                  <label class="form-label required">Type of Caravan</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-caravan" style="color:#ff6600;"></i>
                                      </span>
                                      <select name="caravan_type_id" id="caravan_type_id" class="form-control" required>
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
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-briefcase" style="color:#ff6600;"></i>
                                      </span>
                                      <textarea name="prior_experience" id="prior_experience" class="form-control" rows="2"></textarea>
                                  </div>
                              </div>

                              <!-- Vehicle Registration Number -->
                              <div class="col-md-6">
                                  <label class="form-label required">Vehicle Registration Number</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-id-card" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                          class="form-control" required>
                                  </div>
                              </div>

                              <!-- Capacity -->
                              <div class="col-md-6">
                                  <label class="form-label">How many people can your caravan accommodate?</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-users" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="number" name="capacity" id="capacity" class="form-control" min="1">
                                  </div>
                              </div>

                              <!-- Beds -->
                              <div class="col-md-6">
                                  <label class="form-label">Number of beds in your caravan</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-bed" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="number" name="beds" id="beds" class="form-control" min="1">
                                  </div>
                              </div>

                              <!-- Engine No -->
                              <div class="col-md-6">
                                  <label class="form-label">Vehicle Engine Number</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-gears" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="text" name="engine_no" id="engine_no" class="form-control">
                                  </div>
                              </div>

                              <!-- Chassis No -->
                              <div class="col-md-6">
                                  <label class="form-label">Vehicle Chassis Number</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-truck-pickup" style="color:#ff6600;"></i>
                                      </span>
                                      <input type="text" name="chassis_no" id="chassis_no" class="form-control">
                                  </div>
                              </div>

                              <hr class="mt-4">

                              <div class="col-12">
                                  <h5 class="mb-2">Facilities / Amenities Available</h5>
                                  <div class="row amenities-group">
                                      @foreach ($amenities as $amenity)
                                          <div class="col-md-6 mb-1">
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

                              <hr class="mt-3">

                              <div class="col-12">
                                  <h5 class="mb-2">Optional Features</h5>
                                  <div class="row optional-features-group">
                                      @foreach ($optionalFeatures as $feature)
                                          <div class="col-md-6 mb-1">
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
                                  <label class="form-label required">List all routes / circuits covered</label>
                                  <div class="input-group">
                                      <span class="input-group-text">
                                          <i class="fa-solid fa-route" style="color:#ff6600;"></i>
                                      </span>
                                      <textarea name="routes" id="routes" class="form-control" rows="3" required></textarea>
                                  </div>
                              </div>

                              <hr class="mt-4">

                              <div class="col-12">
                                  <h5 class="mb-3">Upload Documents <small class="text-muted">(Max 200 kB each)</small></h5>

                                  @php
                                      $docs = [
                                          'registration_fee_challan' => 'Registration Fee Challan',
                                          'vehicle_reg_card'         => 'Vehicle Registration Card Document',
                                          'vehicle_insurance'        => 'Vehicle Insurance',
                                          'declaration_form'         => 'Declaration Form',
                                          'aadhar_card'              => 'Aadhar Card',
                                          'pan_card'                 => 'PAN Card',
                                          'vehicle_purchase_copy'    => 'Vehicle Purchase Copy',
                                          'company_proof'            => 'Proof of Company Documents / Partnership Deed / LLP Deed',
                                      ];
                                  @endphp

                                  <div class="table-responsive">
                                      <table class="table table-bordered table-docs align-middle">
                                          <thead>
                                              <tr>
                                                  <th style="width:30%">Document Name</th>
                                                  <th style="width:30%">Upload</th>
                                                  <th style="width:25%">Preview</th>
                                                  <th style="width:15%" class="text-center">Action</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($docs as $key => $label)
                                                  <tr>
                                                      <td class="fw-semibold">
                                                          {{ $label }} <span class="text-danger">*</span>
                                                      </td>
                                                      <td>
                                                          <input type="file" name="{{ $key }}" id="{{ $key }}"
                                                                 class="form-control file-input"
                                                                 accept=".pdf,.jpg,.jpeg,.png" required>
                                                      </td>
                                                      <td>
                                                          <div id="preview_{{ $key }}"
                                                               class="file-preview preview-box text-center position-relative"
                                                               style="height:110px; overflow:hidden; cursor:pointer;">
                                                              <span class="placeholder-text">No file uploaded</span>
                                                          </div>
                                                      </td>
                                                      <td class="text-center">
                                                          <button type="button"
                                                                  class="btn btn-sm btn-danger mt-2 d-none remove-btn"
                                                                  data-target="{{ $key }}">
                                                              <i class="fa fa-trash"></i> Remove
                                                          </button>
                                                      </td>
                                                  </tr>
                                              @endforeach
                                          </tbody>
                                      </table>
                                  </div>
                              </div>

                              <!-- FILE PREVIEW MODAL -->
                              <div class="modal fade" id="previewModal" tabindex="-1">
                                  <div class="modal-dialog modal-dialog-centered modal-lg">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h5 class="modal-title">Document Preview</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                          </div>
                                          <div class="modal-body text-center" id="modalContent"></div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                          </div> {{-- .row g-3 --}}

                          <div class="mt-4 d-flex justify-content-end">
                              <button type="button" class="btn btn-primary px-4" id="previewSubmitBtn">
                                  Save & Preview
                              </button>
                          </div>

                          <!-- FORM PREVIEW MODAL -->


                      </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="formPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Your Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="formPreviewBody"
                 style="max-height: 70vh; overflow-y: auto;">
                <!-- Preview content will be injected dynamically -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmitBtn">
                    Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
{{-- PREVIEW MODAL (XL) --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ===================== FILE INPUT PREVIEW =====================
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function () {
            let file = this.files[0];
            let previewBox = document.getElementById('preview_' + this.id);
            let removeBtn  = document.querySelector(`[data-target="${this.id}"]`);

            if (!previewBox) return;

            // reset preview
            previewBox.innerHTML = '';
            previewBox.removeAttribute('data-type');
            previewBox.removeAttribute('data-src');

            if (!file) {
                previewBox.innerHTML = `<span class="placeholder-text">No file uploaded</span>`;
                if (removeBtn) removeBtn.classList.add('d-none');
                return;
            }

            // FILE SIZE CHECK (200 kB)
            if (file.size > 200 * 1024) {
                alert("File size must be less than 200 kB.");
                this.value = "";
                previewBox.innerHTML = `<span class="text-danger small">File too large (max 200 kB)</span>`;
                if (removeBtn) removeBtn.classList.add("d-none");
                return;
            }

            if (removeBtn) removeBtn.classList.remove("d-none");

            let fileType = file.type;

            if (fileType.includes("image")) {
                let img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = "100%";
                img.style.maxHeight = "100px";
                previewBox.appendChild(img);

                previewBox.dataset.type = "image";
                previewBox.dataset.src  = img.src;

            } else if (fileType.includes("pdf")) {
                previewBox.innerHTML = `
                    <i class="fa-solid fa-file-pdf fa-3x text-danger"></i>
                    <div class="small mt-1 text-truncate">${file.name}</div>
                `;
                previewBox.dataset.type = "pdf";
                previewBox.dataset.src  = URL.createObjectURL(file);

            } else {
                previewBox.innerHTML = `<span class="text-danger small">Unsupported file</span>`;
            }
        });
    });

    // ===================== POPUP PREVIEW =====================
    document.querySelectorAll('.preview-box').forEach(box => {
        box.addEventListener('click', function () {
            let type = this.dataset.type;
            let src  = this.dataset.src;

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
        btn.addEventListener('click', function () {
            let key        = this.dataset.target;
            let input      = document.getElementById(key);
            let previewBox = document.getElementById('preview_' + key);

            if (input) input.value = "";
            if (previewBox) {
                previewBox.innerHTML = `<span class="placeholder-text">No file uploaded</span>`;
                previewBox.removeAttribute("data-type");
                previewBox.removeAttribute("data-src");
            }

            this.classList.add("d-none");
        });
    });

    // ===================== FORM PREVIEW BEFORE SUBMIT =====================
    document.getElementById('previewSubmitBtn').addEventListener('click', function () {
        const form = document.getElementById('caravanForm');

        // Use native HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        let previewHTML = `<table class="table table-bordered table-striped">`;

        // ----- Text Inputs, Selects, Textareas -----
        document.querySelectorAll('#caravanForm input:not([type=file]):not([type=checkbox]):not([type=radio]), #caravanForm select, #caravanForm textarea')
            .forEach(field => {
                // Ignore hidden fields
                if (field.type === 'hidden') return;

                let wrapper = field.closest('.col-md-6, .col-12, .col-sm-6, .mb-3');
                let label   = wrapper ? wrapper.querySelector('.form-label') : null;

                if (!label) return;

                let labelText = label.innerText.trim();
                let valueText = field.value ? field.value : '--';

                previewHTML += `
                    <tr>
                        <th>${labelText}</th>
                        <td>${valueText}</td>
                    </tr>
                `;
            });

        // ----- Amenities (grouped) -----
        const amenitiesGroup = document.querySelector('.amenities-group');
        if (amenitiesGroup) {
            let selectedAmenities = [];
            amenitiesGroup.querySelectorAll('input[type=checkbox]').forEach(cb => {
                if (cb.checked) {
                    const lbl = cb.closest('.form-check')?.querySelector('label');
                    if (lbl) selectedAmenities.push(lbl.innerText.trim());
                }
            });
            previewHTML += `
                <tr>
                    <th>Facilities / Amenities Available</th>
                    <td>${selectedAmenities.length ? selectedAmenities.join(', ') : '--'}</td>
                </tr>
            `;
        }

        // ----- Optional Features (grouped) -----
        const optFeaturesGroup = document.querySelector('.optional-features-group');
        if (optFeaturesGroup) {
            let selectedFeatures = [];
            optFeaturesGroup.querySelectorAll('input[type=checkbox]').forEach(cb => {
                if (cb.checked) {
                    const lbl = cb.closest('.form-check')?.querySelector('label');
                    if (lbl) selectedFeatures.push(lbl.innerText.trim());
                }
            });
            previewHTML += `
                <tr>
                    <th>Optional Features</th>
                    <td>${selectedFeatures.length ? selectedFeatures.join(', ') : '--'}</td>
                </tr>
            `;
        }

        // ----- Uploaded Files -----
        previewHTML += `
            <tr class="table-secondary">
                <th colspan="2" class="text-center">Uploaded Documents</th>
            </tr>
        `;

        document.querySelectorAll('#caravanForm input[type=file]').forEach(fileInput => {
            let labelCell = fileInput.closest('tr')?.querySelector('td:first-child');
            let label     = labelCell ? labelCell.innerText.trim() : fileInput.name;
            let file      = fileInput.files[0];

            previewHTML += `<tr><th>${label}</th><td>`;

            if (!file) {
                previewHTML += `<span class="text-danger">Not Uploaded</span>`;
            } else {
                let fileURL = URL.createObjectURL(file);
                if (file.type.includes('image')) {
                    previewHTML += `
                        <img src="${fileURL}"
                             style="max-width:150px; max-height:150px;"
                             class="img-thumbnail mb-2"><br>
                        <strong>${file.name}</strong>
                    `;
                } else if (file.type.includes('pdf')) {
                    previewHTML += `
                        <i class="fa-solid fa-file-pdf fa-3x text-danger"></i>
                        <div class="mt-1"><strong>${file.name}</strong></div>
                        <a href="${fileURL}" target="_blank"
                           class="btn btn-sm btn-outline-primary mt-1">
                            Open PDF
                        </a>
                    `;
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
    document.getElementById('confirmSubmitBtn').addEventListener('click', function () {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
        document.getElementById('caravanForm').submit();
    });
</script>

@endpush

