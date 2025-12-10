{{-- resources/views/frontend/Application/stamp_duty/wizard_step6.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Step 6: Declaration & Affidavit')

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
        <div class="card-header card-header-orange">
            <i class="fa-solid fa-file-signature"></i>
            <span>Step 6: Declaration</span>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    Please fix the errors below.
                </div>
            @endif

            <form id="step6Form"
                  method="POST"
                  action="{{ route('stamp-duty.wizard.store', ['step' => 6]) }}"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf

                <input type="hidden" name="application_form_id" value="{{ $application_form->id }}">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                {{-- Declaration --}}
                <div class="mb-1">
                    <p>
                        I/We hereby certify that the applicant has not been previously applied to Directorate of Tourism, Mumbai,
                        or any other department in Government of Maharashtra or Central Government and on the basis of that has
                        not availed any relief on payment of duty. Relief / Exemption from Stamp Duty & Registration fee have
                        started under New Tourism Policy 2024. If it is proved that entity has not started their business and
                        incentives are availed by them by supplying wrong information it will be my/our responsibility to return
                        the incentives along with the interest and to inform concerned authority of granting of exemption of stamp duty.
                    </p>
                    <p>
                        I/We hereby certify that, land required by us for the purpose of Tourism Project will be as per Government
                        Rule for commencement of business.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label required">Name & Designation(Owner/Partner/Director/Trustee)</label>
                        <input type="text"
                               name="name_designation"
                               class="form-control @error('name_designation') is-invalid @enderror"
                               value="{{ old('name_designation', $application->name_designation ?? '') }}">
                        <div class="error" id="name_designation_error">@error('name_designation') {{ $message }} @enderror</div>
                    </div>

                    @php
                    $signaturePath = $application->signature_path ?? null;
                    $signatureExt  = $signaturePath ? strtolower(pathinfo($signaturePath, PATHINFO_EXTENSION)) : null;

                    $stampPath = $application->stamp_path ?? null;
                    $stampExt  = $stampPath ? strtolower(pathinfo($stampPath, PATHINFO_EXTENSION)) : null;
                @endphp

                <div class="col-md-6">
                    <label class="form-label required">Upload Signature</label>
                    <input type="file"
                           name="signature"
                           class="form-control @error('signature') is-invalid @enderror"
                           accept="image/*,.pdf">
                    <div class="error" id="signature_error">@error('signature') {{ $message }} @enderror</div>

                    {{-- Preview (existing + live) --}}
                    <div class="mt-2" id="signature_preview">
                        @if($signaturePath)
                            @if(in_array($signatureExt, ['jpg','jpeg','png','gif','bmp','webp']))
                                <img src="{{ asset('storage/'.$signaturePath) }}"
                                     alt="Signature"
                                     class="img-thumbnail preview-image-click"
                                     style="max-height:60px;cursor:pointer;"
                                     data-full-src="{{ asset('storage/'.$signaturePath) }}">
                            @elseif($signatureExt === 'pdf')
                                <a href="{{ asset('storage/'.$signaturePath) }}" target="_blank">
                                    <i class="fa-solid fa-file-pdf" style="color:#ff6600;"></i> View signature (PDF)
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$signaturePath) }}" target="_blank">
                                    View signature
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Upload Rubber Stamp</label>
                    <input type="file"
                           name="stamp"
                           class="form-control @error('stamp') is-invalid @enderror"
                           accept="image/*,.pdf">
                    <div class="error" id="stamp_error">@error('stamp') {{ $message }} @enderror</div>

                    {{-- Preview (existing + live) --}}
                    <div class="mt-2" id="stamp_preview">
                        @if($stampPath)
                            @if(in_array($stampExt, ['jpg','jpeg','png','gif','bmp','webp']))
                                <img src="{{ asset('storage/'.$stampPath) }}"
                                     alt="Stamp"
                                     class="img-thumbnail preview-image-click"
                                     style="max-height:60px;cursor:pointer;"
                                     data-full-src="{{ asset('storage/'.$stampPath) }}">
                            @elseif($stampExt === 'pdf')
                                <a href="{{ asset('storage/'.$stampPath) }}" target="_blank">
                                    <i class="fa-solid fa-file-pdf" style="color:#ff6600;"></i> View stamp (PDF)
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$stampPath) }}" target="_blank">
                                    View stamp
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                </div>

                {{-- Affidavit
                <hr class="my-4">

                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-scroll form-icon"></i>
                    Affidavit (On stamp paper of INR 500/-)
                </h5>

                <p class="fw-bold">(To obtain Certificate for Proposed Tourism Project under Stamp Duty Act 1958)</p>

                <div class="mb-3">
                    <p>
                        I, Shri/Mrs
                        <input type="text"
                               name="aff_name"
                               class="form-control d-inline-block w-auto @error('aff_name') is-invalid @enderror"
                               style="min-width:200px;"
                               value="{{ old('aff_name', $application->aff_name ?? '') }}">
                        , Director of M/s.
                        <input type="text"
                               name="aff_company"
                               class="form-control d-inline-block w-auto @error('aff_company') is-invalid @enderror"
                               style="min-width:200px;"
                               value="{{ old('aff_company', $application->aff_company ?? '') }}">
                        , a Firm/company incorporated under LLP Act / Partnership Act / Companies Act of 1956/2013,
                        having its registered office at
                        <input type="text"
                               name="aff_registered_office"
                               class="form-control mt-2 @error('aff_registered_office') is-invalid @enderror"
                               value="{{ old('aff_registered_office', $application->aff_registered_office ?? '') }}">
                        , solemnly declare on oath that I have submitted an application along with relevant documents
                        under Tourism Policy 2024 started by Government of Maharashtra to promote tourism in the state.
                    </p>
                    <div class="error" id="aff_name_error">@error('aff_name') {{ $message }} @enderror</div>
                    <div class="error" id="aff_company_error">@error('aff_company') {{ $message }} @enderror</div>
                    <div class="error" id="aff_registered_office_error">@error('aff_registered_office') {{ $message }} @enderror</div>
                </div>

                <div class="mb-3">
                    <p>
                        I further state and undertake that as provided in section 14.4 of the Tourism Policy 2024, I am
                        willing to take all the initial effective steps to become eligible for registration as a Tourism
                        Unit and as a part of this I am applying for a certificate from Directorate of Tourism to enable me
                        to claim exemption from payment of stamp duty on registration of deed of conveyance in respect of
                        adjacent piece of land admeasuring
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="aff_land_area"
                               class="form-control d-inline-block w-auto @error('aff_land_area') is-invalid @enderror"
                               style="min-width:100px;"
                               value="{{ old('aff_land_area', $application->aff_land_area ?? '') }}">
                        sq. meters, bearing C.T.S. / Gat No.
                        <input type="text"
                               name="aff_cts"
                               class="form-control d-inline-block w-auto @error('aff_cts') is-invalid @enderror"
                               style="min-width:120px;"
                               value="{{ old('aff_cts', $application->aff_cts ?? '') }}">
                        , Village -
                        <input type="text"
                               name="aff_village"
                               class="form-control d-inline-block w-auto @error('aff_village') is-invalid @enderror"
                               style="min-width:150px;"
                               value="{{ old('aff_village', $application->aff_village ?? '') }}">
                        , Taluka -
                        <input type="text"
                               name="aff_taluka"
                               class="form-control d-inline-block w-auto @error('aff_taluka') is-invalid @enderror"
                               style="min-width:150px;"
                               value="{{ old('aff_taluka', $application->aff_taluka ?? '') }}">
                        , District -
                        <input type="text"
                               name="aff_district"
                               class="form-control d-inline-block w-auto @error('aff_district') is-invalid @enderror"
                               style="min-width:150px;"
                               value="{{ old('aff_district', $application->aff_district ?? '') }}">
                        .
                    </p>
                    <div class="error" id="aff_land_area_error">@error('aff_land_area') {{ $message }} @enderror</div>
                    <div class="error" id="aff_cts_error">@error('aff_cts') {{ $message }} @enderror</div>
                    <div class="error" id="aff_village_error">@error('aff_village') {{ $message }} @enderror</div>
                    <div class="error" id="aff_taluka_error">@error('aff_taluka') {{ $message }} @enderror</div>
                    <div class="error" id="aff_district_error">@error('aff_district') {{ $message }} @enderror</div>
                </div>

                <p>
                    I also state on oath that I shall complete the proposed tourism project in respect of the aforesaid
                    land within the stipulated period of three years, failing which the Certificate to be issued by the
                    Directorate of Tourism regarding entitlement for exemption of stamp duty shall automatically stand cancelled.
                </p>
                <p>
                    I also state on oath that in the event of exemption to pay stamp duty being granted I shall abide by
                    the terms and conditions laid down by the Government of Maharashtra in the Notification dated
                    15/10/2024 issued by the Revenue & Forest Department and I also undertake that the land so purchased
                    shall be used for developing the said "Tourism Project" and I am fully aware that failure to do so
                    shall entail action by the State Government or any other competent authority which may include refund
                    of the amount exempted and fines etc.
                </p>
                --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => 5, 'application' => $application->id ?? null]) }}"
                       class="btn"
                       style="background-color:#6c757d;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        <i class="fa-solid fa-arrow-left"></i> &nbsp; Back
                    </a>

                    {{-- Next goes to review page (controller me already logic hai) --}}
                    <button type="submit" class="btn"
                            style="background-color:#ff6600;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        Save &amp; Go to Review &nbsp;<i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
{{-- Image Preview Modal --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="imagePreviewModalImg" src="" alt="Preview" class="img-fluid">
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
        $('#step6Form').validate({
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
                name_designation:      { required:true, pattern:/^[A-Za-z\s\.]+$/ },
                signature:             { required:true },
                stamp:                 { },
                aff_name:              { required:true, pattern:/^[A-Za-z\s\.]+$/ },
                aff_company:           { required:true },
                aff_registered_office: { required:true },
                aff_land_area:         { required:true, number:true, min:0 },
                aff_cts:               { required:true },
                aff_village:           { required:true, pattern:/^[A-Za-z\s]+$/ },
                aff_taluka:            { required:true, pattern:/^[A-Za-z\s]+$/ },
                aff_district:          { required:true, pattern:/^[A-Za-z\s]+$/ },
            },
            messages: {
                name_designation: { pattern:"Only letters, spaces and dots allowed" },
                aff_name:         { pattern:"Only letters, spaces and dots allowed" },
                aff_village:      { pattern:"Only letters and spaces allowed" },
                aff_taluka:       { pattern:"Only letters and spaces allowed" },
                aff_district:     { pattern:"Only letters and spaces allowed" },
            }
        });
    });
