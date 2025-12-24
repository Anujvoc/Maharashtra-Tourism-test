@extends('frontend.layouts2.master')
@section('title', 'Enclosures')

@push('styles')
  @include('frontend.wizard.css')
  <!-- Font Awesome 6 (CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-+..." crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    .required::after{content:" *"; color:#dc3545;}
    .form-icon{margin-right:.35rem;}
    .invalid-feedback{display:block;}
    .card-wizard{border-radius:1rem;}
    .sticky-actions{gap:.5rem;}

    .table-responsive{border-radius:.5rem;overflow:hidden;box-shadow:0 0 0 1px rgba(0,0,0,.1)}
    .table th{background:#f8f9fa;font-weight:600;border-bottom:2px solid #dee2e6;padding:.75rem}
    .table td{padding:.75rem;vertical-align:middle}
    .table tbody tr:nth-child(even){background:#f8f9fa}
    .table tbody tr:hover{background:rgba(0,0,0,.02)}

    .upload-status{display:inline-flex;align-items:center;gap:.5rem}
    .status-badge{padding:.25rem .5rem;border-radius:.25rem;font-size:.75rem;font-weight:500}
    .status-uploaded{background:#d1fae5;color:#065f46}
    .status-pending{background:#fef3c7;color:#92400e}

    .preview-container{max-width:220px}
    .preview-image{max-width:100%;max-height:110px;border-radius:.25rem;cursor:pointer;transition:transform .2s;border:1px solid #dee2e6}
    .preview-image:hover{transform:scale(1.05);border-color:#0d6efd}
    .preview-placeholder{color:#6c757d;font-style:italic}

    .modal-preview-image{max-width:100%;max-height:80vh;border-radius:.5rem}
    .modal-content{border-radius:.75rem}

    .photos-gallery { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
    .photo-item { position: relative; width: 80px; height: 80px; border: 1px solid #dee2e6; border-radius: 4px; overflow: hidden; }
    .photo-item img { width: 100%; height: 100%; object-fit: cover; cursor: pointer; }
    .photo-count { font-size: 0.75rem; color: #6c757d; margin-top: 4px; }
    .photo-remove { position: absolute; top: 2px; right: 2px; background: rgba(220,53,69,0.9); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; }

    .file-input-label i { margin-right: .4rem; font-size: 1rem; }
    .custom-file-input{
      position: relative;
      display: block;
      width: 100%;
    }

    .file-input-label{
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      width: 100%;
      min-height: 42px;
      padding: .55rem 1rem;
      border: 1px solid #ced4da;
      border-radius: .375rem;
      background: #f8f9fa;
      color: #212529;
      cursor: pointer;
      transition: background .15s ease, border-color .15s ease, box-shadow .15s ease;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .file-input-label:hover{ background:#e9ecef; }
    .custom-file-input:focus-within .file-input-label{
      border-color:#86b7fe;
      box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    }
    .file-input-label i{ margin-right:.25rem; font-size:1rem; }
    .file-input-label .label-text{ overflow:hidden; text-overflow:ellipsis; }

    .custom-file-input input[type="file"]{
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
      z-index: 2;
      border: 0;
      background: transparent;
      padding: 0;
      margin: 0;
    }

    .btn-action{padding:.25rem .5rem;font-size:.875rem}
    .upload-progress{width:100%;height:4px;background:#e9ecef;border-radius:2px;overflow:hidden;margin-top:.5rem}
    .upload-progress-bar{height:100%;background:#0d6efd;width:0;transition:width .3s ease}
    .pdf-tile{cursor:pointer}

    /* highlight missing rows */
    .missing-row { background: #fff5f5 !important; border-left: 4px solid #dc3545; }
    .missing-note { color: #b02a37; font-size: 0.85rem; margin-top: 4px; }
  </style>
@endpush

@section('content')
<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
  </div>

  @include('frontend.wizard._stepper')

  @php
    // load docs
    $application->loadMissing('documents');
    $missing = session('missing_docs', []);
  @endphp

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="section-title mb-0">
        <i class="bi bi-file-earmark-text"></i>
        Documents
    </h4>

    <div class="d-flex gap-2"> <!-- right button group -->
        <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn btn-danger mx-2">
          <i class="bi bi-arrow-repeat me-1"></i> Reset
        </a>
        <a href="{{ asset('frontend/Undertaking_Final.docx') }}" class="btn btn-primary" download target="_blank" rel="noopener">
          <i class="bi bi-download me-1"></i> Download Undertaking Form
        </a>
      </div>
  </div>

  {{-- Alert for missing docs (set by controller when Save & Next fails) --}}
  @if($errors->has('documents') || count($missing))
    <div class="alert alert-danger">
      <strong>Required documents missing.</strong>
      <p class="mb-1">{{ $errors->first('documents') ?? 'Please upload the required documents before proceeding.' }}</p>
      @if(count($missing))
        <small> Missing: {{ implode(', ', $missing) }}</small>
      @endif
    </div>
  @endif

  <form data-wizard method="POST"
        action="{{ route('wizard.enclosures.save', $application) }}"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

    <p class="text-muted mb-3 fw-bold">Enclosures (documents marked * are required). Photos must be ≤ 200 KB. PDFs ≤ 20 MB.</p>

    @php
      $docs = [
        'aadhar' => 'Aadhaar Card of the Applicant*',
        'pan' => 'PAN Card of the Applicant*',
        'business_pan' => 'Business PAN Card (if applicable)',
        'udyam' => 'Udyam Aadhaar (if applicable)',
        'business_reg' => 'Business Registration Certificate*',
        'ownership' => 'Proof of Ownership of Property*',
        'property_photos' => 'Photos of the Property (min 5)*',
        'character' => 'Character Certificate from Police Station*',
        'society_noc' => 'NOC from Society*',
        'building_perm' => 'Building Permission/Completion Certificate*',
        'gras_copy' => 'Copy of GRAS Challan*',
        'undertaking' => 'Undertaking (Duly signed)* ',
        'rental_agreement' => 'Rental Agreement / Management Contract (if applicable)',
      ];
    @endphp

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
        <tr>
          <th width="60">S.N.</th>
          <th>Document</th>
          <th width="380">Upload</th>
          <th>Status</th>
          <th width="220">Preview</th>
          <th width="100">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($docs as $key => $label)
          @php
            $existingDocs = $application->documents->where('category', $key);
            $isRequired = str_contains($label, '*');
            $isPropertyPhotos = $key === 'property_photos';
            // if controller indicated missing docs, highlight them
            $rowClass = in_array($key, $missing) ? 'missing-row' : '';
          @endphp
          <tr class="{{ $rowClass }}">
            <td>{{ $loop->iteration }}</td>
            <td>
              <span class="{{ $isRequired ? 'required' : '' }}">{{ str_replace('*', '', $label) }}</span>

              @if(in_array($key, $missing))
                <div class="missing-note">This document is required. Please upload to proceed.</div>
              @endif
              @if($key === 'undertaking')
    <div class="mt-1">
      <a href="{{ url('frontend/Undertaking_Final.docx') }}" download class="text-primary">
        <i class="bi bi-download me-1"></i> Download Undertaking Form
      </a>
    </div>
  @endif

              @if($isPropertyPhotos)
                <div class="photo-count">
                  @if($existingDocs->count() > 0)
                    {{ $existingDocs->count() }}/5 photos uploaded
                  @else
                    0/5 photos uploaded
                  @endif
                </div>
              @elseif($existingDocs->first())
                <div><small class="text-muted">Stored: {{ number_format(($existingDocs->first()->size ?? 0)/1024) }} KB</small></div>
              @endif
            </td>
            <td>
              <div class="custom-file-input7 mt-1">
                <input class="doc-file" type="file"
                       data-category="{{ $key }}"
                       @if($isPropertyPhotos) multiple @endif
                       accept="image/*,.pdf"
                       id="file-{{ $key }}">
                {{-- <label for="file-{{ $key }}" class="file-input-label">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </label> --}}
                <span class="label-text">
                  @if($isPropertyPhotos) Choose Photos (Max 5) @else Choose File @endif
                </span>
              </div>

              <div class="upload-progress d-none">
                <div class="upload-progress-bar"></div>
              </div>

              <small class="form-text text-muted">
                @if($isPropertyPhotos)
                  Upload 5 photos (≤ 200 KB each)
                @else
                  Photos ≤ 200 KB. PDFs ≤ 20 MB.
                @endif
              </small>
            </td>

            <td>
              @if($existingDocs->count() > 0)
                <div class="upload-status">
                  <span class="status-badge status-uploaded">
                    @if($isPropertyPhotos)
                      {{ $existingDocs->count() }}/5 Uploaded
                    @else
                      Uploaded
                    @endif
                  </span>
                  <i class="bi bi-check-circle-fill text-success"></i>
                </div>
              @else
                <div class="upload-status">
                  <span class="status-badge status-pending">
                    @if($isPropertyPhotos)
                      0/5 Pending
                    @else
                      Pending
                    @endif
                  </span>
                  <i class="bi bi-exclamation-circle-fill text-warning"></i>
                </div>
              @endif
            </td>
            <td class="preview-{{ $key }} preview-container">
                @if($existingDocs->count() > 0)
                    @if($isPropertyPhotos)
                        <div class="photos-gallery">
                            @foreach($existingDocs as $doc)
                                @php
                                    $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                    $url = asset('storage/'.$doc->path ?? '');
                                @endphp
                                @if($isImage)
                                    <div class="photo-item">
                                        <img src="{{ $url }}"
                                             class="preview-image"
                                             alt="{{ $doc->original_name }}"
                                             data-src="{{ $url }}"
                                             data-name="{{ $doc->original_name }}"
                                             onclick="openImageModal(this)">
                                        <button class="photo-remove del-photo"
                                                data-id="{{ $doc->id }}"
                                                data-category="{{ $key }}"
                                                title="Remove photo">
                                            ×
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        @php
                            $doc = $existingDocs->first();
                            $ext = strtolower(pathinfo($doc->original_name ?? '', PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                            $url = asset('storage/'.$doc->path ?? '');
                        @endphp

                        @if($isImage)
                            <img src="{{ $url }}"
                                 class="preview-image"
                                 alt="{{ $doc->original_name }}"
                                 data-src="{{ $url }}"
                                 data-name="{{ $doc->original_name }}"
                                 onclick="openImageModal(this)">
                        @else
                            <div class="pdf-tile d-flex align-items-center"
                                 data-url="{{ $url }}"
                                 data-name="{{ $doc->original_name }}">
                                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-4"></i>
                                <div>
                                    <div class="fw-medium">{{ $doc->original_name }}</div>
                                    <small class="text-muted">{{ number_format(($doc->size ?? 0)/1024) }} KB</small>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="text-muted preview-placeholder">
                        <i class="bi bi-image me-1"></i> No file uploaded
                    </div>
                @endif
            </td>

            <td>
              @if(!$isPropertyPhotos && $existingDocs->first())
                <button class="btn btn-sm btn-danger btn-action del"
                        data-category="{{ $key }}"
                        data-id="{{ $existingDocs->first()->id }}">
                  <i class="bi bi-trash"></i>
                </button>
              @else
                <button class="btn btn-sm btn-danger btn-action del d-none"
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

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-start align-items-center mt-4">
                <a href="{{ route('wizard.show', [$application, 'step' => 5]) }}" class="btn btn-danger text-white">
                    <i class="bi bi-arrow-left"></i> Previous
                </a>

                <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn btn-primary mx-2">
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

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="previewModalLabel">Document Preview</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img src="" class="modal-preview-image d-none" alt="Document Preview" id="modalImage">
          <div id="pdfPreview" class="d-none">
            <i class="bi bi-file-earmark-pdf text-danger display-1"></i>
            <h5 id="pdfFileName" class="mt-2"></h5>
            <p class="text-muted">PDF files can be downloaded but not previewed in the browser.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="#" class="btn btn-primary" id="downloadPreview">
            <i class="bi bi-download"></i> Download
          </a>
        </div>
      </div>
    </div>
  </div>




@endsection
@push('scripts')
<script>
/**
 * Modal dikhane ka common function
 * Bootstrap 4 me jQuery se chalega: $('#previewModal').modal('show')
 */
function showPreviewModal() {
  var modalEl = document.getElementById('previewModal');
  if (!modalEl) return;

  // Bootstrap 5 (agar kabhi use karo future me)
  if (window.bootstrap &&
      bootstrap.Modal &&
      typeof bootstrap.Modal.getOrCreateInstance === 'function') {

    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }
  // Bootstrap 4 / jQuery plugin (tumhaare case me ye chalega)
  else if (typeof $ === 'function' &&
           typeof $('#previewModal').modal === 'function') {

    $('#previewModal').modal('show');
  }
  // Fallback (agar kuch bhi na mila)
  else {
    modalEl.classList.add('show');
    modalEl.style.display = 'block';
    modalEl.removeAttribute('aria-hidden');
  }
}

/**
 * IMAGE PREVIEW MODAL
 */
function openImageModal(el) {
  const src  = el.getAttribute('data-src');
  const name = el.getAttribute('data-name') || 'Preview';

  $('#modalImage').attr({ src, alt: name }).removeClass('d-none');
  $('#pdfPreview').addClass('d-none');
  $('#downloadPreview').attr({ href: src, download: name });
  $('#previewModalLabel').text(name);

  showPreviewModal();
}

/**
 * PDF PREVIEW MODAL (icon + download)
 */
function openPdfModal(name, downloadUrl) {
  $('#pdfFileName').text(name);
  $('#modalImage').addClass('d-none');
  $('#pdfPreview').removeClass('d-none');
  $('#downloadPreview').attr({ href: downloadUrl, download: name });
  $('#previewModalLabel').text(name);

  showPreviewModal();
}

/**
 * Property photos ke status badge ko update karna
 */
function updatePropertyPhotosStatus(category, count) {
  const statusCell   = $(`[data-category="${category}"]`).closest('tr').find('.upload-status');
  const countElement = $(`[data-category="${category}"]`).closest('tr').find('.photo-count');

  if (count > 0) {
    statusCell.html(`
      <div class="upload-status">
        <span class="status-badge status-uploaded">${count}/5 Uploaded</span>
        <i class="bi bi-check-circle-fill text-success"></i>
      </div>
    `);
    countElement.text(`${count}/5 photos uploaded`);
  } else {
    statusCell.html(`
      <div class="upload-status">
        <span class="status-badge status-pending">0/5 Pending</span>
        <i class="bi bi-exclamation-circle-fill text-warning"></i>
      </div>
    `);
    countElement.text('0/5 photos uploaded');
  }
}

$(document).ready(function() {

  // PDF tile click => modal open
  $(document).on('click', '.pdf-tile', function () {
    openPdfModal($(this).data('name'), $(this).data('url'));
  });

  // Label me selected file ka naam dikhana
  $(document).on('change', '.doc-file', function () {
    const files = this.files;
    const label = $(this).closest('.custom-file-input').find('.label-text');

    if (files.length > 0) {
      if (files.length > 1) {
        label.text(`${files.length} files selected`);
      } else {
        label.text(files[0].name);
      }
    } else {
      const category = $(this).data('category');
      label.text(category === 'property_photos' ? 'Choose Photos (Max 5)' : 'Choose File');
    }
  });

  // Upload handler
  $(document).on('change', '.doc-file', function(){
    const fileInput = $(this);
    const files = this.files;
    if(!files || files.length === 0) return;

    const cat = fileInput.data('category');
    const isPropertyPhotos = (cat === 'property_photos');

    // property_photos: multiple, others: single
    const filesToUpload = isPropertyPhotos ? Array.from(files) : [files[0]];

    filesToUpload.forEach((file, index) => {
      const isImage = file.type.startsWith('image/');

      // size validation
      if (isImage && file.size > 200 * 1024) {
        alert(`Photo "${file.name}" must be 200 KB or less. Please compress it and try again.`);
        return;
      }
      if (!isImage && file.size > 20 * 1024 * 1024) {
        alert(`PDF "${file.name}" size exceeds 20 MB limit.`);
        return;
      }

      const fd = new FormData();
      fd.append('file', file);
      fd.append('category', cat);

      const row = fileInput.closest('tr');
      const statusCell = row.find('.upload-status');
      const progressBar = row.find('.upload-progress');
      const progressBarInner = row.find('.upload-progress-bar');

      statusCell.html('<span class="status-badge status-pending">Uploading...</span>');
      progressBar.removeClass('d-none');
      progressBarInner.css('width', '0%');

      $.ajax({
        url: '{{ route('wizard.upload',$application) }}',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        xhr: function () {
          const xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
              const percent = Math.min(99, Math.round((e.loaded / e.total) * 100));
              progressBarInner.css('width', percent + '%');
            }
          });
          return xhr;
        }
      })
      .done(res => {
        progressBarInner.css('width', '100%');

        if (isPropertyPhotos) {
          const gallery = $(`.preview-${cat} .photos-gallery`);
          if (gallery.length === 0) {
            $(`.preview-${cat}`).html(`
              <div class="photos-gallery">
                <div class="photo-item">
                  <img src="${res.url}"
                       alt="${res.file_name}"
                       class="preview-image"
                       data-src="${res.url}"
                       data-name="${res.file_name}"
                       onclick="openImageModal(this)">
                  <button class="photo-remove del-photo"
                          data-id="${res.id}"
                          data-category="${cat}"
                          title="Remove photo">
                    ×
                  </button>
                </div>
              </div>
            `);
          } else {
            gallery.append(`
              <div class="photo-item">
                <img src="${res.url}"
                     alt="${res.file_name}"
                     class="preview-image"
                     data-src="${res.url}"
                     data-name="${res.file_name}"
                     onclick="openImageModal(this)">
                <button class="photo-remove del-photo"
                        data-id="${res.id}"
                        data-category="${cat}"
                        title="Remove photo">
                  ×
                </button>
              </div>
            `);
          }

          const currentCount = $(`.preview-${cat} .photo-item`).length;
          updatePropertyPhotosStatus(cat, currentCount);

        } else {
          let previewHtml = '';
          if (res.is_image) {
            previewHtml = `
              <img src="${res.url}" class="preview-image" alt="${res.file_name}"
                   data-src="${res.url}" data-name="${res.file_name}"
                   onclick="openImageModal(this)">
            `;
          } else {
            previewHtml = `
              <div class="pdf-tile d-flex align-items-center"
                   data-url="${res.url}" data-name="${res.file_name}">
                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-4"></i>
                <div>
                  <div class="fw-medium">${res.file_name}</div>
                  <small class="text-muted">PDF Document</small>
                </div>
              </div>
            `;
          }

          $(`.preview-${cat}`).html(previewHtml);

          statusCell.html(`
            <div class="upload-status">
              <span class="status-badge status-uploaded">Uploaded</span>
              <i class="bi bi-check-circle-fill text-success"></i>
            </div>
          `);

          const deleteBtn = $(`button.del[data-category="${cat}"]`);
          deleteBtn.removeClass('d-none').data('id', res.id);
        }

        setTimeout(() => progressBar.addClass('d-none'), 400);

        // reset input + label text
        if (isPropertyPhotos && index === filesToUpload.length - 1) {
          fileInput.val('');
          fileInput.closest('.custom-file-input').find('.label-text')
                   .text('Choose Photos (Max 5)');
        } else if (!isPropertyPhotos) {
          fileInput.val('');
          fileInput.closest('.custom-file-input').find('.label-text')
                   .text('Choose File');
        }
      })
      .fail(function(xhr){
        alert('Error uploading file: ' + (xhr.responseJSON?.message || 'Unknown error'));
        statusCell.html(`
          <div class="upload-status">
            <span class="status-badge status-pending">
              ${isPropertyPhotos ? '0/5 Pending' : 'Pending'}
            </span>
            <i class="bi bi-exclamation-circle-fill text-warning"></i>
          </div>
        `);
        progressBar.addClass('d-none');
        fileInput.val('');
        fileInput.closest('.custom-file-input').find('.label-text')
                 .text(isPropertyPhotos ? 'Choose Photos (Max 5)' : 'Choose File');
      });
    });
  });

  // Delete single (non-photo) document
  $(document).on('click', 'button.del', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    if(!id) return;

    const cat = $(this).data('category');
    const row = $(this).closest('tr');
    const btn = $(this);

    if(!confirm('Are you sure you want to delete this file?')) return;

    $.ajax({
      url: `{{ route('wizard.upload.destroy',[$application,'document'=>':id']) }}`.replace(':id', id),
      method: 'DELETE',
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .done(() => {
      btn.addClass('d-none');
      row.find('[type=file]').val('');
      row.find('.preview-container').html(`
        <div class="text-muted preview-placeholder">
          <i class="bi bi-image me-1"></i> No file uploaded
        </div>
      `);
      row.find('.upload-status').html(`
        <div class="upload-status">
          <span class="status-badge status-pending">Pending</span>
          <i class="bi bi-exclamation-circle-fill text-warning"></i>
        </div>
      `);
      row.find('.label-text').text('Choose File');
    })
    .fail(() => alert('Error deleting file. Please try again.'));
  });

  // Delete property photo
  $(document).on('click', '.del-photo', function(e){
    e.preventDefault();
    e.stopPropagation();

    const id = $(this).data('id');
    const cat = $(this).data('category');
    const photoItem = $(this).closest('.photo-item');

    if(!confirm('Are you sure you want to delete this photo?')) return;

    $.ajax({
      url: `{{ route('wizard.upload.destroy',[$application,'document'=>':id']) }}`.replace(':id', id),
      method: 'DELETE',
      headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .done(() => {
      photoItem.remove();

      const currentCount = $(`.preview-${cat} .photo-item`).length;
      updatePropertyPhotosStatus(cat, currentCount);

      if (currentCount === 0) {
        $(`.preview-${cat}`).html(`
          <div class="text-muted preview-placeholder">
            <i class="bi bi-image me-1"></i> No file uploaded
          </div>
        `);
      }
    })
    .fail(() => alert('Error deleting photo. Please try again.'));
  });
});
</script>
@endpush
