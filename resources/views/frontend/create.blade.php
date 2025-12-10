@extends('frontend.layouts2.master')
@section('title', 'Provisional Registration')

@push('styles')
@endpush

@section('content')
    <!-- Main Content -->

<div class="auth-wrap container">
    <h2 class="text-center mb-4 text-white">Application for Provisional Registration Certificate</h2>

    <!-- General Details -->
 <form id="generalDetailsForm" action="#" method="POST" novalidate>
    <div class="form-section card p-2">
      <h5 class="section-title">General Details</h5>
      <div class="row g-3">
        <!-- 1. Applicant Name -->
   <div class="col-md-6">
        <label class="form-label">1. Applicant Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="applicant_name" required>
      </div>

         <!-- 2. Company Name -->
      <div class="col-md-6">
        <label class="form-label">2. Company Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="company_name" required>
      </div>

           <!-- 3. Type of Enterprise -->
      <div class="col-md-6">
        <label class="form-label">3. Type of Enterprise <span class="text-danger">*</span></label>
        <select class="form-control" name="enterprise_type" required>
          <option value="">Select</option>
          <option>Proprietorship</option>
          <option>Partnership</option>
          <option>Private Limited</option>
          <option>LLP</option>
          <option>Public Limited</option>
          <option>Co-operative</option>
          <option>Trust</option>
          <option>Society</option>
          <option>Government</option>
          <option>Semi-Government</option>
        </select>
      </div>


       <!-- 4. Aadhar Number -->
      <div class="col-md-6">
        <label class="form-label">4. Aadhar Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="aadhar_number" maxlength="12" minlength="12" required>
      </div>
      
        <!-- 5. Application Category -->
      <div class="col-md-6">
        <label class="form-label">5. Application Category <span class="text-danger">*</span></label>
        <select class="form-control" name="application_category" required>
          <option value="">Select</option>
          <option>SC/ST</option>
          <option>Differently abled</option>
          <option>Women Entrepreneur</option>
          <option>General</option>
        </select>
      </div>
      </div>
    </div>

    <!-- Project Details -->
    <div class="form-section card p-2">
      <h5 class="section-title">Project Details</h5>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">1. Site Address</label>
          
          <!-- Survey No. / CTS No. / Gat No. -->
    <div class="col-md-6">
      <label class="form-label">Survey No. / CTS No. / Gat No.</label>
      <div class="input-group">
        <select class="form-control" name="survey_type" required>
          <option value="">Select Type</option>
          <option value="Survey No.">Survey No.</option>
          <option value="CTS No.">CTS No.</option>
          <option value="Gat No.">Gat No.</option>
        </select>
        <input type="text" class="form-control" name="survey_number" placeholder="Enter Number" required>
      </div>
    </div>
    </div>
        <div class="col-md-4">
        <label class="form-label">Village/City</label>
        <input type="text" class="form-control" name="village_city" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Taluka</label>
        <input type="text" class="form-control" name="taluka" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">District</label>
        <input type="text" class="form-control" name="district" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">State</label>
        <input type="text" class="form-control" name="state" required>
      </div>

       <div class="col-md-4">
      <label class="form-label">Pincode</label>
      <input type="text" class="form-control" name="pincode" maxlength="6" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Mobile Number</label>
      <input type="text" class="form-control" name="mobile" maxlength="10" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>

      <div class="col-md-6">
        <label class="form-label">Website</label>
        <input type="url" class="form-control" name="website" placeholder="https://example.com" required>
      </div>
      </div>
    </div>

       <!-- 2. Udyog Aadhar Number -->
    <div class="col-md-6">
      <label class="form-label">2. Udyog Aadhar Number <small class="text-muted">(If applicable)</small></label>
      <input type="text" class="form-control" name="udyog_aadhar" placeholder="Enter Udyog Aadhar Number">
    </div>

      <!-- 3. GST Registration Number -->
    <div class="col-md-6">
      <label class="form-label">3. GST Registration Number <small class="text-muted">(If available)</small></label>
      <input type="text" class="form-control" name="gst_number" placeholder="Enter GST Number">
    </div>

