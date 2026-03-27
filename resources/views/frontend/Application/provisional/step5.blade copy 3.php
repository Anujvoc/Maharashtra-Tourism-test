{{-- resources/views/frontend/Application/provisional/step4.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 5: Documents')

@push('styles')
<style>
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
    .remove-preview {
        position: absolute;
        top: -8px;
        right: -8px;
        z-index: 2;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header form-header">
        <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
    </div>

    @include('frontend.Application.provisional._stepper', ['step' => $step])

    <div class="card shadow-sm mb-4">
        <div class="card-header text-white fw-bold" style="background:#ff6600">
            Step 5: Upload Documents
        </div>

        <div class="card-body">
            <form id="stepForm"
                  action="{{ route('provisional.wizard.save', [$step]) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                @php
                    $enclosures = $registration->enclosures ?? [];
                    $documents = [
                        'commencement_certificate' => 'Commencement Certificate / Plan Sanction Letter',
                        'sanctioned_plan' => 'Copy of Sanctioned Plan of Construction',
                        'proof_of_identity' => 'Proof of Identity',
                        'proof_of_address' => 'Proof of Address',
                        'land_ownership' => 'Land Ownership Document',
                        'project_report' => 'Project Report',
                        'incorporation_documents' => 'Memorandum and Article of Association along with Certificate of Incorporation of the Company / Partnership Deed / Registration of Co-operative Society / Registration of Trust',
                        'gst_registration' => 'GST Registration',
                        'special_category_proof' => 'Proof of Special Category Application',
                        'ca_certificate' => 'CA Certificate on Project Cost',
                        'processing_fee_challan' => 'Processing Fee Challan (₹10,000)',
                    ];
                @endphp

                {{-- Enclosures --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th width="30%">Document Type</th>
                                <th width="15%">Doc No. (Optional)</th>
                                <th width="15%">Date of Issue (Optional)</th>
                                <th width="20%">Upload <span class="text-danger">*</span></th>
                                <th width="20%">Preview</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($documents as $key => $label)

                            @if($key === 'incorporation_documents' && ($registration->enterprise_type ?? 0) == 1)
                                @continue
                            @endif

                            @php
                                $doc = $enclosures[$key] ?? null;
                                $filePath = $doc['file_path'] ?? null;
                                $fileUrl  = $filePath ? asset('storage/'.$filePath) : null;
                                $ext = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                            @endphp

                            <tr>
                                <td class="text-start fw-semibold">{!! $label !!}</td>

                                <td>
                                    <input type="text"
                                           class="form-control"
                                           name="{{ $key }}_doc_no"
                                           value="{{ old($key.'_doc_no', $doc['doc_no'] ?? '') }}">
                                </td>

                                <td>
                                    <input type="date"
                                           class="form-control"
                                           name="{{ $key }}_issue_date"
                                           value="{{ old($key.'_issue_date', $doc['issue_date'] ?? '') }}">
                                </td>

                                <td>
                                    <input type="file"
                                           class="form-control doc-file"
                                           name="{{ $key }}_file"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required>
                                </td>

                                <td>
                                    <div class="doc-preview">
                                        <input type="hidden"
                                               name="remove_existing_enclosures[{{ $key }}]"
                                               class="remove-existing-flag"
                                               value="0">

                                        @if($fileUrl)
                                            <button type="button"
                                                    class="btn btn-sm btn-light text-danger border-0 remove-preview"
                                                    data-existing="1">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>

                                            @if(in_array($ext, ['jpg','jpeg','png']))
                                                <img src="{{ $fileUrl }}"
                                                     class="img-thumbnail doc-thumb"
                                                     data-full="{{ $fileUrl }}">
                                            @elseif($ext === 'pdf')
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-file-earmark-pdf"></i> View PDF
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

                {{-- ================== OTHER DOCUMENTS (AS IT IS) ================== --}}
                @includeIf('frontend.Application.provisional._other_documents', compact('registration'))

                {{-- Navigation --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('provisional.wizard.show', [$step - 1]) }}"
                       class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>

                    <button type="submit"
                            class="btn btn-primary"
                            style="background:#ff6600;border-color:#ff6600">
                        Save & Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>

{{-- Image Preview Modal --}}
<div class="modal fade" id="docPreviewModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="docPreviewImage" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('change', '.doc-file, .other-doc-file', function () {
    const file = this.files[0];
    const $row = $(this).closest('tr');
    const $preview = $row.find('.doc-preview');

    $preview.empty();
    if (!file) return;

    const url = URL.createObjectURL(file);

    $preview.append(`
        <button type="button"
                class="btn btn-sm btn-light text-danger border-0 remove-preview"
                data-existing="0">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    `);

    if (file.type.startsWith('image/')) {
        $preview.append(`<img src="${url}" class="img-thumbnail doc-thumb" data-full="${url}">`);
    } else {
        $preview.append(`<a href="${url}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-file-earmark-pdf"></i> View PDF</a>`);
    }
});

$(document).on('click', '.remove-preview', function () {
    const $row = $(this).closest('tr');
    $row.find('.doc-file, .other-doc-file').val('');
    $row.find('.remove-existing-flag').val(1);
    $row.find('.doc-preview').html('');
});

$(document).on('click', '.doc-thumb', function () {
    $('#docPreviewImage').attr('src', $(this).data('full'));
    new bootstrap.Modal('#docPreviewModal').show();
});
</script>
@endpush
