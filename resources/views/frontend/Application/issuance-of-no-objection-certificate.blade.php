@extends('frontend.layouts2.master')
@section('title', 'Stamp Duty Exemption Application Form')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  :root{
    --brand: #ff6600;
    --brand-dark: #e25500;
  }
  .form-icon {
        color: var(--brand);
        margin-right:.35rem;
  }
  .required::after {
    content: " *";
    color: #dc3545;
    margin-left: 0.15rem;
    font-weight: 600;
  }
  .no-underline,
  .no-underline:hover,
  .no-underline:focus,
  .no-underline:active {
      text-decoration: none !important;
  }
  .is-valid { border-color: #28a745 !important; }
  .is-invalid { border-color: #dc3545 !important; }

  .enc-preview img {
      max-height: 60px;
      cursor: pointer;
  }
  .enc-remove {
      padding: 0;
      border: none;
  }

  /* Preview Modal Styles */
  .preview-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    border-left: 4px solid var(--brand);
  }
  .preview-label {
    font-weight: 600;
    color: #333;
    min-width: 200px;
  }
  .preview-value {
    color: #555;
  }
  .preview-na {
    color: #6c757d;
    font-style: italic;
  }
  .preview-table {
    font-size: 0.9rem;
  }
  .preview-table th {
    background-color: #e9ecef;
    font-weight: 600;
  }
</style>

<style>
    label {
        font-weight: 600;
        margin-top: 10px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-top: 25px;
        padding-bottom: 5px;
        border-bottom: 2px solid #0d6efd;
    }
</style>

<style>
    table input {
        width: 100%;
        padding: 5px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    th {
        background: #0d6efd;
        color: white;
        text-align: center;
    }

    .title {
        font-size: 20px;
        font-weight: 700;
        margin-top: 20px;
        padding-bottom: 5px;
        border-bottom: 2px solid #0d6efd;
    }
</style>

<style>
    table input {
        width: 100%;
        padding: 6px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    th {
        background: #0d6efd;
        color: white;
        text-align: center;
    }

    .total-field {
        font-weight: bold;
        background: #e9ecef;
    }
</style>

<style>
    .preview-box {
        width: 180px;
        height: 50px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .preview-box img {
        width: 100%;
        height: auto;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for {{ $application_form->name ?? 'Eligibility Certificate Registration' }}
        </h1>

        <a href="{{ url()->previous() }}"
           class="text-white fw-bold d-inline-flex align-items-center no-underline"
           style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
            <i class="bi bi-arrow-left me-2 mx-2"></i> Back
        </a>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif


                        <div class="container my-4">

                            <h2 class="text-center mb-4" style="color: blue">Application for NOC for getting exemption from Stamp Duty</h2>
                            <h3 class="text-center mb-4">(Under Tourism Policy 2024, vide No.TDS/2022/09/C.R.542/Tourism – 4, dt.18/07/2024 for New Eligible Tourism Project / Expansion of Existing Project.)</h3>

                            <!-- SECTION A -->
                            <div class="section-title mb-3">Section A: General Details</div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Applicant / Company Name</label>
                                    <input type="text" class="form-control" name="company_name">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Registration No.</label>
                                    <input type="text" class="form-control" name="reg_no">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Type of Enterprise</label>
                                <select class="form-control" name="applicant_type" id="applicant_type" required>
                                    <option value="">Select</option>
                                    @foreach ($enterprises as $enterprise)
                                        <option value="{{ $enterprise->id }}">{{ $enterprise->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Agreement to be made</label>
                                <select class="form-select" name="agreement_type">
                                    <option>Purchase Deed</option>
                                    <option>Lease Deed</option>
                                    <option>Mortgage</option>
                                    <option>Hypothecation</option>
                                </select>
                            </div>


                                    <!-- Address -->
                                       <div class="section-title mt-4 mb-3">Correspondence Address</div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="c_address">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Village/City</label>
                                    <input type="text" class="form-control" name="c_city">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Taluka</label>
                                    <input type="text" class="form-control" name="c_taluka">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">District</label>
                                    <input type="text" class="form-control" name="c_district">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="c_state">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Pin Code</label>
                                    <input type="number" class="form-control" name="c_pincode">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cell Phone No.</label>
                                    <input type="number" class="form-control" name="c_mobile">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Telephone No.</label>
                                    <input type="text" class="form-control" name="c_phone">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Email ID</label>
                                    <input type="email" class="form-control" name="c_email">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Fax</label>
                                    <input type="text" class="form-control" name="c_fax">
                                </div>
                            </div>


                                    <!-- Project Site Address -->
                                        <div class="section-title mt-4 mb-3">Project Site Address</div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Address (Gat No./ Survey No.)</label>
                                    <input type="text" class="form-control" name="p_address">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Village/City</label>
                                    <input type="text" class="form-control" name="p_city">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Taluka</label>
                                    <input type="text" class="form-control" name="p_taluka">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">District</label>
                                    <input type="text" class="form-control" name="p_district">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="p_state">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Pin Code</label>
                                    <input type="number" class="form-control" name="p_pincode">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cell Phone No.</label>
                                    <input type="number" class="form-control" name="p_mobile">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Telephone No.</label>
                                    <input type="text" class="form-control" name="p_phone">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Email ID</label>
                                    <input type="email" class="form-control" name="p_email">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Website</label>
                                    <input type="text" class="form-control" name="p_website" placeholder="http://">
                                </div>
                            </div>


                                    <!-- Additional Section A Info -->
                                        <div class="mt-4">
                                <label class="form-label">Estimated Project Cost</label>
                                <input type="text" class="form-control" name="project_cost">
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Proposed Employment Generation</label>
                                <input type="text" class="form-control" name="employment">
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Tourism Activities / Facilities</label>
                                <textarea class="form-control" rows="3" name="activities"></textarea>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Details of incentives availed earlier</label>
                                <textarea class="form-control" rows="3" name="incentives"></textarea>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Tourism Project existed before 18/07/2024?</label>
                                <select class="form-select" name="existed_before" id="existedBefore">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>

                            <div id="eligibilitySection" style="display:none;" class="mt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Eligibility Certificate No.</label>
                                        <input type="text" class="form-control" name="eligibility_no">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Eligibility Date</label>
                                        <input type="date" class="form-control" name="eligibility_date">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Present Status of Project</label>
                                    <textarea class="form-control" rows="2" name="present_status"></textarea>
                                </div>
                            </div>

                        </div>



                                    <div class="section-title mt-4 mb-3">Section B: Land & Built-up Area Details</div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Component</th>
                                        <th>Particulars</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td>Land or Built-up area to be purchased for Tourism Project</td>
                                        <td>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">CTS / Gat No.</label>
                                                    <input type="text" class="form-control" name="land_gat">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Village</label>
                                                    <input type="text" class="form-control" name="land_village">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Taluka</label>
                                                    <input type="text" class="form-control" name="land_taluka">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">District</label>
                                                    <input type="text" class="form-control" name="land_district">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>(A) Total Area to be purchase/lease of Land: (Sq. Metres)</td>
                                        <td><input type="number" class="form-control" name="area_a"></td>
                                    </tr>

                                    <tr>
                                        <td>(B) Total Area to be purchase/lease of Land & Built up area: (Sq. Metres)</td>
                                        <td><input type="number" class="form-control" name="area_b"></td>
                                    </tr>

                                    <tr>
                                        <td>(C) Out of (A) total area of land to be used for tourism project (Sq. Metres)</td>
                                        <td><input type="number" class="form-control" name="area_c"></td>
                                    </tr>

                                    <tr>
                                        <td>(D) Out of (A) actual land area required for ancillary activity (godown/office/lab etc.)(Sq. Metres)</td>
                                        <td><input type="number" class="form-control" name="area_d"></td>
                                    </tr>

                                    <tr>
                                        <td>(E) Total area of vacant land out of purchase / lease land (Sq. Metres)</td>
                                        <td><input type="number" class="form-control" name="area_e"></td>
                                    </tr>

                                    <!-- NON AGRICULTURAL LAND -->
                                    <tr>
                                        <td>Details of Non-Agricultural Land</td>
                                        <td>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">CTS / Gat No.</label>
                                                    <input type="text" class="form-control" name="na_gat">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Village</label>
                                                    <input type="text" class="form-control" name="na_village">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Taluka</label>
                                                    <input type="text" class="form-control" name="na_taluka">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">District</label>
                                                    <input type="text" class="form-control" name="na_district">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Total Area of land to be converted to NA (Sq. Metres)</td>
                                        <td><input type="number" class="form-control" name="na_area"></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>





                                    <!-- Project Cost Details -->

                                    <div class="container my-4">

                                        <h4 class="mb-3">Project Cost: </h4>

                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50%;">Particulars</th>
                                                        <th>Amount (in Lakhs)</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>Land</td>
                                                        <td><input type="number" class="cost-input" name="cost_land" step="0.01"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Building</td>
                                                        <td><input type="number" class="cost-input" name="cost_building" step="0.01"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Plant & Machinery</td>
                                                        <td><input type="number" class="cost-input" name="cost_machinery" step="0.01"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Electrical Installations</td>
                                                        <td><input type="number" class="cost-input" name="cost_electrical" step="0.01"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Misc. Fixed Assets</td>
                                                        <td><input type="number" class="cost-input" name="cost_misc" step="0.01"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Other Expenses</td>
                                                        <td><input type="number" class="cost-input" name="cost_other" step="0.01"></td>
                                                    </tr>

                                                    <tr class="table-secondary">
                                                        <td class="fw-bold">Total</td>
                                                        <td><input type="text" id="total_cost" class="total-field" readonly></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>


                                    <label class="mt-3">* Proposed Employment Generation for this project.</label>
                                    <input type="text" class="form-control" name="employment2">

                                    <label>The purpose for which NOC will be utilized:</label>
                                    <textarea class="form-control" name="noc_purpose" rows="2"></textarea>

                                    <label>The name and address of authority to which this NOC will be submitted: (e.g. Sub Registrar)</label>
                                    <textarea class="form-control" name="noc_authority" rows="2"></textarea>



                                    <!-- Declaration Section -->
                                    <div class="section-title mt-5 mb-3">Declaration</div>

                        <div class="border p-3 rounded">

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

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name & Designation</label>
                                    <input type="text" class="form-control" name="name_designation">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Upload Signature</label>
                                    <input type="file" class="form-control" id="signatureInput" accept="image/*">
                                    <div class="preview-box mt-2" id="signaturePreview">
                                        <span class="text-muted small">Signature Preview</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Upload Rubber Stamp</label>
                                    <input type="file" class="form-control" id="stampInput" accept="image/*">
                                    <div class="preview-box mt-2" id="stampPreview">
                                        <span class="text-muted small">Stamp Preview</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                                    <!-- Document Upload Section -->

                                    <div class="container my-4">

                                        <h4 class="mb-3">Section C: List of Documents</h4>

                                        <div class="border p-3 rounded">

                                            <p class="mb-2"><strong>Note:</strong> All documents should be self-attested.</p>

                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%; text-align: center;">Sr.</th>
                                                            <th style="width: 45%;">Document</th>
                                                            <th>Upload</th>
                                                            <th style="width: 15%;">Preview</th>
                                                            <th style="width: 10%;">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        <tr>
                                                            <td class="text-center">1</td>
                                                            <td>Copy of challan for online processing fees of Rs.5,000/- paid on
                                                                <a href="www.gras.mahakosh.gov.in">www.gras.mahakosh.gov.in</a></td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_challan" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">2</td>
                                                            <td>Affidavits (as per the specified format below)</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_affidavit" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">3</td>
                                                            <td>Registration proof for Company / Partnership firm / Co-op. Society etc.</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_registration" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">4</td>
                                                            <td>Records of Right (RoR)</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_ror" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">5</td>
                                                            <td>Map of the land</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_land_map" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">6</td>
                                                            <td>Detailed Project Report (DPR)</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_dpr" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">7</td>
                                                            <td>Certified true copy of Draft Agreement to Sale / Letter of Allotment from Government
                                                                or any authority</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_agreement" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">8</td>
                                                            <td>Copy of proposed plan of constructions</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_construction_plan" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-center">9</td>
                                                            <td>D.P. remarks from Local Planning Authority / Zone Certificate</td>
                                                            <td><input type="file" class="form-control doc-upload" name="doc_dp_remarks" accept="image/*,.pdf"></td>
                                                            <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>



                                    <!-- affidavit-->
                        <div class="section-title mt-5 mb-3">Affidavit</div>

                        <div class="border p-3 rounded">

                            <p class="fw-bold">(On stamp paper of INR 500/-)</p>
                            <p class="fw-bold">(To obtain Certificate for Proposed Tourism Project under Stamp Duty Act 1958)</p>

                            <p>
                                I, Shri/Mrs
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 200px;" name="aff_name">
                                , Director of M/s.
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 200px;" name="aff_company">
                                , a Firm/company incorporated under LLP Act / Partnership Act / Companies Act of 1956/2013,
                                having its registered office at
                                <input type="text" class="form-control mt-2" name="aff_registered_office">
                                , solemnly declare on oath that I have submitted an application along with relevant documents
                                under Tourism Policy 2024 started by Government of Maharashtra to promote tourism in the state.
                            </p>

                            <p>
                                I further state and undertake that as provided in section 14.4 of the Tourism Policy 2024, I am
                                willing to take all the initial effective steps to become eligible for registration as a Tourism
                                Unit and as a part of this I am applying for a certificate from Directorate of Tourism to enable me
                                to claim exemption from payment of stamp duty on registration of deed of conveyance in respect of
                                adjacent piece of land admeasuring
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 100px;" name="aff_land_area">
                                sq. meters,

                                bearing C.T.S. / Gat No.
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 120px;" name="aff_cts">
                                , Village -
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 150px;" name="aff_village">
                                , Taluka -
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 150px;" name="aff_taluka">
                                , District -
                                <input type="text" class="form-control d-inline-block w-auto" style="min-width: 150px;" name="aff_district">
                                .
                            </p>

                            <p>
                                I also state on oath that I shall complete the proposed tourism project in respect of the aforesaid
                                land within the stipulated period of three years, failing which the Certificate to be issued by the
                                Directorate of Tourism regarding entitlement for exemption of stamp duty shall automatically stand cancelled.
                            </p>

                            <p>
                                I also state on oath that in the event of exemption to pay stamp duty being granted I shall abide by
                                the terms and conditions laid down by the Government of Maharashtra in the Notification dated
                                15/10/2024 issued by the Revenue & Forest Department and I also undertake that the land so purchased
                                shall be used for developing the said “Tourism Project” and I am fully aware that failure to do so
                                shall entail action by the State Government or any other competent authority which may include refund
                                of the amount exempted and fines etc.
                            </p>

                        </div>



                                    <!-- SUBMIT -->
                                    <div class="text-center mt-4">
                                        <button class="btn btn-primary px-5 py-2">Submit Application</button>
                                    </div>

                                </form>



                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Image preview modal -->
<div class="modal fade" id="previewImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0 text-center">
                <img id="previewImageModalImg" src="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {

    // Letters only rule
    $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
    }, "Only letters and spaces are allowed.");

    // jQuery Validate
    let validator = $('#eligibilityForm').validate({
        ignore: [],
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('text-danger small mt-1');
            error.insertAfter(element);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        rules: {
            applicant_name: {
                required: true,
                minlength: 2,
                lettersOnly: true
            },
            provisional_number: {
                required: true
            },
            gst_number: {
                required: true,
                minlength: 15,
                maxlength: 15
            },
            project_description: {
                required: true,
                minlength: 10
            },
            commencement_date: {
                required: true
            },
            operation_details: {
                required: true
            },
            declaration_place: {
                required: true,
                lettersOnly: true
            },
            declaration_date:  {
                required: true
            },
            signature_upload:  {
                required: true
            },
            'entrepreneurs[0][name]': {
                required: true,
                lettersOnly: true
            },
            'entrepreneurs[0][designation]': {
                required: true
            },
            'entrepreneurs[0][ownership]': {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            'entrepreneurs[0][gender]': {
                required: true
            },
            'entrepreneurs[0][age]': {
                required: true,
                number: true,
                min: 18
            }
        },
        messages: {
            applicant_name: {
                required: "Please enter the Name of Applicant/Tourism Unit.",
                minlength: "Name must be at least 2 characters."
            },
            provisional_number: {
                required: "Please enter Provisional Certificate Number."
            },
            gst_number: {
                required: "Please enter GST Number.",
                minlength: "GST Number must be 15 characters.",
                maxlength: "GST Number must be 15 characters."
            },
            project_description: {
                required: "Please enter project description.",
                minlength: "Description must be at least 10 characters."
            },
            commencement_date: {
                required: "Please select date of commercial commencement."
            },
            operation_details: {
                required: "Please select operation details."
            },
            declaration_place: {
                required: "Please enter the place."
            },
            declaration_date:  "Please select the date.",
            signature_upload:  "Please upload signature.",
            'entrepreneurs[0][name]': "Name is required.",
            'entrepreneurs[0][designation]': "Designation is required.",
            'entrepreneurs[0][ownership]': "Ownership % is required (0-100).",
            'entrepreneurs[0][gender]': "Select gender.",
            'entrepreneurs[0][age]': "Age is required (min 18)."
        }
    });






    // Signature preview (image/pdf)
    $('#signature_upload').on('change', function () {
        const file = this.files[0];
        const imgBox = $('#signaturePreviewWrapper');
        const imgEl  = $('#signaturePreviewImg');
        const pdfInfo = $('#signaturePdfInfo');

        imgBox.hide();
        pdfInfo.hide().empty();

        if (!file) return;

        const type = file.type.toLowerCase();

        if (type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            imgEl.attr('src', url);
            imgBox.show();

            imgEl.off('click').on('click', function () {
                $('#previewImageModalImg').attr('src', url);
                const modal = new bootstrap.Modal(document.getElementById('previewImageModal'));
                modal.show();
            });

        } else if (type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            pdfInfo.html(`
                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                <a href="${url}" target="_blank" class="ms-2">View PDF</a>
            `).show();
        }
    });

    // Other docs image/pdf preview
    $(document).on('change', '.other-doc-file', function () {
        const file = this.files[0];
        const td = $(this).closest('tr').find('.preview-td');
        td.empty();

        if (!file) return;

        const type = file.type.toLowerCase();

        if (type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            const img = $('<img/>', {
                src: url,
                class: 'img-thumbnail',
                style: 'max-height:60px;cursor:pointer;'
            });
            img.on('click', function () {
                $('#previewImageModalImg').attr('src', url);
                const m = new bootstrap.Modal(document.getElementById('previewImageModal'));
                m.show();
            });
            td.append(img);
        } else if (type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            td.html(`
                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                <a href="${url}" target="_blank" class="ms-2">Open PDF</a>
            `);
        }
    });

    // Declaration checkbox: SweetAlert + enable Preview btn
    $('#previewSubmitBtn').prop('disabled', true);

    $('#declaration_check').on('change', function () {
        const checkbox = this;

        if (checkbox.checked) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you confirm that all the declaration points are true?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff6600',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, I agree',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#previewSubmitBtn').prop('disabled', false);
                } else {
                    checkbox.checked = false;
                    $('#previewSubmitBtn').prop('disabled', true);
                }
            });
        } else {
            $('#previewSubmitBtn').prop('disabled', true);
        }
    });



    // AJAX Final Submit
    $('#confirmSubmitBtn').on('click', function () {
        const form = $('#eligibilityForm')[0];
        const $btn = $('#confirmSubmitBtn');

        if (!$('#eligibilityForm').valid()) {
            return;
        }

        const formData = new FormData(form);

        $btn.prop('disabled', true);
        $('#submitLoader').removeClass('d-none');

        $.ajax({
            url: $('#eligibilityForm').attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
            success: function (resp) {
                $('#submitLoader').addClass('d-none');
                $btn.prop('disabled', false);

                if (resp.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: resp.message,
                        confirmButtonColor: '#ff6600',
                    }).then(() => {
                        if (resp.redirect_url) {
                            window.location.href = resp.redirect_url;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while submitting.',
                    });
                }
            },
            error: function (xhr) {
                $('#submitLoader').addClass('d-none');
                $btn.prop('disabled', false);

                if (xhr.status === 422) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please correct the highlighted fields.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Please try again later.',
                    });
                }
            }
        });
    });
});
</script>
@endpush