<!-- 4. Zone as per MTP 2024 -->
    <div class="col-md-6 mb-3">
      <label class="form-label d-block">
        4. Zone as per MTP 2024 <span class="text-danger">*</span>
      </label>
      <div class="d-flex flex-wrap gap-3">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="zone" id="zoneA" value="A">
          <label class="form-check-label" for="zoneA">A</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="zone" id="zoneB" value="B">
          <label class="form-check-label" for="zoneB">B</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="zone" id="zoneC" value="C">
          <label class="form-check-label" for="zoneC">C</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="zone" id="zoneSTZ" value="STZ/STD">
          <label class="form-check-label" for="zoneSTZ">STZ/STD</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="zone" id="zoneAll" value="Entire State">
          <label class="form-check-label" for="zoneAll">Entire State</label>
        </div>
      </div>
      <div id="zoneError"></div> <!-- Error message placeholder -->
    </div>

    <!-- 5. Type of Project -->
    <div class="col-md-12">
      <label class="form-label d-block">5. Type of Project <span class="text-danger">*</span></label>
      <div class="d-flex flex-wrap gap-3 mb-2">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="project_type" id="projectNew" value="New">
          <label class="form-check-label" for="projectNew">New</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="project_type" id="projectExpansion" value="Expansion">
          <label class="form-check-label" for="projectExpansion">Expansion</label>
        </div>
      </div>

      <!-- Hidden when "New" selected -->
      <div id="eligibilityField" class="mt-3" style="display: none;">
        <label class="form-label">Eligibility Certificate Number 
          <small class="text-muted">(If Applicable)</small>
        </label>
        <input type="text" class="form-control mb-3" name="eligibility_certificate" placeholder="Enter Eligibility Certificate Number">

        <label class="form-label">Expansion Details</label>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle text-center" id="expansionTable">
            <thead class="table-secondary">
              <tr>
                <th rowspan="2">#</th>
                <th colspan="2">Existing Capacity</th>
                <th colspan="2">Expansion Details</th>
                <th rowspan="2">Action</th>
              </tr>
              <tr>
                <th>Facilities</th>
                <th>Employment</th>
                <th>Facilities</th>
                <th>Employment</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><input type="text" class="form-control" name="existing_facilities[]"></td>
                <td><input type="number" class="form-control" name="existing_employment[]"></td>
                <td><input type="text" class="form-control" name="expansion_facilities[]"></td>
                <td><input type="number" class="form-control" name="expansion_employment[]"></td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-primary btn-sm addRow" title="Add Row">
                            <i class="bi bi-plus-circle"></i>
                        </button>

                        
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>

<!-- 6. Entrepreneurs Profile -->
<div class="col-12 mt-3">
  <label class="form-label fw-semibold">
    6. Entrepreneurs Profile (Of All Partner/Directors of the Organization)
  </label>
  
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center" id="entrepreneurTable">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Designation</th>
          <th>Ownership %</th>
          <th>Gender</th>
          <th>Age</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td><input type="text" class="form-control" name="entre_name[]" placeholder="Enter Name"></td>
          <td><input type="text" class="form-control" name="entre_designation[]" placeholder="Enter Designation"></td>
          <td><input type="number" class="form-control" name="entre_ownership[]" placeholder="%"></td>
          <td>
            <select class="form-control" name="entre_gender[]">
              <option value="">Select</option>
              <option>Male</option>
              <option>Female</option>
              <option>Other</option>
            </select>
          </td>
          <td><input type="number" class="form-control" name="entre_age[]" placeholder="Enter Age"></td>
          <td>
            <div class="d-flex justify-content-center gap-2">
              
              <button type="button" class="btn btn-primary btn-sm addEntreRow" title="Add Row">
                <i class="bi bi-plus-circle"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <p class="text-muted mt-2 mb-0">
    *If more profiles, kindly provide the details on a blank page in the same table format.
  </p>
</div>


<!-- 7. Project Category -->
<div class="col-12 mt-3">
  <label class="form-label fw-semibold">7. Project Category:</label>
  <div class="d-flex flex-wrap gap-3 mt-2">

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="project_category" id="categoryA" value="Accommodation A" required>
      <label class="form-check-label" for="categoryA">Accommodation A</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="project_category" id="categoryB" value="Accommodation B">
      <label class="form-check-label" for="categoryB">Accommodation B</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="project_category" id="categoryFB" value="F&B">
      <label class="form-check-label" for="categoryFB">F&amp;B</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="project_category" id="categoryTravel" value="Travel and Tourism">
      <label class="form-check-label" for="categoryTravel">Travel and Tourism</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="project_category" id="categoryEntertainment" value="Entertainment and Recreation">
      <label class="form-check-label" for="categoryEntertainment">Entertainment &amp; Recreation</label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="project_category" id="categoryOthers" value="Others">
      <label class="form-check-label" for="categoryOthers">Others</label>
    </div>
  </div>

  <!-- Hidden input shown only if user selects "Others" -->
  <div id="otherCategoryField" class="mt-3" style="display: none;">
    <label class="form-label">Please specify other category:</label>
    <input type="text" class="form-control" name="other_category" placeholder="Enter category name">
  </div>
</div>


