@extends('frontend.layouts2.master')
@section('title', 'Accommodation')

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

  <form data-wizard method="POST" id="step3FormValidate"
        action="{{ route('wizard.accommodation.save', $application) }}"
        enctype="multipart/form-data"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label required">Total Flats/Rooms</label>
        <input name="flats_count" type="number" min="1"
               class="form-control @error('flats_count') is-invalid @enderror"
               value="{{ old('flats_count', optional($application->accommodation)->flats_count) }}"
               required>
        <div class="invalid-feedback">Enter number of flats.</div>
        @error('flats_count')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>


    @php
    // Prepare initial room types from old() or DB
    $oldTypes = old('flat_types', optional($application->accommodation)->flat_types ?? []);
    if (is_string($oldTypes)) {
        $decoded = json_decode($oldTypes, true);
        $oldTypes = json_last_error() === JSON_ERROR_NONE && is_array($decoded)
                    ? $decoded
                    : ($oldTypes ? [$oldTypes] : []);
    }
  @endphp

  <div class="col-md-9">
    <label class="form-label required">Area of each Flat/Room Types (E.g. 2BHK, 900 sqft)</label>

    {{-- Quick box (also part of flat_types[]) --}}
    <div class="input-group mb-2">
      <input id="ftype"
             name="flat_types[]"
             type="text"
             class="form-control"
             placeholder="e.g. 2BHK, 900 sqft"
             value="{{ old('flat_types.0') }}">
      <button type="button" class="btn btn-primary mx-2" id="addType">
        <i class="fas fa-plus me-1"></i> Add More
      </button>
    </div>

    {{-- Dynamic rows --}}
    <div id="typesRows" data-initial='@json(array_slice($oldTypes, 1))'></div>

    @error('flat_types')
      <div class="text-danger small">{{ $message }}</div>
    @enderror
    @error('flat_types.*')
      <div class="text-danger small">{{ $message }}</div>
    @enderror

    <div class="form-text">
      Click “Add More” to insert multiple flat/room types. Use the minus button to remove.
    </div>
  </div>

