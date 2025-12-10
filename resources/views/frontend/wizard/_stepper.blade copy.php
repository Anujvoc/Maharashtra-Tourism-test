@php($p = $application->progress) {{-- ['done'=>n, 'total'=>6] --}}
<div class="text-center">
  <h2 class="fw-bold">Application Form for the Registration of Tourist Villa</h2>
  <p class="text-muted">Please fill out all required fields marked with an asterisk (*)</p>
</div>

<div class="stepper d-flex justify-content-center gap-5 my-3">
  @foreach ([1=>'Applicant Details',2=>'Property Details',3=>'Accommodation',4=>'Facilities',5=>'Photo & Signature',6=>'Enclosures'] as $num=>$label)
    @php($state = $num <= $p['done'] ? 'done' : ($num == $application->current_step ? 'current' : ''))
    <div class="step {{ $state }} text-center">
      <div class="bubble rounded-circle d-inline-grid place-items-center"
           style="width:48px;height:48px;background:{{ $state==='done'?'#2ecc71':($state==='current'?'#ff7a00':'#e9ecef') }};color:#fff;font-weight:700;">
        {{ $num }}
      </div>
      <div class="small mt-2 fw-semibold text-secondary">{{ $label }}</div>
    </div>
  @endforeach
</div>
<hr class="text-warning opacity-25">

    <div class="form-container">
        <div class="form-header">
            <h2>Application Form for the Registration of Tourist Villa</h2>
            <p>Please fill out all required fields marked with an asterisk (*)</p>
        </div>

        <!-- Step Indicator -->
<!-- Step Indicator -->
<div class="step-indicator">
    @foreach ([1=>'Applicant Details',2=>'Property Details',3=>'Accommodation',4=>'Facilities',5=>'Review & Submit'] as $num=>$label)
        @php
            $isCompleted = $num <= $p['done'];
            $isActive = $num == $application->current_step;
            $stepClass = $isCompleted ? 'completed' : ($isActive ? 'active' : '');
        @endphp
        <div class="step {{ $stepClass }}" id="step{{ $num }}-indicator">
            <div class="step-circle">{{ $num }}</div>
            <div class="step-label">{{ $label }}</div>
        </div>
    @endforeach
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
        </div>
@push('scripts')
<script>
(function () {
  const form = document.querySelector('form[data-wizard]');
  if(!form) return;
  form.addEventListener('input', (e) => {
    const el = e.target;
    if (el.matches('[required], [pattern], [type=email], [type=number]')) {
      el.classList.toggle('is-invalid', !el.checkValidity());
      el.classList.toggle('is-valid', el.checkValidity());
    }
  });
  form.addEventListener('submit', (e) => {
    if (!form.checkValidity()) {
      e.preventDefault(); e.stopPropagation();
      form.querySelectorAll('input,select,textarea').forEach(el=>{
        if(!el.checkValidity()) el.classList.add('is-invalid');
      });
      form.scrollIntoView({behavior:'smooth', block:'start'});
    }
  });
})();
</script>
@endpush
