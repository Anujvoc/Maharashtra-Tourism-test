@extends('frontend.layouts2.master')
@section('title','Hotel Registration - Step 3')

@push('styles')
<style>
  :root{ --brand:#ff6600; }
  .form-container { background:#fff; padding:1.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.05); }
  .file-preview{margin-top:10px;padding:10px;border:1px solid #dee2e6;border-radius:5px;}
  .file-preview img{max-height:120px;}
  .required::after{content:" *";color:red;}
</style>
@endpush

@section('content')
@php($p = $application->progress)

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

      @include('frontend.industrial._step-indicator', ['application'=>$application,'p'=>$p])

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
              {{-- PAN --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">PAN Card</label>
                  <input type="file" name="pan_card" class="form-control file-input"
                         data-preview="pan_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="pan_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- Aadhaar --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Aadhaar Card</label>
                  <input type="file" name="aadhaar_card" class="form-control file-input"
                         data-preview="aadhaar_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="aadhaar_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- GST --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">GST Registration Certificate</label>
                  <input type="file" name="gst_registration" class="form-control file-input"
                         data-preview="gst_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="gst_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- FSSAI --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">FSSAI Registration / Licensed Kitchen</label>
                  <input type="file" name="fssai_registration" class="form-control file-input"
                         data-preview="fssai_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="fssai_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- Light bill --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Light Bill</label>
                  <input type="file" name="light_bill" class="form-control file-input"
                         data-preview="light_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="light_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- Fire NOC --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Fire NOC</label>
                  <input type="file" name="fire_noc" class="form-control file-input"
                         data-preview="fire_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="fire_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- Property Tax --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Property Tax</label>
                  <input type="file" name="property_tax" class="form-control file-input"
                         data-preview="property_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="property_preview" class="file-preview"></div>
                </div>
              </div>

              {{-- Electricity Bill --}}
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-label required">Electricity Bill</label>
                  <input type="file" name="electricity_bill" class="form-control file-input"
                         data-preview="electricity_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                  <div id="electricity_preview" class="file-preview"></div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('industrial.wizard.show', [$application,'step'=>2]) }}"
                 class="btn btn-secondary">
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

{{-- Image preview modal same as tumhara original chaho to yahan daal sakte ho --}}
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
  $("#step3Form").validate({
    rules:{
      pan_card:{ required:true },
      aadhaar_card:{ required:true },
      gst_registration:{ required:true },
      fssai_registration:{ required:true },
      light_bill:{ required:true },
      fire_noc:{ required:true },
      property_tax:{ required:true },
      electricity_bill:{ required:true },
    }
  });

  // file preview (tumhara wala logic)
  $('.file-input').on('change', function(){
    const files = this.files;
    const previewId = $(this).data('preview');
    const $preview = $('#'+previewId);
    if(!$preview.length) return;
    $preview.empty();
    if(!files || !files.length) return;

    const file = files[0];
    const maxSize = 5 * 1024 * 1024;
    if(file.size > maxSize){
      Swal.fire({title:'File too large',text:'File must be less than 5 MB',icon:'warning'});
      this.value='';
      return;
    }
    const url = URL.createObjectURL(file);
    const mime = file.type;

    $preview.append('<div class="mb-2"><strong>Selected file:</strong> '+file.name+'</div>');
    if(mime.startsWith('image/')){
      $preview.append('<img src="'+url+'" class="img-thumbnail" style="max-height:120px;">');
    }else if(mime==='application/pdf'){
      $preview.append('<a target="_blank" href="'+url+'" class="btn btn-sm btn-outline-primary mt-1">View PDF</a>');
    }else{
      $preview.append('<div class="text-muted mt-1">Preview not available</div>');
    }
  });
});
</script>
@endpush
