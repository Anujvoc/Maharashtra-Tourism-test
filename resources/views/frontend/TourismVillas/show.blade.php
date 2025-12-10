@extends('frontend.layouts2.master')

@section('title', 'Tourist Villa Application #'.$item->id)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  .card-soft{ border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.06); border:1px solid rgba(255,106,0,.12); }
  .section-hd{ background:var(--brand); color:#fff; padding:.85rem 1.1rem; font-weight:700; display:flex; align-items:center; gap:.6rem; }
  .kv{ display:grid; grid-template-columns: 220px 1fr; gap:.5rem 1rem; padding:1rem 1.1rem; }
  .kv .k{ color:var(--ink); font-weight:600; }
  .kv .v{ color:var(--muted); }
  .badge-fac{ border:1px solid rgba(255,106,0,.35); color:var(--brand); background:rgba(255,106,0,.08); font-weight:600; }
  .pill{ border-radius:999px; padding:.25rem .65rem; font-weight:700; }
  .pill-yes{ background:#dcfce7; color:#166534; }
  .pill-no{ background:#fee2e2; color:#991b1b; }
  .actions{ display:flex; gap:.5rem; flex-wrap:wrap; }
  @media (max-width:768px){ .kv{ grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Application #{{ $item->id }} — Tourist Villa Registration</h1>
    <div class="actions mt-2">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
      <a href="{{ route('frontend.villa-registrations.index') }}" class="btn btn-outline-primary"><i class="bi bi-list-task"></i> All Applications</a>
      <button onclick="window.print()" class="btn btn-outline-dark"><i class="bi bi-printer"></i> Print</button>
      <a href="{{ route('frontend.villa-registrations.edit', $item) }}" class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i> Edit</a>
    </div>
  </div>

  <div class="container">
    {{-- Meta --}}
    <div class="mb-3 text-muted">
      Submitted on <strong>{{ $item->created_at?->format('d M Y, h:i A') }}</strong>
      @if(!is_null($item->status))
        • Status:
        <span class="pill {{ $item->status ? 'pill-yes':'pill-no' }}">
          {{ $item->status ? 'Active' : 'Inactive' }}
        </span>
      @endif
    </div>

    {{-- A) Applicant --}}
    <div class="card card-soft mb-4">
      <div class="section-hd"><i class="bi bi-person-badge"></i> A) Applicant Details</div>
      <div class="kv">
        <div class="k">Applicant Name</div><div class="v">{{ $item->applicant_name }}</div>
        <div class="k">Phone</div><div class="v">{{ $item->applicant_phone }}</div>
        <div class="k">Email</div><div class="v">{{ $item->applicant_email }}</div>
        <div class="k">Business Name</div><div class="v">{{ $item->business_name }}</div>
        <div class="k">Business Type</div><div class="v">{{ $item->business_type }}</div>
        <div class="k">PAN</div><div class="v">{{ $item->pan_number }}</div>
        <div class="k">Business PAN</div><div class="v">{{ $item->business_pan_number ?? 'N/A' }}</div>
        <div class="k">Aadhaar</div><div class="v">{{ $item->aadhar_number }}</div>
        <div class="k">Udyam Aadhaar</div><div class="v">{{ $item->udyam_aadhar_number ?? 'N/A' }}</div>
        <div class="k">Ownership Proof</div><div class="v">{{ $item->ownership_proof }}</div>
        <div class="k">Property Rented</div>
        <div class="v">
          <span class="pill {{ $item->property_rented ? 'pill-yes':'pill-no' }}">{{ $item->property_rented ? 'Yes' : 'No' }}</span>
        </div>
        @if($item->property_rented)
          <div class="k">Operator Name</div><div class="v">{{ $item->operator_name }}</div>
          <div class="k">Rental Agreement</div>
          <div class="v">
            @if($item->rental_agreement_path)
              <a target="_blank" href="{{ asset('storage/'.$item->rental_agreement_path) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-text"></i> View File
              </a>
            @else
              <span class="text-muted">Not uploaded</span>
            @endif
          </div>
        @endif
      </div>
    </div>

    {{-- B) Property --}}
    <div class="card card-soft mb-4">
      <div class="section-hd"><i class="bi bi-house-check"></i> B) Property Details</div>
      <div class="kv">
        <div class="k">Property Name</div><div class="v">{{ $item->property_name }}</div>
        <div class="k">Address</div><div class="v">{{ $item->property_address }}</div>
        <div class="k">Address Proof</div><div class="v">{{ $item->address_proof }}</div>
        <div class="k">Coordinates / Map</div><div class="v">{{ $item->property_coordinates }}</div>
        <div class="k">Operational</div>
        <div class="v">
          <span class="pill {{ $item->property_operational ? 'pill-yes':'pill-no' }}">{{ $item->property_operational ? 'Yes' : 'No' }}</span>
        </div>
        @if($item->property_operational)
          <div class="k">Operational Since</div><div class="v">{{ $item->operational_year }}</div>
          <div class="k">Guests Hosted (till Mar 2025)</div><div class="v">{{ $item->guests_hosted }}</div>
        @endif
        <div class="k">Total Area</div><div class="v">{{ number_format($item->total_area) }} sq.ft</div>
        <div class="k">Mahabooking No.</div><div class="v">{{ $item->mahabooking_number ?? 'N/A' }}</div>
      </div>
    </div>

    {{-- C) Accommodation --}}
    <div class="card card-soft mb-4">
      <div class="section-hd"><i class="bi bi-door-closed"></i> C) Accommodation</div>
      <div class="kv">
        <div class="k">Number of Rooms</div><div class="v">{{ $item->number_of_rooms }}</div>
        <div class="k">Room Area</div><div class="v">{{ number_format($item->room_area) }} sq.ft</div>
        <div class="k">Attached Toilet</div>
        <div class="v"><span class="pill {{ $item->attached_toilet ? 'pill-yes':'pill-no' }}">{{ $item->attached_toilet ? 'Yes' : 'No' }}</span></div>
        <div class="k">Dustbins in Rooms</div>
        <div class="v"><span class="pill {{ $item->dustbins ? 'pill-yes':'pill-no' }}">{{ $item->dustbins ? 'Yes' : 'No' }}</span></div>
        <div class="k">Road Access</div>
        <div class="v"><span class="pill {{ $item->road_access ? 'pill-yes':'pill-no' }}">{{ $item->road_access ? 'Yes' : 'No' }}</span></div>
        <div class="k">Food on Request</div>
        <div class="v"><span class="pill {{ $item->food_provided ? 'pill-yes':'pill-no' }}">{{ $item->food_provided ? 'Yes' : 'No' }}</span></div>
        <div class="k">Cash/UPI Payment</div>
        <div class="v"><span class="pill {{ $item->payment_options ? 'pill-yes':'pill-no' }}">{{ $item->payment_options ? 'Yes' : 'No' }}</span></div>
      </div>
    </div>

    {{-- D) Facilities --}}
    <div class="card card-soft mb-4">
      <div class="section-hd"><i class="bi bi-grid-3x3-gap"></i> D) Common Facilities</div>
      <div class="p-3">
        @php
          $labels = [
            'kitchen' => 'Kitchen',
            'diningHall' => 'Dining Hall',
            'garden' => 'Garden',
            'parking' => 'Parking',
            'evCharging' => 'EV Charging',
            'playArea' => 'Children Play Area',
            'swimmingPool' => 'Swimming Pool',
            'wifi' => 'Wi-Fi',
            'firstAid' => 'First Aid Box',
            'fireSafety' => 'Fire Safety Equipment',
            'waterPurifier' => 'Water Purifier / RO',
            'rainwaterHarvesting' => 'Rainwater Harvesting',
            'solarPower' => 'Solar Power',
            'renewableEnergy' => 'Other Renewable Energy',
          ];
          $fac = collect($item->facilities ?? []);
        @endphp

        @if($fac->isEmpty())
          <span class="text-muted">No facilities selected.</span>
        @else
          <div class="d-flex flex-wrap gap-2">
            @foreach($fac as $key)
              <span class="badge badge-fac rounded-pill px-3 py-2">
                <i class="bi bi-check2"></i> {{ $labels[$key] ?? $key }}
              </span>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    {{-- E) GRAS --}}
    <div class="card card-soft mb-4">
      <div class="section-hd"><i class="bi bi-cash-coin"></i> E) GRAS Chalan</div>
      <div class="kv">
        <div class="k">Application Fees (₹500) Paid</div>
        <div class="v">
          <span class="pill {{ $item->application_fees ? 'pill-yes':'pill-no' }}">{{ $item->application_fees ? 'Yes' : 'No' }}</span>
        </div>
      </div>
    </div>

    {{-- Footer actions --}}
    <div class="d-flex gap-2 flex-wrap mb-5">
      <a href="{{ route('frontend.villa-registrations.edit', $item) }}" class="btn btn-warning">
        <i class="bi bi-pencil-square"></i> Edit Application
      </a>
      <form action="{{ route('frontend.villa-registrations.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this application?');">
        @csrf @method('DELETE')
        <button class="btn btn-danger"><i class="bi bi-trash3"></i> Delete</button>
      </form>
      <a href="{{ route('frontend.villa-registrations.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
      </a>
    </div>
  </div>
</section>
@endsection