</script>
<script>
    $(function () {

        function openImageModal(src) {
            $('#imagePreviewModalImg').attr('src', src);
            $('#imagePreviewModal').modal('show');
        }

        // generic handler for both signature & stamp
        function setupFilePreview(inputSelector, previewSelector, errorSelector) {
            $(inputSelector).on('change', function () {
                const file = this.files[0];
                const $preview = $(previewSelector);
                const $error   = $(errorSelector);

                $preview.empty();
                $error.text('');

                if (!file) return;

                // 2 MB check
                if (file.size > 2097152) {
                    $error.text('Max file size 2 MB allowed.');
                    this.value = '';
                    return;
                }

                const fileName = file.name.toLowerCase();

                // PDF → new tab link
                if (fileName.endsWith('.pdf')) {
                    const url = URL.createObjectURL(file);
                    const $icon = $('<i>', { class: 'fa-solid fa-file-pdf mr-1', style: 'color:#ff6600;' });
                    const $link = $('<a>', { href: url, target: '_blank', text: 'View PDF' });
                    $preview.append($icon).append($link);
                    return;
                }

                // Image → thumbnail + modal
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        const imgSrc = ev.target.result;
                        const $img = $('<img>', {
                            src: imgSrc,
                            alt: 'Preview',
                            class: 'img-thumbnail preview-image-click',
                            css: { maxHeight: '60px', cursor: 'pointer' },
                            'data-full-src': imgSrc
                        });
                        $preview.html($img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $error.text('Only image or PDF allowed.');
                    this.value = '';
                }
            });
        }

        // setup for signature & stamp
        setupFilePreview('input[name="signature"]', '#signature_preview', '#signature_error');
        setupFilePreview('input[name="stamp"]', '#stamp_preview', '#stamp_error');

        // any preview image click -> modal
        $(document).on('click', '.preview-image-click', function () {
            const src = $(this).data('full-src') || $(this).attr('src');
            if (src) {
                openImageModal(src);
            }
        });

    });
    </script>
@endpush