<!-- 8. Project Subcategory -->
  <div class="col-md-6 mt-3">
    <label class="form-label fw-semibold">
      8. Project Subcategory: <span class="text-danger">*</span>
    </label>
    <input
      type="text"
      class="form-control"
      name="project_subcategory"
      placeholder="Enter project subcategory"
      required
    >
    <div id="subcategoryError"></div>
  </div>


  <!-- 9. Project Description -->
  <div class="col-12 mt-3">
    <label class="form-label fw-semibold">
      9. Project Description: <span class="text-danger">*</span>
    </label>
    <textarea 
      class="form-control" 
      name="project_description" 
      rows="4"
        id="project_description"
      placeholder="Enter detailed description of the project including purpose, objectives, and scope..." 
      required>
    </textarea>
    <div id="descriptionError"></div>
  </div>

<!-- 10. Proposed Investment Details -->
<div class="col-12 mt-3">
  <label class="form-label fw-semibold">10. Proposed Investment Details</label>

  <!-- a. Land Details -->
  <div class="card p-3 mt-2">
    <label class="form-label">a. Land Details:</label>
    <div class="row g-3 align-items-center">
       <div class="col-md-6">
    <label class="form-label fw-semibold">
      Area (Sq. meters) <span class="text-danger">*</span>
    </label>
    <input 
      type="number" 
      class="form-control" 
      name="land_area" 
      placeholder="Enter area in Sq. meters" 
      required>
    <div id="landAreaError"></div>
  </div>


      <div class="col-md-6">
    
  <div class="col-md-6 mb-3">
    <label class="form-label fw-semibold">Ownership Type: <span class="text-danger">*</span></label>
    <div class="d-flex flex-wrap gap-3 mt-1">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="land_ownership_type" id="landOwned" value="Owned">
        <label class="form-check-label" for="landOwned">Owned</label>
      </div>

      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="land_ownership_type" id="landLeased" value="Leased">
        <label class="form-check-label" for="landLeased">Leased</label>
      </div>

      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="land_ownership_type" id="landRent" value="Rent">
        <label class="form-check-label" for="landRent">Rent</label>
      </div>
    </div>

    <!-- Error message placeholder -->
    <div id="ownershipTypeError"></div>
  </div>
      </div>
    </div>
  </div>

  <!-- b. Building Details -->
  <div class="card p-3 mt-3">
    <label class="form-label">b. Building Details:</label>
    <div class="row g-3 align-items-center">
      <div class="col-md-6">
        <label class="form-label">Ownership Type:</label>
        <div class="d-flex flex-wrap gap-3 mt-1">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="building_ownership_type" id="buildingOwned" value="Owned">
            <label class="form-check-label" for="buildingOwned">Owned</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="building_ownership_type" id="buildingLeased" value="Leased">
            <label class="form-check-label" for="buildingLeased">Leased</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="building_ownership_type" id="buildingRent" value="Rent">
            <label class="form-check-label" for="buildingRent">Rent</label>
          </div>
        </div>
      </div>
       <div class="col-md-6 mb-3">
    <label class="form-label fw-semibold">c. Project Cost (₹): <span class="text-danger">*</span></label>
    <input 
      type="number" 
      class="form-control" 
      name="project_cost" 
      placeholder="Enter total project cost in ₹"
      required>
    <div id="projectCostError"></div>
  </div>

 
    </div>
  </div>



<!-- d. Total Number of People to be Employed -->
  <div class="card p-3 mt-3">
    <label class="form-label fw-semibold">
      d. Total number of people to be employed on the Tourism Project: <span class="text-danger">*</span>
    </label>
    <div class="row g-3 align-items-center">
      <div class="col-md-6">
        <input 
          type="number" 
          class="form-control" 
          name="total_employees" 
          placeholder="Enter total number of employees"
          required>
        <div id="totalEmployeesError"></div>
      </div>
    </div>
  </div>
</div>


    <!-- e. Means of Finance / Investment Details -->
