@extends('frontend.layouts2.master')
@section('title','Hotel Registration - Step 4')

@push('styles')
<style>
  :root{ --brand:#ff6600; --brand-soft:#fff5ec; }

  .form-container {
    background:#fff;
    padding:1.5rem;
    border-radius:12px;
    box-shadow:0 4px 20px rgba(0,0,0,.05);
  }
  .summary-section {
    border-radius:12px;
    border:1px solid #f1f1f1;
    margin-bottom:1.25rem;
    overflow:hidden;
  }
  .summary-header {
    background:var(--brand-soft);
    padding:.75rem 1rem;
    display:flex;
    align-items:center;
    justify-content:space-between;
    cursor:pointer;
  }
  .summary-header h5 {
    margin:0;
    font-size:1rem;
    font-weight:700;
    display:flex;
    align-items:center;
  }
  .summary-header h5 i {
    margin-right:.5rem;
    color:var(--brand);
  }
  .summary-body {
    padding:1rem 1rem .75rem;
  }

  .summary-table th {
    width:260px;
    font-weight:600;
    background:#fafafa;
  }
  .summary-table td,
  .summary-table th {
    padding:.4rem .75rem !important;
    vertical-align:middle;
    font-size:.9rem;
  }

  .badge-yes{
    background:#d1f7d8;
    color:#137333;
    font-size:.75rem;
    padding:.25rem .6rem;
    border-radius:50px;
  }
  .badge-no{
    background:#ffe8e6;
    color:#c5221f;
    font-size:.75rem;
    padding:.25rem .6rem;
    border-radius:50px;
  }
  .badge-na{
    background:#e9ecef;
    color:#6c757d;
    font-size:.75rem;
    padding:.25rem .6rem;
    border-radius:50px;
  }

  .pill-list{
    list-style:none;
    padding-left:0;
    margin:0;
    display:flex;
    flex-wrap:wrap;
    gap:.35rem;
  }
  .pill-list li{
    background:#f5f5f5;
    border-radius:999px;
    padding:.25rem .7rem;
    font-size:.8rem;
  }

  .doc-card{
    border-radius:10px;
    border:1px solid #f1f1f1;
    background:#fcfcfc;
    height:100%;
  }
  .doc-card h6{
    font-size:.95rem;
    font-weight:700;
    margin-bottom:.4rem;
  }
  .doc-card .doc-label{
    font-size:.8rem;
    color:#6c757d;
  }
  .doc-link{
    font-size:.85rem;
    display:inline-flex;
    align-items:center;
    gap:.25rem;
    text-decoration:none;
  }
  .doc-link i{
    font-size:1rem;
  }

  .required::after{content:" *";color:red;}
</style>
@endpush

@section('content')
@php
    if (!function_exists('displayOrNA')) {
        function displayOrNA($value, $isBool = false) {
            if ($isBool) {
                if (is_null($value)) return 'N/A';
                return $value ? 'Yes' : 'No';
            }
            return isset($value) && $value !== '' ? $value : 'N/A';
        }
    }

    if (!function_exists('industrialDocPreview')) {
        function industrialDocPreview($model, $field) {
            $path = optional($model)->{$field};
            if (!$path) return [null,null,null];
            $url = asset('storage/'.$path);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            return [$url,$ext,basename($path)];
        }
    }

    $starLabels = [
        '1' => '1 Star',
        '2' => '2 Star',
        '3' => '3 Star',
        '4' => '4 Star',
        '5' => '5 Star',
    ];
@endphp

<section class="section">
  <div class="section-header">
    <h1>{{ $application_form->name ?? 'Hotel Registration' }}</h1>
  </div>

  <div class="section-body">
    <div class="form-container">
      <div class="form-header mb-3">
        <h2>Application Form for the {{ $application_form->name ?? '' }}</h2>
        <p>Step 4: Review & Submit</p>
      </div>

      {{-- Step indicator --}}
      @include('frontend.Application.Industrial._step-indicator')

      <form id="step4Form"
            action="{{ route('industrial.wizard.final-submit', $application) }}"
            method="POST"
            novalidate>
        @csrf
        <input type="hidden" name="slug_id" value="{{ $application->slug_id }}">

        <div class="card p-3 mb-4">
          <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
            <h5 class="m-0">Review & Submit</h5>
          </div>

          <div class="card-body">

            <div class="alert alert-info">
              <h5 class="mb-1"><i class="bi bi-info-circle me-2"></i>Please review all information before submitting</h5>
              <p class="mb-0">Once submitted, you will not be able to edit this application.</p>
            </div>

            {{-- ===================== STEP 1 SUMMARY ===================== --}}
            <div class="summary-section">
              <div class="summary-header" data-toggle="collapse" data-target="#reviewStep1" aria-expanded="true">
                <h5>
                  <i class="bi bi-1-circle"></i> Step 1 – Basic Information
                </h5>
                <i class="bi bi-chevron-down"></i>
              </div>
              <div id="reviewStep1" class="summary-body collapse show">
                <table class="table table-bordered table-sm summary-table mb-2">
                  <tbody>
                    <tr>
                      <th>Hotel Name</th>
                      <td>{{ displayOrNA($step1->hotel_name ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Company Name</th>
                      <td>{{ displayOrNA($step1->company_name ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Authorized Person</th>
                      <td>{{ displayOrNA($step1->authorized_person ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <td>{{ displayOrNA($step1->email ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Mobile</th>
                      <td>{{ displayOrNA($step1->mobile ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Region</th>
                      <td>
                        {{ displayOrNA($step1->region_name ?? $step1->region ?? null) }}
                      </td>
                    </tr>
                    <tr>
                      <th>District</th>
                      <td>
                        {{ displayOrNA($step1->district_name ?? $step1->district ?? null) }}
                      </td>
                    </tr>
                    <tr>
                      <th>Pin Code</th>
                      <td>{{ displayOrNA($step1->pincode ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Hotel Address</th>
                      <td>{{ displayOrNA($step1->hotel_address ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Company Address</th>
                      <td>{{ displayOrNA($step1->company_address ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Applicant Type</th>
                      <td>{{ displayOrNA($step1->applicant_type ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Total Area (sq. m)</th>
                      <td>{{ displayOrNA($step1->total_area ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Total Employees</th>
                      <td>{{ displayOrNA($step1->total_employees ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Total Rooms</th>
                      <td>{{ displayOrNA($step1->total_rooms ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Commencement Date</th>
                      <td>{{ displayOrNA($step1->commencement_date ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Emergency Contact No.</th>
                      <td>{{ displayOrNA($step1->emergency_contact ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>MSEB Consumer Number</th>
                      <td>{{ displayOrNA($step1->mseb_consumer_number ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Star Category</th>
                      <td>
                        @php
                          $sVal = $step1->star_category ?? null;
                          $sLabel = $sVal && isset($starLabels[$sVal]) ? $starLabels[$sVal] : null;
                        @endphp
                        {{ displayOrNA($sLabel ?? $sVal) }}
                      </td>
                    </tr>
                    <tr>
                      <th>Electricity Company (Name & Address)</th>
                      <td>{{ displayOrNA($step1->electricity_company ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Property Tax Dept (Name & Address)</th>
                      <td>{{ displayOrNA($step1->property_tax_dept ?? null) }}</td>
                    </tr>
                    <tr>
                      <th>Water Bill Dept (Name & Address)</th>
                      <td>{{ displayOrNA($step1->water_bill_dept ?? null) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            {{-- ===================== STEP 2 SUMMARY ===================== --}}
            <div class="summary-section">
              <div class="summary-header" data-toggle="collapse" data-target="#reviewStep2" aria-expanded="true">
                <h5>
                  <i class="bi bi-2-circle"></i> Step 2 – Facilities & Services
                </h5>
                <i class="bi bi-chevron-down"></i>
              </div>
              <div id="reviewStep2" class="summary-body collapse show">

                {{-- General Requirements --}}
                <h6 class="mb-1">General Requirements</h6>
                @php
                    $selectedGeneral = is_array($step2->general_requirements ?? null)
                        ? $step2->general_requirements
                        : json_decode($step2->general_requirements ?? '[]', true);

                    $generalNames = collect($GeneralRequirement ?? [])
                        ->whereIn('id', $selectedGeneral ?? [])
                        ->pluck('name');
                @endphp
                @if($generalNames->count())
                  <ul class="pill-list mb-3">
                    @foreach($generalNames as $name)
                      <li>{{ $name }}</li>
                    @endforeach
                  </ul>
                @else
                  <span class="badge badge-na mb-3">N/A</span>
                @endif

                {{-- Bathrooms --}}
                <h6 class="mt-2 mb-1">Bathroom</h6>
                <table class="table table-bordered table-sm summary-table mb-3">
                  <tbody>
                    <tr>
                      <th>Rooms with attached bathrooms</th>
                      <td>
                        @php $val = $step3->bath_attached ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Hot & Cold running water</th>
                      <td>
                        @php $val = $step3->bath_hot_cold ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Water saving taps & showers</th>
                      <td>
                        @php $val = $step3->water_saving_taps ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>

                {{-- Public Area --}}
                <h6 class="mt-2 mb-1">Public Area</h6>
                <table class="table table-bordered table-sm summary-table mb-3">
                  <tbody>
                    <tr>
                      <th>Lounge / Lobby seating</th>
                      <td>
                        @php $val = $step3->public_lobby ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Reception facility</th>
                      <td>
                        @php $val = $step3->reception ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Public restrooms</th>
                      <td>
                        @php $val = $step3->public_restrooms ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>

                {{-- Differently Abled --}}
                <h6 class="mt-2 mb-1">Room & Facilities for Differently Abled Guests</h6>
                <table class="table table-bordered table-sm summary-table mb-3">
                  <tbody>
                    <tr>
                      <th>At least one room for differently abled guest</th>
                      <td>
                        @php $val = $step3->disabled_room ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>

                {{-- Kitchen --}}
                <h6 class="mt-2 mb-1">Kitchen / Food Production Area</h6>
                <table class="table table-bordered table-sm summary-table mb-3">
                  <tbody>
                    <tr>
                      <th>FSSAI registration / Licensed Kitchen</th>
                      <td>
                        @php $val = $step3->fssai_kitchen ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>

                {{-- Staff --}}
                <h6 class="mt-2 mb-1">Hotel Staff & Related Facilities</h6>
                <table class="table table-bordered table-sm summary-table mb-3">
                  <tbody>
                    <tr>
                      <th>Uniforms for front house staff</th>
                      <td>
                        @php $val = $step3->uniforms ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>

                {{-- Code of Conduct --}}
                <h6 class="mt-2 mb-1">Code of Conduct – Safe & Honorable Tourism</h6>
                <table class="table table-bordered table-sm summary-table mb-3">
                  <tbody>
                    <tr>
                      <th>Display of pledge</th>
                      <td>
                        @php $val = $step3->pledge_display ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Complaint & Suggestion book</th>
                      <td>
                        @php $val = $step3->complaint_book ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Nodal officer appointment & display</th>
                      <td>
                        @php $val = $step3->nodal_officer_info ?? null; @endphp
                        @if(is_null($val))
                          <span class="badge badge-na">N/A</span>
                        @else
                          <span class="{{ $val ? 'badge-yes' : 'badge-no' }}">{{ $val ? 'Yes' : 'No' }}</span>
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>

                {{-- Guest Services --}}
                <h6 class="mt-2 mb-1">Guest Services</h6>
                @php
                    $selectedGuest = json_decode($step3->guest_services ?? '[]', true);
                    $guestNames = collect($GuestServices ?? [])
                        ->whereIn('id', $selectedGuest ?? [])
                        ->pluck('name');
                @endphp
                @if($guestNames->count())
                  <ul class="pill-list mb-3">
                    @foreach($guestNames as $name)
                      <li>{{ $name }}</li>
                    @endforeach
                  </ul>
                @else
                  <span class="badge badge-na mb-3">N/A</span>
                @endif

                {{-- Safety & Security --}}
                <h6 class="mt-2 mb-1">Safety & Security</h6>
                @php
                    $selectedSafety = json_decode($step3->safety_security ?? '[]', true);
                    $safetyNames = collect($SafetyAndSecurity ?? [])
                        ->whereIn('id', $selectedSafety ?? [])
                        ->pluck('name');
                @endphp
                @if($safetyNames->count())
                  <ul class="pill-list mb-3">
                    @foreach($safetyNames as $name)
                      <li>{{ $name }}</li>
                    @endforeach
                  </ul>
                @else
                  <span class="badge badge-na mb-3">N/A</span>
                @endif

                {{-- Additional Features --}}
                <h6 class="mt-2 mb-1">Additional Features</h6>
                @php
                    $selectedFeature = json_decode($step3->additional_features ?? '[]', true);
                    $featureNames = collect($AdditionalFeature ?? [])
                        ->whereIn('id', $selectedFeature ?? [])
                        ->pluck('name');
                @endphp
                @if($featureNames->count())
                  <ul class="pill-list mb-1">
                    @foreach($featureNames as $name)
                      <li>{{ $name }}</li>
                    @endforeach
                  </ul>
                @else
                  <span class="badge badge-na">N/A</span>
                @endif

              </div>
            </div>

            {{-- ===================== STEP 3 SUMMARY (DOCUMENTS) ===================== --}}
            @php
              [$panUrl,$panExt,$panName]         = industrialDocPreview($step4 ?? null,'pan_card_path');
              [$aadUrl,$aadExt,$aadName]         = industrialDocPreview($step4 ?? null,'aadhaar_card_path');
              [$beUrl,$beExt,$beName]           = industrialDocPreview($step4 ?? null,'business_reg_path');
              [$gstUrl,$gstExt,$gstName]        = industrialDocPreview($step4 ?? null,'gst_cert_path');
              [$fsUrl,$fsExt,$fsName]           = industrialDocPreview($step4 ?? null,'fssai_cert_path');
              [$bUrl,$bExt,$bName]              = industrialDocPreview($step4 ?? null,'building_cert_path');
              [$decUrl,$decExt,$decName]        = industrialDocPreview($step4 ?? null,'declaration_path');
              [$mpUrl,$mpExt,$mpName]           = industrialDocPreview($step4 ?? null,'mpcb_cert_path');
              [$starUrl,$starExt,$starName]     = industrialDocPreview($step4 ?? null,'star_cert_path');
              [$waterUrl,$waterExt,$waterName]  = industrialDocPreview($step4 ?? null,'water_bill_path');
              [$lightUrl,$lightExt,$lightName]  = industrialDocPreview($step4 ?? null,'light_bill_path');
              [$fireUrl,$fireExt,$fireName]     = industrialDocPreview($step4 ?? null,'fire_noc_path');
              [$propUrl,$propExt,$propName]     = industrialDocPreview($step4 ?? null,'property_tax_path');
              [$elecUrl,$elecExt,$elecName]     = industrialDocPreview($step4 ?? null,'electricity_bill_path');
            @endphp

            <div class="summary-section">
              <div class="summary-header" data-toggle="collapse" data-target="#reviewStep3" aria-expanded="true">
                <h5>
                  <i class="bi bi-3-circle"></i> Step 3 – Documents Upload
                </h5>
                <i class="bi bi-chevron-down"></i>
              </div>
              <div id="reviewStep3" class="summary-body collapse show">
                <div class="row">

                  {{-- PAN --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>PAN Card</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($panUrl)
                        <div class="mb-2 small text-muted">{{ $panName }}</div>
                        @if(in_array($panExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $panUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $panUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Aadhaar --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Aadhaar Card</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($aadUrl)
                        <div class="mb-2 small text-muted">{{ $aadName }}</div>
                        @if(in_array($aadExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $aadUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $aadUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Business Entity Registration --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Business Entity Registration</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($beUrl)
                        <div class="mb-2 small text-muted">{{ $beName }}</div>
                        @if(in_array($beExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $beUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $beUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- GST --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>GST Registration Certificate</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($gstUrl)
                        <div class="mb-2 small text-muted">{{ $gstName }}</div>
                        @if(in_array($gstExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $gstUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $gstUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- FSSAI --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>FSSAI Registration / Licensed Kitchen</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($fsUrl)
                        <div class="mb-2 small text-muted">{{ $fsName }}</div>
                        @if(in_array($fsExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $fsUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $fsUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Building Certificate --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Building Completion / Permission</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($bUrl)
                        <div class="mb-2 small text-muted">{{ $bName }}</div>
                        @if(in_array($bExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $bUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $bUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Declaration Form --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Declaration Form</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($decUrl)
                        <div class="mb-2 small text-muted">{{ $decName }}</div>
                        @if(in_array($decExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $decUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $decUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- MPCB (optional) --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>MPCB Certificate</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($mpUrl)
                        <div class="mb-2 small text-muted">{{ $mpName }}</div>
                        @if(in_array($mpExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $mpUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $mpUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Star Classified (optional) --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Star Classified Certificate</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($starUrl)
                        <div class="mb-2 small text-muted">{{ $starName }}</div>
                        @if(in_array($starExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $starUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $starUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Water Bill (optional) --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Water Bill</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($waterUrl)
                        <div class="mb-2 small text-muted">{{ $waterName }}</div>
                        @if(in_array($waterExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $waterUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $waterUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Light Bill (required) --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Light Bill</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($lightUrl)
                        <div class="mb-2 small text-muted">{{ $lightName }}</div>
                        @if(in_array($lightExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $lightUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $lightUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Fire NOC (optional) --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Fire NOC</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($fireUrl)
                        <div class="mb-2 small text-muted">{{ $fireName }}</div>
                        @if(in_array($fireExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $fireUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $fireUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Property Tax (optional) --}}
                  <div class="col-md-6 col-lg-4 mb-3">
                    <div class="doc-card p-3 h-100">
                      <h6>Property Tax</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($propUrl)
                        <div class="mb-2 small text-muted">{{ $propName }}</div>
                        @if(in_array($propExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $propUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $propUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                  {{-- Electricity Bill (optional) --}}
                  <div class="col-md-6 col-lg-4 mb-1">
                    <div class="doc-card p-3 h-100">
                      <h6>Electricity Bill</h6>
                      <div class="doc-label mb-1">Max 5 MB</div>
                      @if($elecUrl)
                        <div class="mb-2 small text-muted">{{ $elecName }}</div>
                        @if(in_array($elecExt,['jpg','jpeg','png','gif','webp']))
                          <a href="javascript:void(0)"
                             class="doc-link doc-image-thumb"
                             data-img="{{ $elecUrl }}">
                            <i class="bi bi-image"></i> View Image
                          </a>
                        @else
                          <a href="{{ $elecUrl }}" target="_blank" class="doc-link">
                            <i class="bi bi-file-earmark-text"></i> Open Document
                          </a>
                        @endif
                      @else
                        <span class="badge badge-na">N/A</span>
                      @endif
                    </div>
                  </div>

                </div>
              </div>
            </div>

            {{-- ===================== DECLARATION & ACTION BUTTONS ===================== --}}
            <h5 class="mt-3">Declaration</h5>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="declaration_accept" name="declaration_accept">
              <label class="form-check-label" for="declaration_accept">
                I hereby declare that all the information provided is true and correct to the best of my knowledge.
              </label>
            </div>

            <div class="d-flex justify-content-between mt-3">
              <a href="{{ route('industrial.wizard.show', [$application,'step'=>3]) }}"
                 class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Previous
              </a>

              <button type="submit" id="finalSubmitBtn" class="btn btn-success" disabled
                      style="background-color:#28a745;border:none;border-radius:8px;padding:0.6rem 1.5rem;font-weight:700;">
                Submit Application <i class="bi bi-check2-circle"></i>
              </button>
            </div>

          </div>
        </div>

      </form>
    </div>
  </div>
</section>

{{-- Image Preview Modal --}}
<div class="modal fade" id="docImagePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img id="docImagePreviewImg" src="" alt="Preview" style="width:100%;height:auto;">
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(function(){

  // jQuery Validate for declaration
  $("#step4Form").validate({
    rules:{
      declaration_accept:{ required:true }
    },
    messages:{
      declaration_accept:{ required:"You must accept the declaration to submit." }
    },
    errorElement:'div',
    errorPlacement:function(error, element){
      error.addClass('text-danger mt-1');
      element.closest('.form-check').append(error);
    }
  });

  // checkbox -> enable/disable submit button
  $('#declaration_accept').on('change', function(){
    $('#finalSubmitBtn').prop('disabled', !$(this).is(':checked'));
  });

  // Doc image click -> show big in modal
  $(document).on('click','.doc-image-thumb',function(){
    const src = $(this).data('img');
    $('#docImagePreviewImg').attr('src', src);
    $('#docImagePreviewModal').modal('show');
  });
});
</script>
@endpush
