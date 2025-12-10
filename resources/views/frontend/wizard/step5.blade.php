@extends('frontend.layouts2.master')

@section('title', 'Photo & Signature')
@push('styles')
  @include('frontend.wizard.css')
  <style>
    .required::after{content:" *"; color:#dc3545;}
    .form-icon{margin-right:.35rem;}
    .invalid-feedback{display:block;}
    .card-wizard{border-radius:1rem;}
    .sticky-actions{gap:.5rem;}

    .image-preview-container { margin-top: 10px; }
    .image-preview { max-height: 150px; border: 1px solid #ddd; border-radius: 5px; padding: 5px; }
    .file-input-wrapper { position: relative; }
    .existing-file { background-color: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 10px; border: 1px solid #dee2e6; }
    .file-size-info { font-size: 0.875rem; color: #6c757d; margin-top: 5px; display:block; }
    .file-error { color: #dc3545; font-size: 0.875rem; margin-top: 5px; display: none; }
    .file-success { color: #198754; font-size: 0.875rem; margin-top: 5px; display: none; }
    .spinner { animation: spin 1s linear infinite; display: inline-block; }
    @keyframes spin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }
  </style>
@endpush

@section('content')
<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
  </div>

  @include('frontend.wizard._stepper')

  <form data-wizard method="POST" id="step5FormValidate"
        action="{{ route('wizard.photos.save', $application) }}"
        enctype="multipart/form-data"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <h4 class="section-title">
      <i class="bi bi-camera"></i>
      Photo & Signature
    </h4>

    <div class="row g-3">
      <!-- Applicant Photo -->
      <div class="col-md-6">
        <label class="form-label required" for="applicant_image">Upload Applicant Photo (JPG/PNG, max 200 KB)</label>
        <input type="file"
               name="applicant_image"
               class="form-control @error('applicant_image') is-invalid @enderror"
               id="applicant_image"
               accept="image/jpeg,image/png">

        <div class="file-size-info" id="applicant_image_size_info"></div>
        <div class="file-error" id="applicant_image_error"></div>
        <div class="file-success" id="applicant_image_success"></div>

        @error('applicant_image')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(optional($application->photos)->applicant_image)
          <div class="existing-file mt-2">
            <strong>Current Photo:</strong>
            <div class="mt-1">
              <img src="{{asset('storage/'.$application->photos->applicant_image ?? '') }}"
                   class="img-thumbnail image-preview" style="height:120px">
            </div>
            <input type="hidden" name="existing_applicant_image" value="{{ $application->photos->applicant_image }}">
          </div>
        @endif

        <div class="image-preview-container" id="applicant_image_preview" style="display:none;">
          <strong>New Photo Preview:</strong>
          <div class="mt-1">
            <img id="applicant_image_preview_img" class="img-thumbnail image-preview" style="height:120px" alt="Photo Preview">
          </div>
        </div>
      </div>

      <!-- Applicant Signature -->
      <div class="col-md-6">
        <label class="form-label required" for="applicant_signature">Upload Applicant Signature (JPG/PNG, max 50 KB)</label>
        <input type="file"
               name="applicant_signature"
               class="form-control @error('applicant_signature') is-invalid @enderror"
               id="applicant_signature"
               accept="image/jpeg,image/png">

        <div class="file-size-info" id="applicant_signature_size_info"></div>
        <div class="file-error" id="applicant_signature_error"></div>
        <div class="file-success" id="applicant_signature_success"></div>

        @error('applicant_signature')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror




        @if(optional($application->photos)->applicant_signature)

          <div class="existing-file mt-2">
            <strong>Current Signature:</strong>
            <div class="mt-1">
              <img src="{{ asset('storage/'.$application->photos->applicant_signature) ?? '' }}"
                   class="img-thumbnail image-preview" style="height:80px" alt="Signature">
            </div>
            <input type="hidden" name="existing_applicant_signature" value="{{ $application->photos->applicant_signature }}">
          </div>
        @endif

        <div class="image-preview-container" id="applicant_signature_preview" style="display:none;">
          <strong>New Signature Preview:</strong>
          <div class="mt-1">
            <img id="applicant_signature_preview_img" class="img-thumbnail image-preview" style="height:80px" alt="Signature Preview">
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 d-flex justify-content-between align-items-center">


        <div class="d-flex justify-content-start align-items-center mt-4">
            <a href="{{ route('wizard.show', [$application, 'step' => 4]) }}" class="btn btn-danger text-white">
                <i class="bi bi-arrow-left"></i> Previous
            </a>

            <a href="{{ route('wizard.show', [$application, 'step' => 5]) }}" class="btn btn-primary mx-2">
                <i class="bi bi-arrow-repeat me-1"></i> Reset
            </a>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn" style="
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
  const $form = $("#step5FormValidate");
  const MAX_PHOTO_SIZE = 200 * 1024; // 200KB
  const MAX_SIGNATURE_SIZE = 50 * 1024; // 50KB

  // Format file size
  function formatFileSize(bytes) {
    if (!bytes) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  // --------- Preview ----------
  function handleFilePreview(inputEl, $container, $img, fileType) {
    const file = inputEl.files && inputEl.files[0];
    const $sizeInfo = fileType === 'photo' ? $('#applicant_image_size_info') : $('#applicant_signature_size_info');
    const $error = fileType === 'photo' ? $('#applicant_image_error') : $('#applicant_signature_error');
    const $success = fileType === 'photo' ? $('#applicant_image_success') : $('#applicant_signature_success');

    $error.hide(); $success.hide();

    if (!file) {
      $container.hide();
      $sizeInfo.text('');
      return;
    }

    $sizeInfo.text('Selected: ' + formatFileSize(file.size));

    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
      $error.text('❌ Only JPG, JPEG, or PNG files are allowed').show();
      inputEl.value = '';
      $container.hide();
      return;
    }

    const maxSize = fileType === 'photo' ? MAX_PHOTO_SIZE : MAX_SIGNATURE_SIZE;
    if (file.size > maxSize) {
      const maxSizeText = fileType === 'photo' ? '200KB' : '50KB';
      $error.text('❌ File size too large! Maximum ' + maxSizeText + ' allowed').show();
      inputEl.value = '';
      $container.hide();
      return;
    }

    // show preview
    const reader = new FileReader();
    reader.onload = function (e) {
      $img.attr('src', e.target.result);
      $container.show();
      $success.text('✓ File is valid').show();
    };
    reader.readAsDataURL(file);
  }

  $('#applicant_image').on('change', function(){
    handleFilePreview(this, $('#applicant_image_preview'), $('#applicant_image_preview_img'), 'photo');
    $(this).valid();
  });

  $('#applicant_signature').on('change', function(){
    handleFilePreview(this, $('#applicant_signature_preview'), $('#applicant_signature_preview_img'), 'signature');
    $(this).valid();
  });

  // --------- jQuery Validate custom rules ----------
  $.validator.addMethod('filesize', function (value, element, maxBytes) {
    if (this.optional(element)) return true;
    if (!element.files || !element.files.length) return true;
    return element.files[0].size <= maxBytes;
  }, 'File size is too large.');

  $.validator.addMethod('filetype', function (value, element) {
    if (this.optional(element)) return true;
    if (!element.files || !element.files.length) return true;
    const valid = ['image/jpeg', 'image/jpg', 'image/png'];
    return valid.includes(element.files[0].type);
  }, 'Only JPG, JPEG, or PNG files are allowed.');

  // Server-rendered flags (safe)
  const hasExistingImage = {!! json_encode(optional($application->photos)->applicant_image ? true : false) !!};
  const hasExistingSign  = {!! json_encode(optional($application->photos)->applicant_signature ? true : false) !!};

  $form.validate({
    ignore: [],
    errorElement: "div",
    errorClass: "invalid-feedback",
    highlight: function(el) { $(el).addClass("is-invalid"); },
    unhighlight: function(el) { $(el).removeClass("is-invalid"); },

    errorPlacement: function(error, element){
      // Place errors for file inputs near the file-size-info block if present
      if (element.attr('name') === 'applicant_image' || element.attr('name') === 'applicant_signature') {
        const $sizeInfo = element.nextAll('.file-size-info').first();
        if ($sizeInfo.length) {
          error.insertAfter($sizeInfo);
          return;
        }
      }

      if (element.parent(".input-group").length) {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    },

    rules: {
      applicant_image: {
        required: !hasExistingImage,
        filetype: true,
        filesize: MAX_PHOTO_SIZE
      },
      applicant_signature: {
        required: !hasExistingSign,
        filetype: true,
        filesize: MAX_SIGNATURE_SIZE
      }
    },

    messages: {
      applicant_image: {
        required: "Please upload applicant photo.",
        filetype: "Only JPG, JPEG, or PNG files are allowed.",
        filesize: "Photo must be 200 KB or smaller."
      },
      applicant_signature: {
        required: "Please upload applicant signature.",
        filetype: "Only JPG, JPEG, or PNG files are allowed.",
        filesize: "Signature must be 50 KB or smaller."
      }
    },

    submitHandler: function(form) {
      const $btn = $('#submitBtn');
      const originalHtml = $btn.html();

      // loading state
      $btn.prop('disabled', true).html('<span class="spinner">⟳</span> Processing...');

      // extra safety checks (redundant but friendly)
      let isValid = true;
      let errMsg = '';

      const ai = document.getElementById('applicant_image');
      if (ai && ai.files && ai.files.length) {
        const f = ai.files[0];
        if (!['image/jpeg','image/jpg','image/png'].includes(f.type)) { isValid = false; errMsg = 'Invalid photo type.'; }
        if (f.size > MAX_PHOTO_SIZE) { isValid = false; errMsg = 'Photo size must be ≤ 200KB.'; }
      } else if (!hasExistingImage) { isValid = false; errMsg = 'Please upload applicant photo.'; }

      const asig = document.getElementById('applicant_signature');
      if (isValid) {
        if (asig && asig.files && asig.files.length) {
          const f = asig.files[0];
          if (!['image/jpeg','image/jpg','image/png'].includes(f.type)) { isValid = false; errMsg = 'Invalid signature type.'; }
          if (f.size > MAX_SIGNATURE_SIZE) { isValid = false; errMsg = 'Signature size must be ≤ 50KB.'; }
        } else if (!hasExistingSign) { isValid = false; errMsg = 'Please upload applicant signature.'; }
      }

      if (!isValid) {
        alert(errMsg);
        $btn.prop('disabled', false).html(originalHtml);
        return false;
      }

      // submit
      form.submit();
    },

    invalidHandler: function(event, validator) {
      const errors = validator.numberOfInvalids();
      if (errors) {
        // focus first invalid element
        if (validator.errorList.length) {
          try { $(validator.errorList[0].element).focus(); } catch(e){}
        }
      }
    }
  });

  // Live size-check: avoid letting user keep too-large file selected
  $('input[type="file"]').on('change', function() {
    const file = this.files && this.files[0];
    if (!file) return;
    const max = this.id === 'applicant_image' ? MAX_PHOTO_SIZE : MAX_SIGNATURE_SIZE;
    if (file.size > max) {
      const txt = this.id === 'applicant_image' ? '200KB' : '50KB';
      alert(`File too large. Maximum ${txt}.`);
      this.value = '';
      // hide preview if any
      if (this.id === 'applicant_image') { $('#applicant_image_preview').hide(); $('#applicant_image_size_info').text(''); }
      if (this.id === 'applicant_signature') { $('#applicant_signature_preview').hide(); $('#applicant_signature_size_info').text(''); }
    }
  });

});
</script>
@endpush