<div class="col-12 mt-3">
  <label class="form-label fw-semibold">e. Means of Finance / Investment Details</label>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center" id="investmentTable">
      <thead class="table-primary">
        <tr>
          <th style="width: 30%;">Component</th>
          <th style="width: 35%;">Estimated Cost (₹ in Lakh)</th>
          <th style="width: 35%;">Investment already made on or after the date of coming into effect of the scheme (₹ in Lakh)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Land</td>
          <td><input type="number" class="form-control est-cost" name="land_est" placeholder="0.00" step="0.01" min="0"></td>
          <td><input type="number" class="form-control inv-made" name="land_inv" placeholder="0.00" step="0.01" min="0"></td>
        </tr>
        <tr>
          <td>Building</td>
          <td><input type="number" class="form-control est-cost" name="building_est" placeholder="0.00" step="0.01" min="0"></td>
          <td><input type="number" class="form-control inv-made" name="building_inv" placeholder="0.00" step="0.01" min="0"></td>
        </tr>
        <tr>
          <td>Plant & Machinery</td>
          <td><input type="number" class="form-control est-cost" name="machinery_est" placeholder="0.00" step="0.01" min="0"></td>
          <td><input type="number" class="form-control inv-made" name="machinery_inv" placeholder="0.00" step="0.01" min="0"></td>
        </tr>
        <tr>
          <td>Engineering Fees</td>
          <td><input type="number" class="form-control est-cost" name="engineering_est" placeholder="0.00" step="0.01" min="0"></td>
          <td><input type="number" class="form-control inv-made" name="engineering_inv" placeholder="0.00" step="0.01" min="0"></td>
        </tr>
        <tr>
          <td>Preliminary, Pre-Operative Expense</td>
          <td><input type="number" class="form-control est-cost" name="preop_est" placeholder="0.00" step="0.01" min="0"></td>
          <td><input type="number" class="form-control inv-made" name="preop_inv" placeholder="0.00" step="0.01" min="0"></td>
        </tr>
        <tr>
          <td>Margin for Working Capital</td>
          <td><input type="number" class="form-control est-cost" name="margin_est" placeholder="0.00" step="0.01" min="0"></td>
          <td><input type="number" class="form-control inv-made" name="margin_inv" placeholder="0.00" step="0.01" min="0"></td>
        </tr>

        <!-- Total Row -->
        <tr class="table-secondary fw-bold">
          <td>Total</td>
          <td><input type="text" class="form-control" id="totalEst" readonly></td>
          <td><input type="text" class="form-control" id="totalInv" readonly></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>





    <!-- Means of Finance -->
<div class="form-section card p-2">
  <h5 class="section-title">Means of Finance</h5>
  <div class="table-responsive">
    <table class="table table-bordered align-middle" id="financeTable">
      <thead class="table-secondary">
        <tr>
          <th>Category</th>
          <th>Details</th>
          <th>Amount (₹)</th>
        </tr>
      </thead>
      <tbody>
        <!-- Share Capital -->
        <tr>
          <td rowspan="4">Share Capital</td>
          <td>Promoters</td>
          <td><input type="number" class="form-control share-input" min="0" step="any"></td>
        </tr>
        <tr>
          <td>Financial Institutions</td>
          <td><input type="number" class="form-control share-input" min="0" step="any"></td>
        </tr>
        <tr>
          <td>Public</td>
          <td><input type="number" class="form-control share-input" min="0" step="any"></td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td><input type="number" class="form-control share-total" readonly></td>
        </tr>

        <!-- Loans -->
        <tr>
          <td rowspan="4">Loans</td>
          <td>Financial Institutions</td>
          <td><input type="number" class="form-control loan-input" min="0" step="any"></td>
        </tr>
        <tr>
          <td>Banks</td>
          <td><input type="number" class="form-control loan-input" min="0" step="any"></td>
        </tr>
        <tr>
          <td>Others (If any)</td>
          <td><input type="number" class="form-control loan-input" min="0" step="any"></td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td><input type="number" class="form-control loan-total" readonly></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Enclosures Section -->
<div class="col-12 mt-4">
  <label class="form-label fw-semibold">Enclosures:</label>
  <p class="text-muted mb-2">Tick mark the necessary documents enclosed with the application form</p>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center" id="enclosureTable">
      <thead class="table-primary">
        <tr>
          <th style="width: 5%;">Select</th>
          <th style="width: 30%;">Document Type</th>
          <th style="width: 20%;">Doc No.</th>
          <th style="width: 20%;">Date of Issue</th>
          <th style="width: 25%;">Upload Document</th>
        </tr>
      </thead>
      <tbody>
        <!-- 1 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Commencement Certificate / Plan Sanction Letter</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 2 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Copy of Sanctioned Plan of Construction</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 3 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Proof of Identity</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 4 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Proof of Address</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 5 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">
            Land Ownership Document
            <div class="d-flex justify-content-center gap-3 mt-2">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="land_type" id="landOwned" value="Owned" disabled>
                <label class="form-check-label" for="landOwned">Owned</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="land_type" id="landLeased" value="Leased" disabled>
                <label class="form-check-label" for="landLeased">Leased</label>
              </div>
            </div>
          </td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 6 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Project Report</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 7 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">
            Memorandum and Article of Association along with Certificate of Incorporation of the Company /
            Partnership Deed / Registration of Co-operative Society / Registration of Trust
          </td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 8 -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">GST Registration</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        
        <!-- 9 Proof of Special Category Application -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Proof of Special Category Application (Refer to 14.4.6 of MTP 2024)</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 10 CA Certificate -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">CA Certificate on Project Cost including investments already made</td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>

        <!-- 11 Processing Fee Challan -->
        <tr>
          <td><input type="checkbox" class="form-check-input doc-check"></td>
          <td class="text-start">Processing Fee Challan (₹10,000) — paid on <a href="https://www.gras.mahakosh.gov.in" target="_blank">www.gras.mahakosh.gov.in</a></td>
          <td><input type="text" class="form-control" placeholder="Enter Doc No." disabled></td>
          <td><input type="date" class="form-control" disabled></td>
          <td><input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" disabled></td>
        </tr>



      </tbody>
    </table>
  </div>
