@extends('frontend.layouts.app')

@section('title', 'Tourist Villa Registration')

@push('styles')
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
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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

    .form-control:focus, .form-select:focus {
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
        color: white;
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
        color: white;
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
<div class="container py-4">
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

        <form id="villaRegistrationForm">
            <!-- Step 1: Applicant Details -->
            <div class="form-step active" id="step1">
                <div class="form-alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Please provide accurate personal and business information as per official documents.
                </div>

                <h4 class="section-title">
                    <i class="bi bi-person-badge"></i>
                    A) Details of the Applicant
                </h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="applicantName" class="form-label required">
                            <i class="bi bi-person form-icon"></i> Name of the Applicant (Owner of the Unit)
                        </label>
                        <input type="text" class="form-control" id="applicantName" required>
                    </div>
                    <div class="col-md-6">
                        <label for="applicantPhone" class="form-label required">
                            <i class="bi bi-telephone form-icon"></i> Telephone/Mobile No of Applicant
                        </label>
                        <input type="tel" class="form-control" id="applicantPhone" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="applicantEmail" class="form-label required">
                            <i class="bi bi-envelope form-icon"></i> E-Mail ID of Applicant
                        </label>
                        <input type="email" class="form-control" id="applicantEmail" required>
                    </div>
                    <div class="col-md-6">
                        <label for="businessName" class="form-label required">
                            <i class="bi bi-building form-icon"></i> Name of the Business
                        </label>
                        <input type="text" class="form-control" id="businessName" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="businessType" class="form-label required">
                            <i class="bi bi-diagram-3 form-icon"></i> Type of Business
                        </label>
                        <select class="form-select" id="businessType" required>
                            <option value="" selected disabled>Select Business Type</option>
                            <option value="Proprietorship">Proprietorship</option>
                            <option value="Partnership">Partnership</option>
                            <option value="Pvt Ltd">Pvt Ltd</option>
                            <option value="LLP">LLP</option>
                            <option value="Public Ltd">Public Ltd</option>
                            <option value="Co-operative">Co-operative</option>
                            <option value="Society">Society</option>
                            <option value="Trust">Trust</option>
                            <option value="SHG">SHG</option>
                            <option value="JFMC">JFMC</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="panNumber" class="form-label required">
                            <i class="bi bi-credit-card form-icon"></i> PAN Card Number
                        </label>
                        <input type="text" class="form-control" id="panNumber" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="businessPanNumber" class="form-label">
                            <i class="bi bi-building form-icon"></i> Business PAN Card Number (If applicable)
                        </label>
                        <input type="text" class="form-control" id="businessPanNumber">
                        <div class="info-text">Leave blank if not applicable</div>
                    </div>
                    <div class="col-md-6">
                        <label for="aadharNumber" class="form-label required">
                            <i class="bi bi-person-vcard form-icon"></i> Aadhar Number of Applicant
                        </label>
                        <input type="text" class="form-control" id="aadharNumber" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="udyamAadharNumber" class="form-label">
                            <i class="bi bi-file-earmark-text form-icon"></i> Udyam Aadhar Number (If applicable)
                        </label>
                        <input type="text" class="form-control" id="udyamAadharNumber">
                        <div class="info-text">Leave blank if not applicable</div>
                    </div>
                    <div class="col-md-6">
                        <label for="ownershipProof" class="form-label required">
                            <i class="bi bi-house-door form-icon"></i> Proof of Ownership of the Property
                        </label>
                        <select class="form-select" id="ownershipProof" required>
                            <option value="" selected disabled>Select Proof Type</option>
                            <option value="7/12 document">7/12 document</option>
                            <option value="Property Tax Receipts">Property Tax Receipts</option>
                            <option value="Property Card">Property Card</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="propertyRented" class="form-label required">
                            <i class="bi bi-house-gear form-icon"></i> Is the property rented to an operator?
                        </label>
                        <select class="form-select" id="propertyRented" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="operatorNameContainer" style="display: none;">
                        <label for="operatorName" class="form-label">
                            <i class="bi bi-person-gear form-icon"></i> Operator's Name
                        </label>
                        <input type="text" class="form-control" id="operatorName">
                    </div>
                </div>

                <div class="mb-3" id="rentalAgreementContainer" style="display: none;">
                    <label for="rentalAgreement" class="form-label">
                        <i class="bi bi-file-earmark-arrow-up form-icon"></i> Share copy the rental agreement / management contract executed with the operator
                    </label>
                    <div class="upload-area" id="rentalAgreementUpload">
                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                        <p>Click to upload or drag and drop</p>
                        <p class="info-text">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                        <input type="file" class="d-none" id="rentalAgreement">
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-outline-brand" disabled>
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-brand" id="nextToStep2">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Property Details -->
            <div class="form-step" id="step2">
                <div class="form-alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Please provide accurate property details as per official documents.
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
                        <input type="text" class="form-control" id="propertyName" required>
                    </div>
                    <div class="col-md-6">
                        <label for="propertyAddress" class="form-label required">
                            <i class="bi bi-geo-alt form-icon"></i> Complete Postal Address of the Property
                        </label>
                        <textarea class="form-control" id="propertyAddress" rows="2" required></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="addressProof" class="form-label required">
                            <i class="bi bi-file-text form-icon"></i> Proof of Address
                        </label>
                        <select class="form-select" id="addressProof" required>
                            <option value="" selected disabled>Select Proof Type</option>
                            <option value="Latest Electricity Bill">Latest Electricity Bill</option>
                            <option value="Water Bill">Water Bill</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="propertyCoordinates" class="form-label required">
                            <i class="bi bi-geo form-icon"></i> Geographic Co-ordinates of the Property / Google Map Link
                        </label>
                        <input type="text" class="form-control" id="propertyCoordinates" required>
                        <div class="info-text">Example: 19.0760° N, 72.8777° E or Google Maps share link</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="propertyOperational" class="form-label required">
                            <i class="bi bi-building-check form-icon"></i> Is the Tourism Villa already Operational?
                        </label>
                        <select class="form-select" id="propertyOperational" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="operationalDetailsContainer" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <label for="operationalYear" class="form-label">
                                    <i class="bi bi-calendar-event form-icon"></i> Since which year?
                                </label>
                                <input type="number" class="form-control" id="operationalYear" min="1900" max="2030">
                            </div>
                            <div class="col-6">
                                <label for="guestsHosted" class="form-label">
                                    <i class="bi bi-people form-icon"></i> Guests hosted till March 2025?
                                </label>
                                <input type="number" class="form-control" id="guestsHosted" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="totalArea" class="form-label required">
                            <i class="bi bi-aspect-ratio form-icon"></i> Total Area (sq.ft) of the Property
                        </label>
                        <input type="number" class="form-control" id="totalArea" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="mahabookingNumber" class="form-label">
                            <i class="bi bi-globe form-icon"></i> Mahabooking Portal Registration Number
                        </label>
                        <input type="text" class="form-control" id="mahabookingNumber">
                        <div class="info-text">If already registered on Mahabooking portal</div>
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-outline-brand" id="prevToStep1">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-brand" id="nextToStep3">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Accommodation Details -->
            <div class="form-step" id="step3">
                <div class="form-alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Please provide accurate details about your accommodation facilities.
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
                        <input type="number" class="form-control" id="numberOfRooms" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label for="roomArea" class="form-label required">
                            <i class="bi bi-square form-icon"></i> Area (sq. ft) of each room
                        </label>
                        <input type="number" class="form-control" id="roomArea" min="0" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="attachedToilet" class="form-label required">
                            <i class="bi bi-droplet form-icon"></i> Does each room have attached toilet?
                        </label>
                        <select class="form-select" id="attachedToilet" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dustbins" class="form-label required">
                            <i class="bi bi-trash form-icon"></i> Do the rooms have dustbins for garbage disposal?
                        </label>
                        <select class="form-select" id="dustbins" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="roadAccess" class="form-label required">
                            <i class="bi bi-signpost form-icon"></i> Is the property accessible by road?
                        </label>
                        <select class="form-select" id="roadAccess" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="foodProvided" class="form-label required">
                            <i class="bi bi-egg-fried form-icon"></i> Can food be provided to guests on request?
                        </label>
                        <select class="form-select" id="foodProvided" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="paymentOptions" class="form-label required">
                            <i class="bi bi-credit-card form-icon"></i> Payment collection through cash and/or UPI?
                        </label>
                        <select class="form-select" id="paymentOptions" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-outline-brand" id="prevToStep2">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-brand" id="nextToStep4">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Step 4: Common Facilities -->
            <div class="form-step" id="step4">
                <div class="form-alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Please select all available facilities at your property.
                </div>

                <h4 class="section-title">
                    <i class="bi bi-grid-3x3-gap"></i>
                    D) Common Facilities
                </h4>

                <div class="checkbox-grid mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="kitchen">
                        <label class="form-check-label" for="kitchen">
                            <i class="bi bi-egg-fried me-1"></i> Kitchen
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="diningHall">
                        <label class="form-check-label" for="diningHall">
                            <i class="bi bi-cup-straw me-1"></i> Dining Hall
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="garden">
                        <label class="form-check-label" for="garden">
                            <i class="bi bi-flower1 me-1"></i> Garden
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="parking">
                        <label class="form-check-label" for="parking">
                            <i class="bi bi-car-front me-1"></i> Parking
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="evCharging">
                        <label class="form-check-label" for="evCharging">
                            <i class="bi bi-lightning-charge me-1"></i> EV Charging
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="playArea">
                        <label class="form-check-label" for="playArea">
                            <i class="bi bi-joystick me-1"></i> Children Play Area
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="swimmingPool">
                        <label class="form-check-label" for="swimmingPool">
                            <i class="bi bi-water me-1"></i> Swimming Pool
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="wifi">
                        <label class="form-check-label" for="wifi">
                            <i class="bi bi-wifi me-1"></i> Wi-Fi
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="firstAid">
                        <label class="form-check-label" for="firstAid">
                            <i class="bi bi-bandaid me-1"></i> First Aid Box
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fireSafety">
                        <label class="form-check-label" for="fireSafety">
                            <i class="bi bi-fire me-1"></i> Fire Safety Equipment
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="waterPurifier">
                        <label class="form-check-label" for="waterPurifier">
                            <i class="bi bi-droplet me-1"></i> Water Purifier / RO
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rainwaterHarvesting">
                        <label class="form-check-label" for="rainwaterHarvesting">
                            <i class="bi bi-cloud-rain me-1"></i> Rainwater Harvesting
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="solarPower">
                        <label class="form-check-label" for="solarPower">
                            <i class="bi bi-sun me-1"></i> Solar Power
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="renewableEnergy">
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
                        <select class="form-select" id="applicationFees" required>
                            <option value="" selected disabled>Select Yes/No</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <div class="info-text">Payment must be made through GRAS portal before submission</div>
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-outline-brand" id="prevToStep3">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-brand" id="nextToStep5">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Step 5: Review and Submit -->
            <div class="form-step" id="step5">
                <div class="form-alert">
                    <i class="bi bi-info-circle-fill me-2"></i> Please review all information carefully before submitting.
                </div>

                <h4 class="section-title">
                    <i class="bi bi-clipboard-check"></i>
                    Review Your Information
                </h4>
                <p class="mb-4">Please verify all details before final submission. Incorrect information may delay processing.</p>

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
                        I hereby declare that all the information provided in this application is true and correct to the best of my knowledge. I understand that any false information may lead to rejection of my application.
                    </label>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn btn-outline-brand" id="prevToStep4">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button type="submit" class="btn btn-success" id="submitForm">
                        <i class="bi bi-check-circle"></i> Submit Application
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
                    <p class="info-text">Click to change file</p>
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
            const facilities = [
                { id: 'kitchen', label: 'Kitchen' },
                { id: 'diningHall', label: 'Dining Hall' },
                { id: 'garden', label: 'Garden' },
                { id: 'parking', label: 'Parking' },
                { id: 'evCharging', label: 'EV Charging' },
                { id: 'playArea', label: 'Children Play Area' },
                { id: 'swimmingPool', label: 'Swimming Pool' },
                { id: 'wifi', label: 'Wi-Fi' },
                { id: 'firstAid', label: 'First Aid Box' },
                { id: 'fireSafety', label: 'Fire Safety Equipment' },
                { id: 'waterPurifier', label: 'Water Purifier / RO' },
                { id: 'rainwaterHarvesting', label: 'Rainwater Harvesting' },
                { id: 'solarPower', label: 'Solar Power' },
                { id: 'renewableEnergy', label: 'Other Renewable Energy' }
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
                // In a real application, you would submit the form data to a server here
                alert('Form submitted successfully! Thank you for registering your tourist villa.');
                // Reset form or redirect as needed
            }
        });
    });
</script>
@endpush
