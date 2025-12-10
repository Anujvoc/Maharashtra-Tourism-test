<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container py-2">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}#home">
        <img src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png"
             alt="Maharashtra Tourism logo" height="48"/>
        <span class="fw-bold">Maharashtra Tourism</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
              aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#registrations">Tourism Registrations</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#events">Events</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#plan">Plan Your Trip</a></li>
          <li class="nav-item ms-lg-3">
            <a class="btn btn-brand rounded-pill px-3" href="{{ url('/login') }}">

                <i class="bi bi-person-circle me-1"></i> login
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