</div>


    <!-- Other Documents -->
    <div class="form-section">
      <h5 class="section-title">Other Documents</h5>
      <table class="table table-bordered table-striped" id="otherDocs">
        <thead class="table-primary">
          <tr>
            <th>Document Name</th><th>Document No</th><th>Issue Date</th><th>Validity Date</th><th>Upload</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" class="form-control"></td>
            <td><input type="text" class="form-control"></td>
            <td><input type="date" class="form-control"></td>
            <td><input type="date" class="form-control"></td>
            <td><input type="file" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
          </tr>
        </tbody>
      </table>
      <button type="button" class="btn btn-success btn-sm" id="addDocRow">+ Add Document</button>
    </div>

    <!-- Notes -->
    <div class="form-section">
      <h5 class="section-title">Notes</h5>
      <ul>
        <li>All documents should be self-attested by the applicant.</li>
        <li>In case of multiple NOC/Certificate/Insurance, please fill details in the "Other Documents" section above.</li>
        <li>In case of more than 5 other documents, please provide details on an additional page.</li>
        <li>Fields marked with * are mandatory.</li>
      </ul>
    </div>

    <!-- Declaration -->
    <div class="form-section">
      <h5 class="section-title">Declaration</h5>
      <ol>
        <li>Certified that the information / statement contained in this application are true to the best of my / our knowledge and belief.</li>
        <li>Declared that no Government enquiry has been instituted against the applicant unit and / or any of its Proprietor / Partner(s)/ Director(s) of this applicant unit for any economic offence.</li>
        <li>We hereby agree to abide by the terms and conditions of the certificate to be issued.</li>
        <li>We hereby agree that the Provisional Registration letter issued on the basis of the above statements made and information furnished either along with this application or hereafter in connection with the above matter is liable to be cancelled ab-initio or rendered invalid or withdrawn if any of the statements and / or information is / are found to incorrect / untrue. The applicant will be liable to the relevant legal prosecution by the Government in such a situation.</li>
      </ol>
 <!-- Declaration Checkbox -->
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" id="declaration">
    <label class="form-check-label" for="declaration">I hereby agree to the above declaration.</label>
    <div class="invalid-feedback" style="display:none; color:red;">
      You must agree to the declaration before submitting.
    </div>
  </div>

  <!-- Place and Date -->
  <div class="row mb-3">
    <div class="col-md-6">
      <label class="form-label" for="place">Place</label>
      <input type="text" class="form-control" id="place">
      <div class="invalid-feedback" style="display:none; color:red;">Place is required.</div>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="date">Date</label>
      <input type="date" class="form-control" id="date">
      <div class="invalid-feedback" style="display:none; color:red;">Date is required.</div>
    </div>
  </div>

  <!-- Signature -->
  <div class="mt-3">
    <label class="form-label" for="signature">Signature of Applicant (Proprietor / Partner / Director / Trustee)</label>
    <input type="file" class="form-control" id="signature" accept="image/*,application/pdf">
    <div class="invalid-feedback" style="display:none; color:red;">Please upload your signature or PDF document.</div>
  </div>
    </div>


<div class="alert alert-secondary small fst-italic mt-4" role="alert">
  (This application shall be signed only by any one of the persons indicated above with appropriate rubber stamp of the applicant and designation of the signatory.)
</div>


    <div class="text-center mb-5">
      <button type="submit" class="btn btn-primary px-4">Submit Application</button>
    </div>
    </form>
  </div>

 




