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

    .file-preview { background:#f8f9fa; padding:.5rem; text-align:center; }
    .file-preview img { max-height:180px; width:auto; display:block; margin:0 auto; object-fit:contain; }
    .upload-area { cursor:pointer; border:2px dashed #e9ecef; padding:1rem; border-radius:.5rem; text-align:center; }
    .info-text{ font-size:.85rem; }
  </style>
@endpush

@section('content')
@php

  $property = optional($application->property);
  $isOperational = old('is_operational', $property->is_operational ?? null);
  $showOperational = ($isOperational !== null) ? ($isOperational == '1' || $isOperational === 1 || $isOperational === true) : false;

  // existing address proof url if stored
  $existingAddress = $property->address_proof ?? null;
//   $addressUrl = ($existingAddress && Illuminate\Support\Facades\Storage::disk('public')->exists($existingAddress)) ? Illuminate\Support\Facades\Storage::disk('public')->url($existingAddress) : null;
  $addressUrl  = $existingAddress ? asset('storage/'.$existingAddress) : null;
  // districts loaded earlier in your original blade; keep same query
  $districts = DB::table('districts')->where('state_id', 14)->orderBy('name','asc')->get();


  $words = explode(' ', $application_form->name);
    $lastTwo = implode(' ', array_slice($words, -2));

@endphp

<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
  </div>

  @include('frontend.wizard._stepper')

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
               value="{{ old('property_name', $property->property_name) }}"
               inputmode="text"
               autocomplete="organization"
               oninput="this.value = this.value.replace(/[^A-Za-z\s.'-]/g, ''); this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase(); });"
               pattern="[A-Za-z\s.'-]{2,120}"
               maxlength="120"
               required>
        @error('property_name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label for="propertyCoordinates" class="form-label required">
          <i class="bi bi-geo form-icon"></i> Geographic Co-ordinates of the Property / Google Map Link
        </label>
        <input type="text"
               class="form-control @error('geo_link') is-invalid @enderror"
               id="propertyCoordinates"
               name="geo_link"
               value="{{ old('geo_link', $property->geo_link) }}"
               required>
        <div class="info-text text-info">Example: 19.0760° N, 72.8777° E or Google Maps share link</div>
        @error('geo_link') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="addressProofType" class="form-label required">
          <i class="bi bi-file-text form-icon"></i> Proof of Address
        </label>
        <select class="form-control @error('address_proof_type') is-invalid @enderror"
                id="addressProofType" name="address_proof_type" required>
          <option value="" disabled {{ old('address_proof_type', $property->address_proof_type) ? '' : 'selected' }}>Select Proof Type</option>
          <option value="Latest Electricity Bill" {{ old('address_proof_type', $property->address_proof_type) == 'Latest Electricity Bill' ? 'selected' : '' }}>Latest Electricity Bill</option>
          <option value="Water Bill" {{ old('address_proof_type', $property->address_proof_type) == 'Water Bill' ? 'selected' : '' }}>Water Bill</option>
          <option value="Other" {{ old('address_proof_type', $property->address_proof_type) == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('address_proof_type') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Address Proof (PDF/Image)</label>

        {{-- existing preview (edit) --}}
        @if($addressUrl)
          <div id="existingAddressPreview" class="file-preview mb-2">
            @if(Illuminate\Support\Str::endsWith(strtolower($existingAddress), ['.jpg','.jpeg','.png','.webp']))
              <a href="{{ $addressUrl }}" target="_blank"><img src="{{ $addressUrl }}" alt="address proof" /></a>
            @else
              <a href="{{ $addressUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Open existing PDF
              </a>
            @endif
            <div class="small text-muted mt-1">{{ $existingAddress }}</div>
          </div>
        @endif

        <div id="addressProofPreview" class="mb-2"></div>

        <input type="file"
               id="address_proof"
               name="address_proof"
               class="form-control @error('address_proof') is-invalid @enderror"
               accept="image/*,.pdf,.webp">
        @error('address_proof') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-8">
        <label for="propertyAddress" class="form-label required">
          <i class="bi bi-geo-alt form-icon"></i> Complete Postal Address of the Property
        </label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="propertyAddress" name="address" rows="2" required>{{ old('address', $property->address) }}</textarea>
        @error('address') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-4">
        <label for="district_id" class="form-label required">
          <i class="bi bi-geo-alt form-icon"></i> District
        </label>

        <select class="form-control @error('district_id') is-invalid @enderror"
                id="district_id" name="district_id" required>
          <option value="" disabled>Select District</option>
          @foreach ($districts as $district)
            <option value="{{ $district->id }}" {{ old('district_id', $property->district_id) == $district->id ? 'selected' : '' }}>
              {{ $district->name }}
            </option>
          @endforeach
        </select>

        @error('district_id') <div class="text-danger">{{ $message }}</div> @enderror
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
               value="{{ old('total_area_sqft', $property->total_area_sqft) }}"
               min="0" required>
        @error('total_area_sqft') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label for="mahabookingNumber" class="form-label">
          <i class="bi bi-globe form-icon"></i> Mahabooking Portal Registration Number
        </label>
        <input type="text"
               class="form-control @error('mahabooking_reg_no') is-invalid @enderror"
               id="mahabookingNumber"
               name="mahabooking_reg_no"
               value="{{ old('mahabooking_reg_no', $property->mahabooking_reg_no) }}">
        <div class="info-text text-info">If already registered on Mahabooking portal</div>
        @error('mahabooking_reg_no') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="is_operational" class="form-label required">
          <i class="bi bi-building-check form-icon"></i> Is the {{ $lastTwo ?? ''}} already Operational?
        </label>
        <select class="form-control @error('is_operational') is-invalid @enderror"
                id="is_operational" name="is_operational" required>
          <option value="" disabled {{ old('is_operational', $property->is_operational) ? '' : 'selected' }}>Select Yes/No</option>
          <option value="1" {{ old('is_operational', $property->is_operational) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('is_operational', $property->is_operational) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('is_operational') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6" id="operationalDetailsContainer" style="display: {{ $showOperational ? 'block' : 'none' }};">
        <div class="row">
          <div class="col-6">
            <label for="operationalYear" class="form-label">
              <i class="bi bi-calendar-event form-icon"></i> Since which year?
            </label>
            <input type="number"
                   class="form-control @error('operational_since') is-invalid @enderror"
                   id="operationalYear"
                   name="operational_since"
                   value="{{ old('operational_since', $property->operational_since) }}"
                   min="1900" max="2030">
            @error('operational_since') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
          <div class="col-6">
            <label for="guestsHosted" class="form-label">
              <i class="bi bi-people form-icon"></i> Guests hosted till March {{ now()->year }}?
            </label>
            <input type="number"
                   class="form-control @error('guests_till_march') is-invalid @enderror"
                   id="guestsHosted"
                   name="guests_till_march"
                   value="{{ old('guests_till_march', $property->guests_till_march) }}"
                   min="0">
            @error('guests_till_march') <div class="text-danger">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 d-flex justify-content-between align-items-center">

        <div class="d-flex justify-content-start align-items-center mt-4">
            <a href="{{ route('wizard.show', [$application, 'step' => 1]) }}" class="btn btn-danger text-white">
                <i class="bi bi-arrow-left"></i> Previous
            </a>

            <a href="{{ route('wizard.show', [$application, 'step' => 2]) }}" class="btn btn-primary mx-2">
                <i class="bi bi-arrow-repeat me-1"></i> Reset
            </a>
        </div>

        <button type="submit" class="btn btn-primary" style="background-color:#ff6600; color:#fff; font-weight:700; border:none; border-radius:8px; padding:.6rem 1.5rem; cursor:pointer;">
          Save & Next <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>
  </form>
</section>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" crossorigin="anonymous"></script>

  <script>
    /* ---------- Custom validator methods ---------- */
    $.validator.addMethod("lettersBasic", function(value, element) {
      return this.optional(element) || /^[A-Za-z\s.'-]{2,120}$/.test(value);
    }, "Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed.");

    $.validator.addMethod("coordOrUrl", function(value, element) {
      if (this.optional(element)) return true;
      const coordRegex = /^\s*-?\d{1,2}(?:\.\d+)?\s*°?\s*[NS]?\s*,\s*-?\d{1,3}(?:\.\d+)?\s*°?\s*[EW]?\s*$/i;
      const urlRegex   = /^(https?:\/\/)?([^\s\/?#]+\.)+[^\s]{2,}([\/?#].*)?$/i;
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
      const MAX = 2 * 1024 * 1024; // 2MB

      if (file.size > MAX) {
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
      const isYes = (val === "1");
      const $container = $("#operationalDetailsContainer");

      if (isYes) {
        $container.slideDown();
      } else {
        // hide and clear values
        $container.slideUp(function(){
          $("#operationalYear").val('');
          $("#guestsHosted").val('');
          $("#operationalYear").removeClass("is-invalid").next(".invalid-feedback").remove();
          $("#guestsHosted").removeClass("is-invalid").next(".invalid-feedback").remove();
        });
      }
    }

    $(document).on("change", "#is_operational", toggleOperationalDetails);
    $(document).ready(function(){
      toggleOperationalDetails(); // initial state (edit or add)
    });

    /* ---------- jQuery Validate init ---------- */
    $.validator.addMethod("acceptExt", function(value, element, param) {
      if (element.files.length === 0) return true;
      const fileName = element.files[0].name.toLowerCase();
      const exts = param.split(',');
      return exts.some(ext => fileName.endsWith(ext.trim()));
    }, "Invalid file type");

    const validator = $("#villaRegistrationForm").validate({
      ignore: [],
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
        address: { required: true, minlength: 5, maxlength: 1000 },
        district_id: { required: true },
        address_proof_type: { required: true },
        address_proof: { acceptExt: ".pdf,.jpg,.jpeg,.png,.webp", filesize: 2 * 1024 * 1024 },
        is_operational: { required: true },
        operational_since: { required: function(){ return $("#is_operational").val() === "1"; }, number: true, min: 1900, max: 2030 },
        guests_till_march: { required: function(){ return $("#is_operational").val() === "1"; }, number: true, min: 0 },
        total_area_sqft: { required: true, number: true, min: 0 },
        mahabooking_reg_no: { maxlength: 120 }
      },
      messages: {
        property_name: { required: "Please enter the property name.", minlength: "Property name must be at least 2 characters.", maxlength: "Property name cannot exceed 120 characters." },
        geo_link: { required: "Please enter coordinates or a Google Maps link.", coordOrUrl: "Enter valid coordinates or a valid URL.", maxlength: "Maximum 255 characters allowed." },
        address: { required: "Please enter the complete postal address.", minlength: "Address seems too short.", maxlength: "Address is too long." },
        district_id: { required: "Please select a district." },
        address_proof_type: { required: "Please select the proof type." },
        address_proof: { acceptExt: "Only image (jpg, jpeg, png, webp) or PDF allowed.", filesize: "File must be 2MB or smaller." },
        is_operational: { required: "Please select Yes or No." },
        operational_since: { required: "Please enter the year since operational.", number: "Enter a valid year.", min: "Year cannot be before 1900.", max: "Year cannot be after 2030." },
        guests_till_march: { required: "Please enter number of guests hosted.", number: "Enter a valid number.", min: "Guests cannot be negative." },
        total_area_sqft: { required: "Please enter total area in sq.ft.", number: "Enter a valid number.", min: "Area cannot be negative." }
      }
    });

    // Clear validation message on change for specific elements
    $('#operationalYear, #guestsHosted').on('input change', function(){
      $(this).valid();
    });

    // final submit guard
    $("#villaRegistrationForm").on("submit", function(e){
      if (!$(this).valid()) { e.preventDefault(); return; }

      const f = $("#address_proof")[0];
      if (f && f.files && f.files[0] && f.files[0].size > 2 * 1024 * 1024) {
        e.preventDefault();
        alert("Address Proof file must be 2MB or smaller.");
      }
    });
  </script>
@endpush
