{{-- resources/views/frontend/Application/stamp_duty/wizard_step5.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Step 5: Upload Documents')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root{
        --brand:#ff6600;
        --brand-dark:#e25500;
    }
    .form-icon { color: var(--brand); margin-right:.35rem; }
    .required::after {
        content:" *";
        color:#dc3545;
        margin-left:0.15rem;
        font-weight:600;
    }
    .is-valid { border-color:#28a745 !important; }
    .is-invalid { border-color:#dc3545 !important; }
    .error {
        color:#dc3545;
        font-size:0.85rem;
        margin-top:0.25rem;
    }
    .card-header-orange {
        background-color:var(--brand);
        color:#ff6600;
        padding:.75rem 1rem;
        font-weight:700;
        display:flex;
        align-items:center;
        gap:.5rem;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for Stamp Duty Exemption
        </h1>
    </div>

    @include('frontend.Application.stamp_duty._stepper', [
        'step'        => $step,
        'application' => $application,
        'progress'    => $progress,
        'done'        => $done,
        'total'       => $total,
    ])

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-orange d-flex justify-content-between align-items-center">
            <div>
                <i class="fa-solid fa-file-arrow-up"></i>
                <span>Step 5: Upload Required Documents</span>
            </div>

            <a href="{{ asset('frontend/Affidavit.docx') }}"
               class="btn btn-primary btn-sm"
               download
               target="_blank"
               rel="noopener">
                <i class="bi bi-download me-1"></i>
                Download Affidavit Format
            </a>
        </div>


        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    Please fix the errors below.
                </div>
            @endif

            <p class="mb-3">
                <strong>Note:</strong> All documents should be self-attested. Maximum file size: 2MB each. <br>
                <strong>Payment Link:</strong>
                <a href="https://www.gras.mahakosh.gov.in" target="_blank" rel="noopener noreferrer">
                    www.gras.mahakosh.gov.in
                </a>.

            </p>

            <form id="step5Form"
                  method="POST"
                  action="{{ route('stamp-duty.wizard.store', ['step' => 5]) }}"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf

                <input type="hidden" name="application_form_id" value="{{ $application_form->id }}">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width:5%;text-align:center;">Sr.</th>
                                <th style="width:45%;">Document</th>
                                <th>Upload <span class="text-danger">*</span></th>
                                <th>Preview</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $docs = [
                                    'doc_challan'           => 'Copy of challan for online processing fees of Rs.5,000/- paid on www.gras.mahakosh.gov.in',
                                    'doc_affidavit'         => 'Affidavits (as per the specified format)',
                                    'doc_registration'      => 'Registration proof for Company / Partnership firm / Co-op. Society etc.',
                                    'doc_ror'               => 'Records of Right (RoR)',
                                    'doc_land_map'          => 'Map of the land',
                                    'doc_dpr'               => 'Detailed Project Report (DPR)',
                                    'doc_agreement'         => 'Certified true copy of Draft Agreement to Sale / Letter of Allotment from Government or any authority',
                                    'doc_construction_plan' => 'Copy of proposed plan of constructions',
                                    'doc_dp_remarks'        => 'D.P. remarks from Local Planning Authority / Zone Certificate',
                                ];
                                $i = 1;
                            @endphp

                            @foreach($docs as $field => $label)
                                @php
                                    $existingPath = $application->{$field} ?? null;
                                    $ext = $existingPath ? strtolower(pathinfo($existingPath, PATHINFO_EXTENSION)) : null;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{ $label }}</td>

                                    <td>
                                        <input type="file"
                                               name="{{ $field }}"
                                               class="form-control @error($field) is-invalid @enderror"
                                               accept="image/*,.pdf"
                                               data-has-existing="{{ $existingPath ? 1 : 0 }}">
                                        <div class="error" id="{{ $field }}_error">@error($field) {{ $message }} @enderror</div>
                                    </td>

                                    {{-- Preview (existing + live) --}}
                                    <td class="text-center" id="preview_{{ $field }}">
                                        @if($existingPath)
                                            @if(in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']))
                                                <img src="{{ asset('storage/'.$existingPath) }}"
                                                     alt="Document preview"
                                                     class="img-thumbnail doc-preview-thumb"
                                                     style="max-height:60px;cursor:pointer;"
                                                     data-full-src="{{ asset('storage/'.$existingPath) }}">
                                            @elseif($ext === 'pdf')
                                                <a href="{{ asset('storage/'.$existingPath) }}" target="_blank">
                                                    <i class="fa-solid fa-file-pdf" style="color:#ff6600;"></i>
                                                    View PDF
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/'.$existingPath) }}" target="_blank">
                                                    View file
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => 4, 'application' => $application->id ?? null]) }}"
                       class="btn"
                       style="background-color:#6c757d;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        <i class="fa-solid fa-arrow-left"></i> &nbsp; Back
                    </a>

                    <button type="submit" class="btn"
                            style="background-color:#ff6600;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        Next &nbsp;<i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
{{-- Image Preview Modal --}}
<div class="modal fade" id="docPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="docPreviewImage" src="" alt="Document preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function () {

    // Custom rule: file size (2MB)
    $.validator.addMethod('filesize', function (value, element, param) {
        if (element.files.length === 0) return true;
        return element.files[0].size <= param;
    }, 'File size must be less than {0} bytes.');

    $('#step5Form').validate({
        ignore: [],
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorElement: 'div',
        errorPlacement: function (error, element) {
            const id = element.attr('name') + '_error';
            $('#' + id).html(error.text());
        },
        success: function (label, element) {
            const id = $(element).attr('name') + '_error';
            $('#' + id).html('');
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        rules: {
            doc_challan: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_affidavit: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_registration: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_ror: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_land_map: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_dpr: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_agreement: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_construction_plan: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            },
            doc_dp_remarks: {
                required: function (element) {
                    return $(element).data('has-existing') != 1;
                },
                extension: "jpg|jpeg|png|pdf",
                filesize: 2097152
            }
        },
        messages: {
            doc_challan:           { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_affidavit:         { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_registration:      { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_ror:               { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_land_map:          { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_dpr:               { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_agreement:         { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_construction_plan: { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
            doc_dp_remarks:        { extension: "Only JPG, PNG, or PDF allowed", filesize: "Max file size 2 MB" },
        }
    });

    // ------ Preview + Modal as you already have ------

    function openImageInModal(src) {
        $('#docPreviewImage').attr('src', src);
        $('#docPreviewModal').modal('show');
    }

    $(document).on('click', '.doc-preview-thumb', function () {
        const fullSrc = $(this).data('full-src') || $(this).attr('src');
        openImageInModal(fullSrc);
    });

    $('input[type="file"]').on('change', function () {
        const file = this.files[0];
        const fieldName = $(this).attr('name');
        const $preview = $('#preview_' + fieldName);
        $preview.empty();

        if (!file) return;

        if (file.size > 2097152) {
            $preview.html('<span class="text-danger">File exceeds 2 MB limit</span>');
            $(this).val('');
            return;
        }

        const fileName = file.name.toLowerCase();

        if (fileName.endsWith('.pdf')) {
            const url = URL.createObjectURL(file);
            const $icon = $('<i>', { class: 'fa-solid fa-file-pdf mr-1', style: 'color:#ff6600;' });
            const $link = $('<a>', { href: url, target: '_blank', text: 'View PDF' });
            $preview.append($icon).append($link);
            return;
        }

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                const imgSrc = ev.target.result;
                const $img = $('<img>', {
                    src: imgSrc,
                    alt: 'Preview',
                    class: 'img-thumbnail',
                    css: { maxHeight: '60px', cursor: 'pointer' }
                });
                $img.on('click', function () {
                    openImageInModal(imgSrc);
                });
                $preview.html($img);
            };
            reader.readAsDataURL(file);
        } else {
            $preview.html('<span class="text-danger">Invalid file type</span>');
            $(this).val('');
        }
    });
});
</script>
@endpush