@endsection
    @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>


    // Dynamic Other Documents Rows
    document.getElementById('addDocRow').addEventListener('click', function() {
      const table = document.querySelector('#otherDocs tbody');
      const newRow = table.rows[0].cloneNode(true);
      newRow.querySelectorAll('input').forEach(i => i.value = '');
      table.appendChild(newRow);
    });

    // Remove Row
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('removeRow')) {
        const row = e.target.closest('tr');
        const tbody = row.parentElement;
        if (tbody.rows.length > 1) row.remove();
      }
    });

    

    // Auto Totals
    function calcTotal(selector, outputId) {
      let total = 0;
      document.querySelectorAll(selector).forEach(i => total += parseFloat(i.value) || 0);
      document.getElementById(outputId).value = total.toFixed(2);
    }
    document.querySelectorAll('.share-input').forEach(i => i.addEventListener('input', () => calcTotal('.share-input', 'shareTotal')));
    document.querySelectorAll('.loan-input').forEach(i => i.addEventListener('input', () => calcTotal('.loan-input', 'loanTotal')));
  </script>


<!-- JavaScript for show/hide + dynamic rows -->
<script>
  const projectNew = document.getElementById('projectNew');
  const projectExpansion = document.getElementById('projectExpansion');
  const eligibilityField = document.getElementById('eligibilityField');

  // Show/hide the expansion table when "Expansion" selected
  function toggleEligibilityField() {
    eligibilityField.style.display = projectExpansion.checked ? 'block' : 'none';
  }

  projectNew.addEventListener('change', toggleEligibilityField);
  projectExpansion.addEventListener('change', toggleEligibilityField);

  // Dynamic row addition/removal for Expansion Details table
  document.getElementById('addExpansionRow').addEventListener('click', function () {
    const tableBody = document.querySelector('#expansionTable tbody');
    const newRow = tableBody.rows[0].cloneNode(true);

    // Clear previous input values
    newRow.querySelectorAll('input').forEach(input => input.value = '');

    // Update row number
    newRow.querySelector('td').textContent = tableBody.rows.length + 1;

    tableBody.appendChild(newRow);
  });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
      const row = e.target.closest('tr');
      const tableBody = document.querySelector('#expansionTable tbody');
      if (tableBody.rows.length > 1) row.remove();
    }
  });
</script>

<!-- JavaScript for dynamic add/remove row -->
<script>
  const tableBody = document.querySelector('#entrepreneurTable tbody');
  const addBtn = document.getElementById('addEntreRow');

  addBtn.addEventListener('click', function() {
    const newRow = tableBody.rows[0].cloneNode(true);
    newRow.querySelectorAll('input, select').forEach(input => input.value = '');
    newRow.querySelector('td').textContent = tableBody.rows.length + 1;
    tableBody.appendChild(newRow);
  });

  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow')) {
      const row = e.target.closest('tr');
      const rows = tableBody.rows;
      if (rows.length > 1) row.remove();

      // Renumber rows
      [...rows].forEach((r, i) => r.cells[0].textContent = i + 1);
    }
  });
</script>

<!-- JavaScript for showing/hiding 'Others' field -->
<script>
  // Select all category radio buttons
  const categoryRadios = document.querySelectorAll('input[name="project_category"]');
  const otherCategoryField = document.getElementById('otherCategoryField');

  categoryRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.value === 'Others') {
        otherCategoryField.style.display = 'block';
      } else {
        otherCategoryField.style.display = 'none';
        otherCategoryField.querySelector('input').value = '';
      }
    });
  });
</script>

<!-- JavaScript for auto total calculation -->
<script>
  function calculateTotals() {
    let totalEst = 0, totalInv = 0;

    document.querySelectorAll('.est-cost').forEach(input => {
      totalEst += parseFloat(input.value) || 0;
    });

    document.querySelectorAll('.inv-made').forEach(input => {
      totalInv += parseFloat(input.value) || 0;
    });

    document.getElementById('totalEst').value = totalEst.toFixed(2);
    document.getElementById('totalInv').value = totalInv.toFixed(2);
  }

  // Add event listeners for real-time updates
  document.querySelectorAll('.est-cost, .inv-made').forEach(input => {
    input.addEventListener('input', calculateTotals);
  });
</script>


    <!-- JavaScript to enable/disable inputs dynamically -->
<script>
  document.querySelectorAll('#enclosureTable .doc-check').forEach((checkbox) => {
    checkbox.addEventListener('change', function() {
      const row = this.closest('tr');
      const inputs = row.querySelectorAll('input[type="text"], input[type="date"], input[type="file"], input[type="radio"]');
      inputs.forEach(input => {
        input.disabled = !this.checked;
      });
    });
  });
</script>


<!-- JavaScript for Auto Totals -->
<script>
  // Function to calculate totals dynamically
  function calculateTotals(classSelector, totalSelector) {
    const inputs = document.querySelectorAll(classSelector);
    const totalField = document.querySelector(totalSelector);
    let total = 0;

    inputs.forEach(input => {
      const val = parseFloat(input.value) || 0;
      total += val;
    });

    totalField.value = total.toFixed(2);
  }

  // Add event listeners to all number inputs
  document.querySelectorAll('.share-input, .loan-input').forEach(input => {
    input.addEventListener('input', () => {
      calculateTotals('.share-input', '.share-total');
      calculateTotals('.loan-input', '.loan-total');
    });
  });