</div>
    <div class="row mb-3 mt-4">
      <div class="col-md-6">
        <label for="attachedToilet" class="form-label required">
          <i class="bi bi-droplet form-icon"></i> Does each room have attached toilet?
        </label>
        <select class="form-control @error('attached_toilet') is-invalid @enderror"
                id="attachedToilet" name="attached_toilet" required>
          <option value="" selected disabled>Select Yes/No</option>
          <option value="1" {{ old('attached_toilet', optional($application->accommodation)->attached_toilet) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('attached_toilet', optional($application->accommodation)->attached_toilet) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('attached_toilet')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="dustbins" class="form-label required">
          <i class="bi bi-trash form-icon"></i> Do the rooms have dustbins for garbage disposal?
        </label>
        <select class="form-control @error('has_dustbins') is-invalid @enderror"
                id="dustbins" name="has_dustbins" required>
          <option value="" selected disabled>Select Yes/No</option>
          <option value="1" {{ old('has_dustbins', optional($application->accommodation)->has_dustbins) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('has_dustbins', optional($application->accommodation)->has_dustbins) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('has_dustbins')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mt-2">
        <label for="roadAccess" class="form-label required">
          <i class="bi bi-signpost form-icon"></i> Is the property accessible by road?
        </label>
        <select class="form-control @error('road_access') is-invalid @enderror"
                id="roadAccess" name="road_access" required>
          <option value="" selected disabled>Select Yes/No</option>
          <option value="1" {{ old('road_access', optional($application->accommodation)->road_access) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('road_access', optional($application->accommodation)->road_access) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('road_access')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3 mt-2">
      <div class="col-md-6">
        <label for="foodProvided" class="form-label required">
          <i class="bi bi-egg-fried form-icon"></i> Can food (Breakfast/Lunch/Dinner) be provided to guests on request?
        </label>
        <select class="form-control @error('food_on_request') is-invalid @enderror"
                id="foodProvided" name="food_on_request" required>
          <option value="" selected disabled>Select Yes/No</option>
          <option value="1" {{ old('food_on_request', optional($application->accommodation)->food_on_request) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('food_on_request', optional($application->accommodation)->food_on_request) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('food_on_request')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label for="paymentOptions" class="form-label required">
          <i class="bi bi-credit-card form-icon"></i> Payment collection through cash and/or UPI?
        </label>
        <select class="form-control @error('payment_upi') is-invalid @enderror"
                id="paymentOptions" name="payment_upi" required>
          <option value="" selected disabled>Select Yes/No</option>
          <option value="1" {{ old('payment_upi', optional($application->accommodation)->payment_upi) == '1' ? 'selected' : '' }}>Yes</option>
          <option value="0" {{ old('payment_upi', optional($application->accommodation)->payment_upi) == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('payment_upi')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('wizard.show', [$application, 'step' => 2]) }}" class="btn btn-danger text-white">
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
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" crossorigin="anonymous"></script>

  <script>
    $(function () {
      /* -------------------- Dynamic Flat/Room Types -------------------- */
      const $rows = $("#typesRows");



      /* -------------------- jQuery Validate -------------------- */
      const $form = $("#step3FormValidate");

      const validator = $form.validate({
        ignore: [],
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(el){ $(el).addClass("is-invalid"); },
        unhighlight: function(el){ $(el).removeClass("is-invalid"); },
        errorPlacement: function(error, element){
          if (element.parent(".input-group").length) {
            error.insertAfter(element.parent());
          } else {
            error.insertAfter(element);
          }
        },
        rules: {
          flats_count:     { required: true, number: true, min: 1 },
          attached_toilet: { required: true },
          has_dustbins:    { required: true },
          road_access:     { required: true },
          food_on_request: { required: true },
          payment_upi:     { required: true }
        },
        messages: {
          flats_count:     { required: "Enter number of flats/rooms.", min: "Must be at least 1." },
          attached_toilet: { required: "Please select Yes/No." },
          has_dustbins:    { required: "Please select Yes/No." },
          road_access:     { required: "Please select Yes/No." },
          food_on_request: { required: "Please select Yes/No." },
          payment_upi:     { required: "Please select Yes/No." }
        }
      });

      /* -------------------- Hard guard for flat_types[] -------------------- */
      $form.on("submit", function(e){
        // run regular validation first
        if (!$form.valid()) {
          e.preventDefault();
          return;
        }

        const dynValues = $rows.find(".flat-type-input").map(function(){ return $(this).val().trim(); }).get();
        const dynNonEmpty = dynValues.filter(v => v.length > 0);
        const quickVal = $("#ftype").val().trim();

        $("#flatTypesError").remove();

        if (dynNonEmpty.length === 0) {
          if (quickVal.length > 0) {
            // promote quick box value to a submitted row
            $('<input>', { type:'hidden', name:'flat_types[]', value: quickVal }).appendTo($form);
          } else {
            e.preventDefault();
            $('<div id="flatTypesError" class="text-danger small mb-2">Please add at least one flat/room type.</div>')
              .insertBefore($rows);
            $("#ftype").focus();
          }
        }
      });
    });
  </script>

<script>
$(function () {
  const $rows = $("#typesRows");

  // Template for each extra row
  function rowTemplate(value = "") {
    return `
      <div class="input-group mb-2 type-row">
        <input type="text" name="flat_types[]" class="form-control flat-type-input"
               placeholder="e.g. 2BHK, 900 sqft"
               value="${$('<div/>').text(value).html()}">
        <button type="button" class="btn btn-danger mx-2 removeRow" title="Remove">&minus;</button>
      </div>`;
  }

  // Add a new input row
  function addRow(val = "") {
    $rows.append(rowTemplate(val));
    $rows.find(".type-row:last .flat-type-input").focus();
  }

  // Restore previous values beyond the first
  (function renderInitial() {
    const initial = $rows.data("initial");
    if (Array.isArray(initial) && initial.length) {
      initial.forEach(v => addRow(v));
    }
  })();

  // “Add More” → create new blank row
  $("#addType").on("click", function () {
    addRow("");
  });

  // Remove a specific row
  $rows.on("click", ".removeRow", function () {
    $(this).closest(".type-row").remove();
  });

  // Before submit, remove empty inputs (so backend doesn't get blanks)
  $("form").on("submit", function () {
    $('input[name="flat_types[]"]').each(function () {
      if ($(this).val().trim() === "") {
        $(this).removeAttr("name");
      }
    });
  });
});
</script>
@endpush




