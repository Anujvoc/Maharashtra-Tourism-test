@extends('frontend.layouts2.master')

@section('title', 'Property Details')

@push('styles')
  @include('frontend.wizard.css')
  <style>
    .required::after{content:" *"; color:#dc3545;}
    .form-icon{margin-right:.35rem;}
    .invalid-feedback{display:block;}
    .card-wizard{border-radius:1rem;}
    .sticky-actions{gap:.5rem;}
  </style>
@endpush

@section('content')
<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the Registration of Tourist Villa</h1>
  </div>

  @include('frontend.wizard._stepper')

  {{-- NOTE: unified form id = villaRegistrationForm --}}
  <form method="POST" id="villaRegistrationForm"
        action="{{ route('wizard.property.save', $application) }}"
        enctype="multipart/form-data"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <h4 class="section-title">
      <i class="bi bi-house-check"></i>
      B) Details of the Property
    </h4>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="propertyName" class="form-label required">
          <i class="bi bi-house form-icon"></i> Name of the Property
        </label>
        <input type="text"
               class="form-control @error('property_name') is-invalid @enderror"
               id="propertyName"
               name="property_name"
               value="{{ old('property_name', optional($application->property)->property_name) }}"
               inputmode="text"
               autocomplete="organization"
               oninput="
                 this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                 this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); });
               "
               pattern="[A-Za-z\s.'-]{2,120}"
               title="Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
               maxlength="120"
               required>
        @error('property_name')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="propertyCoordinates" class="form-label required">
          <i class="bi bi-geo form-icon"></i> Geographic Co-ordinates of the Property / Google Map Link
        </label>
        <input type="text"
               class="form-control @error('geo_link') is-invalid @enderror"
               id="propertyCoordinates"
               name="geo_link"
               value="{{ old('geo_link', optional($application->property)->geo_link) }}"
               required>
        <div class="info-text text-danger">
          Example: 19.0760° N, 72.8777° E or Google Maps share link
        </div>
        @error('geo_link')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="addressProofType" class="form-label required">
          <i class="bi bi-file-text form-icon"></i> Proof of Address
        </label>
        <select class="form-control @error('address_proof_type') is-invalid @enderror"
                id="addressProofType" name="address_proof_type" required>
          <option value="" selected disabled>Select Proof Type</option>
          <option value="Latest Electricity Bill" {{ old('address_proof_type') == 'Latest Electricity Bill' ? 'selected' : '' }}>
            Latest Electricity Bill
          </option>
          <option value="Water Bill" {{ old('address_proof_type') == 'Water Bill' ? 'selected' : '' }}>
            Water Bill
          </option>
          <option value="Other" {{ old('address_proof_type') == 'Other' ? 'selected' : '' }}>
            Other
          </option>
        </select>
        @error('address_proof_type')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Address Proof (PDF/Image)</label>

        {{-- Preview area --}}
        <div id="addressProofPreview" class="mb-2"></div>

        <input type="file"
               id="address_proof"
               name="address_proof"
               class="form-control @error('address_proof') is-invalid @enderror"
               accept="image/*,.pdf">
        @error('address_proof')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-8">
        <label for="propertyAddress" class="form-label required">
          <i class="bi bi-geo-alt form-icon"></i> Complete Postal Address of the Property
        </label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="propertyAddress" name="address" rows="2" required>{{ old('address', optional($application->property)->address) }}</textarea>
        @error('address')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      @php
        $districts = DB::table('districts')
          ->where('state_id', 14)
          ->orderBy('name', 'asc')
          ->get();
      @endphp

      <div class="col-md-4">
        <label for="district_id" class="form-label required">
          <i class="bi bi-geo-alt form-icon"></i> District
        </label>

        <select class="form-control @error('district_id') is-invalid @enderror"
                id="district_id" name="district_id" required>
          <option value="" selected disabled>Select District</option>
          @foreach ($districts as $district)
            <option value="{{ $district->id }}"
              {{ old('district_id', optional($application->property)->district_id) == $district->id ? 'selected' : '' }}>
              {{ $district->name }}
            </option>
          @endforeach
        </select>

        @error('district_id')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="totalArea" class="form-label required">
          <i class="bi bi-aspect-ratio form-icon"></i> Total Area (sq.ft) of the Property
        </label>
        <input type="number"
               class="form-control @error('total_area_sqft') is-invalid @enderror"
               id="totalArea"
               name="total_area_sqft"
               value="{{ old('total_area_sqft', optional($application->property)->total_area_sqft) }}"
               min="0" required>
        @error('total_area_sqft')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="mahabookingNumber" class="form-label">
          <i class="bi bi-globe form-icon"></i> Mahabooking Portal Registration Number
        </label>
        <input type="text"
               class="form-control @error('mahabooking_reg_no') is-invalid @enderror"
               id="mahabookingNumber"
               name="mahabooking_reg_no"
               value="{{ old('mahabooking_reg_no', optional($application->property)->mahabooking_reg_no) }}">
        <div class="info-text text-info">If already registered on Mahabooking portal</div>
        @error('mahabooking_reg_no')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="is_operational" class="form-label required">
          <i class="bi bi-building-check form-icon"></i> Is the Tourism Villa already Operational?
        </label>
        <select class="form-control @error('is_operational') is-invalid @enderror"
                id="is_operational" name="is_operational" required>
          <option value="" selected disabled>Select Yes/No</option>
          <option value="1" {{ old('is_operational', optional($application->property)->is_operational) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('is_operational', optional($application->property)->is_operational) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('is_operational')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6" id="operationalDetailsContainer" style="display:none;">
        <div class="row">
          <div class="col-6">
            <label for="operationalYear" class="form-label">
              <i class="bi bi-calendar-event form-icon"></i> Since which year?
            </label>
            <input type="number"
                   class="form-control @error('operational_since') is-invalid @enderror"
                   id="operationalYear"
                   name="operational_since"
                   value="{{ old('operational_since', optional($application->property)->operational_since) }}"
                   min="1900" max="2030">
            @error('operational_since')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-6">
            <label for="guestsHosted" class="form-label">
              <i class="bi bi-people form-icon"></i> Guests hosted till March 2025?
            </label>
            <input type="number"
                   class="form-control @error('guests_till_march') is-invalid @enderror"
                   id="guestsHosted"
                   name="guests_till_march"
                   value="{{ old('guests_till_march', optional($application->property)->guests_till_march) }}"
                   min="0">
            @error('guests_till_march')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>

    {{-- Opposite-corner buttons --}}
    <div class="row mt-4">
      <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('wizard.show', [$application, 'step' => 1]) }}" class="btn btn-danger text-white">
          <i class="bi bi-arrow-left"></i> Previous
        </a>

        <button type="submit" class="btn btn-primary" style="
          background-color:#ff6600; color:#fff; font-weight:700;
          border:none; border-radius:8px; padding:.6rem 1.5rem; cursor:pointer;">
          Save & Next <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>

  </form>
