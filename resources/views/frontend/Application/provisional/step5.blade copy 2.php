{{-- resources/views/frontend/Application/provisional/step4.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 5: Documents')

@push('styles')
<style>
    .form-icon {
        color: var(--brand, #ff6600);
        font-size: 1.1rem;
        margin-right: .35rem;
    }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .step {
        text-align: center;
        flex: 1;
    }
    .step-label {
        font-size: 0.9rem;
        font-weight: 600;
    }

    table th {
        font-weight: 600;
        background-color: #f8f9fa;
        vertical-align: middle !important;
    }
    table td {
        background-color: #fff;
        vertical-align: middle;
    }

    .doc-preview {
        position: relative;
        min-height: 40px;
    }

    .doc-thumb {
        max-height: 60px;
        cursor: pointer;
    }

    .doc-preview small {
        font-size: 0.75rem;
    }

    .remove-preview {
        position: absolute;
        top: -8px;
        right: -8px;
        z-index: 2;
        padding: 0.1rem 0.25rem;
        line-height: 1;
        border-radius: 999px;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header form-header">
        <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
    </div>

    {{-- Stepper / Progress --}}
    @include('frontend.Application.provisional._stepper', ['step' => $step])

    {{-- MAIN CARD --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header"
             style="background:#ff6600;
                    color:#fff;
                    padding:.75rem 1rem;
                    font-weight:700;
                    display:flex;
                    align-items:center;
                    gap:.5rem;">
            <i class="bi bi-file-earmark-arrow-up form-icon"></i>
            <span>Step 5: Upload Documents</span>
        </div>

        <div class="card-body">
            <form id="stepForm"
                  action="{{ route('provisional.wizard.save', [$step]) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf

                @php
                    $enclosures = $registration->enclosures ?? [];
                    $documents = [
                        'commencement_certificate' => [
                            'label'   => 'Commencement Certificate / Plan Sanction Letter',
                            'required'=> true,
                        ],
                        'sanctioned_plan' => [
                            'label'   => 'Copy of Sanctioned Plan of Construction',
                            'required'=> true,
                        ],
                        'proof_of_identity' => [
                            'label'   => 'Proof of Identity',
                            'required'=> true,
                        ],
                        'proof_of_address' => [
                            'label'   => 'Proof of Address',
                            'required'=> true,
                        ],
                        'land_ownership' => [
                            'label'   => 'Land Ownership Document',
                            'required'=> true,
                        ],
                        'project_report' => [
                            'label'   => 'Project Report',
                            'required'=> true,
                        ],
                        'incorporation_documents' => [
                            'label'   => 'Memorandum and Article of Association along with Certificate of Incorporation of the Company / Partnership Deed / Registration of Co-operative Society / Registration of Trust',
                            'required'=> true,
                        ],
                        'gst_registration' => [
                            'label'   => 'GST Registration',
                            'required'=> false,
                        ],
                        'special_category_proof' => [
                            'label'   => 'Proof of Special Category Application (Refer to 14.4.6 of MTP 2024)',
                            'required'=> false,
                        ],
                        'ca_certificate' => [
                            'label'   => 'CA Certificate on Project Cost including investments already made',
                            'required'=> true,
                        ],
                        'processing_fee_challan' => [
                            'label'   => 'Processing Fee Challan (₹10,000) — paid on <a href="https://www.gras.mahakosh.gov.in" target="_blank">www.gras.mahakosh.gov.in</a>',
                            'required'=> true,
                        ],
                    ];
                @endphp

                <div class="card-body">

                    {{-- Enclosures Section --}}
                    <h6 class="mb-3">Enclosures:</h6>
                    <p class="text-muted mb-3">Tick mark the necessary documents enclosed with the application form.</p>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped align-middle text-center" id="enclosureTable">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width:5%;">Select</th>
                                    <th style="width:30%;">Document Type</th>
                                    <th style="width:15%;">Doc No.</th>
                                    <th style="width:15%;">Date of Issue</th>
                                    <th style="width:20%;">Upload Document</th>
                                    <th style="width:15%;">Preview</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $key => $doc)
                                    @php
                                        // Hide incorporation_documents if enterprise_type == 1
                                        if($key === 'incorporation_documents' && ($registration->enterprise_type ?? 0) == 1) {
                                            continue;
                                        }

                                        $docData   = $enclosures[$key] ?? null;
                                        $isChecked = (bool) $docData;
                                        $filePath  = $docData['file_path'] ?? null;
                                        $fileUrl   = $filePath ? asset('storage/'.$filePath) : null;
                                        $ext       = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                                    @endphp
                                    <tr class="document-row">
                                        {{-- Checkbox --}}
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input doc-check"
                                                   data-doc="{{ $key }}"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                        </td>

                                        {{-- Label + land ownership radios --}}
                                        <td class="text-start">
                                            {!! $doc['label'] !!}
                                            @if($key === 'land_ownership')
                                                <div class="d-flex justify-content-start gap-3 mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input land-type @error('land_type') is-invalid @enderror"
                                                               type="radio"
                                                               name="land_type"
                                                               id="landOwned{{ $key }}"
                                                               value="Owned"
                                                               {{ $isChecked ? '' : 'disabled' }}
                                                               {{ old('land_type', $registration->land_type ?? '') === 'Owned' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="landOwned{{ $key }}">Owned</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input land-type @error('land_type') is-invalid @enderror"
                                                               type="radio"
                                                               name="land_type"
                                                               id="landLeased{{ $key }}"
                                                               value="Leased"
                                                               {{ $isChecked ? '' : 'disabled' }}
                                                               {{ old('land_type', $registration->land_type ?? '') === 'Leased' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="landLeased{{ $key }}">Leased</label>
                                                    </div>
                                                </div>
                                                @error('land_type')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </td>

                                        {{-- Doc No --}}
                                        <td>
                                            <input type="text"
                                                   class="form-control @error($key.'_doc_no') is-invalid @enderror doc-number"
                                                   name="{{ $key }}_doc_no"
                                                   placeholder="Enter Doc No."
                                                   value="{{ old($key.'_doc_no', $docData['doc_no'] ?? '') }}"
                                                   {{ $isChecked ? '' : 'disabled' }}>
                                            @error($key.'_doc_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Date of Issue --}}
                                        <td>
                                            <input type="date"
                                                   class="form-control @error($key.'_issue_date') is-invalid @enderror doc-date"
                                                   name="{{ $key }}_issue_date"
                                                   value="{{ old($key.'_issue_date', $docData['issue_date'] ?? '') }}"
                                                   {{ $isChecked ? '' : 'disabled' }}>
                                            @error($key.'_issue_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Upload --}}
                                        <td>
                                            <input type="file"
                                                   class="form-control doc-file @error($key.'_file') is-invalid @enderror"
                                                   name="{{ $key }}_file"
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   {{ $isChecked ? '' : 'disabled' }}>
                                            @error($key.'_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Preview + remove --}}
                                        <td class="preview-cell">
                                            <div class="doc-preview" data-doc-type="{{ $key }}">
                                                @if($fileUrl)
                                                    <button type="button"
                                                            class="btn btn-sm btn-light text-danger border-0 remove-preview"
                                                            data-doc-type="{{ $key }}"
                                                            data-file-url="{{ $fileUrl }}"
                                                            data-existing="1">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>

                                                    @if(in_array($ext, ['jpg','jpeg','png']))
                                                        <img src="{{ $fileUrl }}"
                                                             alt="Document"
                                                             class="img-thumbnail doc-thumb"
                                                             data-full="{{ $fileUrl }}">
                                                        <div><small class="text-muted">Click image to enlarge</small></div>
                                                    @elseif($ext === 'pdf')
                                                        <a href="{{ $fileUrl }}" target="_blank"
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-file-earmark-pdf"></i> View PDF
                                                        </a>
                                                    @else
                                                        <a href="{{ $fileUrl }}" target="_blank" class="small">
                                                            Open file
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Other Documents --}}
                    <div class="mb-4">
                        <h6 class="mb-3">Other Documents</h6>
                        <p class="text-muted mb-3">Add any additional documents not listed above.</p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="otherDocs">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Document No</th>
                                        <th>Issue Date</th>
                                        <th>Validity Date</th>
                                        <th>Upload</th>
                                        <th>Preview</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $otherDocuments = $registration->other_documents ?? [];
                                    @endphp

                                    @if(count($otherDocuments) > 0)
                                        @foreach($otherDocuments as $index => $doc)
                                            @php
                                                $otherPath = $doc['file_path'] ?? null;
                                                $otherUrl  = $otherPath ? asset('storage/'.$otherPath) : null;
                                                $otherExt  = $otherPath ? strtolower(pathinfo($otherPath, PATHINFO_EXTENSION)) : null;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="text"
                                                           class="form-control @error('other_doc_name.'.$index) is-invalid @enderror"
                                                           name="other_doc_name[]"
                                                           placeholder="Document name"
                                                           value="{{ old('other_doc_name.'.$index, $doc['name'] ?? '') }}">
                                                    @error('other_doc_name.'.$index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="form-control @error('other_doc_no.'.$index) is-invalid @enderror"
                                                           name="other_doc_no[]"
                                                           placeholder="Document number"
                                                           value="{{ old('other_doc_no.'.$index, $doc['doc_no'] ?? '') }}">
                                                    @error('other_doc_no.'.$index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="date"
                                                           class="form-control @error('other_issue_date.'.$index) is-invalid @enderror"
                                                           name="other_issue_date[]"
                                                           value="{{ old('other_issue_date.'.$index, $doc['issue_date'] ?? '') }}">
                                                    @error('other_issue_date.'.$index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="date"
                                                           class="form-control @error('other_validity_date.'.$index) is-invalid @enderror"
                                                           name="other_validity_date[]"
                                                           value="{{ old('other_validity_date.'.$index, $doc['validity_date'] ?? '') }}">
                                                    @error('other_validity_date.'.$index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="file"
                                                           class="form-control other-doc-file @error('other_doc_file.'.$index) is-invalid @enderror"
                                                           name="other_doc_file[]" 
                                                           accept=".pdf,.jpg,.jpeg,.png">
                                                    @error('other_doc_file.'.$index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td class="preview-cell">
                                                    <div class="doc-preview">
                                                        @if($otherUrl)
                                                            <button type="button"
                                                                    class="btn btn-sm btn-light text-danger border-0 remove-preview"
                                                                    data-existing="1">
                                                                <i class="bi bi-x-circle-fill"></i>
                                                            </button>

                                                            @if(in_array($otherExt, ['jpg','jpeg','png']))
                                                                <img src="{{ $otherUrl }}"
                                                                     alt="Other Document"
                                                                     class="img-thumbnail doc-thumb"
                                                                     data-full="{{ $otherUrl }}">
                                                                <div><small class="text-muted">Click image to enlarge</small></div>
                                                            @elseif($otherExt === 'pdf')
                                                                <a href="{{ $otherUrl }}" target="_blank"
                                                                   class="btn btn-outline-primary btn-sm">
                                                                    <i class="bi bi-file-earmark-pdf"></i> View PDF
                                                                </a>
                                                            @else
                                                                <a href="{{ $otherUrl }}" target="_blank" class="small">
                                                                    Open file
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm removeRow">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>
                                                <input type="text"
                                                       class="form-control @error('other_doc_name.0') is-invalid @enderror"
                                                       name="other_doc_name[]"
                                                       placeholder="Document name">
                                                @error('other_doc_name.0')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control @error('other_doc_no.0') is-invalid @enderror"
                                                       name="other_doc_no[]"
                                                       placeholder="Document number">
                                                @error('other_doc_no.0')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                       class="form-control @error('other_issue_date.0') is-invalid @enderror"
                                                       name="other_issue_date[]">
                                                @error('other_issue_date.0')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                       class="form-control @error('other_validity_date.0') is-invalid @enderror"
                                                       name="other_validity_date[]">
                                                @error('other_validity_date.0')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="file"
                                                       class="form-control other-doc-file @error('other_doc_file.0') is-invalid @enderror"
                                                       name="other_doc_file[]"
                                                       accept=".pdf,.jpg,.jpeg,.png">
                                                @error('other_doc_file.0')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td class="preview-cell">
                                                <div class="doc-preview">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm removeRow">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success btn-sm mt-2" id="addDocRow">
                            <i class="bi bi-plus-circle"></i> Add Document
                        </button>
                    </div>

                    {{-- Notes --}}
                    <div class="alert alert-warning mb-0">
                        <h6 class="alert-heading mb-2">
                            <i class="bi bi-exclamation-triangle"></i> Notes:
                        </h6>
                        <ul class="mb-0">
                            <li>All documents should be self-attested by the applicant.</li>
                            <li>In case of multiple NOC/Certificate/Insurance, use the "Other Documents" section.</li>
                            <li>For more than 5 other documents, provide details on an additional page.</li>
                            <li>Maximum file size: <strong>5MB</strong> per file. Allowed formats: <strong>PDF, JPG, JPEG, PNG</strong>.</li>
                        </ul>
                    </div>

                </div>{{-- /.card-body --}}

                {{-- Navigation Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('provisional.wizard.show', [$step - 1]) }}"
                       class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>

                    <button type="submit"
                            class="btn btn-primary"
                            style="background-color:#ff6600;border-color:#ff6600;font-weight:600;">
                        Save & Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Modal for image preview --}}
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="docPreviewLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="docPreviewImage" src="" alt="Preview" class="img-fluid rounded shadow-sm">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // Get CSRF token for AJAX requests
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Enable/disable document inputs based on checkbox
    $(document).on('change', '.doc-check', function() {
        const row       = $(this).closest('tr');
        const isChecked = $(this).is(':checked');

        row.find('.doc-number, .doc-date, .doc-file, .land-type').prop('disabled', !isChecked);

        // Auto-select first land type if row is enabled
        if (isChecked && row.find('.land-type').length > 0) {
            if (!$('input[name="land_type"]:checked').length) {
                row.find('.land-type').first().prop('checked', true);
            }
        }
    });

    // Add new other document row
    $('#addDocRow').on('click', function() {
        const newRow = `
            <tr>
                <td>
                    <input type="text"
                           class="form-control"
                           name="other_doc_name[]"
                           placeholder="Document name">
                </td>
                <td>
                    <input type="text"
                           class="form-control"
                           name="other_doc_no[]"
                           placeholder="Document number">
                </td>
                <td>
                    <input type="date"
                           class="form-control"
                           name="other_issue_date[]">
                </td>
                <td>
                    <input type="date"
                           class="form-control"
                           name="other_validity_date[]">
                </td>
                <td>
                    <input type="file"
                           class="form-control other-doc-file"
                           name="other_doc_file[]"
                           accept=".pdf,.jpg,.jpeg,.png">
                </td>
                <td class="preview-cell">
                    <div class="doc-preview">
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#otherDocs tbody').append(newRow);
    });

    // Remove other document row
    $(document).on('click', '.removeRow', function() {
        if ($('#otherDocs tbody tr').length > 1) {
            $(this).closest('tr').remove();
        }
    });

    // Image click → open modal
    $(document).on('click', '.doc-thumb', function() {
        const src = $(this).data('full');
        if (!src) return;
        $('#docPreviewImage').attr('src', src);
        const modal = new bootstrap.Modal(document.getElementById('docPreviewModal'));
        modal.show();
    });

    // Remove existing file via AJAX
    $(document).on('click', '.remove-preview[data-existing="1"]', function() {
        const $btn = $(this);
        const docType = $btn.data('doc-type');
        const fileUrl = $btn.data('file-url');
        const $previewDiv = $btn.closest('.doc-preview');
        const $row = $btn.closest('tr');

        // Confirm removal
        if (!confirm('Are you sure you want to remove this document? This action cannot be undone.')) {
            return;
        }

        // Show loading
        $previewDiv.html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');

        // Make AJAX request to remove file
        $.ajax({
            url: '{{ route("provisional.remove-document") }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                registration_id: '{{ $registration->id ?? 0 }}',
                doc_type: docType,
                file_url: fileUrl
            },
            success: function(response) {
                if (response.success) {
                    // Clear preview and enable file input
                    $previewDiv.empty();
                    $row.find('.doc-file').val('').prop('disabled', false);
                    $row.find('.doc-check').prop('checked', false).trigger('change');

                    // Show success message
                    showToast('Document removed successfully. Please upload a new document.');
                } else {
                    alert('Error: ' + response.message);
                    location.reload(); // Reload on error
                }
            },
            error: function(xhr) {
                alert('An error occurred while removing the document.');
                location.reload(); // Reload on error
            }
        });
    });

    // Remove new file preview (not yet saved)
    $(document).on('click', '.remove-preview[data-existing="0"]', function() {
        const $row = $(this).closest('tr');
        $row.find('.doc-file, .other-doc-file').val('');
        $row.find('.doc-preview').empty();
    });

    // Live preview for newly selected files (enclosures + other docs)
    $(document).on('change', '.doc-file, .other-doc-file', function() {
        const file = this.files[0];
        const $row = $(this).closest('tr');
        const $preview = $row.find('.doc-preview');

        $preview.empty();

        // If nothing selected, no preview
        if (!file) return;

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            $(this).val('');
            return;
        }

        const url = URL.createObjectURL(file);
        const type = file.type.toLowerCase();
        const name = file.name.toLowerCase();

        // For new files, flag will be 0 (not existing)
        $preview.append(
            '<button type="button" class="btn btn-sm btn-light text-danger border-0 remove-preview" data-existing="0">' +
                '<i class="bi bi-x-circle-fill"></i>' +
            '</button>'
        );

        if (type.startsWith('image/')) {
            const img = $('<img>')
                .attr('src', url)
                .attr('alt', 'Selected Document')
                .addClass('img-thumbnail doc-thumb')
                .css({maxHeight: '60px'})
                .attr('data-full', url);

            $preview.append(img);
            $preview.append('<div><small class="text-muted">Click image to enlarge</small></div>');

        } else if (type === 'application/pdf' || name.endsWith('.pdf')) {
            const link = $('<a>')
                .attr('href', url)
                .attr('target', '_blank')
                .addClass('btn btn-outline-primary btn-sm')
                .html('<i class="bi bi-file-earmark-pdf"></i> View PDF');

            $preview.append(link);

        } else {
            $preview.append('<small class="text-muted">Selected file: ' + file.name + '</small>');
        }
    });

    // Form validation - only require file upload
    $("#stepForm").validate({
        ignore: [],
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function(error, element) {
            if (element.is(':checkbox')) {
                error.insertAfter(element.closest('td'));
            } else if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            // Only require file if checkbox is checked
            commencement_certificate_file: {
                required: function() {
                    return $('[data-doc="commencement_certificate"]').is(':checked');
                }
            },
            sanctioned_plan_file: {
                required: function() {
                    return $('[data-doc="sanctioned_plan"]').is(':checked');
                }
            },
            proof_of_identity_file: {
                required: function() {
                    return $('[data-doc="proof_of_identity"]').is(':checked');
                }
            },
            proof_of_address_file: {
                required: function() {
                    return $('[data-doc="proof_of_address"]').is(':checked');
                }
            },
            land_ownership_file: {
                required: function() {
                    return $('[data-doc="land_ownership"]').is(':checked');
                }
            },
            project_report_file: {
                required: function() {
                    return $('[data-doc="project_report"]').is(':checked');
                }
            },
            // Incorporation documents not required if enterprise_type == 1 (already hidden)
            incorporation_documents_file: {
                required: function() {
                    return $('[data-doc="incorporation_documents"]').is(':checked');
                }
            },
            gst_registration_file: {
                required: function() {
                    return $('[data-doc="gst_registration"]').is(':checked');
                }
            },
            special_category_proof_file: {
                required: function() {
                    return $('[data-doc="special_category_proof"]').is(':checked');
                }
            },
            ca_certificate_file: {
                required: function() {
                    return $('[data-doc="ca_certificate"]').is(':checked');
                }
            },
            processing_fee_challan_file: {
                required: function() {
                    return $('[data-doc="processing_fee_challan"]').is(':checked');
                }
            }
        },
        messages: {
            commencement_certificate_file: {
                required: "Please upload this document."
            },
            sanctioned_plan_file: {
                required: "Please upload this document."
            },
            proof_of_identity_file: {
                required: "Please upload this document."
            },
            proof_of_address_file: {
                required: "Please upload this document."
            },
            land_ownership_file: {
                required: "Please upload this document."
            },
            project_report_file: {
                required: "Please upload this document."
            },
            incorporation_documents_file: {
                required: "Please upload this document."
            },
            gst_registration_file: {
                required: "Please upload this document."
            },
            special_category_proof_file: {
                required: "Please upload this document."
            },
            ca_certificate_file: {
                required: "Please upload this document."
            },
            processing_fee_challan_file: {
                required: "Please upload this document."
            }
        }
    });

    // Helper function to show toast messages
    function showToast(message) {
        // Create toast element
        const toast = $(`
            <div class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);

        // Add to container
        $('.toast-container').append(toast);

        // Initialize and show
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();

        // Remove after hide
        toast.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
});

// Add toast container to page
if ($('.toast-container').length === 0) {
    $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
}
</script>
@endpush
