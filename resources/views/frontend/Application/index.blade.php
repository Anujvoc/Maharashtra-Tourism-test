@extends('frontend.layouts2.master')

@section('title', 'Application form')

@push('styles')
<style>
  :root{
    --brand: #ff6600;   /* Orange color */
    --brand-dark: #e25500;
  }

  /* Section eyebrow */
  .eyebrow{
    display:inline-block;
    text-transform:uppercase;
    letter-spacing:.12em;
    font-size:.78rem;
    color: var(--brand);
    font-weight:600;
  }

  /* Main outer card */
  .main-app-card {
    border: 2px solid var(--brand);
    border-radius: 18px;
    background-color: #fff;
    box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    padding: 40px 30px;
  }

  /* Inner application cards */
  .app-card{
    border: 1.5px solid var(--brand) !important;
    border-radius:14px;
    overflow:hidden;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    background:#fff;
  }
  .app-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 10px 28px rgba(0,0,0,.08);
    border-color: var(--brand-dark) !important;
  }

  .app-card-img{
    height:220px;
    object-fit:cover;
    display:block;
  }

  .app-card-body{
    padding:16px 16px 18px 16px;
  }

  .app-card-title{
    font-size:1.05rem;
    margin-bottom:.35rem;
    font-weight:700;
    color:#000;
  }

  .app-card-desc{
    font-size:.9rem;
    color:#6c757d;
    margin:0;
  }

  /* Button – fixed orange, no hover change */
  .btn-brand{
    background: var(--brand);
    border: 1px solid var(--brand);
    color:#fff;
    font-weight:600;
    border-radius:8px;
    transition: none !important;
  }

  /* Disable hover/focus/active effects */
  .btn-brand:hover,
  .btn-brand:focus,
  .btn-brand:active {
    background: var(--brand) !important;
    border-color: var(--brand) !important;
    color:#fff !important;
    box-shadow: none !important;
  }

  .btn-brand i{
    opacity:.9;
  }

  @media (max-width: 575.98px){
    .app-card-img{ height:200px; }
    .main-app-card { padding: 25px 20px; }
  }
</style>
@endpush

@section('content')
<main class="container py-5" class="container-fluid px-0">

    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    </script>
<script>
    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>


  <!-- Outer main card -->
  <div class="main-app-card" style="margin:0; padding:30px 20px;">

    <div class="text-center mb-4">
      <span class="eyebrow fs-5">RIGHT TO SERVICES (RTS)</span>
      <h1 class="section-title mb-2" style="font-weight:700; color:#000;">
        Tourism Registrations &amp; Certificates
      </h1>
      <p class="text-muted mb-0">
        Forms and supporting documents for the following services can be accessed below.
        <span class="text-danger">Click “Apply Now”</span> to open the respective registration form.
      </p>
    </div>

    <div class="row g-4">
      @forelse($forms as $form)
        <div class="col-md-6 col-lg-4 mt-2">
          <div class="card app-card h-100 border-0">
            {{-- Image --}}
            <img
              src="{{ $form->image ? asset('storage/'.$form->image) : 'https://via.placeholder.com/1200x800?text=No+Image' }}"
              alt="{{ $form->name }}"
              class="card-img-top app-card-img">

            {{-- Body --}}
            <div class="card-body app-card-body d-flex flex-column">
              <h5 class="app-card-title">{{ $form->name }}</h5>

              @if($form->short_description)
                <p class="app-card-desc">{{ $form->short_description }}</p>
              @endif

              <div class="mt-2 d-grid">
                <a
                  href="{{ route('frontend.application.create', $form->slug) }}"
                  class="btn btn-brand btn-sm"
                  aria-label="Apply for {{ $form->name }}">

                  Apply Now
                </a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <p class="text-muted mb-0">No application forms are currently active.</p>
        </div>
      @endforelse
    </div>

  </div><!-- /.main-app-card -->

</main>
@endsection

@push('scripts')
<script>
  console.log("Application index loaded");
</script>
@endpush