</script>





<!-- jQuery Validation Rules -->
<script>
  $(document).ready(function () {
    $("#generalDetailsForm").validate({
      rules: {
        applicant_name: {
          required: true,
          minlength: 3,
          pattern: /^[a-zA-Z\s]+$/   // ✅ allows only alphabets and spaces
        },
        company_name: {
          required: true,
          minlength: 2,
          pattern: /^[a-zA-Z\s]+$/   // ✅ allows only alphabets and spaces

        },
        enterprise_type: {
          required: true
        },
        aadhar_number: {
          required: true,
          digits: true,
          minlength: 12,
          maxlength: 12
        },
        application_category: {
          required: true
        },
        project_description: {
          required: true
        }
      },
      messages: {
        applicant_name: {
          required: "Applicant Name is required.",
          minlength: "Please enter at least 3 characters."
        },
        company_name: {
          required: "Company Name is required.",
          minlength: "Please enter at least 2 characters."
        },
        enterprise_type: {
          required: "Please select the type of enterprise."
        },
        aadhar_number: {
          required: "Aadhar Number is required.",
          digits: "Aadhar Number must contain only digits.",
          minlength: "Aadhar Number must be exactly 12 digits.",
          maxlength: "Aadhar Number must be exactly 12 digits."
        },
        application_category: {
          required: "Please select an application category."
        },
        project_description: {
          required: "Project Description is required."
        }
      },
      errorClass: "text-danger small mt-1",
      errorElement: "div",
      highlight: function (element) {
        $(element).addClass("is-invalid");
      },
      unhighlight: function (element) {
        $(element).removeClass("is-invalid");
      },
      submitHandler: function (form) {
        alert("Form submitted successfully!");
        form.submit(); // remove this alert when integrating with backend
      }
    });
  });
</script>


<script>
$(document).ready(function () {
  // Add new row dynamically when user clicks "+" icon
  $(document).on("click", ".addRow", function () {
    const rowCount = $("#expansionTable tbody tr").length + 1;
    const newRow = `
      <tr>
        <td>${rowCount}</td>
        <td><input type="text" class="form-control" name="existing_facilities[]"></td>
        <td><input type="number" class="form-control" name="existing_employment[]"></td>
        <td><input type="text" class="form-control" name="expansion_facilities[]"></td>
        <td><input type="number" class="form-control" name="expansion_employment[]"></td>
        <td>
          <div class="d-flex justify-content-center gap-2">
              <button type="button" class="btn btn-primary btn-sm addRow" title="Add Row">
              <i class="bi bi-plus-circle"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm removeRow" title="Delete Row">
              <i class="bi bi-trash"></i>
            </button>
          
          </div>
        </td>
      </tr>`;
    $("#expansionTable tbody").append(newRow);
    updateRowNumbers();
  });

  // Delete a row
  $(document).on("click", ".removeRow", function () {
    $(this).closest("tr").remove();
    updateRowNumbers();
  });

  // Function to keep numbering correct
  function updateRowNumbers() {
    $("#expansionTable tbody tr").each(function (index) {
      $(this).find("td:first").text(index + 1);
    });
  }
});
</script>

<script>
$(document).ready(function () {

  // Add new entrepreneur row
  $(document).on("click", ".addEntreRow", function () {
    const rowCount = $("#entrepreneurTable tbody tr").length + 1;
    const newRow = `
      <tr>
        <td>${rowCount}</td>
        <td><input type="text" class="form-control" name="entre_name[]" placeholder="Enter Name"></td>
        <td><input type="text" class="form-control" name="entre_designation[]" placeholder="Enter Designation"></td>
        <td><input type="number" class="form-control" name="entre_ownership[]" placeholder="%"></td>
        <td>
          <select class="form-select" name="entre_gender[]">
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </td>
        <td><input type="number" class="form-control" name="entre_age[]" placeholder="Enter Age"></td>
        <td>
          <div class="d-flex justify-content-center gap-2">

              <button type="button" class="btn btn-primary btn-sm addEntreRow" title="Add Row">
              <i class="bi bi-plus-circle"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm removeEntreRow" title="Delete Row">
              <i class="bi bi-trash"></i>
            </button>
          
          </div>
        </td>
      </tr>`;
    $("#entrepreneurTable tbody").append(newRow);
    updateEntreButtons();
  });

  // Delete row
  $(document).on("click", ".removeEntreRow", function () {
    $(this).closest("tr").remove();
    updateEntreNumbers();
    updateEntreButtons();
  });

  // Update numbering
  function updateEntreNumbers() {
    $("#entrepreneurTable tbody tr").each(function (index) {
      $(this).find("td:first").text(index + 1);
    });
  }

  // Control button visibility
  function updateEntreButtons() {
    const rows = $("#entrepreneurTable tbody tr");
    rows.find(".addEntreRow").hide(); // hide all "+" buttons
    rows.find(".removeEntreRow").show(); // show delete on all
    rows.last().find(".addEntreRow").show(); // show "+" on last row only
    if (rows.length === 1) {
      rows.find(".removeEntreRow").hide(); // hide delete if only 1 row
    }
  }

  // Initialize state on load
  updateEntreButtons();
});
</script>


