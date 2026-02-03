@extends('frontend.layouts2.master')
@section('title', 'Review & Submit')


  @push('styles')
  @include('frontend.wizard.css')
  <style>
    .required::after{content:" *"; color:#dc3545;}
    .form-icon{margin-right:.35rem;}
    .invalid-feedback{display:block;}
    .card-wizard{border-radius:1rem;}
    .sticky-actions{gap:.5rem;}

    /* optional: nice grid for checkboxes */
    .checkbox-grid{
      display:grid; grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
      gap:.5rem 1.25rem;
    }
  </style>
@endpush


@push('styles')
<style>
  .review-card { border:1px solid #efefef; border-radius:8px; overflow:hidden; margin-bottom:1rem; background:#fff; }
  .review-card-header { background:#ff6600; color:#fff; padding:.75rem 1rem; font-weight:700; display:flex; align-items:center; gap:.5rem; }
  .review-card-body { padding:1rem; }
  .rc-col { flex:1 1 320px; min-width:220px; }
  .rc-files { flex:0 1 300px; min-width:260px; }
  .badge-field { display:inline-block; padding:.25rem .5rem; background:#f1f1f1; border-radius:6px; margin:.15rem 0; color:#333; font-size:.9rem; }
  .muted { color:#666; font-size:.9rem; }
  .small-muted { font-size:.85rem; color:#888; word-break:break-all;}
  .img-preview { max-width:100%; height:auto; border-radius:6px; border:1px solid #e9e9e9; object-fit:contain; }
  .file-action { display:inline-block; padding:.35rem .6rem; background:#fff; border:1px solid #ddd; border-radius:6px; color:#333; text-decoration:none; }
</style>
</style>
@endpush
@section('content')
<section class="section">
  <div class="section-header form-header">
    <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
  </div>
  @include('frontend.wizard._stepper')
  <h4 class="section-title">
    <i class="bi bi-file-check"></i>
    Review & Submit
</h4>



@php



$a = optional($application->applicant);
// if $business_type collection is available, map id->name
$bizTypeName = null;
if (isset($business_type) && $business_type instanceof \Illuminate\Support\Collection) {
    $biz = $business_type->firstWhere('id', $a->business_type);
    $bizTypeName = $biz ? $biz->name : ($a->business_type ?? null);
} else {
    $bizTypeName = $a->business_type ?? null;
}

$ownershipUrl = $a->ownership_proof && Illuminate\Support\Facades\Storage::disk('public')->exists($a->ownership_proof)
    ? Illuminate\Support\Facades\Storage::disk('public')->url($a->ownership_proof) : null;

$rentalUrl = $a->rental_agreement && Illuminate\Support\Facades\Storage::disk('public')->exists($a->rental_agreement)
    ? Illuminate\Support\Facades\Storage::disk('public')->url($a->rental_agreement) : null;
@endphp

<div class="review-card" style="border:1px solid #efefef;border-radius:8px;overflow:hidden;margin-bottom:1rem;">
<div class="review-card-header"
     style="background:#ff6600;color:#fff;padding:.75rem 1rem;font-weight:700;display:flex;align-items:center;gap:.5rem;">
  <i class="bi bi-person-badge"></i>
  Applicant Details
</div>

<div class="review-card-body" style="padding:1rem;background:#fff;">
  <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
    <div style="flex:1 1 320px;min-width:220px;">
      <div style="margin-bottom:.6rem;"><strong>Name:</strong><div style="color:#333">{{ $a->name ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Phone:</strong><div style="color:#333">{{ $a->phone ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Email:</strong><div style="color:#333">{{ $a->email ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Business Name:</strong><div style="color:#333">{{ $a->business_name ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Business Type:</strong><div style="color:#333">{{ $bizTypeName ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>PAN:</strong><div style="color:#333">{{ $a->pan ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Business PAN:</strong><div style="color:#333">{{ $a->business_pan ?? '—' }}</div></div>
    </div>

    <div style="flex:1 1 320px;min-width:220px;">
      <div style="margin-bottom:.6rem;"><strong>Aadhaar:</strong><div style="color:#333">{{ $a->aadhaar ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Udyam URN:</strong><div style="color:#333">{{ $a->udyam ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Ownership Proof Type:</strong><div style="color:#333">{{ $a->ownership_proof_type ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>State:</strong><div style="color:#333">{{ $a->state ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>District:</strong><div style="color:#333">{{ $a->district ?? '—' }}</div></div>
      <div style="margin-bottom:.6rem;"><strong>Is Property Rented:</strong>
        <div style="color:#333">{{ isset($a->is_property_rented) ? ($a->is_property_rented ? 'Yes' : 'No') : '—' }}</div>
      </div>
      <div style="margin-bottom:.6rem;"><strong>Operator Name:</strong><div style="color:#333">{{ $a->operator_name ?? '—' }}</div></div>
    </div>


    <div style="flex:0 1 300px;min-width:260px;">
      <div style="margin-bottom:.6rem;"><strong>Ownership Proof:</strong></div>
      @if($ownershipUrl)
        @if(Illuminate\Support\Str::endsWith(strtolower($a->ownership_proof), ['.jpg','.jpeg','.png']))
          <a href="{{ $ownershipUrl }}" target="_blank" style="display:block;margin-bottom:.5rem;">
            <img src="{{ $ownershipUrl }}" alt="ownership" style="max-width:100%;height:140px;object-fit:contain;border-radius:6px;border:1px solid #ddd;" />
          </a>
          <div style="font-size:.85rem;color:#666;word-break:break-all;">{{ $a->ownership_proof }}</div>
        @else
          <div style="margin-bottom:.5rem;">
            <a href="{{ $ownershipUrl }}" target="_blank" style="display:inline-block;padding:.4rem .6rem;border:1px solid #ddd;border-radius:6px;background:#fff;color:#333;text-decoration:none;">
              <i class="bi bi-file-earmark-pdf"></i> Open PDF
            </a>
          </div>
          <div style="font-size:.85rem;color:#666;word-break:break-all;">{{ $a->ownership_proof }}</div>
        @endif
      @else
        <div style="color:#888">No file uploaded</div>
      @endif

      <hr style="margin: .75rem 0; border-color:#f0f0f0;">

      <div style="margin-bottom:.6rem;"><strong>Rental Agreement:</strong></div>
      @if($rentalUrl)
        @if(Illuminate\Support\Str::endsWith(strtolower($a->rental_agreement), ['.jpg','.jpeg','.png']))
          <a href="{{ $rentalUrl }}" target="_blank" style="display:block;margin-bottom:.5rem;">
            <img src="{{ $rentalUrl }}" alt="rental" style="max-width:100%;height:120px;object-fit:contain;border-radius:6px;border:1px solid #ddd;" />
          </a>
          <div style="font-size:.85rem;color:#666;word-break:break-all;">{{ $a->rental_agreement }}</div>
        @else
          <div style="margin-bottom:.5rem;">
            <a href="{{ $rentalUrl }}" target="_blank" style="display:inline-block;padding:.4rem .6rem;border:1px solid #ddd;border-radius:6px;background:#fff;color:#333;text-decoration:none;">
              <i class="bi bi-file-earmark-pdf"></i> Open PDF
            </a>
          </div>
          <div style="font-size:.85rem;color:#666;word-break:break-all;">{{ $a->rental_agreement }}</div>
        @endif
      @else
        <div style="color:#888">No file uploaded</div>
      @endif
    </div>
  </div> {{-- end flex --}}

  {{-- small actions / edit button --}}
  <div style="margin-top:1rem;display:flex;gap:.5rem;justify-content:flex-end;">
    <a href="{{ route('wizard.show', [$application, 'step' => 1]) }}" class="btn" style="background:#2d06bc;border:1px solid #ddd;padding:.45rem .8rem;border-radius:6px;color:#ffffff;">
      Edit
    </a>

  </div>
</div>
</div>

@php
  $p = optional($application->property);
  // existing file URLs
  $addressPath = $p->address_proof ?? null;
  $addressUrl = $addressPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($addressPath)
      ? \Illuminate\Support\Facades\Storage::disk('public')->url($addressPath)
      : null;
@endphp

<div class="review-card" style="border:1px solid #efefef;border-radius:8px;overflow:hidden;margin-bottom:1rem;">
  <div class="review-card-header"
       style="background:#ff6600;color:#fff;padding:.75rem 1rem;font-weight:700;display:flex;align-items:center;gap:.5rem;">
    <i class="bi bi-person-badge"></i>
    B) Details of the Property
  </div>

  <div class="review-card-body" style="padding:1rem;background:#fff;">
    <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
      <div style="flex:1 1 320px;min-width:220px;">
        <div style="margin-bottom:.6rem;"><strong>Property Name:</strong>
          <div style="color:#333">{{ $p->property_name ?? '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Geo / Map Link:</strong>
          <div style="color:#333;word-break:break-word">{{ $p->geo_link ?? '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Address:</strong>
          <div style="color:#333;white-space:pre-wrap">{{ $p->address ?? '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>District:</strong>
          <div style="color:#333">{{ optional($p->district)->name ?? ($p->district_id ? $p->district_id : '—') }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Total Area (sq.ft):</strong>
          <div style="color:#333">{{ $p->total_area_sqft ?? '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Mahabooking Reg. No:</strong>
          <div style="color:#333">{{ $p->mahabooking_reg_no ?? '—' }}</div>
        </div>
      </div>

      <div style="flex:1 1 320px;min-width:220px;">
        <div style="margin-bottom:.6rem;"><strong>Address Proof Type:</strong>
          <div style="color:#333">{{ $p->address_proof_type ?? '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Operational:</strong>
          <div style="color:#333">{{ isset($p->is_operational) ? ($p->is_operational ? 'Yes' : 'No') : '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Operational Since (Year):</strong>
          <div style="color:#333">{{ $p->operational_since ?? '—' }}</div>
        </div>

        <div style="margin-bottom:.6rem;"><strong>Guests till Mar 2025:</strong>
          <div style="color:#333">{{ $p->guests_till_march ?? '—' }}</div>
        </div>
      </div>

      {{-- files column --}}
      <div style="flex:0 1 300px;min-width:260px;">
        <div style="margin-bottom:.5rem;"><strong>Address Proof:</strong></div>

        @if($addressUrl)
          @if(\Illuminate\Support\Str::endsWith(strtolower($addressPath), ['.jpg','.jpeg','.png','.webp']))
            <a href="{{ $addressUrl }}" target="_blank" style="display:block;margin-bottom:.5rem;">
              <img src="{{ $addressUrl }}" alt="address proof" style="max-width:100%;height:160px;object-fit:contain;border-radius:6px;border:1px solid #ddd;" />
            </a>
            <div style="font-size:.85rem;color:#666;word-break:break-all;">{{ $addressPath }}</div>
          @else
            <div style="margin-bottom:.5rem;">
              <a href="{{ $addressUrl }}" target="_blank" style="display:inline-block;padding:.4rem .6rem;border:1px solid #ddd;border-radius:6px;background:#fff;color:#333;text-decoration:none;">
                <i class="bi bi-file-earmark-pdf"></i> Open PDF
              </a>
            </div>
            <div style="font-size:.85rem;color:#666;word-break:break-all;">{{ $addressPath }}</div>
          @endif
        @else
          <div style="color:#888">No file uploaded</div>
        @endif
      </div>
    </div>

    <div style="margin-top:1rem;display:flex;gap:.5rem;justify-content:flex-end;">
      <a href="{{ route('wizard.show', [$application, 'step' => 2]) }}" class="btn" style="background:#4104ec;border:1px solid #ddd;padding:.45rem .8rem;border-radius:6px;color:#ffffff;">
        Edit
      </a>
      <a href="{{ route('wizard.show', [$application, 'step' => 3]) }}" class="btn" style="background:#ff6600;color:#fff;padding:.45rem .8rem;border-radius:6px;border:0;">
        Continue
      </a>
    </div>
  </div>
</div>

@php
    $acc = optional($application->accommodation);
    // flat types: ensure array
    $flatTypes = $acc->flat_types ?? [];
    if (is_string($flatTypes)) {
        $decoded = json_decode($flatTypes, true);
        $flatTypes = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [$flatTypes];
    }
    $flatTypes = is_array($flatTypes) ? $flatTypes : [];
  @endphp

  <div class="review-card">
    <div class="review-card-header">
      <i class="bi bi-house-door"></i>
      C) Accommodation Details
    </div>

    <div class="review-card-body">
      <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">

        {{-- Left column: main details --}}
        <div class="rc-col">
          <div style="margin-bottom:.6rem;"><strong>Total Flats/Rooms:</strong>
            <div class="muted">{{ $acc->flats_count ?? '—' }}</div>
          </div>

          <div style="margin-bottom:.6rem;"><strong>Flat/Room Types:</strong>
            <div style="margin-top:.4rem;">
              @if(count($flatTypes))
                @foreach($flatTypes as $ft)
                  <div class="badge-field">{{ $ft }}</div>
                @endforeach
              @else
                <div class="muted">—</div>
              @endif
            </div>
          </div>

          <div style="margin-bottom:.6rem;"><strong>Attached Toilet for each room:</strong>
            <div class="muted">{{ isset($acc->attached_toilet) ? ($acc->attached_toilet ? 'Yes' : 'No') : '—' }}</div>
          </div>

          <div style="margin-bottom:.6rem;"><strong>Dustbins for garbage disposal:</strong>
            <div class="muted">{{ isset($acc->has_dustbins) ? ($acc->has_dustbins ? 'Yes' : 'No') : '—' }}</div>
          </div>
        </div>

        {{-- Right column: other toggles --}}
        <div class="rc-col">
          <div style="margin-bottom:.6rem;"><strong>Road Access:</strong>
            <div class="muted">{{ isset($acc->road_access) ? ($acc->road_access ? 'Yes' : 'No') : '—' }}</div>
          </div>

          <div style="margin-bottom:.6rem;"><strong>Food on Request:</strong>
            <div class="muted">{{ isset($acc->food_on_request) ? ($acc->food_on_request ? 'Yes' : 'No') : '—' }}</div>
          </div>

          <div style="margin-bottom:.6rem;"><strong>Payment via Cash/UPI:</strong>
            <div class="muted">{{ isset($acc->payment_upi) ? ($acc->payment_upi ? 'Yes' : 'No') : '—' }}</div>
          </div>
        </div>

        {{-- Files or notes column (if you later add files) --}}
        <div class="rc-files">
          <div style="margin-bottom:.6rem;"><strong>Notes / Raw Data:</strong>
            <div class="small-muted">@if($acc) Stored record ID: {{ $acc->id ?? '—' }} @else — @endif</div>
          </div>

          <div style="margin-top:.6rem;">
            <strong>Saved JSON (flat_types)</strong>
            <pre style="white-space:pre-wrap; background:#fafafa;border:1px solid #eee;padding:.5rem;border-radius:6px;font-size:.85rem;">{{ json_encode($flatTypes, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
          </div>
        </div>
      </div>

      {{-- actions --}}
      <div style="margin-top:1rem;display:flex;gap:.5rem;justify-content:flex-end;">
        <a href="{{ route('wizard.show', [$application, 'step' => 3]) }}" class="btn" style="background:#5403f6;border:1px solid #ddd;padding:.45rem .8rem;border-radius:6px;color:#ffffff;">
          Edit
        </a>

        <a href="{{ route('wizard.show', [$application, 'step' => 4]) }}" class="btn" style="background:#ff6600;color:#fff;padding:.45rem .8rem;border-radius:6px;border:0;">
          Continue
        </a>
      </div>
    </div>
  </div>


    @php
      // safe access to relation
      $fRel = optional($application->facilities);
      $saved = $fRel->facilities ?? null;

      // normalize to array of ints
      if (is_string($saved)) {
        $decoded = json_decode($saved, true);
        $saved = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [$saved];
      }
      $selectedIds = is_array($saved) ? array_values(array_filter(array_map('intval', $saved))) : [];

      // fetch facility records for display (non-blocking simple query)
      $facilityRows = \Illuminate\Support\Facades\DB::table('tourismfacilities')
                        ->whereIn('id', $selectedIds ?: [0])
                        ->orderBy('name')
                        ->get();

      // gras_paid value
      $grasPaid = isset($fRel->gras_paid) ? (int)$fRel->gras_paid : null;
    @endphp

    <div class="review-card">
      <div class="review-card-header">
        <i class="bi bi-grid-3x3-gap"></i>
        D) Common Facilities
      </div>

      <div class="review-card-body">
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
          <div class="rc-col">
            <div style="margin-bottom:.6rem;"><strong>Selected Facilities:</strong>
              <div style="margin-top:.4rem;">
                @if(count($facilityRows))
                  @foreach($facilityRows as $row)
                    <span class="badge-field"><i class="{{ $row->icon ?? 'bi bi-check-circle' }}" aria-hidden="true"></i> &nbsp;{{ $row->name }}</span>
                  @endforeach
                @elseif(count($selectedIds))
                  {{-- if ids exist but no rows found --}}
                  @foreach($selectedIds as $id)
                    <span class="badge-field">ID: {{ $id }}</span>
                  @endforeach
                @else
                  <div class="muted">No facilities selected</div>
                @endif
              </div>
            </div>
          </div>

          <div class="rc-col">
            <div style="margin-bottom:.6rem;"><strong>GRAS Payment (Rs. 500/-):</strong>
              <div class="muted">
                @if($grasPaid === 1) Yes @elseif($grasPaid === 0) No @else — @endif
              </div>
            </div>

            <div style="margin-top:.6rem;">
              <strong>Saved record ID:</strong>
              <div class="muted">{{ $fRel->id ?? '—' }}</div>
            </div>
          </div>

          <div style="flex:0 1 300px;min-width:260px;">
            <div style="margin-bottom:.4rem;"><strong>Raw saved payload (for debug)</strong></div>
            <pre class="json-box">{{ json_encode($saved, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
          </div>
        </div>

        <div style="margin-top:1rem;display:flex;gap:.5rem;justify-content:flex-end;">
          <a href="{{ route('wizard.show', [$application, 'step' => 4]) }}" class="btn" style="background:#fff;border:1px solid #ddd;padding:.45rem .8rem;border-radius:6px;color:#333;">
            Edit
          </a>

          <a href="{{ route('wizard.show', [$application, 'step' => 5]) }}" class="btn" style="background:#ff6600;color:#fff;padding:.45rem .8rem;border-radius:6px;border:0;">
            Continue
          </a>
        </div>
      </div>
    </div>

    @php
    $p = optional($application->photos);
    $imagePath = $p->applicant_image ?? null;
    $signPath  = $p->applicant_signature ?? null;

    $imageUrl = $imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath) : null;

    $signUrl  = $signPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($signPath)
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($signPath) : null;
  @endphp

  <div class="review-card">
    <div class="review-card-header">
      <i class="bi bi-camera"></i>
      Photo & Signature
    </div>

    <div class="review-card-body">
      <div style="display:flex;gap:1.25rem;flex-wrap:wrap;align-items:flex-start;">

        <div class="rc-col">
          <div style="margin-bottom:.6rem;">
            <strong>Applicant Photo</strong>
            <div style="margin-top:.5rem;">
              @if($imageUrl)
                <a href="{{ $imageUrl }}" target="_blank" rel="noopener" style="display:block;max-width:280px;">
                  <img src="{{ $imageUrl }}" alt="Applicant Photo" class="img-preview" style="height:160px; width:auto;">
                </a>
                <div class="small-muted mt-2">{{ $imagePath }}</div>
              @elseif($imagePath)
                <div class="muted">File saved but not found on disk: <span class="small-muted">{{ $imagePath }}</span></div>
              @else
                <div class="muted">No photo uploaded</div>
              @endif
            </div>
          </div>
        </div>

        <div class="rc-col">
          <div style="margin-bottom:.6rem;">
            <strong>Applicant Signature</strong>
            <div style="margin-top:.5rem;">
              @if($signUrl)
                <a href="{{ $signUrl }}" target="_blank" rel="noopener" style="display:block;max-width:260px;">
                  <img src="{{ $signUrl }}" alt="Applicant Signature" class="img-preview" style="height:100px; width:auto;">
                </a>
                <div class="small-muted mt-2">{{ $signPath }}</div>
              @elseif($signPath)
                <div class="muted">File saved but not found on disk: <span class="small-muted">{{ $signPath }}</span></div>
              @else
                <div class="muted">No signature uploaded</div>
              @endif
            </div>
          </div>
        </div>

        <div class="rc-files">
          <div style="margin-bottom:.6rem;"><strong>Record</strong>
            <div class="muted mt-1">Photos record ID: {{ $p->id ?? '—' }}</div>
          </div>

          <div style="margin-top:.75rem;">
            @if($imageUrl || $signUrl)
              <div style="display:flex;flex-direction:column;gap:.5rem;">
                @if($imageUrl)
                  <a class="file-action" href="{{ $imageUrl }}" target="_blank" rel="noopener"><i class="bi bi-image"></i> Open Photo</a>
                @endif
                @if($signUrl)
                  <a class="file-action" href="{{ $signUrl }}" target="_blank" rel="noopener"><i class="bi bi-pencil"></i> Open Signature</a>
                @endif
              </div>
            @else
              <div class="muted">No file links available</div>
            @endif
          </div>
        </div>

      </div>

      <div style="margin-top:1rem;display:flex;gap:.5rem;justify-content:flex-end;">
        <a href="{{ route('wizard.show', [$application, 'step' => 5]) }}" class="btn" style="background:#fff;border:1px solid #ddd;padding:.45rem .8rem;border-radius:6px;color:#333;">
          Edit
        </a>

        <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn" style="background:#ff6600;color:#fff;padding:.45rem .8rem;border-radius:6px;border:0;">
          Continue
        </a>
      </div>
    </div>
  </div>

  @push('styles')
  <style>
    .review-table { border:1px solid #efefef; border-radius:8px; overflow:hidden; background:#fff; }
    .review-table thead th { background:#ff6600; color:#fff; font-weight:700; padding:.75rem; text-align:left; }
    .review-table td, .review-table th { padding:.75rem; vertical-align:middle; border-bottom:1px solid #f3f3f3; }
    .preview-thumb { max-height:110px; border-radius:6px; border:1px solid #e9e9e9; object-fit:contain; display:block; }
    .no-file { color:#888; font-style:italic; }
    .small-muted { font-size:.85rem; color:#666; word-break:break-all; }
    .status-badge { padding:.25rem .5rem; border-radius:.25rem; font-weight:600; font-size:.85rem; display:inline-block; }
    .status-uploaded { background:#d1fae5; color:#065f46; }
    .status-pending { background:#fff4e5; color:#92400e; }
    .actions .btn { padding:.35rem .6rem; margin-right:.25rem; border-radius:6px; }
    .photos-gallery { display:flex; flex-wrap:wrap; gap:8px; }
    .photo-item { width:80px; height:80px; border:1px solid #e9e9e9; border-radius:6px; overflow:hidden; position:relative; }
    .photo-item img { width:100%; height:100%; object-fit:cover; cursor:pointer; }
    @media (max-width:800px) {
      .preview-thumb { max-height:80px; }
    }
  </style>
  @endpush
  @php
  use Illuminate\Support\Facades\Storage;

  // define the documents list (same as upload page)
  $docs = [
    'aadhar' => 'Aadhaar Card of the Applicant*',
    'pan' => 'PAN Card of the Applicant*',
    'business_pan' => 'Business PAN Card (if applicable)',
    'udyam' => 'Udyam Aadhaar (if applicable)',
    'business_reg' => 'Business Registration Certificate*',
    'ownership' => 'Proof of Ownership of Property*',
    'property_photos' => 'Photos of the Property (min 5)*',
    'character' => 'Character Certificate from Police Station*',
    'society_noc' => 'NOC from Society*',
    'building_perm' => 'Building Permission/Completion Certificate*',
    'gras_copy' => 'Copy of GRAS Challan*',
    'undertaking' => 'Undertaking (Duly signed)*',
    'rental_agreement' => 'Rental Agreement / Management Contract (if applicable)',
  ];

  // ensure documents relationship loaded
  $application->loadMissing('documents');
@endphp

<div class="mb-3 d-flex justify-content-between align-items-center">
  <div>
    <h4 class="section-title mb-0"><i class="bi bi-file-earmark-text"></i> Documents summary</h4>
    <div class="small-muted">Click image to preview (modal). Click file name / view to open PDF in new tab.</div>
  </div>

  <a href="{{ asset('frontend/Undertaking_Final.docx') }}" class="btn btn-primary" download target="_blank" rel="noopener">
    <i class="bi bi-download me-1"></i> Download Undertaking Form
  </a>
</div>

<div class="review-table">
  <table class="w-100">
    <thead>
      <tr>
        <th width="60">S.N.</th>
        <th>Document</th>
        <th width="200">Status</th>
        <th width="300">Preview</th>
        <th>Stored path / info</th>
        <th width="160">Actions</th>
      </tr>
    </thead>

    <tbody>
      @foreach($docs as $key => $label)
        @php
          $existingDocs = $application->documents->where('category', $key);
          $isPropertyPhotos = $key === 'property_photos';
          $first = $existingDocs->first();
        @endphp

        <tr>
          <td>{{ $loop->iteration }}</td>

          <td>
            <strong>{{ str_replace('*','',$label) }}</strong>
            @if(str_contains($label, '*'))
              <div class="small-muted">Required</div>
            @endif
            @if($isPropertyPhotos)
              <div class="small-muted mt-1">
                {{ $existingDocs->count() }} / 5 photos uploaded
              </div>
            @endif
          </td>

          <td>
            @if($existingDocs->count() > 0)
              <span class="status-badge status-uploaded">Uploaded</span>
            @else
              <span class="status-badge status-pending">Pending</span>
            @endif
          </td>

          <td>
            @if($existingDocs->count() > 0)
              @if($isPropertyPhotos)
                <div class="photos-gallery">
                  @foreach($existingDocs as $doc)
                    @php
                      $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                      $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                      $url = Storage::disk('public')->exists($doc->path) ? Storage::disk('public')->url($doc->path) : null;
                    @endphp

                    @if($isImage && $url)
                      <div class="photo-item">
                        <img src="{{ $url }}" alt="{{ $doc->original_name }}"
                             data-src="{{ $url }}" data-name="{{ $doc->original_name }}"
                             onclick="openPreviewModal(this)">
                      </div>
                    @endif
                  @endforeach
                </div>
              @else
                @php
                  $ext = strtolower(pathinfo($first->original_name ?? '', PATHINFO_EXTENSION));
                  $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                  $url = $first && Storage::disk('public')->exists($first->path) ? Storage::disk('public')->url($first->path) : null;
                @endphp

                @if($isImage && $url)
                  <a href="javascript:void(0)" onclick="openPreviewModal(this)" data-src="{{ $url }}" data-name="{{ $first->original_name }}">
                    <img src="{{ $url }}" alt="{{ $first->original_name }}" class="preview-thumb">
                  </a>
                @elseif($url)
                  <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                    <div>
                      <div><a href="{{ $url }}" target="_blank" rel="noopener">{{ $first->original_name }}</a></div>
                      <div class="small-muted">{{ number_format(($first->size ?? 0)/1024) }} KB</div>
                    </div>
                  </div>
                @else
                  <div class="no-file">File saved but not found on disk</div>
                  <div class="small-muted">{{ $first->path ?? '' }}</div>
                @endif
              @endif
            @else
              <div class="no-file">No file uploaded</div>
            @endif
          </td>

          <td>
            @if($existingDocs->count() > 0)
              @if($isPropertyPhotos)
                <div class="small-muted">
                  @foreach($existingDocs as $doc)
                    <div style="margin-bottom:.25rem;">
                      <a href="{{ Storage::disk('public')->exists($doc->path) ? Storage::disk('public')->url($doc->path) : '#' }}"
                         target="_blank" rel="noopener">{{ $doc->original_name }}</a>
                      <div class="small-muted">{{ number_format(($doc->size ?? 0)/1024) }} KB</div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="small-muted">{{ $first->path ?? '—' }}</div>
                <div class="small-muted mt-1">{{ $first->original_name ?? '—' }}</div>
              @endif
            @else
              <div class="small-muted">—</div>
            @endif
          </td>

          <td class="actions">
            @if($existingDocs->count() > 0)
              {{-- View / Download --}}
              @if($isPropertyPhotos)
                <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
              @else
                @php $fileUrl = ($first && Storage::disk('public')->exists($first->path)) ? Storage::disk('public')->url($first->path) : null; @endphp
                @if($fileUrl)
                  <a class="btn btn-sm btn-primary" href="{{ $fileUrl }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> View</a>
                @endif
                <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                @if($fileUrl)
                  <a class="btn btn-sm btn-secondary" href="{{ $fileUrl }}" download target="_blank" rel="noopener4"><i class="bi bi-download"></i> Download</a>
                @endif
              @endif
            @else
              <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i> Upload</a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>




  <div class="card card-wizard border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('wizard.submit', $application) }}" class="mt-4">
        @csrf
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="decl" required>
          <label class="form-check-label" for="decl">
            I hereby declare that all the information provided in this application is true and correct
            to the best of my knowledge. I understand that any false information may lead to rejection
            of my application.
          </label>
          {{-- <div class="invalid-feedback text-ibn">Please accept the declaration.</div> --}}
        </div>

        {{-- <div class="d-flex justify-content-between">
          <a href="{{ route('wizard.show',[$application,'step'=>6]) }}" class="btn btn-light">
            <i class="bi bi-arrow-left"></i> Previous
          </a>
          <button class="btn btn-success" type="submit">Submit Application</button>
        </div> --}}
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
              <a href="{{ route('wizard.show', [$application, 'step' => 6]) }}" class="btn btn-danger text-white">
                <i class="bi bi-arrow-left"></i> Previous
              </a>

              <button type="submit"
                style="
                    background-color:#055f0e;
                    color:#fff;
                    font-weight:700;
                    border:none;
                    border-radius:8px;
                    padding:.6rem 1.5rem;
                    cursor:pointer;
                ">

<i class="bi bi-send-fill" style="margin-right:6px;"></i>
  Submit Application
                </button>

            </div>
          </div>
      </form>
    </div>
  </div>


  <div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="docPreviewLabel">Preview</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img id="docPreviewImage" src="" alt="Preview" class="img-fluid" style="max-height:75vh; border-radius:6px;">
          <div id="docPreviewPdf" class="d-none">
            <i class="bi bi-file-earmark-pdf text-danger display-1"></i>
            <h5 id="docPreviewPdfName" class="mt-2"></h5>
            <p class="text-muted">This is a PDF. Click download/open to view in new tab.</p>
          </div>
        </div>
        <div class="modal-footer">
          <a id="docPreviewDownload" href="#" class="btn btn-primary" target="_blank" rel="noopener"><i class="bi bi-download"></i> Open / Download</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



</section>
@endsection

@push('scripts')
<script>
  function openPreviewModal(el) {
    // el can be <img> or <a> with data-src/data-name or clicked <img> inside .photo-item
    const src = el.getAttribute('data-src') || el.getAttribute('href') || el.src;
    const name = el.getAttribute('data-name') || el.getAttribute('alt') || 'Preview';

    if (!src) return;

    const lower = src.toLowerCase();
    const isPdf = lower.endsWith('.pdf');

    if (isPdf) {
      document.getElementById('docPreviewImage').classList.add('d-none');
      document.getElementById('docPreviewPdf').classList.remove('d-none');
      document.getElementById('docPreviewPdfName').textContent = name;
      document.getElementById('docPreviewDownload').setAttribute('href', src);
    } else {
      document.getElementById('docPreviewImage').classList.remove('d-none');
      document.getElementById('docPreviewPdf').classList.add('d-none');
      document.getElementById('docPreviewImage').setAttribute('src', src);
      document.getElementById('docPreviewDownload').setAttribute('href', src);
    }

    // set download name
    document.getElementById('docPreviewDownload').setAttribute('download', name);
    // set modal title
    document.getElementById('docPreviewLabel').textContent = name;

    // show modal (Bootstrap 5)
    var myModal = new bootstrap.Modal(document.getElementById('docPreviewModal'));
    myModal.show();
  }

  // allow clicking the img inside <a> which had onclick above: make sure event target is element with data-src
  document.addEventListener('click', function (e) {
    var el = e.target;
    if (el && el.matches('.photo-item img')) {
      openPreviewModal(el);
    } else if (el && el.closest && el.closest('.photo-item')) {
      var img = el.closest('.photo-item').querySelector('img');
      if (img) openPreviewModal(img);
    } else if (el && el.matches('a[data-src]')) {
      e.preventDefault();
      openPreviewModal(el);
    }
  }, false);
</script>
@endpush
