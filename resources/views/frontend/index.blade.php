@extends('frontend.layouts.app')

@section('title', $title ?? 'Home')

@push('styles')
  <style>
    :root{
      --brand:#ff6a00;           /* saffron accent */
      --brand-dark:#d45400;      /* darker accent */
      --ink:#152238;             /* dark text */
      --muted:#6c757d;           /* muted */
      --bg:#fffaf5;              /* warm bg */
    }

    body{font-family: system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji"; color:var(--ink); background:var(--bg);}

    /* Navbar */
    .navbar{box-shadow:0 4px 18px rgba(0,0,0,.06); background:#ffffffba; backdrop-filter: blur(8px);}
    .navbar .nav-link{font-weight:600}
    .btn-brand{background:var(--brand); color:#fff; border:none}
    .btn-brand:hover{background:var(--brand-dark); color:#fff}

    /* Hero */
    .hero{
      position:relative; min-height:76vh; display:grid; place-items:center; text-align:center; color:#fff;
      background:linear-gradient(rgba(21,34,56,.45),rgba(21,34,56,.55)), url("https://thumbs.dreamstime.com/b/grand-railway-station-features-intricate-architectural-details-majestic-towers-set-against-bright-backdrop-399438444.jpg") center/cover no-repeat;
    }
    .hero h1{font-size:clamp(2rem,4vw,4rem); font-weight:800; text-shadow:0 8px 30px rgba(0,0,0,.35)}
    .hero p{max-width:850px; margin:auto; font-size:clamp(1rem,1.6vw,1.25rem)}

    /* Section titles */
    .section-title{font-weight:800; letter-spacing:.5px}
    .eyebrow{color:var(--brand); font-weight:700; text-transform:uppercase; letter-spacing:1.8px; font-size:.85rem}

    /* Cards */
    .card{border:none; box-shadow:0 8px 30px rgba(0,0,0,.06);}
    .card-img-top{height:220px; object-fit:cover}

    /* Chips */
    .chip{display:inline-block; padding:.4rem .7rem; border-radius:999px; background:#fff; color:#333; box-shadow:0 4px 14px rgba(0,0,0,.08); font-weight:600; font-size:.9rem}

    /* Footer */
    footer{background:#101826; color:#cbd5e1}
    footer a{color:#e2e8f0; text-decoration:none}
    footer a:hover{color:#fff}

    /* Back to top */
    #toTop{position:fixed; right:1rem; bottom:1rem; display:none; z-index:9999}

    /* Small utilities */
    .shadow-soft{box-shadow:0 10px 35px rgba(0,0,0,.08)}
  </style>
  @endpush

<body>
  <!-- NAVBAR -->
  @section('content')

  <!-- HERO -->
  <header id="home" class="hero">
    <div class="container py-5">
      <span class="eyebrow" style="font-size: 30px">Welcome to Maharashtra</span>
      <h1 class="mt-2">Vibrant Culture, Timeless Heritage, and Stunning Landscapes</h1>

      <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
        {{-- <a href="#destinations" class="btn btn-brand btn-lg rounded-pill px-4">Explore Destinations</a> --}}
        {{-- <a href="#plan" class="btn btn-outline-light  btn-lg rounded-pill px-4">Plan Your Trip</a> --}}
        <a href="#plan" class="btn  btn-brand  btn-lg rounded-pill px-4">Plan Your Trip</a>
      </div>
      <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
        <span class="chip"><i class="bi bi-stars me-1"></i> World Heritage</span>
        <span class="chip"><i class="bi bi-sunrise me-1"></i> Beaches</span>
        <span class="chip"><i class="bi bi-tree me-1"></i> Wildlife</span>
        <span class="chip"><i class="bi bi-heart me-1"></i> Festivals</span>
      </div>
    </div>
  </header>

@php
   $forms =  App\Models\Admin\ApplicationForm::where('is_active',1)->get();
@endphp

  <!-- EXPERIENCES -->
  <section id="registrations" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <span class="eyebrow">Right To Services</span>
        <h2 class="section-title">Tourism Registrations & Certificates</h2>
        <p class="text-muted">
          Forms and supporting documents for the following services can be accessed below. Click “Apply Now” to open the respective registration form.
        </p>
      </div>

      <div class="row g-4">
        <!-- 1 -->
        @forelse($forms as $form)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 shadow-soft">
            <img  src="{{ $form->image ? asset('storage/'.$form->image) : 'https://via.placeholder.com/1200x800?text=No+Image' }}"
            alt="{{ $form->name }}"
            {{-- src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop" --}}
             class="card-img-top">
            <div class="card-body">
              <h5 class="card-title">{{$form->name ?? '' }}</h5>
              @if($form->short_description)
              <p class="card-text small text-muted">{{ $form->short_description ?? 'For Tourist Entities under the Tourism Policy 2024.'}}</p>
            @endif
            <div class="mt-2 d-grid">
                <a
                  href="{{ route('frontend.application.create', $form->slug ?? '') }}"
                  class="btn btn-brand btn-sm"
                  aria-label="Apply for {{ $form->name ?? ''}}">
                  Apply Now
                </a>
              </div>
              {{-- <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#form1Modal">Apply Now</button> --}}
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center">
          <p class="text-danger fs-1 mb-0">No application forms are currently active.</p>
        </div>
      @endforelse
      </div>
    </div>
  </section>

  <!-- EVENTS / CAROUSEL -->
  <section id="events" class="py-5">
    <div class="container">
      <div class="text-center mb-4">
        <span class="eyebrow">Calendar</span>
        <h2 class="section-title">Upcoming Highlights</h2>
        <p class="text-muted">Replace placeholders with your real event data or connect to your backend.</p>
      </div>

      <div id="eventCarousel" class="carousel slide shadow-soft rounded-4 overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="https://websiteupload.s3.ap-south-1.amazonaws.com/2024/05/ganesh.jpg" class="d-block w-100" alt="Ganesh festival procession">
            <div class="carousel-caption d-none d-md-block">
              <h5>Ganesh Chaturthi (Statewide)</h5>
              <p>Vibrant processions, pandals, and cultural programs in August/September.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://www.aljazeera.com/wp-content/uploads/2021/09/2021-09-10T090858Z_1478876605_RC2UMP9CAEZS_RTRMADP_3_INDIA-RELIGION.jpg?resize=1800%2C1800" class="d-block w-100 h-20" alt="Fort trek in Sahyadris">
            <div class="carousel-caption d-none d-md-block">
                <h5>Ganesh Chaturthi (Statewide)</h5>
                <p>Vibrant processions, pandals, and cultural programs in August/September.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://english.cdn.zeenews.com/sites/default/files/2023/06/20/1224225-treks-maharashtra.jpeg" class="d-block w-100" alt="Fort trek in Sahyadris">
            <div class="carousel-caption d-none d-md-block">
              <h5>Monsoon Trek Season</h5>
              <p>Lush green trails and waterfalls across the Western Ghats (June–September).</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://travelindiadestinations.com/wp-content/uploads/2024/08/devghali-beach-maharashtra-1024x576.webp" class="d-block w-100" alt="Konkan beach festival">
            <div class="carousel-caption d-none d-md-block">
              <h5>Konkan Beach Fest</h5>
              <p>Food, music, and watersports on the coast (December–January).</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>

  <!-- PLAN YOUR TRIP -->





  <section id="plan" class="py-5 bg-white">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-5">
          <span class="eyebrow">Start here</span>
          <h2 class="section-title">Plan Your Trip</h2>
          <p class="text-muted">Quick access to essentials. Link these to your Laravel routes or external services.</p>
          <div class="d-grid gap-2">
            <a class="btn btn-outline-dark" href="#"><i class="bi bi-map me-2"></i>Itineraries</a>
            <a class="btn btn-outline-dark" href="#"><i class="bi bi-building me-2"></i>Hotels & Stays</a>
            <a class="btn btn-outline-dark" href="#"><i class="bi bi-train-front me-2"></i>How to Reach</a>
            <a class="btn btn-outline-dark" href="#"><i class="bi bi-shield-check me-2"></i>Travel Tips & Safety</a>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="row g-3">
            <div class="col-6">
              <div class="p-4 rounded-4 shadow-soft bg-white h-100">
                <h6 class="fw-bold">Best Time to Visit</h6>
                <p class="small text-muted mb-0">Oct–Feb for cool weather; Jun–Sep for lush monsoons and treks.</p>
              </div>
            </div>
            <div class="col-6">
              <div class="p-4 rounded-4 shadow-soft bg-white h-100">
                <h6 class="fw-bold">Local Transport</h6>
                <p class="small text-muted mb-0">Rail, MSRTC buses, metros, and app cabs in major cities.</p>
              </div>
            </div>
            <div class="col-6">
              <div class="p-4 rounded-4 shadow-soft bg-white h-100">
                <h6 class="fw-bold">Cuisine</h6>
                <p class="small text-muted mb-0">Vada pav, misal, coastal seafood, Puran Poli—don’t miss it!</p>
              </div>
            </div>
            <div class="col-6">
              <div class="p-4 rounded-4 shadow-soft bg-white h-100">
                <h6 class="fw-bold">Language</h6>
                <p class="small text-muted mb-0">Marathi is widely spoken; Hindi & English are common too.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- NEWSLETTER -->
  <section class="py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-md-7">
          <h3 class="section-title mb-1">Get travel ideas in your inbox</h3>
          <p class="text-muted mb-0">Monthly highlights, itineraries, and alerts. No spam—promise.</p>
        </div>
        <div class="col-md-5">
          <form class="d-flex gap-2" onsubmit="event.preventDefault(); alert('Thanks for subscribing!');">
            <input class="form-control form-control-lg" type="email" placeholder="Enter your email" required>
            <button class="btn btn-brand btn-lg" type="submit">Subscribe</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACT / FOOTER -->
  @endsection


</body>
</html>
