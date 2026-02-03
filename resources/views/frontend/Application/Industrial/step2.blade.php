@extends('frontend.layouts2.master')
@section('title','Hotel Registration - Step 2')

@push('styles')
<style>
  :root{ --brand:#ff6600; --brand-dark:#e25500; }
  .form-container { background:#fff; padding:1.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.05); }
</style>
@endpush

@section('content')


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

      @include('frontend.Application.Industrial._step-indicator')

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

            <div class="card mb-3">
                <div class="row mb-3">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
                          <h5 class="m-0">General Requirements</h5>
                        </div>


                        <div class="card-body">
                            <ul class="list-unstyled">
                                @php
                                    $selected = is_array($step2->general_requirements ?? null)
                                                ? $step2->general_requirements
                                                : json_decode($step2->general_requirements ?? '[]', true);
                                @endphp

                                @foreach($GeneralRequirement as $req)
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="requirement_{{ $req->id }}"
                                                name="requirements[]"
                                                value="{{ $req->id }}"
                                                {{ in_array($req->id, old('requirements', $selected ?? [])) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="requirement_{{ $req->id }}">
                                                {{ $req->name }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                      </div>
                    </div>
                  </div>

              <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;"><h6 class="m-0">Bathroom</h6></div>
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
                      <label class="form-check-label" for="hot_cold_water">Availability of Hot & Cold running water</label>
                    </div>
                  </li>
                  <li class="mb-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="water_saving_taps" name="water_saving_taps"
                             {{ old('water_saving_taps', $step3->water_saving_taps ?? false) ? 'checked' : '' }}>
                      <label class="form-check-label" for="water_saving_taps_showers">
                        Water saving taps and showers
                      </label>
                    </div>
                  </li>


                </ul>
              </div>
            </div>

            {{-- Public area --}}
            <div class="card mb-3">
                <div class="card-header"style="background-color:#ff6600;color:#fff;font-weight:700;">
                  <h6 class="m-0">Public Area</h6>
                </div>

                <div class="card-body">
                  <ul class="list-unstyled">
                    <li class="mb-2">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="public_lobby" name="public_lobby"
                               {{ old('public_lobby', $step3->public_lobby ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="public_lobby">
                          Lounge or seating area in the lobby
                        </label>
                      </div>
                    </li>

                    <li class="mb-2">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="reception" name="reception"
                               {{ old('reception', $step3->reception ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="reception">
                          Reception facility
                        </label>
                      </div>
                    </li>

                    <li class="mb-2">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="public_restrooms" name="public_restrooms"
                               {{ old('public_restrooms', $step3->public_restrooms ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="public_restrooms">
                          Public restrooms with a wash basin, a mirror, and a sanitary bin with lid in unisex toilet
                        </label>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>


            <!-- Room And Facilities for the Differently Abled Guests -->
<div class="card mb-3">
    <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;"><h6 class="m-0">Room And Facilities for the Differently Abled Guests</h6></div>
    <div class="card-body">
      <ul class="list-unstyled">
        <li class="mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="disabled_room" name="disabled_room"
                   {{ old('disabled_room', $step3->disabled_room ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="room_for_differently_abled">
              At least one room for the differently abled guest
            </label>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <!-- Kitchen / Food Production Area -->
  <div class="card mb-3">
    <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;"><h6 class="m-0">Kitchen / Food Production Area</h6></div>
    <div class="card-body">
      <ul class="list-unstyled">
        <li class="mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="fssai_kitchen" name="fssai_kitchen"
                   {{ old('fssai_kitchen', $step3->fssai_kitchen ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="fssai_registration">
              FSSAI registration / Licensed Kitchen
            </label>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <!-- Hotel Staff And Related Facilities -->
  <div class="card mb-3">
    <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;"><h6 class="m-0">Hotel Staff And Related Facilities</h6></div>
    <div class="card-body">
      <ul class="list-unstyled">
        <li class="mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="uniforms" name="uniforms"
                   {{ old('uniforms', $step3->uniforms ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="uniforms">
              Uniforms for front house staff
            </label>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <!-- Code of Conduct for Safe And Honorable Tourism -->
  <div class="card mb-3">
    <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;"><h6 class="m-0">Code of Conduct for Safe And Honorable Tourism</h6></div>
    <div class="card-body">
      <ul class="list-unstyled">

        <li class="mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pledge_display" name="pledge_display"
                   {{ old('pledge_display', $step3->pledge_display ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="display_of_pledge">Display of pledge</label>
          </div>
        </li>

        <li class="mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="complaint_book" name="complaint_book"
                   {{ old('complaint_book', $step3->complaint_book ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="complaint_book">
              Maintenance of Complaint book and Suggestion Book
            </label>
          </div>
        </li>

        <li class="mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="nodal_officer_info" name="nodal_officer_info"
                   {{ old('nodal_officer_info', $step3->nodal_officer_info ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="nodal_officer_info">
              Appointment of Nodal officers and display of information of Nodal officer at reception
            </label>
          </div>
        </li>

      </ul>
    </div>
  </div>


  <div class="card mb-3">
    <div class="card-header"style="background-color:#ff6600;color:#fff;font-weight:700;">
        <h6 class="m-0">Guest Services</h6>
    </div>

    <div class="card-body">
        @php
            // already saved ids from JSON
            $selectedGuestServices = json_decode($step3->guest_services ?? '[]', true);
        @endphp

        <ul class="list-unstyled">
            @foreach($GuestServices as $service)
                <li class="mb-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="guest_service_{{ $service->id }}"
                            name="guest_services[]"
                            value="{{ $service->id }}"
                            {{ in_array($service->id, old('guest_services', $selectedGuestServices ?? [])) ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="guest_service_{{ $service->id }}">
                            {{ $service->name }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>



            {{-- Safety --}}
            <div class="card mb-3">
                <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
                  <h6 class="m-0">Safety & Security</h6>
                </div>

                <div class="card-body">
                  @php
                      // previously saved selected ids (from JSON column)
                      $selectedSafety = json_decode($step3->safety_security ?? '[]', true);
                  @endphp

                  <ul class="list-unstyled">
                    @foreach($SafetyAndSecurity as $item)
                      <li class="mb-2">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="safety_{{ $item->id }}"
                            name="safety_security[]"
                            value="{{ $item->id }}"
                            {{ in_array($item->id, old('safety_security', $selectedSafety ?? [])) ? 'checked' : '' }}
                          >
                          <label class="form-check-label" for="safety_{{ $item->id }}">
                            {{ $item->name }}
                          </label>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>


            <div class="card mb-3">
                <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
                  <h6 class="m-0">Additional Features</h6>
                </div>

                <div class="card-body">
                  @php
                    // Decode previously saved JSON selections (if any)
                    $selected = json_decode($step3->additional_features ?? '[]', true);
                  @endphp

                  <ul class="list-unstyled">
                    @foreach($AdditionalFeature as $feature)
                      <li class="mb-2">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            id="feature_{{ $feature->id }}"
                            name="additional_features[]"
                            value="{{ $feature->id }}"
                            {{ in_array($feature->id, old('additional_features', $selected ?? [])) ? 'checked' : '' }}
                          >
                          <label class="form-check-label" for="feature_{{ $feature->id }}">
                            {{ $feature->name }}
                          </label>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>


            <div class="d-flex justify-content-between">
              <a href="{{ route('industrial.wizard.show', [$application,'step'=>1]) }}"
                 class="btn btn-primary rounded-pill">
                <i class="bi bi-arrow-left mt-1"></i> Previous
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

  });
});
</script>
@endpush
