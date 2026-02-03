@extends('frontend.layouts2.master')
@section('title','Hotel Registration - Step 3')

@push('styles')
<style>
  :root{ --brand:#ff6600; }
  .form-container { background:#fff; padding:1.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.05); }
  .file-preview{margin-top:10px;padding:10px;border:1px solid #dee2e6;border-radius:5px;font-size:0.85rem;}
  .file-preview img{max-height:120px;cursor:pointer;}
  .required::after{content:" *";color:red;}
</style>
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $application_form->name ?? 'Hotel Registration' }}</h1>
  </div>
  <div class="section-body">
    <div class="form-container">
      <div class="form-header mb-3">
        <h2>Application Form for the {{ $application_form->name ?? '' }}</h2>
        <p>Step 3: Documents Upload</p>
      </div>

      @include('frontend.Application.Industrial._step-indicator')

      @php
          // Helper for existing file preview (guard against redefinition)
          if (!function_exists('industrialDocPreview')) {
              function industrialDocPreview($model, $field) {
                  $path = optional($model)->{$field};
                  if (!$path) return [null,null,null];
                  $url = asset('storage/'.$path);
                  $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                  return [$url,$ext,basename($path)];
              }
          }
      @endphp

      <form id="step3Form"
            action="{{ route('industrial.wizard.step3.store', $application) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate>
        @csrf
        <input type="hidden" name="slug_id" value="{{ $application->slug_id }}">

        <div class="card p-3 mb-4">
          <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
            <h5 class="m-0">Documents Upload</h5>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">All documents must be less than 5 MB in size.</p>

            <div class="row">

              {{-- PAN Card --}}
              @php([$panUrl,$panExt,$panName] = industrialDocPreview($step4 ?? null,'pan_card_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">PAN Card (File Size 5 MB)</label>
                  <input type="file"
                         name="pan_card"
                         class="form-control file-input"
                         data-preview="pan_preview"
                         data-has-file="{{ $panUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('pan_card') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="pan_preview" class="file-preview">
                    @if($panUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $panUrl }}" target="_blank">{{ $panName }}</a>
                      </div>
                      @if(in_array($panExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $panUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Aadhaar Card --}}
              @php([$aadUrl,$aadExt,$aadName] = industrialDocPreview($step4 ?? null,'aadhaar_card_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Aadhar Card (File Size 5 MB)</label>
                  <input type="file"
                         name="aadhar_card"
                         class="form-control file-input"
                         data-preview="aadhaar_preview"
                         data-has-file="{{ $aadUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('aadhar_card') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="aadhaar_preview" class="file-preview">
                    @if($aadUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $aadUrl }}" target="_blank">{{ $aadName }}</a>
                      </div>
                      @if(in_array($aadExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $aadUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Business Entity Registration --}}
              @php([$beUrl,$beExt,$beName] = industrialDocPreview($step4 ?? null,'business_reg_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Business Entity Registration Copy (File Size 5 MB)</label>
                  <input type="file"
                         name="business_registration"
                         class="form-control file-input"
                         data-preview="business_registration_preview"
                         data-has-file="{{ $beUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('business_registration') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="business_registration_preview" class="file-preview">
                    @if($beUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $beUrl }}" target="_blank">{{ $beName }}</a>
                      </div>
                      @if(in_array($beExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $beUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- GST Registration --}}
              @php([$gstUrl,$gstExt,$gstName] = industrialDocPreview($step4 ?? null,'gst_cert_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">GST Registration Certificate Copy (File Size 5 MB)</label>
                  <input type="file"
                         name="gst_registration"
                         class="form-control file-input"
                         data-preview="gst_preview"
                         data-has-file="{{ $gstUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('gst_registration') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="gst_preview" class="file-preview">
                    @if($gstUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $gstUrl }}" target="_blank">{{ $gstName }}</a>
                      </div>
                      @if(in_array($gstExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $gstUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- FSSAI --}}
              @php([$fsUrl,$fsExt,$fsName] = industrialDocPreview($step4 ?? null,'fssai_cert_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Copy of FSSAI registration / Licensed Kitchen (File Size 5 MB)</label>
                  <input type="file"
                         name="fssai_registration"
                         class="form-control file-input"
                         data-preview="fssai_preview"
                         data-has-file="{{ $fsUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('fssai_registration') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="fssai_preview" class="file-preview">
                    @if($fsUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $fsUrl }}" target="_blank">{{ $fsName }}</a>
                      </div>
                      @if(in_array($fsExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $fsUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Building completion / permission --}}
              @php([$bUrl,$bExt,$bName] = industrialDocPreview($step4 ?? null,'building_cert_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">
                    Copy of completion of building certificate from competent authority OR Building Permission/Sanctioned Plan Copy (File Size 5 MB)
                  </label>
                  <input type="file"
                         name="building_certificate"
                         class="form-control file-input"
                         data-preview="building_certificate_preview"
                         data-has-file="{{ $bUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('building_certificate') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="building_certificate_preview" class="file-preview">
                    @if($bUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $bUrl }}" target="_blank">{{ $bName }}</a>
                      </div>
                      @if(in_array($bExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $bUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Declaration Form --}}
              @php([$decUrl,$decExt,$decName] = industrialDocPreview($step4 ?? null,'declaration_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">
                    Declaration Form (File Size 5 MB)
                    <a href="{{ asset('raj.doc') }}" target="_blank" class="text-primary ml-2" style="font-weight:600;">
                      Download Format
                    </a>
                  </label>
                  <input type="file"
                         name="declaration_form"
                         class="form-control file-input"
                         data-preview="declaration_preview"
                         data-has-file="{{ $decUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('declaration_form') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="declaration_preview" class="file-preview">
                    @if($decUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $decUrl }}" target="_blank">{{ $decName }}</a>
                      </div>
                      @if(in_array($decExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $decUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- MPCB Certificate (optional) --}}
              @php([$mpUrl,$mpExt,$mpName] = industrialDocPreview($step4 ?? null,'mpcb_cert_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label">MPCB Certificate (File Size 5 MB)</label>
                  <input type="file"
                         name="mpcb_certificate"
                         class="form-control file-input"
                         data-preview="mpcb_preview"
                         data-has-file="{{ $mpUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('mpcb_certificate') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="mpcb_preview" class="file-preview">
                    @if($mpUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $mpUrl }}" target="_blank">{{ $mpName }}</a>
                      </div>
                      @if(in_array($mpExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $mpUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Star Classified Certificate (optional) --}}
              @php([$starUrl,$starExt,$starName] = industrialDocPreview($step4 ?? null,'star_cert_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label">Star Classified Certificate (File Size 5 MB)</label>
                  <input type="file"
                         name="star_certificate"
                         class="form-control file-input"
                         data-preview="star_preview"
                         data-has-file="{{ $starUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('star_certificate') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="star_preview" class="file-preview">
                    @if($starUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $starUrl }}" target="_blank">{{ $starName }}</a>
                      </div>
                      @if(in_array($starExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $starUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Water Bill (optional) --}}
              @php([$waterUrl,$waterExt,$waterName] = industrialDocPreview($step4 ?? null,'water_bill_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label">Water Bill (File Size 5 MB)</label>
                  <input type="file"
                         name="water_bill"
                         class="form-control file-input"
                         data-preview="water_preview"
                         data-has-file="{{ $waterUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('water_bill') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="water_preview" class="file-preview">
                    @if($waterUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $waterUrl }}" target="_blank">{{ $waterName }}</a>
                      </div>
                      @if(in_array($waterExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $waterUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Light Bill (required) --}}
              @php([$lightUrl,$lightExt,$lightName] = industrialDocPreview($step4 ?? null,'light_bill_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Light Bill (File Size 5 MB)</label>
                  <input type="file"
                         name="light_bill"
                         class="form-control file-input"
                         data-preview="light_preview"
                         data-has-file="{{ $lightUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('light_bill') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="light_preview" class="file-preview">
                    @if($lightUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $lightUrl }}" target="_blank">{{ $lightName }}</a>
                      </div>
                      @if(in_array($lightExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $lightUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Fire NOC (optional) --}}
              @php([$fireUrl,$fireExt,$fireName] = industrialDocPreview($step4 ?? null,'fire_noc_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label">Fire NOC (File Size 5 MB)</label>
                  <input type="file"
                         name="fire_noc"
                         class="form-control file-input"
                         data-preview="fire_preview"
                         data-has-file="{{ $fireUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('fire_noc') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="fire_preview" class="file-preview">
                    @if($fireUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $fireUrl }}" target="_blank">{{ $fireName }}</a>
                      </div>
                      @if(in_array($fireExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $fireUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Property Tax (optional) --}}
              @php([$propUrl,$propExt,$propName] = industrialDocPreview($step4 ?? null,'property_tax_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label">Property Tax (File Size 5 MB)</label>
                  <input type="file"
                         name="property_tax"
                         class="form-control file-input"
                         data-preview="property_preview"
                         data-has-file="{{ $propUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('property_tax') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="property_preview" class="file-preview">
                    @if($propUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $propUrl }}" target="_blank">{{ $propName }}</a>
                      </div>
                      @if(in_array($propExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $propUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

              {{-- Electricity Bill (optional) --}}
              @php([$elecUrl,$elecExt,$elecName] = industrialDocPreview($step4 ?? null,'electricity_bill_path'))
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label">Electricity Bill (File Size 5 MB)</label>
                  <input type="file"
                         name="electricity_bill"
                         class="form-control file-input"
                         data-preview="electricity_preview"
                         data-has-file="{{ $elecUrl ? 1 : 0 }}"
                         accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  @error('electricity_bill') <small class="text-danger">{{ $message }}</small> @enderror
                  <div id="electricity_preview" class="file-preview">
                    @if($elecUrl)
                      <div class="mb-1">
                        <strong>Existing file:</strong>
                        <a href="{{ $elecUrl }}" target="_blank">{{ $elecName }}</a>
                      </div>
                      @if(in_array($elecExt,['jpg','jpeg','png','gif','webp']))
                        <img src="{{ $elecUrl }}" class="img-thumbnail preview-clickable">
                      @endif
                    @endif
                  </div>
                </div>
              </div>

            </div> {{-- row --}}

            <div class="d-flex justify-content-between">
              <a href="{{ route('industrial.wizard.show', [$application,'step'=>2]) }}"
                 class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Previous
              </a>

              <button type="submit" class="btn btn-primary" style="
                  background-color:#ff6600;
                  color:#fff;
                  font-weight:700;
                  border:none;
                  border-radius:8px;
                  padding:0.6rem 1.5rem;
                  cursor:pointer;">
                Save & Next <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</section>

{{-- Image preview modal --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img id="imagePreviewModalImg" src="" alt="Preview" style="width:100%;height:auto;">
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){


$("#step3Form").validate({
  ignore: [],
  errorClass: 'is-invalid',
  validClass: 'is-valid',
  errorElement: 'div',
  errorPlacement: function (error, element) {
    error.addClass('invalid-feedback');        // Bootstrap style red text
    if (element.parent('.form-group').length) {
      error.insertAfter(element);              // place error below input
    } else {
      error.insertAfter(element);
    }
  },
  highlight: function (element, errorClass, validClass) {
    $(element).addClass(errorClass).removeClass(validClass);
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass(errorClass).addClass(validClass);
  },
  rules: {
    pan_card: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    aadhar_card: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    business_registration: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    gst_registration: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    fssai_registration: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    building_certificate: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    declaration_form: {
      required: function(el){ return $(el).data('has-file') == 0; }
    },
    light_bill: {
      required: function(el){ return $(el).data('has-file') == 0; }
    }
  },
  messages: {
    pan_card: "Please upload PAN Card",
    aadhar_card: "Please upload Aadhaar Card",
    business_registration: "Please upload Business Registration Copy",
    gst_registration: "Please upload GST Certificate",
    fssai_registration: "Please upload FSSAI Registration",
    building_certificate: "Please upload Building Certificate",
    declaration_form: "Please upload Declaration Form",
    light_bill: "Please upload Light Bill"
  }
});


  // file preview + size check
  $('.file-input').on('change', function(){
    const files     = this.files;
    const previewId = $(this).data('preview');
    const $preview  = $('#'+previewId);
    if(!$preview.length) return;
    $preview.empty();

    if(!files || !files.length) return;

    const file    = files[0];
    const maxSize = 5 * 1024 * 1024;
    if(file.size > maxSize){
      Swal.fire({
        title:'File too large',
        text:'File must be less than 5 MB',
        icon:'warning'
      });
      this.value='';
      return;
    }

    const url  = URL.createObjectURL(file);
    const mime = file.type;

    $preview.append('<div class="mb-2"><strong>Selected file:</strong> '+file.name+'</div>');

    if(mime.startsWith('image/')){
      const img = $('<img class="img-thumbnail preview-clickable" style="max-height:120px;">');
      img.attr('src',url);
      $preview.append(img);
    }else if(mime === 'application/pdf'){
      $preview.append('<a target="_blank" href="'+url+'" class="btn btn-sm btn-outline-primary mt-1">View PDF</a>');
    }else{
      $preview.append('<a target="_blank" href="'+url+'" class="btn btn-sm btn-outline-secondary mt-1">View Document</a>');
    }
  });

  // image click -> modal
  $(document).on('click','.preview-clickable',function(){
    const src = $(this).attr('src');
    $('#imagePreviewModalImg').attr('src',src);
    $('#imagePreviewModal').modal('show');
  });
});
</script>
@endpush
