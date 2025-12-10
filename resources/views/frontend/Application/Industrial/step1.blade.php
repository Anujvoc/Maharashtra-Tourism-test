@extends('frontend.layouts2.master')
@section('title', 'Hotel Registration - Step 1')

@push('styles')
<style>
  :root{
    --brand: #ff6600;
    --brand-dark: #e25500;
  }
  .form-icon {
    color: var(--brand);
    font-size: 1.5rem;
    margin-right:.35rem;
  }
  .form-container {
    background:#fff;
    padding:1.5rem;
    border-radius:12px;
    box-shadow:0 4px 20px rgba(0,0,0,.05);
  }
  .required::after { content:" *"; color:red; }
</style>
@endpush

@section('content')

@php($p = $application->progress) {{-- ['done'=>n, 'total'=>4] --}}

<section class="section">
  <div class="section-header">
    <h1>{{ $application_form->name ?? 'Hotel Registration' }}</h1>
  </div>

  <div class="section-body">
    <div class="form-container">

      <div class="form-header mb-3">
        <h2>Application Form for the {{ $application_form->name ?? '' }}</h2>
        <p>Please fill out all required fields marked with an asterisk (*)</p>
      </div>

      {{-- STEP INDICATOR --}}
      @include('frontend.Application.Industrial._step-indicator')

      {{-- STEP 1 FORM --}}
      <form id="step1Form"
            action="{{ route('industrial.wizard.step1.store', $application) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate>
        @csrf
        <input type="hidden" name="slug_id" value="{{ $application->slug_id }}">

        <div class="card p-3 mb-4">
          <div class="card-header" style="background-color:#ff6600;color:#fff;font-weight:700;">
            <h5 class="m-0">Basic Information</h5>
          </div>

          <div class="card-body">

            {{-- Email + Mobile --}}
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-envelope form-icon"></i>Email Id
                  </label>
                  <input type="email" name="email" class="form-control"
                         value="{{ old('email', $step1->email ?? auth()->user()->email) }}">
                  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-phone form-icon"></i>Mobile No.
                  </label>
                  <input type="text" name="mobile" class="form-control"
                         oninput="onlyDigits(this,10)"
                         value="{{ old('mobile', $step1->mobile ?? auth()->user()->phone) }}">
                  @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-building form-icon"></i>Name of Hotel and Resort
                  </label>
                  <input type="text" name="hotel_name" class="form-control"
                         onkeypress="return onlyText(event)"
                         value="{{ old('hotel_name', $step1->hotel_name ?? '') }}">
                  @error('hotel_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

            </div>

            {{-- Hotel / Company --}}
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-briefcase form-icon"></i>Company Name
                  </label>
                  <input type="text" name="company_name" class="form-control"
                         onkeypress="return onlyText(event)"
                         value="{{ old('company_name', $step1->company_name ?? '') }}">
                  @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-person form-icon"></i>Name of Authorized Person
                  </label>
                  <input type="text" name="authorized_person" class="form-control"
                         onkeypress="return onlyText(event)"
                         value="{{ old('authorized_person', $step1->authorized_person ?? '') }}">
                  @error('authorized_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>


            </div>



              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="region_id" class="form-label required">
                    <i class="bi bi-geo-alt form-icon"></i>Select Region
                  </label>
                  <select id="region_id" name="region"
                          class="form-control {{ $errors->has('region') ? 'is-invalid' : '' }}"
                          onchange="get_Region_District(this.value)">
                    <option value="">Select Region</option>
                    @foreach($regions as $r)
                      <option value="{{ $r->id }}"
                        {{ old('region', $step1->region ?? '') == $r->id ? 'selected' : '' }}>
                        {{ $r->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>


            </div>

            {{-- District + Pin --}}
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="district_id" class="form-label required">
                    <i class="bi bi-geo form-icon"></i>Select District
                  </label>
                  <select id="district_id" name="district" required
                          class="form-control district_id {{ $errors->has('district') ? 'is-invalid' : '' }}">
                    <option value="" selected disabled>Select District</option>
                    {{-- agar edit hai to pre-fill --}}
                    @if(old('district', $step1->district ?? false))
                        <option value="{{ old('district', $step1->district ?? '') }}" selected>
                            {{ old('district_name', $step1->district_name ?? 'Selected District') }}
                        </option>
                    @endif
                  </select>
                  @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-pin-map form-icon"></i>Pin Code
                  </label>
                  <input type="text" name="pincode" class="form-control"
                         oninput="onlyDigits(this,6)"
                         value="{{ old('pincode', $step1->pincode ?? '') }}">
                  @error('pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>


            </div>

            {{-- Hotel / Company address --}}
            <div class="row mb-3">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-geo form-icon"></i>Hotel Address
                  </label>
                  <textarea name="hotel_address" rows="3" class="form-control">{{ old('hotel_address', $step1->hotel_address ?? '') }}</textarea>
                  @error('hotel_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-geo form-icon"></i>Company Address
                  </label>
                  <textarea name="company_address" rows="3" class="form-control">{{ old('company_address', $step1->company_address ?? '') }}</textarea>
                  @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- Applicant Type + Total Area --}}
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-person-badge form-icon"></i>Applicant Type
                  </label>
                  <select name="applicant_type"
                          class="form-control {{ $errors->has('applicant_type') ? 'is-invalid' : '' }}">
                    <option value="">Select Applicant Type</option>
                    <option value="individual" {{ old('applicant_type', $step1->applicant_type ?? '')=='individual'?'selected':'' }}>Individual</option>
                    <option value="company" {{ old('applicant_type', $step1->applicant_type ?? '')=='company'?'selected':'' }}>Company</option>
                    <option value="partnership" {{ old('applicant_type', $step1->applicant_type ?? '')=='partnership'?'selected':'' }}>Partnership</option>
                  </select>
                  @error('applicant_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-arrows-angle-expand form-icon"></i>Total Area in sq.M
                  </label>
                  <input type="text" name="total_area" class="form-control"
                         oninput="onlyDecimal(this)"
                         value="{{ old('total_area', $step1->total_area ?? '') }}">
                  @error('total_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- Employees + Rooms --}}
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-people form-icon"></i>Total Number of Employees
                  </label>
                  <input type="text" name="total_employees" class="form-control"
                         oninput="onlyDigits(this)"
                         value="{{ old('total_employees', $step1->total_employees ?? '') }}">
                  @error('total_employees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-door-closed form-icon"></i>Total Number of Rooms
                  </label>
                  <input type="text" name="total_rooms" class="form-control"
                         oninput="onlyDigits(this)"
                         value="{{ old('total_rooms', $step1->total_rooms ?? '') }}">
                  @error('total_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- Commencement Date + Emergency Contact --}}
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-calendar form-icon"></i>Commencement Date
                  </label>
                  <input type="date" name="commencement_date" class="form-control"
                         value="{{ old('commencement_date', $step1->commencement_date ?? '') }}">
                  @error('commencement_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-telephone form-icon"></i>Emergency Contact No.
                  </label>
                  <input type="text" name="emergency_contact" class="form-control"
                         oninput="onlyDigits(this,10)"
                         value="{{ old('emergency_contact', $step1->emergency_contact ?? '') }}">
                  @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- MSEB + Star Category --}}
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-lightning form-icon"></i>MSEB Consumer Number
                  </label>
                  <input type="text" name="mseb_consumer_number" class="form-control"
                         value="{{ old('mseb_consumer_number', $step1->mseb_consumer_number ?? '') }}">
                  @error('mseb_consumer_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-star form-icon"></i>Star Category
                  </label>
                  <select name="star_category"
                          class="form-control {{ $errors->has('star_category') ? 'is-invalid' : '' }}">
                    <option value="">Select Star Category</option>
                    <option value="1" {{ old('star_category', $step1->star_category ?? '')=='1'?'selected':'' }}>1 Star</option>
                    <option value="2" {{ old('star_category', $step1->star_category ?? '')=='2'?'selected':'' }}>2 Star</option>
                    <option value="3" {{ old('star_category', $step1->star_category ?? '')=='3'?'selected':'' }}>3 Star</option>
                    <option value="4" {{ old('star_category', $step1->star_category ?? '')=='4'?'selected':'' }}>4 Star</option>
                    <option value="5" {{ old('star_category', $step1->star_category ?? '')=='5'?'selected':'' }}>5 Star</option>
                  </select>
                  @error('star_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- Electricity / Property Tax / Water Dept --}}
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-building form-icon"></i>Electricity Company Name and Address
                  </label>
                  <textarea name="electricity_company" rows="3" class="form-control">{{ old('electricity_company', $step1->electricity_company ?? '') }}</textarea>
                  @error('electricity_company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-building form-icon"></i>Property Tax Dept Name and Address
                  </label>
                  <textarea name="property_tax_dept" rows="3" class="form-control">{{ old('property_tax_dept', $step1->property_tax_dept ?? '') }}</textarea>
                  @error('property_tax_dept')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-label required">
                    <i class="bi bi-droplet form-icon"></i>Water Bill Dept Name and Address
                  </label>
                  <textarea name="water_bill_dept" rows="3" class="form-control">{{ old('water_bill_dept', $step1->water_bill_dept ?? '') }}</textarea>
                  @error('water_bill_dept')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            {{-- Save & Next --}}
            <div class="row mt-4">
              <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" style="
                    background-color:#ff6600;
                    color:#fff;
                    font-weight:700;
                    border:none;
                    border-radius:8px;
                    padding:0.6rem 1.5rem;
                    cursor:pointer;
                    transition:none !important;">
                  Save & Next <i class="bi bi-arrow-right"></i>
                </button>
              </div>
            </div>

          </div>
        </div>

      </form>
    </div>
  </div>
</section>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- jQuery Validate --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
/** on-key helpers **/
function onlyText(e){
    const ch = String.fromCharCode(e.which);
    const pattern = /^[A-Za-z\s.'-]$/;
    if(!pattern.test(ch)){
        e.preventDefault();
        return false;
    }
    return true;
}

function onlyDigits(el, maxLen = null){
    el.value = el.value.replace(/[^0-9]/g,'');
    if(maxLen && el.value.length > maxLen){
        el.value = el.value.slice(0, maxLen);
    }
}

function onlyDecimal(el){
    let val = el.value;
    val = val.replace(/[^0-9.]/g,'');
    const parts = val.split('.');
    if(parts.length > 2){
        val = parts[0] + '.' + parts.slice(1).join('');
    }
    el.value = val;
}

$(function () {

  // 🔹 pattern rule ko support karne ke liye custom method
  $.validator.addMethod("pattern", function(value, element, param) {
      if (this.optional(element)) {
          return true;
      }
      var re = new RegExp(param);
      return re.test(value);
  }, "Invalid format.");

  // 🔹 jQuery Validate init
  const form = $("#step1Form");

  form.validate({
    ignore: [], // hidden fields bhi validate hon (agar ho)
    rules:{
      email:  { required:true, email:true },

      mobile: {
        required:true,
        pattern:/^[6-9][0-9]{9}$/,
        minlength:10,
        maxlength:10
      },

      hotel_name: {
        required:true,
        minlength:2,
        maxlength:120,
        pattern:/^[A-Za-z\s.'-]+$/
      },
      company_name: {
        required:true,
        minlength:2,
        maxlength:120,
        pattern:/^[A-Za-z\s.'-]+$/
      },
      authorized_person:{
        required:true,
        minlength:2,
        maxlength:120,
        pattern:/^[A-Za-z\s.'-]+$/
      },

      region:{ required:true },
      district:{ required:true },

      pincode:{
        required:true,
        digits:true,
        minlength:6,
        maxlength:6
      },

      hotel_address:{ required:true, minlength:5 },
      company_address:{ required:true, minlength:5 },

      applicant_type:{ required:true },

      total_area:{
        required:true,
        number:true,
        min:1
      },
      total_employees:{
        required:true,
        digits:true,
        min:1
      },
      total_rooms:{
        required:true,
        digits:true,
        min:6
      },

      commencement_date:{ required:true, date:true },

      emergency_contact:{
        required:true,
        pattern:/^[6-9][0-9]{9}$/,
        minlength:10,
        maxlength:10
      },

      mseb_consumer_number:{ required:true },

      star_category:{ required:true },

      electricity_company:{ required:true, minlength:5 },
      property_tax_dept:{ required:true, minlength:5 },
      water_bill_dept:{ required:true, minlength:5 },
    },
    messages:{
      mobile:{
        pattern:"Enter valid mobile number starting 6-9"
      },
      emergency_contact:{
        pattern:"Enter valid mobile number starting 6-9"
      },
      authorized_person:{
        pattern:"Only letters, spaces and .'- allowed"
      },
      hotel_name:{
        pattern:"Only letters, spaces and .'- allowed"
      },
      company_name:{
        pattern:"Only letters, spaces and .'- allowed"
      },
      pincode:{
        digits:"Please enter a valid 6-digit pincode",
        minlength:"Please enter a valid 6-digit pincode",
        maxlength:"Please enter a valid 6-digit pincode"
      },
      total_rooms:{
        min:"Minimum 6 rooms required"
      }
    },
    errorElement:'div',
    errorPlacement:function(error, element){
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight:function(element){
      $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight:function(element){
      $(element).removeClass('is-invalid').addClass('is-valid');
    }
  });

  // 🔹 Ye MOST IMPORTANT part:
  // submit hone se pehle valid() check karega, galat hua to form submit nahi hoga
  form.on('submit', function(e){
      if (!form.valid()) {
          e.preventDefault();
          return false;
      }
  });

});
</script>
@endpush
