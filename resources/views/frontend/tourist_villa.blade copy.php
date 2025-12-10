<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Villa Registration Form</title>
    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            color: white;
            font-weight: bold;
        }
        .step.active .step-circle {
            background-color: #3498db;
        }
        .step.completed .step-circle {
            background-color: #2ecc71;
        }
        .step-label {
            font-size: 14px;
            text-align: center;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .form-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        .section-title {
            color: #3498db;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }
        .btn-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        .form-check-input:checked {
            background-color: #3498db;
            border-color: #3498db;
        }
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            border-color: #3498db;
            background-color: #f8f9fa;
        }
        .upload-icon {
            font-size: 40px;
            color: #6c757d;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Application Form for the Registration of Tourist Villa</h2>
                <p class="text-muted">Please fill out all required fields marked with an asterisk (*)</p>
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
                    <h4 class="section-title">A) Details of the Applicant</h4>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="applicantName" class="form-label required">Name of the Applicant (Owner of the Unit)</label>
                            <input type="text" class="form-control" id="applicantName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="applicantPhone" class="form-label required">Telephone/Mobile No of Applicant</label>
                            <input type="tel" class="form-control" id="applicantPhone" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="applicantEmail" class="form-label required">E-Mail ID of Applicant</label>
                            <input type="email" class="form-control" id="applicantEmail" required>
                        </div>
                        <div class="col-md-6">
                            <label for="businessName" class="form-label required">Name of the Business</label>
                            <input type="text" class="form-control" id="businessName" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="businessType" class="form-label required">Type of Business</label>
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
                            <label for="panNumber" class="form-label required">PAN Card Number</label>
                            <input type="text" class="form-control" id="panNumber" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="businessPanNumber" class="form-label">Business PAN Card Number (If applicable)</label>
                            <input type="text" class="form-control" id="businessPanNumber">
                        </div>
                        <div class="col-md-6">
                            <label for="aadharNumber" class="form-label required">Aadhar Number of Applicant</label>
                            <input type="text" class="form-control" id="aadharNumber" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="udyamAadharNumber" class="form-label">Udyam Aadhar Number (If applicable)</label>
                            <input type="text" class="form-control" id="udyamAadharNumber">
                        </div>
                        <div class="col-md-6">
                            <label for="ownershipProof" class="form-label required">Proof of Ownership of the Property</label>
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
                            <label for="propertyRented" class="form-label required">Is the property rented to an operator?</label>
                            <select class="form-select" id="propertyRented" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="operatorNameContainer" style="display: none;">
                            <label for="operatorName" class="form-label">Operator's Name</label>
                            <input type="text" class="form-control" id="operatorName">
                        </div>
                    </div>

                    <div class="mb-3" id="rentalAgreementContainer" style="display: none;">
                        <label for="rentalAgreement" class="form-label">Share copy the rental agreement / management contract executed with the operator</label>
                        <div class="upload-area" id="rentalAgreementUpload">
                            <i class="bi bi-cloud-upload upload-icon"></i>
                            <p>Click to upload or drag and drop</p>
                            <input type="file" class="d-none" id="rentalAgreement">
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" disabled>Previous</button>
                        <button type="button" class="btn btn-primary" id="nextToStep2">Next</button>
                    </div>
                </div>

                <!-- Step 2: Property Details -->
                <div class="form-step" id="step2">
                    <h4 class="section-title">B) Details of the Property</h4>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="propertyName" class="form-label required">Name of the Property</label>
                            <input type="text" class="form-control" id="propertyName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="propertyAddress" class="form-label required">Complete Postal Address of the Property</label>
                            <textarea class="form-control" id="propertyAddress" rows="2" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addressProof" class="form-label required">Proof of Address</label>
                            <select class="form-select" id="addressProof" required>
                                <option value="" selected disabled>Select Proof Type</option>
                                <option value="Latest Electricity Bill">Latest Electricity Bill</option>
                                <option value="Water Bill">Water Bill</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="propertyCoordinates" class="form-label required">Geographic Co-ordinates of the Property / Google Map Link</label>
                            <input type="text" class="form-control" id="propertyCoordinates" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="propertyOperational" class="form-label required">Is the Tourism Villa already Operational?</label>
                            <select class="form-select" id="propertyOperational" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="operationalDetailsContainer" style="display: none;">
                            <div class="row">
                                <div class="col-6">
                                    <label for="operationalYear" class="form-label">Since which year?</label>
                                    <input type="number" class="form-control" id="operationalYear" min="1900" max="2030">
                                </div>
                                <div class="col-6">
                                    <label for="guestsHosted" class="form-label">Guests hosted till March 2025?</label>
                                    <input type="number" class="form-control" id="guestsHosted" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="totalArea" class="form-label required">Total Area (sq.ft) of the Property</label>
                            <input type="number" class="form-control" id="totalArea" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="mahabookingNumber" class="form-label">Share the registration number received from Mahabooking portal</label>
                            <input type="text" class="form-control" id="mahabookingNumber">
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="prevToStep1">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextToStep3">Next</button>
                    </div>
                </div>

                <!-- Step 3: Accommodation Details -->
                <div class="form-step" id="step3">
                    <h4 class="section-title">C) Details of the Accommodation</h4>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numberOfRooms" class="form-label required">Number of Rooms</label>
                            <input type="number" class="form-control" id="numberOfRooms" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="roomArea" class="form-label required">Area (sq. ft) of each room</label>
                            <input type="number" class="form-control" id="roomArea" min="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="attachedToilet" class="form-label required">Does each room have attached toilet?</label>
                            <select class="form-select" id="attachedToilet" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="dustbins" class="form-label required">Do the rooms have dustbins for garbage disposal?</label>
                            <select class="form-select" id="dustbins" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="roadAccess" class="form-label required">Is the property accessible by road?</label>
                            <select class="form-select" id="roadAccess" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="foodProvided" class="form-label required">Can food be provided to guests on request?</label>
                            <select class="form-select" id="foodProvided" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="paymentOptions" class="form-label required">Are there provisions to collect payment through cash and/or UPI?</label>
                            <select class="form-select" id="paymentOptions" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="prevToStep2">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextToStep4">Next</button>
                    </div>
                </div>

                <!-- Step 4: Common Facilities -->
                <div class="form-step" id="step4">
                    <h4 class="section-title">D) Common Facilities</h4>

                    <div class="checkbox-grid mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="kitchen">
                            <label class="form-check-label" for="kitchen">Kitchen</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="diningHall">
                            <label class="form-check-label" for="diningHall">Dining Hall</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="garden">
                            <label class="form-check-label" for="garden">Garden</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="parking">
                            <label class="form-check-label" for="parking">Parking</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="evCharging">
                            <label class="form-check-label" for="evCharging">EV charging stations/facility</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="playArea">
                            <label class="form-check-label" for="playArea">Children Play Area</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="swimmingPool">
                            <label class="form-check-label" for="swimmingPool">Swimming Pool</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="wifi">
                            <label class="form-check-label" for="wifi">Wi fi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="firstAid">
                            <label class="form-check-label" for="firstAid">First Aid Box</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fireSafety">
                            <label class="form-check-label" for="fireSafety">Fire Safety Equipment</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="waterPurifier">
                            <label class="form-check-label" for="waterPurifier">Water Purifier / RO</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rainwaterHarvesting">
                            <label class="form-check-label" for="rainwaterHarvesting">Rainwater Harvesting / Ground Water Recharge Facility</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="solarPower">
                            <label class="form-check-label" for="solarPower">Use of Solar Power</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="renewableEnergy">
                            <label class="form-check-label" for="renewableEnergy">Use of any other renewable Energy-Biogas, wind, etc.</label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="section-title">E) GRAS Chalan (www.gras.mahakosh.gov.in)</h5>
                        <div class="mb-3">
                            <label for="applicationFees" class="form-label required">Application fees (Rs. 500/-) Paid</label>
                            <select class="form-select" id="applicationFees" required>
                                <option value="" selected disabled>Select Yes/No</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="prevToStep3">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextToStep5">Next</button>
                    </div>
                </div>

                <!-- Step 5: Review and Submit -->
                <div class="form-step" id="step5">
                    <h4 class="section-title">Review Your Information</h4>
                    <p class="mb-4">Please review all the information you've provided before submitting the form.</p>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">A) Applicant Details</h5>
                        </div>
                        <div class="card-body">
                            <div id="reviewApplicantDetails">
                                <!-- Applicant details will be populated here by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">B) Property Details</h5>
                        </div>
                        <div class="card-body">
                            <div id="reviewPropertyDetails">
                                <!-- Property details will be populated here by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">C) Accommodation Details</h5>
                        </div>
                        <div class="card-body">
                            <div id="reviewAccommodationDetails">
                                <!-- Accommodation details will be populated here by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">D) Common Facilities</h5>
                        </div>
                        <div class="card-body">
                            <div id="reviewFacilities">
                                <!-- Facilities will be populated here by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">E) GRAS Chalan</h5>
                        </div>
                        <div class="card-body">
                            <div id="reviewChalan">
                                <!-- Chalan details will be populated here by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="prevToStep4">Previous</button>
                        <button type="submit" class="btn btn-success" id="submitForm">Submit Application</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
                    `;
                }
            });

            // Step navigation functions
            function goToStep(stepNumber) {
                steps.forEach(step => step.classList.remove('active'));
                document.getElementById(`step${stepNumber}`).classList.add('active');

                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.remove('active');
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
                    <p><strong>Name:</strong> ${document.getElementById('applicantName').value}</p>
                    <p><strong>Phone:</strong> ${document.getElementById('applicantPhone').value}</p>
                    <p><strong>Email:</strong> ${document.getElementById('applicantEmail').value}</p>
                    <p><strong>Business Name:</strong> ${document.getElementById('businessName').value}</p>
                    <p><strong>Business Type:</strong> ${document.getElementById('businessType').value}</p>
                    <p><strong>PAN:</strong> ${document.getElementById('panNumber').value}</p>
                    <p><strong>Business PAN:</strong> ${document.getElementById('businessPanNumber').value || 'N/A'}</p>
                    <p><strong>Aadhar:</strong> ${document.getElementById('aadharNumber').value}</p>
                    <p><strong>Udyam Aadhar:</strong> ${document.getElementById('udyamAadharNumber').value || 'N/A'}</p>
                    <p><strong>Ownership Proof:</strong> ${document.getElementById('ownershipProof').value}</p>
                    <p><strong>Property Rented:</strong> ${document.getElementById('propertyRented').value}</p>
                    ${document.getElementById('propertyRented').value === 'Yes' ?
                      `<p><strong>Operator Name:</strong> ${document.getElementById('operatorName').value}</p>` : ''}
                `;

                // Property Details
                document.getElementById('reviewPropertyDetails').innerHTML = `
                    <p><strong>Property Name:</strong> ${document.getElementById('propertyName').value}</p>
                    <p><strong>Address:</strong> ${document.getElementById('propertyAddress').value}</p>
                    <p><strong>Address Proof:</strong> ${document.getElementById('addressProof').value}</p>
                    <p><strong>Coordinates:</strong> ${document.getElementById('propertyCoordinates').value}</p>
                    <p><strong>Operational:</strong> ${document.getElementById('propertyOperational').value}</p>
                    ${document.getElementById('propertyOperational').value === 'Yes' ?
                      `<p><strong>Operational Since:</strong> ${document.getElementById('operationalYear').value}</p>
                       <p><strong>Guests Hosted:</strong> ${document.getElementById('guestsHosted').value}</p>` : ''}
                    <p><strong>Total Area:</strong> ${document.getElementById('totalArea').value} sq.ft</p>
                    <p><strong>Mahabooking Number:</strong> ${document.getElementById('mahabookingNumber').value || 'N/A'}</p>
                `;

                // Accommodation Details
                document.getElementById('reviewAccommodationDetails').innerHTML = `
                    <p><strong>Number of Rooms:</strong> ${document.getElementById('numberOfRooms').value}</p>
                    <p><strong>Room Area:</strong> ${document.getElementById('roomArea').value} sq.ft</p>
                    <p><strong>Attached Toilet:</strong> ${document.getElementById('attachedToilet').value}</p>
                    <p><strong>Dustbins:</strong> ${document.getElementById('dustbins').value}</p>
                    <p><strong>Road Access:</strong> ${document.getElementById('roadAccess').value}</p>
                    <p><strong>Food Provided:</strong> ${document.getElementById('foodProvided').value}</p>
                    <p><strong>Payment Options:</strong> ${document.getElementById('paymentOptions').value}</p>
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

                let facilitiesHTML = '<p>';
                const selectedFacilities = facilities.filter(facility =>
                    document.getElementById(facility.id).checked
                );

                if (selectedFacilities.length > 0) {
                    facilitiesHTML += selectedFacilities.map(facility => facility.label).join(', ');
                } else {
                    facilitiesHTML += 'No facilities selected';
                }
                facilitiesHTML += '</p>';

                document.getElementById('reviewFacilities').innerHTML = facilitiesHTML;

                // Chalan
                document.getElementById('reviewChalan').innerHTML = `
                    <p><strong>Application Fees Paid:</strong> ${document.getElementById('applicationFees').value}</p>
                `;
            }

            // Form submission
            document.getElementById('submitForm').addEventListener('click', function(e) {
                e.preventDefault();

                if (validateStep(5)) {
                    // In a real application, you would submit the form data to a server here
                    alert('Form submitted successfully!');
                    // Reset form or redirect as needed
                }
            });
        });
    </script>
</body>
</html>
