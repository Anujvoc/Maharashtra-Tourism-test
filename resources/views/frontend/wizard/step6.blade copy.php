{{-- @extends('frontend.layouts2.master')
@section('title', 'Enclosures')


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

  <form data-wizard method="POST"
        action="{{ route('wizard.enclosures.save', $application) }}"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <p class="text-muted mb-3">Enclosures (documents marked * are required).</p>

    @php($docs = [
      'aadhar'=>'Aadhaar Card of the Applicant*',
      'pan'=>'PAN Card of the Applicant*',
      'business_pan'=>'Business PAN Card (if applicable)',
      'udyam'=>'Udyam Aadhaar (if applicable)',
      'business_reg'=>'Business Registration Certificate*',
      'ownership'=>'Proof of Ownership of Property*',
      'property_photos'=>'Photos of the Property (min 5)*',
      'character'=>'Character Certificate from Police Station*',
      'society_noc'=>'NOC from Society*',
      'building_perm'=>'Building Permission/Completion Certificate*',
      'gras_copy'=>'Copy of GRAS Challan*',
      'undertaking'=>'Undertaking (Duly signed)*',
      'rental_agreement'=>'Rental Agreement / Management Contract (if applicable)',
    ])

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light"><tr><th width="60">S.N.</th><th>Document</th><th width="380">Upload</th><th>Preview</th><th width="80">Action</th></tr></thead>
        <tbody>
        @foreach($docs as $key=>$label)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $label }}</td>
            <td>
              <input class="form-control doc-file" type="file" data-category="{{ $key }}" accept="image/*,.pdf">
            </td>
            <td class="preview-{{ $key }}"></td>
            <td><button class="btn btn-sm btn-outline-danger d-none del" data-category="{{ $key }}"><i class="bi bi-trash"></i></button></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <div class="sticky-actions d-flex justify-content-between mt-3">
      <a href="{{ route('wizard.show',[$application,'step'=>5]) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Previous</a>
      <a href="{{ route('wizard.show',[$application,'step'=>7]) }}" class="btn btn-primary">Save & Review <i class="bi bi-arrow-right"></i></a>
    </div>

    </section>
  </form>
@endsection

