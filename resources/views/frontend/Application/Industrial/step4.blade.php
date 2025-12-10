@extends('frontend.layouts2.master')
@section('title','Hotel Registration - Step 4')

@push('styles')
<style>
  :root{ --brand:#ff6600; }
  .form-container { background:#fff; padding:1.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.05); }
</style>
@endpush

@section('content')
@php($p = $application->progress)

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

      @include('frontend.industrial._step-indicator', ['application'=>$application,'p'=>$p])

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
              <h5><i class="bi bi-info-circle me-2"></i>Please review all information before submitting</h5>
              <p class="mb-0">Once submitted, you will not be able to edit this application.</p>
            </div>

            {{-- Basic info summary from DB --}}
            <h5 class="mt-3">Basic Information</h5>
            <table class="table table-sm">
              <tbody>
                <tr><th>Hotel Name</th><td>{{ $step1->hotel_name ?? '-' }}</td></tr>
                <tr><th>Company Name</th><td>{{ $step1->company_name ?? '-' }}</td></tr>
                <tr><th>Email</th><td>{{ $step1->email ?? '-' }}</td></tr>
                <tr><th>Mobile</th><td>{{ $step1->mobile ?? '-' }}</td></tr>
                <tr><th>Region</th><td>{{ $step1->region ?? '-' }}</td></tr>
                <tr><th>District</th><td>{{ $step1->district ?? '-' }}</td></tr>
                <tr><th>Pin Code</th><td>{{ $step1->pincode ?? '-' }}</td></tr>
              </tbody>
            </table>

            <h5 class="mt-3">Key Facilities</h5>
            <ul>
              @if(($step2->min_rooms ?? false)) <li>Minimum 6 lettable rooms</li> @endif
              @if(($step3->bath_attached ?? false)) <li>Attached bathrooms</li> @endif
              @if(($step3->public_lobby ?? false)) <li>Lounge / Lobby seating</li> @endif
              @if(($step3->doctor_on_call ?? false)) <li>Doctor on call</li> @endif
            </ul>

            <h5 class="mt-3">Declaration</h5>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="declaration_accept" name="declaration_accept">
              <label class="form-check-label" for="declaration_accept">
                I hereby declare that all the information provided is true and correct to the best of my knowledge.
              </label>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('industrial.wizard.show', [$application,'step'=>3]) }}"
                 class="btn btn-secondary">
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
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(function(){
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

  $('#declaration_accept').on('change', function(){
    $('#finalSubmitBtn').prop('disabled', !$(this).is(':checked'));
  });
});
</script>
@endpush