@push('scripts')
    <script>
        document.getElementById('existedBefore').addEventListener('change', function() {
            const section = document.getElementById('eligibilitySection');
            section.style.display = this.value === 'yes' ? 'block' : 'none';
        });
    </script>

    <!-- Auto Calculation Script -->
    <script>
        const inputs = document.querySelectorAll(".cost-input");
        const totalField = document.getElementById("total_cost");

        function calculateTotal() {
            let total = 0;
            inputs.forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
            });
            totalField.value = total.toFixed(2);
        }

        inputs.forEach(input => {
            input.addEventListener("input", calculateTotal);
        });
    </script>

    <script>
        // Signature Preview

document.getElementById("signatureInput").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("signaturePreview");

    if (file) {
        const reader = new FileReader();
        reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" class="img-fluid">`;
        reader.readAsDataURL(file);
    }
});

document.getElementById("stampInput").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("stampPreview");

    if (file) {
        const reader = new FileReader();
        reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" class="img-fluid">`;
        reader.readAsDataURL(file);
    }
});
</script>


    <script>
                document.querySelectorAll('.doc-upload').forEach(input => {
                    input.addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        const row = this.closest('tr');
                        const preview = row.querySelector('.doc-preview');
                        const removeBtn = row.querySelector('.btn-remove');

                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                if (file.type.startsWith('image/')) {
                                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 100%;">`;
                                } else if (file.type === 'application/pdf') {
                                    preview.innerHTML = `<div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 12px;"><span>PDF</span></div>`;
                                } else {
                                    preview.innerHTML = `<div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 12px;"><span>File</span></div>`;
                                }
                            };
                            reader.readAsDataURL(file);
                            removeBtn.style.display = 'inline-block';
                        } else {
                            preview.innerHTML = '';
                            removeBtn.style.display = 'none';
                        }
                    });

                    const removeBtn = input.closest('tr').querySelector('.btn-remove');
                    removeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        input.value = '';
                        input.closest('tr').querySelector('.doc-preview').innerHTML = '';
                        this.style.display = 'none';
                    });
                });
            </script>


@endpush
