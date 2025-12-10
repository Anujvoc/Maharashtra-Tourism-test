@extends('frontend.layouts2.master')

@section('title', 'Tourist Villa Registration')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .form-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            margin: 2rem 0;
            border: 1px solid rgba(255, 106, 0, 0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--ink);
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(255, 106, 0, 0.2);
        }

        .form-header h2 {
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--ink);
        }

        .form-header p {
            color: var(--muted);
            font-size: 1.05rem;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            position: relative;
            counter-reset: step;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
            border-radius: 3px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            color: #6c757d;
            font-weight: 700;
            font-size: 1.1rem;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background-color: var(--brand);
            color: white;
            transform: scale(1.05);
        }

        .step.completed .step-circle {
            background-color: #28a745;
            color: white;
        }

        .step.completed .step-circle::after {
            content: '✓';
            font-weight: bold;
        }

        .step-label {
            font-size: 0.9rem;
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .step.active .step-label {
            color: var(--brand);
            font-weight: 700;
        }

        .step.completed .step-label {
            color: #28a745;
        }

        .form-step {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-step.active {
            display: block;
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .section-title {
            color: var(--brand);
            margin-bottom: 1.25rem;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            font-size: 1.4rem;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 0.25rem rgba(255, 106, 0, 0.15);
        }

        .btn-brand {
            background: var(--brand);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-brand:hover {
            background: var(--brand-dark);
            color: rgb(12, 39, 248);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 106, 0, 0.3);
        }

        .btn-outline-brand {
            background: transparent;
            color: var(--brand);
            border: 2px solid var(--brand);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-brand:hover {
            background: var(--brand);
            color: rgb(104, 10, 255);
            transform: translateY(-2px);
        }

        .form-check-input:checked {
            background-color: var(--brand);
            border-color: var(--brand);
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .upload-area:hover {
            border-color: var(--brand);
            background-color: rgba(255, 106, 0, 0.05);
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .upload-area:hover .upload-icon {
            color: var(--brand);
        }

        .review-card {
            border-radius: 12px;
            border: 1px solid #e9ecef;
            margin-bottom: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .review-card-header {
            background: var(--brand);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .review-card-body {
            padding: 1.5rem;
        }

        .review-item {
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .review-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .review-label {
            font-weight: 600;
            color: var(--ink);
        }

        .review-value {
            color: var(--muted);
        }

        .form-icon {
            color: var(--brand);
            font-size: 1.1rem;
        }

        .info-text {
            font-size: 0.9rem;
            color: var(--muted);
            margin-top: 0.5rem;
        }

        .form-alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            background: rgba(255, 106, 0, 0.1);
            border-left: 4px solid var(--brand);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
                margin: 1rem 0;
            }

            .step-label {
                font-size: 0.8rem;
            }

            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .checkbox-grid {
                grid-template-columns: 1fr;
            }

            .form-navigation {
                flex-direction: column;
                gap: 1rem;
            }

            .form-navigation button {
                width: 100%;
            }
        }
    </style>
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Application Form for the Registration of Tourist Villa</h1>

        </div>


        <div class="container ">
            <div class="form-container">
                <div class="form-header">
                    <h2>Application Form for the Registration of Tourist Villa</h2>
                    <p>Please fill out all required fields marked with an asterisk (*)</p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step completed" id="step1-indicator">
                        <div class="step-circle">1</div>
                        <div class="step-label">Applicant Details</div>
                    </div>
                    <div class="step active" id="step2-indicator">
                        <div class="step-circle">2</div>
                        <div class="step-label">Property Details</div>
                    </div>
                    <div class="step" id="step3-indicator">
                        <div class="step-circle">3</div>
                        <div class="step-label">Accommodation</div>
                    </div>
                    <div class="step" id="step4-indicator">
                        <div class="step-circle">4</div>
                        <div class="step-label">Facilities</div>
                    </div>
                    <div class="step" id="step5-indicator">
                        <div class="step-circle">5</div>
                        <div class="step-label">Review & Submit</div>
                    </div>
                </div>

                <form id="villaRegistrationForm" action="{{ route('frontend.villa-registrations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Step 1: Applicant Details -->
                    <div class="form-step active" id="step1">


                        <h4 class="section-title">
                            {{-- <i class="fa-solid fa-user"></i>  --}}
                            A) Details of the Applicant
                        </h4>

                        <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                        <div class="row mb-3">
                            <div class="col-md-6 " class="form-group">
                                <label for="applicantName" class="form-label required">
                                    <i class="bi bi-person form-icon"></i> Name of the Applicant (Owner of the Unit)
                                </label>
                                <input type="text" class="form-control @error('applicantName') is-invalid @enderror"
                                    id="applicantName" name="applicantName" value="{{ Auth::user()->name ?? '' }}"
                                    inputmode="text" text-uppercase autocomplete="organization" text-capitalize
                                    oninput="
                        this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                        this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                        "
                                    pattern="[A-Za-z\s.'-]{2,120}"
                                    title="Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
                                    maxlength="120" required>
                                @error('applicantName')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" class="form-group">
                                <label for="applicantPhone" class="form-label required">
                                    <i class="bi bi-telephone form-icon"></i> Telephone/Mobile No of Applicant
                                </label>
                                <input type="tel" class="form-control" class="form-control" id="applicantPhone"
                                    maxlength="10" name="applicantPhone" value="{{ Auth::user()->phone ?? '' }}" required>
                                @error('applicantPhone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6" class="form-group">
                                <label for="applicantEmail" class="form-label required">
                                    <i class="bi bi-envelope form-icon"></i> E-Mail ID of Applicant
                                </label>
                                <input type="email" class="form-control" id="applicantEmail" name="applicantEmail"
                                    value="{{ Auth::user()->email ?? '' }}" required>
                                @error('applicantEmail')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" class="form-group">
                                <label for="businessName" class="form-label required">
                                    <i class="bi bi-building form-icon"></i> Name of the Business
                                </label>
                                <input type="text" text-uppercase class="form-control" id="businessName"
                                    name="businessName" value="{{ old('businessName') }}" inputmode="text"
                                    autocomplete="organization" text-capitalize
                                    oninput="
                        this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                        this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });"
                                    pattern="[A-Za-z\s.'-]{2,120}"
                                    title="Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
                                    maxlength="120" required>
                                @error('businessName')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6" class="form-group">
                                <label for="businessType" class="form-label required">
                                    <i class="bi bi-diagram-3 form-icon"></i> Type of Business
                                </label>
                                <select class="form-control" id="businessType" name="businessType" required>
                                    <option value="" selected disabled>Select Business Type</option>
                                    <option value="Proprietorship"
                                        {{ old('businessType') == 'Proprietorship' ? 'selected' : '' }}>Proprietorship
                                    </option>
                                    <option value="Partnership"
                                        {{ old('businessType') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="Pvt Ltd" {{ old('businessType') == 'Pvt Ltd' ? 'selected' : '' }}>Pvt
                                        Ltd</option>
                                    <option value="LLP" {{ old('businessType') == 'LLP' ? 'selected' : '' }}>LLP
                                    </option>
                                    <option value="Public Ltd"
                                        {{ old('businessType') == 'Public Ltd' ? 'selected' : '' }}>Public Ltd</option>
                                    <option value="Co-operative"
                                        {{ old('businessType') == 'Co-operative' ? 'selected' : '' }}>Co-operative</option>
                                    <option value="Society" {{ old('businessType') == 'Society' ? 'selected' : '' }}>
                                        Society</option>
                                    <option value="Trust" {{ old('businessType') == 'Trust' ? 'selected' : '' }}>Trust
                                    </option>
                                    <option value="SHG" {{ old('businessType') == 'SHG' ? 'selected' : '' }}>SHG
                                    </option>
                                    <option value="JFMC" {{ old('businessType') == 'JFMC' ? 'selected' : '' }}>JFMC
                                    </option>
                                    <option value="Other" {{ old('businessType') == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('businessType')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" class="form-group">
                                <label for="panNumber" class="form-label required">
                                    <i class="bi bi-credit-card form-icon"></i> PAN Card Number
                                </label>
                                <input type="text" text-uppercase maxlength="10" class="form-control" id="panNumber"
                                    name="panNumber" value="{{ old('panNumber') }}" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                    title="Enter a valid 10-character PAN, e.g., ABCDE1234F"
                                    oninput="
                                    this.value = this.value.toUpperCase();
                                     this.value = this.value.replace(/[^A-Z0-9]/g, '');
                                         if (this.value.length > 10) this.value = this.value.slice(0, 10);
                                                "
                                    required>
                                <div class="info-text text-info">Format: 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F)
                                </div>
                                @error('panNumber')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="businessPanNumber" class="form-label">
                                    <i class="bi bi-building form-icon"></i> Business PAN Card Number (If applicable)
                                </label>
                                <input type="text" class="form-control" id="businessPanNumber"
                                    name="businessPanNumber" value="{{ old('businessPanNumber') }}"
                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" text-uppercase maxlength="10"
                                    title="Enter a valid 10-character PAN, e.g., ABCDE1234F"
                                    oninput="
                          this.value = this.value.toUpperCase();
                          this.value = this.value.replace(/[^A-Z0-9]/g, '');
                          if (this.value.length > 10) this.value = this.value.slice(0, 10);
                        ">
                                {{-- <div class="info-text ">Leave blank if not applicable</div> --}}
                                <div class="info-text text-info">Format: 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="aadharNumber" class="form-label required">
                                    <i class="bi bi-person-vcard form-icon"></i> Aadhar Number of Applicant
                                </label>
                                <input type="number" class="form-control" id="aadharNumber" name="aadharNumber"
                                    value="{{ Auth::user()->aadhar ?? '' }}" required>
                                @error('aadharNumber')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="udyamAadharNumber" class="form-label">
                                    <i class="bi bi-file-earmark-text form-icon"></i> Udyam Aadhar Number (If applicable)
                                </label>
                                <input type="number" class="form-control" id="udyamAadharNumber"
                                    name="udyamAadharNumber" value="{{ old('udyamAadharNumber') }}">
                                <div class="info-text text-info">Leave blank if not applicable</div>
                            </div>
                            <div class="col-md-6">
                                <label for="ownershipProof" class="form-label required">
                                    <i class="bi bi-house-door form-icon"></i> Proof of Ownership of the Property
                                </label>
                                <select class="form-control" id="ownershipProof" name="ownershipProof" required>
                                    <option value="" selected disabled>Select Proof Type</option>
                                    <option value="7/12 document"
                                        {{ old('ownershipProof') == '7/12 document' ? 'selected' : '' }}>7/12 document
                                    </option>
                                    <option value="Property Tax Receipts"
                                        {{ old('ownershipProof') == 'Property Tax Receipts' ? 'selected' : '' }}>Property
                                        Tax Receipts</option>
                                    <option value="Property Card"
                                        {{ old('ownershipProof') == 'Property Card' ? 'selected' : '' }}>Property Card
                                    </option>
                                    <option value="Other" {{ old('ownershipProof') == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('ownershipProof')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="propertyRented" class="form-label required">
                                    <i class="bi bi-house-gear form-icon"></i> Is the property rented to an operator?
                                </label>
                                <select class="form-control" id="propertyRented" name="propertyRented" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('propertyRented') == 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('propertyRented') == 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                                @error('propertyRented')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="operatorNameContainer" style="display: none;">
                                <label for="operatorName" class="form-label">
                                    <i class="bi bi-person-gear form-icon"></i> Operator's Name
                                </label>
                                <input type="text" class="form-control" id="operatorName" name="operatorName"
                                    value="{{ old('operatorName') }}">
                            </div>
                        </div>

                        <div class="mb-3" id="rentalAgreementContainer" style="display: none;">
                            <label for="rentalAgreement" class="form-label">
                                <i class="bi bi-file-earmark-arrow-up form-icon"></i> Share copy the rental agreement /
                                management contract executed with the operator
                            </label>
                            <div class="upload-area" id="rentalAgreementUpload">
                                <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                <p>Click to upload or drag and drop</p>
                                <p class="info-text text-info">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                                <input type="file" class="d-none" id="rentalAgreement" name="rentalAgreement">
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-brand" disabled>
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-brand" id="nextToStep2"
                                style="
                                background-color: #ff6600;
                                color: #fff;
                                font-weight: 700;
                                border: none;
                                border-radius: 8px;
                                padding: 0.6rem 1.5rem;
                                cursor: pointer;
                                transition: none !important;
                            "
                                onmouseover="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';"
                                onmouseout="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Property Details -->
                    <div class="form-step" id="step2">
                        <div class="form-alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Please provide accurate property details as per
                            official documents.
                        </div>

                        <h4 class="section-title">
                            <i class="bi bi-house-check"></i>
                            B) Details of the Property
                        </h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="propertyName" class="form-label required">
                                    <i class="bi bi-house form-icon"></i> Name of the Property
                                </label>
                                <input type="text" class="form-control" id="propertyName" name="propertyName"
                                    value="{{ old('propertyName') }}" inputmode="text" text-uppercase
                                    autocomplete="organization" text-capitalize
                                    oninput="
                                        this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                                        this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                                        "
                                    pattern="[A-Za-z\s.'-]{2,120}"
                                    title="Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
                                    maxlength="120" required>
                                @error('propertyName')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="propertyAddress" class="form-label required">
                                    <i class="bi bi-geo-alt form-icon"></i> Complete Postal Address of the Property
                                </label>
                                <textarea class="form-control" id="propertyAddress" name="propertyAddress" rows="2" required>{{ old('propertyAddress') }}</textarea>
                                @error('propertyAddress')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addressProof" class="form-label required">
                                    <i class="bi bi-file-text form-icon"></i> Proof of Address
                                </label>
                                <select class="form-control" id="addressProof" name="addressProof" required>
                                    <option value="" selected disabled>Select Proof Type</option>
                                    <option value="Latest Electricity Bill"
                                        {{ old('addressProof') == 'Latest Electricity Bill' ? 'selected' : '' }}>Latest
                                        Electricity Bill</option>
                                    <option value="Water Bill"
                                        {{ old('addressProof') == 'Water Bill' ? 'selected' : '' }}>Water Bill</option>
                                    <option value="Other" {{ old('addressProof') == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('addressProof')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="propertyCoordinates" class="form-label required">
                                    <i class="bi bi-geo form-icon"></i> Geographic Co-ordinates of the Property / Google
                                    Map Link
                                </label>
                                <input type="text" class="form-control" id="propertyCoordinates"
                                    name="propertyCoordinates" value="{{ old('propertyCoordinates') }}" required>
                                <div class="info-text text-danger">Example: 19.0760° N, 72.8777° E or Google Maps share
                                    link</div>
                                @error('propertyCoordinates')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="propertyOperational" class="form-label required">
                                    <i class="bi bi-building-check form-icon"></i> Is the Tourism Villa already
                                    Operational?
                                </label>
                                <select class="form-control" id="propertyOperational" name="propertyOperational"
                                    required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('propertyOperational') == 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('propertyOperational') == 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                                @error('propertyOperational')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="operationalDetailsContainer" style="display: none;">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="operationalYear" class="form-label">
                                            <i class="bi bi-calendar-event form-icon"></i> Since which year?
                                        </label>
                                        <input type="number" class="form-control" id="operationalYear"
                                            name="operationalYear" value="{{ old('operationalYear') }}" min="1900"
                                            max="2030">
                                        @error('operationalYear')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="guestsHosted" class="form-label">
                                            <i class="bi bi-people form-icon"></i> Guests hosted till March 2025?
                                        </label>
                                        <input type="number" class="form-control" id="guestsHosted" name="guestsHosted"
                                            value="{{ old('guestsHosted') }}" min="0">
                                        @error('guestsHosted')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="totalArea" class="form-label required">
                                    <i class="bi bi-aspect-ratio form-icon"></i> Total Area (sq.ft) of the Property
                                </label>
                                <input type="number" class="form-control" id="totalArea" name="totalArea"
                                    value="{{ old('totalArea') }}" min="0" required>
                                @error('totalArea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="mahabookingNumber" class="form-label">
                                    <i class="bi bi-globe form-icon"></i> Mahabooking Portal Registration Number
                                </label>
                                <input type="text" class="form-control" id="mahabookingNumber"
                                    name="mahabookingNumber" value="{{ old('mahabookingNumber') }}">
                                <div class="info-text info-text">If already registered on Mahabooking portal</div>
                                @error('mahabookingNumber')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-brand" id="prevToStep1">
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-brand" id="nextToStep3"
                                style="
                                    background-color: #ff6600;
                                    color: #fff;
                                    font-weight: 700;
                                    border: none;
                                    border-radius: 8px;
                                    padding: 0.6rem 1.5rem;
                                    cursor: pointer;
                                    transition: none !important;
                                "
                                onmouseover="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';"
                                onmouseout="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Accommodation Details -->
                    <div class="form-step" id="step3">
                        <div class="form-alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Please provide accurate details about your
                            accommodation facilities.
                        </div>

                        <h4 class="section-title">
                            <i class="bi bi-door-closed"></i>
                            C) Details of the Accommodation
                        </h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="numberOfRooms" class="form-label required">
                                    <i class="bi bi-door-open form-icon"></i> Number of Rooms
                                </label>
                                <input type="number" class="form-control" id="numberOfRooms" name="numberOfRooms"
                                    value="{{ old('numberOfRooms') }}" min="1" required>
                                @error('numberOfRooms')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="roomArea" class="form-label required">
                                    <i class="bi bi-square form-icon"></i> Area (sq. ft) of each room
                                </label>
                                <input type="number" class="form-control" id="roomArea" name="roomArea"
                                    value="{{ old('roomArea') }}" min="0" required>
                                @error('roomArea')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="attachedToilet" class="form-label required">
                                    <i class="bi bi-droplet form-icon"></i> Does each room have attached toilet?
                                </label>
                                <select class="form-control" id="attachedToilet" name="attachedToilet" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('attachedToilet') == 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('attachedToilet') == 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                                @error('attachedToilet')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="dustbins" class="form-label required">
                                    <i class="bi bi-trash form-icon"></i> Do the rooms have dustbins for garbage disposal?
                                </label>
                                <select class="form-control" id="dustbins" name="dustbins" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('dustbins') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('dustbins') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('dustbins')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="roadAccess" class="form-label required">
                                    <i class="bi bi-signpost form-icon"></i> Is the property accessible by road?
                                </label>
                                <select class="form-control" id="roadAccess" name="roadAccess" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('roadAccess') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('roadAccess') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('roadAccess')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="foodProvided" class="form-label required">
                                    <i class="bi bi-egg-fried form-icon"></i> Can food be provided to guests on request?
                                </label>
                                <select class="form-control" id="foodProvided" name="foodProvided" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('foodProvided') == 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('foodProvided') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('foodProvided')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="paymentOptions" class="form-label required">
                                    <i class="bi bi-credit-card form-icon"></i> Payment collection through cash and/or UPI?
                                </label>
                                <select class="form-control" id="paymentOptions" name="paymentOptions" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('paymentOptions') == 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('paymentOptions') == 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                                @error('paymentOptions')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-brand" id="prevToStep2">
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-brand" id="nextToStep4"
                                style="
                                    background-color: #ff6600;   /* Brand orange */
                                    color: #fff;                 /* White text */
                                    font-weight: 700;            /* Bold text */
                                    border: none;                /* No border */
                                    border-radius: 8px;          /* Slightly rounded corners */
                                    padding: 0.6rem 1.5rem;      /* Balanced padding */
                                    cursor: pointer;             /* Pointer cursor */
                                    transition: none !important; /* Disable animation */
                                "
                                onmouseover="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';"
                                onmouseout="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>
                    </div>

                    <!-- Step 4: Common Facilities -->
                    <div class="form-step" id="step4">
                        <div class="form-alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Please select all available facilities at your
                            property.
                        </div>

                        <h4 class="section-title">
                            <i class="bi bi-grid-3x3-gap"></i>
                            D) Common Facilities
                        </h4>

                        <div class="checkbox-grid mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="kitchen" name="facilities[]"
                                    value="kitchen" {{ in_array('kitchen', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kitchen">
                                    <i class="bi bi-egg-fried me-1"></i> Kitchen
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="diningHall" name="facilities[]"
                                    value="diningHall"
                                    {{ in_array('diningHall', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="diningHall">
                                    <i class="bi bi-cup-straw me-1"></i> Dining Hall
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="garden" name="facilities[]"
                                    value="garden" {{ in_array('garden', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="garden">
                                    <i class="bi bi-flower1 me-1"></i> Garden
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="parking" name="facilities[]"
                                    value="parking" {{ in_array('parking', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="parking">
                                    <i class="bi bi-car-front me-1"></i> Parking
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="evCharging" name="facilities[]"
                                    value="evCharging"
                                    {{ in_array('evCharging', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="evCharging">
                                    <i class="bi bi-lightning-charge me-1"></i> EV Charging
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="playArea" name="facilities[]"
                                    value="playArea" {{ in_array('playArea', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="playArea">
                                    <i class="bi bi-joystick me-1"></i> Children Play Area
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="swimmingPool" name="facilities[]"
                                    value="swimmingPool"
                                    {{ in_array('swimmingPool', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="swimmingPool">
                                    <i class="bi bi-water me-1"></i> Swimming Pool
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="wifi" name="facilities[]"
                                    value="wifi" {{ in_array('wifi', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="wifi">
                                    <i class="bi bi-wifi me-1"></i> Wi-Fi
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="firstAid" name="facilities[]"
                                    value="firstAid" {{ in_array('firstAid', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="firstAid">
                                    <i class="bi bi-bandaid me-1"></i> First Aid Box
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fireSafety" name="facilities[]"
                                    value="fireSafety"
                                    {{ in_array('fireSafety', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="fireSafety">
                                    <i class="bi bi-fire me-1"></i> Fire Safety Equipment
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="waterPurifier" name="facilities[]"
                                    value="waterPurifier"
                                    {{ in_array('waterPurifier', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="waterPurifier">
                                    <i class="bi bi-droplet me-1"></i> Water Purifier / RO
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rainwaterHarvesting"
                                    name="facilities[]" value="rainwaterHarvesting"
                                    {{ in_array('rainwaterHarvesting', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rainwaterHarvesting">
                                    <i class="bi bi-cloud-rain me-1"></i> Rainwater Harvesting
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="solarPower" name="facilities[]"
                                    value="solarPower"
                                    {{ in_array('solarPower', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="solarPower">
                                    <i class="bi bi-sun me-1"></i> Solar Power
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="renewableEnergy" name="facilities[]"
                                    value="renewableEnergy"
                                    {{ in_array('renewableEnergy', old('facilities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="renewableEnergy">
                                    <i class="bi bi-recycle me-1"></i> Other Renewable Energy
                                </label>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-cash-coin"></i>
                                E) GRAS Chalan (www.gras.mahakosh.gov.in)
                            </h5>
                            <div class="mb-3">
                                <label for="applicationFees" class="form-label required">
                                    <i class="bi bi-currency-rupee form-icon"></i> Application fees (Rs. 500/-) Paid
                                </label>
                                <select class="form-control" id="applicationFees" name="applicationFees" required>
                                    <option value="" selected disabled>Select Yes/No</option>
                                    <option value="Yes" {{ old('applicationFees') == 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('applicationFees') == 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                                @error('applicationFees')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="info-text text-info">Payment must be made through GRAS portal before submission
                                </div>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-brand" id="prevToStep3">
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-brand " id="nextToStep5"
                                style="
                    background-color: #ff6600;
                    color: #fff;
                    font-weight: 700;
                    border: none;
                    border-radius: 8px;
                    padding: 0.6rem 1.5rem;
                    cursor: pointer;
                    transition: none !important;
                  "
                                onmouseover="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';"
                                onmouseout="this.style.backgroundColor='#ff6600'; this.style.color='#fff'; this.style.boxShadow='none';">
                                Next <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 5: Review and Submit -->
                    <div class="form-step" id="step5">
                        <div class="form-alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Please review all information carefully before
                            submitting.
                        </div>

                        <h4 class="section-title">
                            <i class="bi bi-clipboard-check"></i>
                            Review Your Information
                        </h4>
                        <p class="mb-4">Please verify all details before final submission. Incorrect information may
                            delay processing.</p>

                        <div class="review-card">
                            <div class="review-card-header">
                                <i class="bi bi-person-badge"></i> A) Applicant Details
                            </div>
                            <div class="review-card-body">
                                <div id="reviewApplicantDetails">
                                    <!-- Applicant details will be populated here by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <i class="bi bi-house-check"></i> B) Property Details
                            </div>
                            <div class="review-card-body">
                                <div id="reviewPropertyDetails">
                                    <!-- Property details will be populated here by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <i class="bi bi-door-closed"></i> C) Accommodation Details
                            </div>
                            <div class="review-card-body">
                                <div id="reviewAccommodationDetails">
                                    <!-- Accommodation details will be populated here by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <i class="bi bi-grid-3x3-gap"></i> D) Common Facilities
                            </div>
                            <div class="review-card-body">
                                <div id="reviewFacilities">
                                    <!-- Facilities will be populated here by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <i class="bi bi-cash-coin"></i> E) GRAS Chalan
                            </div>
                            <div class="review-card-body">
                                <div id="reviewChalan">
                                    <!-- Chalan details will be populated here by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="declaration" required>
                            <label class="form-check-label" for="declaration">
                                I hereby declare that all the information provided in this application is true and correct
                                to the best of my knowledge. I understand that any false information may lead to rejection
                                of my application.
                            </label>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn btn-outline-brand" id="prevToStep4">
                                <i class="bi bi-arrow-left"></i> Previous
                            </button>
                            {{-- <button type="submit" class="btn btn-success " id="submitForm">
                        <i class="bi bi-check-circle"></i> Submit Application
                    </button> --}}
                            <button type="submit" class="btn" id="submitForm"
                                style="
                                background-color: #198754;   /* Bootstrap success green */
                                color: #fff;                 /* white text */
                                font-weight: 700;            /* bold text */
                                border: none;                /* clean border */
                                border-radius: 50px;         /* pill shape */
                                padding: 0.6rem 1.5rem;      /* balanced size */
                                transition: all 0.3s ease;
                            ">
                                <i class="bi bi-check-circle me-1"></i>
                                Submit Application
                            </button>

                        </div>
                    </div>
                </form>


            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- (अगर पहले से शामिल नहीं हैं तो) ये दो CDN जोड़ दें -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(function() {
            /* ---------- Custom Methods ---------- */

            // letters + spaces + .'- allowed
            $.validator.addMethod("lettersonly", function(v, el) {
                return this.optional(el) || /^[A-Za-z\s.'-]+$/.test(v);
            }, "Letters and spaces only");

            // Indian mobile (allows +91, 6-9 से शुरू, कुल 10 digits)
            $.validator.addMethod("indMobile", function(v, el) {
                v = v.replace(/\s|-|\+91/g, "");
                return this.optional(el) || /^[6-9]\d{9}$/.test(v);
            }, "Enter a valid 10-digit mobile");

            // PAN (ABCDE1234F)
            $.validator.addMethod("validPAN", function(v, el) {
                return this.optional(el) || /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(v);
            }, "Please enter a valid PAN (e.g., ABCDE1234F)");

            // Aadhaar (12 digits, spaces ignored)
            $.validator.addMethod("validAadhar", function(v, el) {
                v = (v || "").replace(/\s+/g, "");
                return this.optional(el) || /^\d{12}$/.test(v);
            }, "Enter 12-digit Aadhaar number");

            // Coords OR Google Maps URL
            $.validator.addMethod("coordsOrUrl", function(v, el) {
                if (this.optional(el)) return true;
                v = (v || "").trim();
                const latlng =
                    /^-?\d{1,2}\.\d{3,},?\s*-?\d{1,3}\.\d{3,}$/; // loose check like 19.0760, 72.8777
                const gmap =
                    /(google\.[a-z.]+\/maps|goo\.gl\/maps)/i;
                return latlng.test(v) || gmap.test(v);
            }, "Enter lat,long (e.g., 19.0760, 72.8777) or a Google Maps link");

            // Year range (1900..currentYear+5)
            const currentYear = new Date().getFullYear();
            $.validator.addMethod("yearRange", function(v, el) {
                if (this.optional(el)) return true;
                const n = Number(v);
                return n >= 1900 && n <= currentYear + 5;
            }, `Enter a year between 1900 and ${currentYear + 5}`);

            // File extension
            $.validator.addMethod("fileExt", function(v, el, exts) {
                if (this.optional(el) || !el.files || !el.files.length) return true;
                const name = el.files[0].name.toLowerCase();
                return (exts || ["pdf", "jpg", "jpeg", "png"])
                    .some(ext => name.endsWith("." + ext));
            }, "Allowed: PDF, JPG, PNG");

            // File size (MB)
            $.validator.addMethod("fileSizeMB", function(v, el, maxMB) {
                if (this.optional(el) || !el.files || !el.files.length) return true;
                const sizeMB = el.files[0].size / (1024 * 1024);
                return sizeMB <= (maxMB || 5);
            }, "File too large");

            $.validator.addMethod("lettersonly", function(v, el) {
                return this.optional(el) || /^[A-Za-z\s.'-]+$/.test(v);
            }, "Letters and spaces only");

            // Alphanumeric (for optional codes)
            $.validator.addMethod("alphanumeric", function(v, el) {
                return this.optional(el) || /^[A-Za-z0-9-_/]+$/.test(v);
            }, "Letters and numbers only");

            /* ---------- Validator Init ---------- */

            const validator = $("#villaRegistrationForm").validate({
                ignore: [], // hidden fields भी validate होंगे जब depends true होगा
                errorElement: "div",
                errorClass: "invalid-feedback",
                highlight: function(el) {
                    $(el).addClass("is-invalid");
                },
                unhighlight: function(el) {
                    $(el).removeClass("is-invalid");
                },
                errorPlacement: function(error, element) {
                    if (element.parent(".input-group").length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },

                /* ---------- RULES ---------- */
                rules: {
                    // Step 1
                    applicantName: {
                        required: true,
                        lettersonly: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    applicantPhone: {
                        required: true,
                        indMobile: true
                    },
                    applicantEmail: {
                        required: true,
                        email: true,
                        maxlength: 120
                    },
                    businessName: {
                        required: true,
                        lettersonly: true,
                        minlength: 2,
                        maxlength: 120
                    },
                    businessType: {
                        required: true
                    },
                    panNumber: {
                        required: true,
                        validPAN: true
                    },
                    businessPanNumber: {
                        validPAN: true
                    }, // optional but must match if filled
                    aadharNumber: {
                        required: true,
                        validAadhar: true
                    },
                    udyamAadharNumber: {
                        alphanumeric: true,
                        minlength: 6,
                        maxlength: 25
                    }, // optional/loose
                    ownershipProof: {
                        required: true
                    },
                    propertyRented: {
                        required: true
                    },
                    operatorName: {
                        required: {
                            depends: function() {
                                return $("#propertyRented").val() === "Yes";
                            }
                        },
                        lettersonly: true,
                        minlength: {
                            depends: function() {
                                return $("#propertyRented").val() === "Yes";
                            },
                            param: 2
                        },
                        maxlength: 120
                    },
                    rentalAgreement: {
                        required: {
                            depends: function() {
                                return $("#propertyRented").val() === "Yes";
                            }
                        },
                        fileExt: ["pdf", "jpg", "jpeg", "png"],
                        fileSizeMB: 5
                    },

                    businessName: {
                        required: true,
                        lettersonly: true,
                        minlength: 2,
                        maxlength: 120
                    },

                    // Step 2
                    propertyName: {
                        required: true,
                        minlength: 2,
                        maxlength: 120
                    },
                    propertyAddress: {
                        required: true,
                        minlength: 5,
                        maxlength: 500
                    },
                    addressProof: {
                        required: true
                    },
                    propertyCoordinates: {
                        required: true,
                        coordsOrUrl: true
                    },
                    propertyOperational: {
                        required: true
                    },
                    operationalYear: {
                        required: {
                            depends: function() {
                                return $("#propertyOperational").val() === "Yes";
                            }
                        },
                        digits: true,
                        yearRange: true
                    },
                    guestsHosted: {
                        required: {
                            depends: function() {
                                return $("#propertyOperational").val() === "Yes";
                            }
                        },
                        digits: true,
                        min: 0,
                        max: 1000000
                    },
                    totalArea: {
                        required: true,
                        number: true,
                        min: 1,
                        max: 10000000
                    },
                    mahabookingNumber: {
                        alphanumeric: true,
                        minlength: 4,
                        maxlength: 40
                    }, // optional

                    // Step 3
                    numberOfRooms: {
                        required: true,
                        digits: true,
                        min: 1,
                        max: 500
                    },
                    roomArea: {
                        required: true,
                        number: true,
                        min: 1,
                        max: 100000
                    },
                    attachedToilet: {
                        required: true
                    },
                    dustbins: {
                        required: true
                    },
                    roadAccess: {
                        required: true
                    },
                    foodProvided: {
                        required: true
                    },
                    paymentOptions: {
                        required: true
                    },

                    // Step 4
                    applicationFees: {
                        required: true
                    },

                    // Step 5
                    declaration: {
                        required: true
                    }
                },

                /* ---------- MESSAGES ---------- */
                messages: {
                    applicantName: {
                        required: "Please enter your name",
                        lettersonly: "Name should contain letters and spaces only",
                        minlength: "Name must be at least 2 characters",
                        maxlength: "Name cannot exceed 100 characters"
                    },
                    applicantPhone: {
                        required: "Please enter your mobile number",
                        indMobile: "Enter a valid 10-digit Indian mobile"
                    },
                    applicantEmail: {
                        required: "Email is required",
                        email: "Enter a valid email address",
                        maxlength: "Email is too long"
                    },
                    businessName: {
                        required: "Business name is required",
                        lettersonly: "Business name should contain letters and spaces only",
                        minlength: "Business name must be at least 2 characters",
                        maxlength: "Business name cannot exceed 120 characters"
                    },
                    businessType: {
                        required: "Please select a type of business"
                    },
                    panNumber: {
                        required: "PAN number is required"
                    },
                    businessPanNumber: {
                        validPAN: "Enter a valid Business PAN (or leave blank)"
                    },
                    aadharNumber: {
                        required: "Aadhar number is required"
                    },
                    ownershipProof: {
                        required: "Please select ownership proof"
                    },
                    propertyRented: {
                        required: "Please select Yes/No"
                    },
                    operatorName: {
                        required: "Please enter operator name"
                    },
                    rentalAgreement: {
                        required: "Please upload rental agreement",
                        fileExt: "Allowed: PDF, JPG, PNG only",
                        fileSizeMB: "File must be 5MB or less"
                    },

                    propertyName: {
                        required: "Please enter property name"
                    },
                    propertyAddress: {
                        required: "Please enter property address"
                    },
                    addressProof: {
                        required: "Please select address proof"
                    },
                    propertyCoordinates: {
                        required: "Please enter coordinates or map link",
                        coordsOrUrl: "Enter lat,long (e.g., 19.0760, 72.8777) or Google Maps URL"
                    },
                    propertyOperational: {
                        required: "Please select Yes/No"
                    },
                    operationalYear: {
                        required: "Please enter year",
                        digits: "Digits only",
                        yearRange: `Year must be between 1900 and ${currentYear + 5}`
                    },
                    guestsHosted: {
                        required: "Please enter guest count",
                        digits: "Digits only",
                        min: "Cannot be negative",
                        max: "That looks too large"
                    },
                    totalArea: {
                        required: "Please enter total area",
                        number: "Enter a valid number",
                        min: "Area must be at least 1 sq.ft"
                    },

                    numberOfRooms: {
                        required: "Please enter number of rooms",
                        digits: "Digits only",
                        min: "At least 1 room"
                    },
                    roomArea: {
                        required: "Please enter room area",
                        number: "Enter a valid number",
                        min: "Area must be at least 1 sq.ft"
                    },
                    attachedToilet: {
                        required: "Please select Yes/No"
                    },
                    dustbins: {
                        required: "Please select Yes/No"
                    },
                    roadAccess: {
                        required: "Please select Yes/No"
                    },
                    foodProvided: {
                        required: "Please select Yes/No"
                    },
                    paymentOptions: {
                        required: "Please select Yes/No"
                    },

                    applicationFees: {
                        required: "Please confirm if fees paid"
                    },
                    declaration: {
                        required: "Please accept the declaration"
                    }
                },

                // Final submit (अगर आप custom submit करना चाहते हैं तो यहाँ करें)
                submitHandler: function(form) {
                    form.submit();
                }
            });

            /* ---------- Step-wise validation hook ---------- */
            // आपके existing step buttons validateStep() call करते हैं,
            // उसे jQuery Validate से जोड़ देते हैं:
            window.validateStep = function(stepNumber) {
                let valid = true;
                // current step के inputs पर element-wise validation
                $(`#step${stepNumber}`)
                    .find(":input")
                    .not(":button,:submit,:reset,[disabled]")
                    .each(function() {
                        // केवल visible fields या जिन पर depends true है, उन्हीं को validate करें
                        // validator.element() hidden पर भी काम करेगा जब rule active हो.
                        if (!validator.element(this)) valid = false;
                    });
                return valid;
            };

            /* ---------- UX niceties ---------- */

            // Live: remove non-digits in phone; limit 10
            $("#applicantPhone").on("input", function() {
                this.value = this.value.replace(/\D/g, "").slice(0, 10);
            });

            // Uppercase PANs automatically
            $("#panNumber, #businessPanNumber").on("input", function() {
                this.value = this.value.toUpperCase().replace(/\s+/g, "");
            });


            // Trim whitespaces on blur for common text fields
            $("#villaRegistrationForm input[type='text'], #villaRegistrationForm textarea").on("blur", function() {
                this.value = this.value.trim();
            });

            // Re-validate conditional fields when drivers change
            $("#propertyRented, #propertyOperational").on("change", function() {
                $("#operatorName, #rentalAgreement, #operationalYear, #guestsHosted").each(function() {
                    validator.element(this);
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form navigation
            const steps = document.querySelectorAll('.form-step');
            const stepIndicators = document.querySelectorAll('.step');

            // Show/hide fields based on selections
            const propertyRentedSelect = document.getElementById('propertyRented');
            const operatorNameContainer = document.getElementById('operatorNameContainer');
            const rentalAgreementContainer = document.getElementById('rentalAgreementContainer');

            propertyRentedSelect.addEventListener('change', function() {
                if (this.value === 'Yes') {
                    operatorNameContainer.style.display = 'block';
                    rentalAgreementContainer.style.display = 'block';
                } else {
                    operatorNameContainer.style.display = 'none';
                    rentalAgreementContainer.style.display = 'none';
                }
            });

            const propertyOperationalSelect = document.getElementById('propertyOperational');
            const operationalDetailsContainer = document.getElementById('operationalDetailsContainer');

            propertyOperationalSelect.addEventListener('change', function() {
                if (this.value === 'Yes') {
                    operationalDetailsContainer.style.display = 'block';
                } else {
                    operationalDetailsContainer.style.display = 'none';
                }
            });

            // File upload functionality
            const rentalAgreementUpload = document.getElementById('rentalAgreementUpload');
            const rentalAgreementInput = document.getElementById('rentalAgreement');

            rentalAgreementUpload.addEventListener('click', function() {
                rentalAgreementInput.click();
            });

            rentalAgreementInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    rentalAgreementUpload.innerHTML = `
                    <i class="bi bi-file-check text-success upload-icon"></i>
                    <p>File selected: ${this.files[0].name}</p>
                    <p class="info-text text-info">Click to change file</p>
                `;
                    rentalAgreementUpload.addEventListener('click', function() {
                        rentalAgreementInput.click();
                    });
                }
            });

            // Step navigation functions
            function goToStep(stepNumber) {
                steps.forEach(step => step.classList.remove('active'));
                document.getElementById(`step${stepNumber}`).classList.add('active');

                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.remove('active', 'completed');
                    if (index < stepNumber - 1) {
                        indicator.classList.add('completed');
                    }
                });
                document.getElementById(`step${stepNumber}-indicator`).classList.add('active');
            }

            // Navigation button event listeners
            document.getElementById('nextToStep2').addEventListener('click', function() {
                if (validateStep(1)) {
                    goToStep(2);
                }
            });

            document.getElementById('prevToStep1').addEventListener('click', function() {
                goToStep(1);
            });

            document.getElementById('nextToStep3').addEventListener('click', function() {
                if (validateStep(2)) {
                    goToStep(3);
                }
            });

            document.getElementById('prevToStep2').addEventListener('click', function() {
                goToStep(2);
            });

            document.getElementById('nextToStep4').addEventListener('click', function() {
                if (validateStep(3)) {
                    goToStep(4);
                }
            });

            document.getElementById('prevToStep3').addEventListener('click', function() {
                goToStep(3);
            });

            document.getElementById('nextToStep5').addEventListener('click', function() {
                if (validateStep(4)) {
                    updateReviewSection();
                    goToStep(5);
                }
            });

            document.getElementById('prevToStep4').addEventListener('click', function() {
                goToStep(4);
            });

            // Form validation function
            function validateStep(stepNumber) {
                const currentStep = document.getElementById(`step${stepNumber}`);
                const requiredFields = currentStep.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Please fill in all required fields before proceeding.');
                }

                return isValid;
            }

            // Update review section
            function updateReviewSection() {
                // Applicant Details
                document.getElementById('reviewApplicantDetails').innerHTML = `
                <div class="review-item">
                    <span class="review-label">Name:</span>
                    <span class="review-value">${document.getElementById('applicantName').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Phone:</span>
                    <span class="review-value">${document.getElementById('applicantPhone').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Email:</span>
                    <span class="review-value">${document.getElementById('applicantEmail').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Business Name:</span>
                    <span class="review-value">${document.getElementById('businessName').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Business Type:</span>
                    <span class="review-value">${document.getElementById('businessType').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">PAN:</span>
                    <span class="review-value">${document.getElementById('panNumber').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Business PAN:</span>
                    <span class="review-value">${document.getElementById('businessPanNumber').value || 'N/A'}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Aadhar:</span>
                    <span class="review-value">${document.getElementById('aadharNumber').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Udyam Aadhar:</span>
                    <span class="review-value">${document.getElementById('udyamAadharNumber').value || 'N/A'}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Ownership Proof:</span>
                    <span class="review-value">${document.getElementById('ownershipProof').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Property Rented:</span>
                    <span class="review-value">${document.getElementById('propertyRented').value}</span>
                </div>
                ${document.getElementById('propertyRented').value === 'Yes' ?
                  `<div class="review-item">
                            <span class="review-label">Operator Name:</span>
                            <span class="review-value">${document.getElementById('operatorName').value}</span>
                          </div>` : ''}
            `;

                // Property Details
                document.getElementById('reviewPropertyDetails').innerHTML = `
                <div class="review-item">
                    <span class="review-label">Property Name:</span>
                    <span class="review-value">${document.getElementById('propertyName').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Address:</span>
                    <span class="review-value">${document.getElementById('propertyAddress').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Address Proof:</span>
                    <span class="review-value">${document.getElementById('addressProof').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Coordinates:</span>
                    <span class="review-value">${document.getElementById('propertyCoordinates').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Operational:</span>
                    <span class="review-value">${document.getElementById('propertyOperational').value}</span>
                </div>
                ${document.getElementById('propertyOperational').value === 'Yes' ?
                  `<div class="review-item">
                            <span class="review-label">Operational Since:</span>
                            <span class="review-value">${document.getElementById('operationalYear').value}</span>
                          </div>
                          <div class="review-item">
                            <span class="review-label">Guests Hosted:</span>
                            <span class="review-value">${document.getElementById('guestsHosted').value}</span>
                          </div>` : ''}
                <div class="review-item">
                    <span class="review-label">Total Area:</span>
                    <span class="review-value">${document.getElementById('totalArea').value} sq.ft</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Mahabooking Number:</span>
                    <span class="review-value">${document.getElementById('mahabookingNumber').value || 'N/A'}</span>
                </div>
            `;

                // Accommodation Details
                document.getElementById('reviewAccommodationDetails').innerHTML = `
                <div class="review-item">
                    <span class="review-label">Number of Rooms:</span>
                    <span class="review-value">${document.getElementById('numberOfRooms').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Room Area:</span>
                    <span class="review-value">${document.getElementById('roomArea').value} sq.ft</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Attached Toilet:</span>
                    <span class="review-value">${document.getElementById('attachedToilet').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Dustbins:</span>
                    <span class="review-value">${document.getElementById('dustbins').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Road Access:</span>
                    <span class="review-value">${document.getElementById('roadAccess').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Food Provided:</span>
                    <span class="review-value">${document.getElementById('foodProvided').value}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Payment Options:</span>
                    <span class="review-value">${document.getElementById('paymentOptions').value}</span>
                </div>
            `;

                // Facilities
                const facilities = [{
                        id: 'kitchen',
                        label: 'Kitchen'
                    },
                    {
                        id: 'diningHall',
                        label: 'Dining Hall'
                    },
                    {
                        id: 'garden',
                        label: 'Garden'
                    },
                    {
                        id: 'parking',
                        label: 'Parking'
                    },
                    {
                        id: 'evCharging',
                        label: 'EV Charging'
                    },
                    {
                        id: 'playArea',
                        label: 'Children Play Area'
                    },
                    {
                        id: 'swimmingPool',
                        label: 'Swimming Pool'
                    },
                    {
                        id: 'wifi',
                        label: 'Wi-Fi'
                    },
                    {
                        id: 'firstAid',
                        label: 'First Aid Box'
                    },
                    {
                        id: 'fireSafety',
                        label: 'Fire Safety Equipment'
                    },
                    {
                        id: 'waterPurifier',
                        label: 'Water Purifier / RO'
                    },
                    {
                        id: 'rainwaterHarvesting',
                        label: 'Rainwater Harvesting'
                    },
                    {
                        id: 'solarPower',
                        label: 'Solar Power'
                    },
                    {
                        id: 'renewableEnergy',
                        label: 'Other Renewable Energy'
                    }
                ];

                let facilitiesHTML = '';
                const selectedFacilities = facilities.filter(facility =>
                    document.getElementById(facility.id).checked
                );

                if (selectedFacilities.length > 0) {
                    selectedFacilities.forEach(facility => {
                        facilitiesHTML += `
                        <div class="review-item">
                            <span class="review-label">${facility.label}:</span>
                            <span class="review-value text-success">Available</span>
                        </div>
                    `;
                    });
                } else {
                    facilitiesHTML = `
                    <div class="review-item">
                        <span class="review-label">Facilities:</span>
                        <span class="review-value text-muted">No facilities selected</span>
                    </div>
                `;
                }

                document.getElementById('reviewFacilities').innerHTML = facilitiesHTML;

                // Chalan
                document.getElementById('reviewChalan').innerHTML = `
                <div class="review-item">
                    <span class="review-label">Application Fees Paid:</span>
                    <span class="review-value ${document.getElementById('applicationFees').value === 'Yes' ? 'text-success' : 'text-danger'}">
                        ${document.getElementById('applicationFees').value}
                    </span>
                </div>
            `;
            }

            // Form submission
            document.getElementById('submitForm').addEventListener('click', function(e) {
                e.preventDefault();

                const declaration = document.getElementById('declaration');
                if (!declaration.checked) {
                    alert('Please accept the declaration before submitting.');
                    return;
                }

                if (validateStep(5)) {
                    document.getElementById('villaRegistrationForm').submit();
                    // In a real application, you would submit the form data to a server here
                    //  alert('Form submitted successfully! Thank you for registering your tourist villa.');
                    // Reset form or redirect as needed
                }
            });
        });
    </script>
@endpush