<script>
$(document).ready(function () {
  $("#generalDetailsForm").validate({
    rules: {
      zone: { required: true }
    },
    messages: {
      zone: "Please select a zone as per MTP 2024."
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") === "zone") {
        error.appendTo("#zoneError"); // show message under radios
      } else {
        error.insertAfter(element);
      }
    },
    errorClass: "text-danger small mt-1",
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      alert("Form submitted successfully!");
      form.submit();
    }
  });
});
</script>

<script>
$(document).ready(function () {
  // Custom rule: allow only letters, numbers, spaces, hyphens, and ampersands
  $.validator.addMethod("validSubcategory", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s\-\&]+$/.test(value);
  }, "Please enter valid text (letters, numbers, spaces, -, & allowed).");

  // Initialize form validation
  $("#generalDetailsForm").validate({
    rules: {
      project_subcategory: {
        required: true,
        minlength: 3,
        validSubcategory: true
      }
    },
    messages: {
      project_subcategory: {
        required: "Please enter the project subcategory.",
        minlength: "Project subcategory must be at least 3 characters long."
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") === "project_subcategory") {
        error.appendTo("#subcategoryError");
      } else {
        error.insertAfter(element);
      }
    },
    errorClass: "text-danger small mt-1",
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      alert("Form submitted successfully!");
      form.submit(); // Replace with backend submission
    }
  });
});
</script>


<script>
$(document).ready(function () {

  // Initialize validation
  $("#generalDetailsForm").validate({
    rules: {
      land_area: {
        required: true,
        number: true,
        min: 1
      }
    },
    messages: {
      land_area: {
        required: "Please enter the land area.",
        number: "Please enter a valid number.",
        min: "Area must be greater than zero."
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") === "land_area") {
        error.appendTo("#landAreaError"); // custom placement below input
      } else {
        error.insertAfter(element);
      }
    },
    errorClass: "text-danger small mt-1",
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      alert("Form submitted successfully!");
      form.submit(); // replace with backend logic
    }
  });
});
</script>

<script>
$(document).ready(function () {
  $("#generalDetailsForm").validate({
    rules: {
      project_cost: {
        required: true,
        number: true,
        min: 1
      }
    },
    messages: {
      project_cost: {
        required: "Please enter the project cost.",
        number: "Enter a valid numeric amount.",
        min: "Project cost must be greater than zero."
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") === "project_cost") {
        error.appendTo("#projectCostError"); // Place error below input
      } else {
        error.insertAfter(element);
      }
    },
    errorClass: "text-danger small mt-1",
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      alert("Form submitted successfully!");
      form.submit(); // Replace with backend logic
    }
  });
});
</script>


<script>
$(document).ready(function () {
  $("#generalDetailsForm").validate({
    rules: {
      total_employees: {
        required: true,
        number: true,
        min: 1
      }
    },
    messages: {
      total_employees: {
        required: "Please enter the total number of employees.",
        number: "Enter a valid numeric value.",
        min: "Number of employees must be at least 1."
      }
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") === "total_employees") {
        error.appendTo("#totalEmployeesError"); // place error under field
      } else {
        error.insertAfter(element);
      }
    },
    errorClass: "text-danger small mt-1",
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
      alert("Form submitted successfully!");
      form.submit(); // replace with your backend logic
    }
  });
});
</script>

<script>
  const form = document.getElementById('generalDetailsForm');
  const checkbox = document.getElementById('declaration');
  const feedback = checkbox.nextElementSibling.nextElementSibling; // target invalid-feedback div

  form.addEventListener('submit', function(e) {
    if (!checkbox.checked) {
      e.preventDefault(); // Stop form submission
      feedback.style.display = 'block'; // Show error message
      checkbox.focus(); // Focus the checkbox
    } else {
      feedback.style.display = 'none'; // Hide error if checked
    }
  });
</script>






    @endpush























