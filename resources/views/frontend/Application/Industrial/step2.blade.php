@extends('frontend.layouts2.master')
@section('title','Hotel Registration - Step 2')

@push('styles')
<style>
  :root{ --brand:#ff6600; --brand-dark:#e25500; }
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
        <p>Step 2: Facilities & Services</p>
      </div>

      {{-- step indicator same as step1 --}}
      @include('frontend.industrial._step-indicator', ['application'=>$application, 'p'=>$p])

      <form id="step2Form"
            action="{{ route('industrial.wizard.step2.store', $application) }}"
            method="POST"
            novalidate>
        @csrf
        <input type="hidden" name="slug_id" value="{{ $application->slug_id }}">

        <div class="card p-3 mb-4">
          <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
            <h5 class="m-0">Facilities & Services</h5>
          </div>
          <div class="card-body">

            {{-- Bathroom --}}
            <div class="card mb-3">
                <div class="row mb-3">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
                          <h5 class="m-0">General Requirements</h5>
                        </div>
                        <div class="card-body">
                          <ul class="list-unstyled">
                            <li class="mb-2">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="min_rooms" name="min_rooms"
                                       {{ old('min_rooms', $step2->min_rooms ?? false)?'checked':'' }}>
                                <label class="form-check-label" for="min_rooms">
                                  Minimum 6 lettable rooms, all rooms with outside windows / ventilation
                                </label>
                              </div>
                            </li>
                            <li class="mb-2">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="room_size" name="room_size"
                                       {{ old('room_size', $step2->room_size_ok ?? false)?'checked':'' }}>
                                <label class="form-check-label" for="room_size">
                                  Minimum room size (Single 80 sq.ft., Double 120 sq.ft.) with attached bathroom
                                </label>
                              </div>
                            </li>
                            <li class="mb-2">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bathroom_size" name="bathroom_size"
                                       {{ old('bathroom_size', $step2->bathroom_size_ok ?? false)?'checked':'' }}>
                                <label class="form-check-label" for="bathroom_size">
                                  Minimum bathroom size should be 30 sq.ft.
                                </label>
                              </div>
                            </li>
                            <li class="mb-2">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="electricity_availability" name="electricity_availability"
                                       {{ old('electricity_availability', $step2->electricity_availability ?? false)?'checked':'' }}>
                                <label class="form-check-label" for="electricity_availability">
                                  24x7 availability of electricity
                                </label>
                              </div>
                            </li>
                            <li class="mb-2">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="security_guards" name="security_guards"
                                       {{ old('security_guards', $step2->security_guards ?? false)?'checked':'' }}>
                                <label class="form-check-label" for="security_guards">
                                  Security guards available 24 hours a day
                                </label>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>

              <div class="card-header"><h6 class="m-0">Bathroom</h6></div>
              <div class="card-body">
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="bath_attached" name="bath_attached"
                             {{ old('bath_attached', $step3->bath_attached ?? false)?'checked':'' }}>
                      <label class="form-check-label" for="bath_attached">Rooms with attached bathrooms</label>
                    </div>
                  </li>
                  <li class="mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="hot_cold_water" name="hot_cold_water"
                             {{ old('hot_cold_water', $step3->bath_hot_cold ?? false)?'checked':'' }}>
                      <label class="form-check-label" for="hot_cold_water">Hot & Cold running water</label>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            {{-- Public area --}}
            <div class="card mb-3">
              <div class="card-header"><h6 class="m-0">Public Area</h6></div>
              <div class="card-body">
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="public_lobby" name="public_lobby"
                             {{ old('public_lobby', $step3->public_lobby ?? false)?'checked':'' }}>
                      <label class="form-check-label" for="public_lobby">Lounge / seating in lobby</label>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            {{-- Safety --}}
            <div class="card mb-3">
              <div class="card-header"><h6 class="m-0">Safety & Security</h6></div>
              <div class="card-body">
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="fire_safety" name="fire_safety"
                             {{ old('fire_safety', $step3->fire_drills ?? false)?'checked':'' }}>
                      <label class="form-check-label" for="fire_safety">Fire fighting drills & norms followed</label>
                    </div>
                  </li>
                  <li class="mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="doctor_on_call" name="doctor_on_call"
                             {{ old('doctor_on_call', $step3->doctor_on_call ?? false)?'checked':'' }}>
                      <label class="form-check-label" for="doctor_on_call">Doctor on call</label>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('industrial.wizard.show', [$application,'step'=>1]) }}"
                 class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Previous
              </a>

              <button type="submit" class="btn btn-primary" style="
                  background-color:#ff6600;
                  color:#fff;
                  font-weight:700;
                  border:none;
                  border-radius:8px;
                  padding:0.6rem 1.5rem;
                  cursor:pointer;">
                Save & Next <i class="bi bi-arrow-right"></i>
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
  $("#step2Form").validate({
    // yahan mostly checkbox hai, isliye koi hard rules nahi – at least form submit hoga
  });
});
</script>
@endpush