</section>
@endsection

@push('scripts')
  {{-- jQuery (remove if already included in your base layout) --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  {{-- jQuery Validate --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" crossorigin="anonymous"></script>

  <script>
    /* ---------- Custom validator methods ---------- */
    $.validator.addMethod("lettersBasic", function(value, element) {
      return this.optional(element) || /^[A-Za-z\s.'-]{2,120}$/.test(value);
    }, "Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed.");

    $.validator.addMethod("coordOrUrl", function(value, element) {
      if (this.optional(element)) return true;
      const coordRegex = /^\s*-?\d{1,2}(?:\.\d+)?\s*°?\s*[NS]?\s*,\s*-?\d{1,3}(?:\.\d+)?\s*°?\s*[EW]?\s*$/i;
      const urlRegex   = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}([\/?#].*)?$/i;
      const v = (value || "").trim();
      return coordRegex.test(v) || urlRegex.test(v);
    }, "Enter valid coordinates (e.g., 19.0760° N, 72.8777° E) or a valid URL.");

    $.validator.addMethod("filesize", function (value, element, param) {
      if (this.optional(element) || !element.files || !element.files.length) return true;
      return element.files[0].size <= param;
    }, "File must be smaller.");

    /* ---------- File preview (image or pdf link) ---------- */
    function renderAddressProofPreview(input) {
      const $preview = $("#addressProofPreview");
      $preview.empty();
      if (!input.files || !input.files.length) return;

      const file = input.files[0];
      const type = (file.type || "").toLowerCase();
      const TWO_MB = 2 * 1024 * 1024;

      if (file.size > TWO_MB) {
        $preview.html('<div class="text-danger">File must be ≤ 2MB.</div>');
        return;
      }

      if (type === "application/pdf" || /\.pdf$/i.test(file.name)) {
        const url = URL.createObjectURL(file);
        $preview.append($('<a/>', { href:url, target:"_blank", rel:"noopener", text:"View uploaded PDF" }));
      } else if (type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = e => {
          $preview.append($('<img/>', {
            src: e.target.result, alt: "Preview",
            class: "img-fluid rounded border", style: "max-height:180px"
          }));
        };
        reader.readAsDataURL(file);
      } else {
        $preview.html('<div class="text-danger">Only image or PDF allowed.</div>');
      }
    }
    $("#address_proof").on("change", function(){ renderAddressProofPreview(this); });

    /* ---------- Show/Hide operational extra fields ---------- */
    function toggleOperationalDetails() {
      const val = $("#is_operational").val();
      const $year   = $("#operationalYear");
      const $guests = $("#guestsHosted");
      const isYes = (val === "1");

      if (isYes) {
        $("#operationalDetailsContainer").slideDown();
      } else {
        $("#operationalDetailsContainer").slideUp();
        // Clear values when NOT operational
        $year.val('');
        $guests.val('');
        // Remove error UI (if any)
        $year.removeClass("is-invalid").next(".invalid-feedback").remove();
        $guests.removeClass("is-invalid").next(".invalid-feedback").remove();
        // Revalidate if plugin attached
        const v = $("#villaRegistrationForm").data("validator");
        if (v) { $year.valid(); $guests.valid(); }
      }
    }
    $(document).on("change", "#is_operational", toggleOperationalDetails);
    $(document).ready(toggleOperationalDetails);

    /* ---------- jQuery Validate init ---------- */
    const validator = $("#villaRegistrationForm").validate({
      ignore: [], // validate hidden fields when depends returns true
      errorElement: "div",
      errorClass: "invalid-feedback",
      highlight: function(el){ $(el).addClass("is-invalid"); },
      unhighlight: function(el){ $(el).removeClass("is-invalid"); },
      errorPlacement: function(error, element){
        if (element.parent(".input-group").length) {
          error.insertAfter(element.parent());
        } else if (element.attr("type") === "file") {
          error.insertAfter(element);
        } else {
          error.insertAfter(element);
        }
      },
      rules: {
        property_name: { required: true, minlength: 2, maxlength: 120, lettersBasic: true },
        geo_link: { required: true, coordOrUrl: true, maxlength: 255 },
        address: { required: true, minlength: 5, maxlength: 500 },
        district_id: { required: true },                 // <— added
        address_proof_type: { required: true },
        address_proof: { extension: "jpg|jpeg|png|webp|pdf", filesize: 2 * 1024 * 1024 },
        is_operational: { required: true },
        operational_since: {
          required: { depends: function(){ return $("#is_operational").val() === "1"; } },
          number: true, min: 1900, max: 2030
        },
        guests_till_march: {
          required: { depends: function(){ return $("#is_operational").val() === "1"; } },
          number: true, min: 0
        },
        total_area_sqft: { required: true, number: true, min: 0 },
        mahabooking_reg_no: { maxlength: 120 }
      },
      messages: {
        property_name: {
          required: "Please enter the property name.",
          minlength: "Property name must be at least 2 characters.",
          maxlength: "Property name cannot exceed 120 characters."
        },
        geo_link: {
          required: "Please enter coordinates or a Google Maps link.",
          coordOrUrl: "Enter valid coordinates (e.g., 19.0760° N, 72.8777° E) or a valid URL.",
          maxlength: "Maximum 255 characters allowed."
        },
        address: {
          required: "Please enter the complete postal address.",
          minlength: "Address seems too short.",
          maxlength: "Address is too long."
        },
        district_id: { required: "Please select a district." },
        address_proof_type: { required: "Please select the proof type." },
        address_proof: {
          extension: "Only image (jpg, jpeg, png, webp) or PDF allowed.",
          filesize: "File must be 2MB or smaller."
        },
        is_operational: { required: "Please select Yes or No." },
        operational_since: {
          required: "Please enter the year since it is operational.",
          number: "Enter a valid year.",
          min: "Year cannot be before 1900.",
          max: "Year cannot be after 2030."
        },
        guests_till_march: {
          required: "Please enter number of guests hosted.",
          number: "Enter a valid number.",
          min: "Guests cannot be negative."
        },
        total_area_sqft: {
          required: "Please enter total area in sq.ft.",
          number: "Enter a valid number.",
          min: "Area cannot be negative."
        },
        mahabooking_reg_no: { maxlength: "Maximum 120 characters allowed." }
      }
    });

    /* ---------- Hard guard: block submit if invalid ---------- */
    $("#villaRegistrationForm").on("submit", function(e){

      // run validator first
      if (!$("#villaRegistrationForm").valid()) {
        e.preventDefault();
        return;
      }
      // extra guard for file size (in case of manual changes)
      const f = $("#address_proof")[0];
      if (f && f.files && f.files[0] && f.files[0].size > 2 * 1024 * 1024) {
        e.preventDefault();
        alert("Address Proof file must be 2MB or smaller.");
      }
    });
  </script>
@endpush
