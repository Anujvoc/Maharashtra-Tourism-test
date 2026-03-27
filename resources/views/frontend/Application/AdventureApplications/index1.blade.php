@extends('frontend.layouts2.master')
@section('title', 'Adventure Tourism form')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .info-box { padding: 13px 16px; display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; }
    .info-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 16px; }
    .cert-card { border: 2px solid #ff6600; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; gap: 14px; background: #fff; transition: border-color .2s; }
    .cert-card:hover { border-color: #e05500; }
    .apply-btn { display: inline-flex; align-items: center; gap: 8px; background: #ff6600; color: #fff !important; border-radius: 8px; padding: 9px 22px; font-size: 13px; font-weight: 600; text-decoration: none !important; width: fit-content; }
    .apply-btn:hover { background: #e05500; }
    .cert-badge { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; margin-bottom: 5px; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for {{ $application_form->name ?? 'Adventure Tourism Certificate Registration' }}
        </h1>
        <a href="{{ url()->previous() }}"
           class="text-white fw-bold d-inline-flex align-items-center no-underline"
           style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
            <i class="bi bi-arrow-left me-2 mx-2"></i> Back
        </a>
    </div>
</section>

<div class="section-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border-top: 3px solid #ff6600; border-radius: 12px; overflow: hidden;">
                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Notice Box --}}
                    <div class="info-box" style="background: #fff8f0; border-left: 4px solid #ff6600;">
                        <div class="info-icon" style="background: #ffe0cc;">
                            <i class="fa-solid fa-triangle-exclamation" style="color: #cc4400;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="font-size: 13px; font-weight: 600; color: #cc4400;">Notice</p>
                            <p class="mb-1" style="font-size: 13px; color: #333; line-height: 1.6;">
                                In case you do not find the required Sub Activity for Land/Air/Water Activity, kindly apply yourself through the
                                <a href="#" style="color: #ff6600; font-weight: 600;">Adventure Application</a> link.
                            </p>
                            <p class="mb-0" style="font-size: 12.5px; color: #666; line-height: 1.6;">
                                जर Land/Air/Water Activity मधील अपेक्षित Sub Activity ड्रॉपडाउन मध्ये नसेल तर
                                <a href="#" style="color: #ff6600; font-weight: 600;">Adventure Application</a>
                                ह्या लिंक द्वारे नवीन नोंदणी करावी.
                            </p>
                        </div>
                    </div>

                    {{-- Policy Box --}}
                    <div class="info-box" style="background: #f0faf5; border-left: 4px solid #1D9E75;">
                        <div class="info-icon" style="background: #c8f0e0;">
                            <i class="fa-solid fa-landmark" style="color: #0F6E56;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="font-size: 13px; font-weight: 600; color: #0F6E56;">Policy</p>
                            <p class="mb-0" style="font-size: 13px; color: #333; line-height: 1.6;">
                                Directorate of Tourism <strong>(Government of Maharashtra)</strong> has announced the
                                <strong style="color: #0F6E56;">Adventure Tourism Initiative Policy</strong>.
                                Fill out the form below to register yourself or your organization temporarily and permanently.
                            </p>
                        </div>
                    </div>

                    {{-- Payment Box --}}
                    <div class="info-box mb-4" style="background: #fff8e8; border-left: 4px solid #EF9F27;">
                        <div class="info-icon" style="background: #fde8b0;">
                            <i class="fa-solid fa-credit-card" style="color: #854F0B;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="font-size: 13px; font-weight: 600; color: #854F0B;">Payment Gateway</p>
                            <p class="mb-0" style="font-size: 13px; color: #333;">
                                To complete the payment visit:
                                <a href="https://gras.mahakosh.gov.in" target="_blank"
                                   style="color: #ff6600; font-weight: 600; word-break: break-all;">
                                    https://gras.mahakosh.gov.in
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- Certificate Cards Section --}}
                    <div style="background: #fff4ed; border-radius: 10px; padding: 20px;">
                        <p class="mb-3" style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-certificate me-1" style="color: #ff6600;"></i> Select Certificate Type
                        </p>

                        <div class="row g-3">

                            {{-- Provisional Certificate --}}
                            <div class="col-md-6">
                                <div class="cert-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 42px; height: 42px; border-radius: 10px; background: #fff0e6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fa-regular fa-file-lines" style="color: #ff6600; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <span class="cert-badge" style="background: #ffe0cc; color: #cc4400;">Provisional</span>
                                            <h5 class="mb-0" style="font-size: 15px; font-weight: 700; color: #222;">
                                                Application for Provisional Certificate
                                            </h5>
                                        </div>
                                    </div>
                                    <p class="mb-0" style="font-size: 12.5px; color: #666; line-height: 1.6;">
                                        Temporary registration for adventure tourism operators. Valid for a limited period pending verification.
                                    </p>
                                    <div>
                                        <a href="{{ route('frontend.adventure.provisional.applications') }}" class="apply-btn" aria-label="Apply for Provisional Certificate">
                                            <i class="fa-solid fa-paper-plane" style="font-size: 13px;"></i> Apply Now
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Permanent Certificate --}}
                            <div class="col-md-6">
                                <div class="cert-card">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 42px; height: 42px; border-radius: 10px; background: #fff0e6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fa-solid fa-award" style="color: #ff6600; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <span class="cert-badge" style="background: #d4f5e7; color: #0F6E56;">Permanent</span>
                                            <h5 class="mb-0" style="font-size: 15px; font-weight: 700; color: #222;">
                                                Application for Permanent Certificate
                                            </h5>
                                        </div>
                                    </div>
                                    <p class="mb-0" style="font-size: 12.5px; color: #666; line-height: 1.6;">
                                        Full registration for certified adventure tourism operators. Requires document verification and fee payment.
                                    </p>
                                    <div>
                                        <a href="" class="apply-btn" aria-label="Apply for Permanent Certificate">
                                            <i class="fa-solid fa-paper-plane" style="font-size: 13px;"></i> Apply Now
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush