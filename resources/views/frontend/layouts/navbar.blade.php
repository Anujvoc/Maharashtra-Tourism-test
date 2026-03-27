<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}#home">
      <img src="{{ asset('backend/mah-logo-300x277.png') }}"
        alt="Maharashtra Tourism logo" height="48" />
      <span class="fw-bold">Maharashtra Tourism</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
        <div id="home"></div>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#registrations">Tourism Registrations</a></li>
        {{-- <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#events">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#plan">Plan Your Trip</a></li> --}}
        <li class="nav-item ms-lg-3">
          @auth
            @if(Auth::user()->role === 'admin')
              <a class="btn btn-brand rounded-pill px-3" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
              </a>
            @else
              <a class="btn btn-brand rounded-pill px-3" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
              </a>
            @endif
          @else
            <a class="btn btn-brand rounded-pill px-3" href="{{ route('login') }}">
              <i class="bi bi-person-circle me-1"></i> Login
            </a>
          @endauth
        </li>
      </ul>
    </div>
  </div>
</nav>
