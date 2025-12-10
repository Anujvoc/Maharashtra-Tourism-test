@extends('frontend.layouts2.master')
@section('title', 'Facilities')

@push('styles')
  @include('frontend.wizard.css')
  <style>
    .required::after{content:" *"; color:#dc3545;}
    .form-icon{margin-right:.35rem;}
    .invalid-feedback{display:block;}
    .card-wizard{border-radius:1rem;}
    .sticky-actions{gap:.5rem;}

    /* optional: nice grid for checkboxes */
    .checkbox-grid{
      display:grid; grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
      gap:.5rem 1.25rem;
    }
  </style>
@endpush

@section('content')
<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
  </div>

  @include('frontend.wizard._stepper')

  <form data-wizard method="POST" id="step4FormValidate"
        action="{{ route('wizard.facilities.save', $application) }}"
        enctype="multipart/form-data"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <h4 class="section-title">
      <i class="bi bi-grid-3x3-gap"></i>
      D) Common Facilities
    </h4>

@php
  $facilities = DB::table('tourismfacilities')
      ->where('is_active', 1)
      ->get();

  // CORRECTED: Properly get saved facilities
  $savedFacilities = [];
  if ($application->facilities && $application->facilities->facilities) {
      $savedFacilities = is_array($application->facilities->facilities)
          ? $application->facilities->facilities
          : json_decode($application->facilities->facilities, true);
  }

  $selected = old('facilities', $savedFacilities ?? []);

  // Debug info (remove in production)
  // dd($selected, $savedFacilities, $application->facilities);
@endphp

    <div class="mb-2">
      <div class="checkbox-grid" id="facilitiesGroup">
        @foreach($facilities as $facility)
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              id="facility_{{ $facility->id }}"
              name="facilities[]"
              value="{{ $facility->id }}"
              {{ in_array($facility->id, $selected) ? 'checked' : '' }}>
            <label class="form-check-label" for="facility_{{ $facility->id }}">
              <i class="{{ $facility->icon ?? '' }} me-1"></i> {{ $facility->name ?? '' }}
            </label>
          </div>
        @endforeach
      </div>

      @error('facilities')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
      @error('facilities.*')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
      <div id="facilities_error" class="text-danger small"></div>
    </div>

    <hr class="my-4">

    <div class="form-section">
      <h5 class="section-title">
        <i class="bi bi-cash-coin"></i>
        E) GRAS Chalan (www.gras.mahakosh.gov.in)
      </h5>

      <div class="mb-3">
        <label for="applicationFees" class="form-label required">
          <i class="bi bi-currency-rupee form-icon"></i>
          Application fees (Rs. 500/-) Paid
        </label>

        <select class="form-control @error('gras_paid') is-invalid @enderror"
                id="applicationFees" name="gras_paid" required>
          <option value="" disabled {{ old('gras_paid', optional($application->facilities)->gras_paid) === null ? 'selected' : '' }}>
            Select Yes/No
          </option>
          <option value="1" {{ old('gras_paid', optional($application->facilities)->gras_paid) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('gras_paid', optional($application->facilities)->gras_paid) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('gras_paid')
          <div class="text-danger">{{ $message }}</div>
        @enderror

        <div class="info-text text-info">
          Payment must be made through GRAS portal before submission
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 d-flex justify-content-between align-items-center">

        <div class="d-flex justify-content-start align-items-center mt-4">
            <a href="{{ route('wizard.show', [$application, 'step' => 3]) }}" class="btn btn-danger text-white">
                <i class="bi bi-arrow-left"></i> Previous
            </a>

            <a href="{{ route('wizard.show', [$application, 'step' => 4]) }}" class="btn btn-primary mx-2">
                <i class="bi bi-arrow-repeat me-1"></i> Reset
            </a>
        </div>

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
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" crossorigin="anonymous"></script>

<script>
$(function () {
  const $form = $("#step4FormValidate");

  // Custom method: at least N checkboxes in a group
  $.validator.addMethod("minChecked", function (value, element, min) {
    const name = $(element).attr('name'); // "facilities[]"
    return $(`input[name="${name}"]:checked`).length >= min;
  }, "Select at least one option.");

  $form.validate({
    ignore: [],
    errorElement: "div",
    errorClass: "invalid-feedback",
    highlight: function(el){ $(el).addClass("is-invalid"); },
    unhighlight: function(el){ $(el).removeClass("is-invalid"); },

    errorPlacement: function(error, element){
      // place errors for checkbox group under custom container
      if (element.attr("name") === "facilities[]") {
        $("#facilities_error").html(error);
      } else if (element.parent(".input-group").length) {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    },

    rules: {
      // validate the GROUP by attaching rule to one element of the group
      'facilities[]': { minChecked: 1 },
      gras_paid: { required: true }
    },

    messages: {
      'facilities[]': { minChecked: "Please select at least one facility." },
      gras_paid: "Please select Yes or No."
    }
  });

  // Optional: when any facility checkbox changes, revalidate the group
  $(document).on('change', 'input[name="facilities[]"]', function(){
    $('input[name="facilities[]"]').valid();
  });
});
</script>
@endpush