@push('scripts')
<script>
$('.doc-file').on('change', function(){
  const f = this.files[0]; if(!f) return;
  const cat = $(this).data('category');
  const fd = new FormData(); fd.append('file', f); fd.append('category', cat);
  $.ajax({url:'{{ route('wizard.upload',$application) }}', method:'POST', data:fd,
    processData:false, contentType:false, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
    .done(res=>{
      $(`.preview-${cat}`).html(`<span class="badge text-bg-light">${f.name}</span>`);
      $(`button.del[data-category="${cat}"]`).removeClass('d-none').data('id', res.id);
    });
});
$('button.del').on('click', function(e){
  e.preventDefault();
  const id = $(this).data('id'); if(!id) return;
  $.ajax({url:`{{ route('wizard.upload.destroy',[$application,'document'=>':id']) }}`.replace(':id',id),
    method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
    .done(()=>{ $(this).addClass('d-none'); $(this).closest('tr').find('[type=file]').val(''); $(this).closest('tr').find('[class^=preview-]').empty(); });
});
</script>
@endpush --}}

@extends('frontend.layouts2.master')
@section('title', 'Enclosures')

@push('styles')
  @include('frontend.wizard.css')
  <style>
    .required::after{content:" *"; color:#dc3545;}
    .form-icon{margin-right:.35rem;}
    .invalid-feedback{display:block;}
    .card-wizard{border-radius:1rem;}
    .sticky-actions{gap:.5rem;}

    /* Enhanced table styling */
    .table-responsive {
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
    }

    .table th {
      background-color: #f8f9fa;
      font-weight: 600;
      border-bottom: 2px solid #dee2e6;
      padding: 0.75rem;
    }

    .table td {
      padding: 0.75rem;
      vertical-align: middle;
    }

    .table tbody tr:nth-child(even) {
      background-color: #f8f9fa;
    }

    .table tbody tr:hover {
      background-color: rgba(0,0,0,0.02);
    }

    /* Document status indicators */
    .upload-status {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .status-badge {
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .status-uploaded {
      background-color: #d1fae5;
      color: #065f46;
    }

    .status-pending {
      background-color: #fef3c7;
      color: #92400e;
    }

    /* Preview styling */
    .preview-container {
      max-width: 200px;
    }

    .preview-image {
      max-width: 100%;
      max-height: 100px;
      border-radius: 0.25rem;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .preview-image:hover {
      transform: scale(1.05);
    }

    .preview-placeholder {
      color: #6c757d;
      font-style: italic;
    }

    /* Modal styling for image preview */
    .modal-preview-image {
      max-width: 100%;
      max-height: 80vh;
    }

    /* File input styling */
    .custom-file-input {
      position: relative;
      overflow: hidden;
      display: inline-block;
      width: 100%;
    }

    .custom-file-input input[type="file"] {
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .file-input-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem 1rem;
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 0.375rem;
      cursor: pointer;
      transition: all 0.15s ease-in-out;
    }

    .file-input-label:hover {
      background-color: #e9ecef;
    }

    .file-input-label i {
      margin-right: 0.5rem;
    }

    /* Action buttons */
    .btn-action {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
  </style>
@endpush

@section('content')
<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the Registration of Tourist Villa</h1>
  </div>

  @include('frontend.wizard._stepper')

  <form data-wizard method="POST"
        action="{{ route('wizard.enclosures.save', $application) }}"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <p class="text-muted mb-3">Enclosures (documents marked * are required).</p>

    @php($docs = [
      'aadhar'=>'Aadhaar Card of the Applicant*',
      'pan'=>'PAN Card of the Applicant*',
      'business_pan'=>'Business PAN Card (if applicable)',
      'udyam'=>'Udyam Aadhaar (if applicable)',
      'business_reg'=>'Business Registration Certificate*',
      'ownership'=>'Proof of Ownership of Property*',
      'property_photos'=>'Photos of the Property (min 5)*',
      'character'=>'Character Certificate from Police Station*',
      'society_noc'=>'NOC from Society*',
      'building_perm'=>'Building Permission/Completion Certificate*',
      'gras_copy'=>'Copy of GRAS Challan*',
      'undertaking'=>'Undertaking (Duly signed)*',
      'rental_agreement'=>'Rental Agreement / Management Contract (if applicable)',
    ])

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th width="60">S.N.</th>
            <th>Document</th>
            <th width="380">Upload</th>
            <th>Status</th>
            <th width="200">Preview</th>
            <th width="100">Action</th>
          </tr>
        </thead>
        <tbody>
        @foreach($docs as $key=>$label)
          @php
            $existingDoc = $application->documents->where('category', $key)->first();
            $isRequired = str_contains($label, '*');
          @endphp
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              <span class="{{ $isRequired ? 'required' : '' }}">{{ str_replace('*', '', $label) }}</span>
            </td>
            <td>
              <div class="custom-file-input">
                <input class="form-control doc-file" type="file" data-category="{{ $key }}" accept="image/*,.pdf" id="file-{{ $key }}">
                <label for="file-{{ $key }}" class="file-input-label">
                  <i class="bi bi-cloud-upload"></i> Choose File
                </label>
              </div>
              <small class="form-text text-muted">Max size: 20MB (PDF, JPG, PNG)</small>
            </td>
            <td>
              @if($existingDoc)
                <div class="upload-status">
                  <span class="status-badge status-uploaded">Uploaded</span>
                  <i class="bi bi-check-circle-fill text-success"></i>
                </div>
              @else
                <div class="upload-status">
                  <span class="status-badge status-pending">Pending</span>
                  <i class="bi bi-exclamation-circle-fill text-warning"></i>
                </div>
              @endif
            </td>
            <td class="preview-{{ $key }} preview-container">
              @if($existingDoc)
                @if(in_array(pathinfo($existingDoc->original_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                  <img src="{{ Storage::disk('public')->url($existingDoc->path) }}"
                       class="preview-image"
                       alt="{{ $existingDoc->original_name }}"
                       data-bs-toggle="modal"
                       data-bs-target="#previewModal"
                       data-src="{{ Storage::disk('public')->url($existingDoc->path) }}"
                       data-name="{{ $existingDoc->original_name }}">
                @else
                  <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                    <span>{{ $existingDoc->original_name }}</span>
                  </div>
                @endif
              @else
                <span class="preview-placeholder">No file uploaded</span>
              @endif
            </td>
            <td>
              @if($existingDoc)
                <button class="btn btn-sm btn-outline-danger btn-action del"
                        data-category="{{ $key }}"
                        data-id="{{ $existingDoc->id }}">
                  <i class="bi bi-trash"></i> Remove
                </button>
              @else
                <button class="btn btn-sm btn-outline-danger btn-action del d-none"
                        data-category="{{ $key }}">
                  <i class="bi bi-trash"></i> Remove
                </button>
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="previewModalLabel">Document Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img src="" class="modal-preview-image" alt="Document Preview">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a href="#" class="btn btn-primary" id="downloadPreview">Download</a>
          </div>
        </div>
      </div>
    </div>

    <div class="sticky-actions d-flex justify-content-between mt-3">
      <a href="{{ route('wizard.show',[$application,'step'=>5]) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Previous</a>
      <a href="{{ route('wizard.show',[$application,'step'=>7]) }}" class="btn btn-primary">Save & Review <i class="bi bi-arrow-right"></i></a>
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Handle file upload
  $('.doc-file').on('change', function(){
    const fileInput = $(this);
    const f = this.files[0];
    if(!f) return;

    // Validate file size (20MB)
    if (f.size > 20 * 1024 * 1024) {
      alert('File size exceeds 20MB limit. Please choose a smaller file.');
      fileInput.val('');
      return;
    }

    const cat = $(this).data('category');
    const fd = new FormData();
    fd.append('file', f);
    fd.append('category', cat);

    // Show loading state
    const statusCell = fileInput.closest('tr').find('.upload-status');
    statusCell.html('<span class="status-badge status-pending">Uploading...</span>');

    $.ajax({
      url: '{{ route('wizard.upload',$application) }}',
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .done(res => {
      // Update preview
      let previewHtml = '';
      const fileExt = f.name.split('.').pop().toLowerCase();

      if (['jpg', 'jpeg', 'png', 'webp'].includes(fileExt)) {
        previewHtml = `<img src="${res.url}" class="preview-image" alt="${f.name}"
                     data-bs-toggle="modal" data-bs-target="#previewModal"
                     data-src="${res.url}" data-name="${f.name}">`;
      } else {
        previewHtml = `<div class="d-flex align-items-center">
                      <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                      <span>${f.name}</span>
                    </div>`;
      }

      $(`.preview-${cat}`).html(previewHtml);

      // Update status
      statusCell.html(`
        <div class="upload-status">
          <span class="status-badge status-uploaded">Uploaded</span>
          <i class="bi bi-check-circle-fill text-success"></i>
        </div>
      `);

      // Show delete button
      $(`button.del[data-category="${cat}"]`).removeClass('d-none').data('id', res.id);

      // Reset file input
      fileInput.val('');
    })
    .fail(function(xhr) {
      alert('Error uploading file: ' + (xhr.responseJSON?.message || 'Unknown error'));
      statusCell.html(`
        <div class="upload-status">
          <span class="status-badge status-pending">Pending</span>
          <i class="bi bi-exclamation-circle-fill text-warning"></i>
        </div>
      `);
    });
  });

  // Handle file deletion
  $('button.del').on('click', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    if(!id) return;

    const cat = $(this).data('category');
    const row = $(this).closest('tr');

    $.ajax({
      url: `{{ route('wizard.upload.destroy',[$application,'document'=>':id']) }}`.replace(':id', id),
      method: 'DELETE',
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .done(() => {
      // Reset row
      $(this).addClass('d-none');
      row.find('[type=file]').val('');
      row.find('.preview-container').html('<span class="preview-placeholder">No file uploaded</span>');

      // Update status
      row.find('.upload-status').html(`
        <div class="upload-status">
          <span class="status-badge status-pending">Pending</span>
          <i class="bi bi-exclamation-circle-fill text-warning"></i>
        </div>
      `);
    })
    .fail(function() {
      alert('Error deleting file. Please try again.');
    });
  });

  // Preview modal functionality
  $('#previewModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const src = button.data('src');
    const name = button.data('name');
    const modal = $(this);

    modal.find('.modal-preview-image').attr('src', src);
    modal.find('#downloadPreview').attr('href', src).attr('download', name);
  });
});
</script>
@endpush
