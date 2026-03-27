<footer id="contact" class="pt-5 pb-4">
  <div class="container">
      <div class="d-md-flex justify-content-between text-start gap-4">
        <!-- Brand Section -->
        <div style="flex: 1; max-width: 400px;">
          <div class="d-flex align-items-center gap-2 mb-3">
            <img src="{{ asset('backend/mah-logo-300x277.png') }}"
                 alt="Maharashtra Tourism logo" height="44">
            <h5 class="m-0 text-white fw-bold">Maharashtra Tourism</h5>
          </div>
          <p class="small text-secondary mb-4" style="line-height: 1.6;">
            Experience the spirit of Maharashtra—heritage, nature, beaches, forts, and festivals across a vibrant state.
          </p>
          <div class="d-flex gap-3 fs-5">
            <a aria-label="Twitter" href="#" class="text-secondary hover-white"><i class="bi bi-twitter-x"></i></a>
            <a aria-label="Facebook" href="#" class="text-secondary hover-white"><i class="bi bi-facebook"></i></a>
            <a aria-label="Instagram" href="#" class="text-secondary hover-white"><i class="bi bi-instagram"></i></a>
            <a aria-label="YouTube" href="#" class="text-secondary hover-white"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <!-- Links Section -->
        <div>
          <h6 class="text-uppercase text-white mb-3 fw-bold ls-1">Quick Links</h6>
          <ul class="list-unstyled small text-secondary d-flex flex-column gap-2">
            <li><a href="{{ url('/') }}#experiences" class="text-decoration-none text-secondary hover-white">Experiences</a></li>
            <li><a href="{{ url('/') }}#events" class="text-decoration-none text-secondary hover-white">Events</a></li>
            <li><a href="{{ url('/') }}#plan" class="text-decoration-none text-secondary hover-white">Plan Your Trip</a></li>
          </ul>
        </div>

        <!-- Contact Section -->
        <div>
          <h6 class="text-uppercase text-white mb-3 fw-bold ls-1">Contact</h6>
         <ul class="list-unstyled small text-secondary d-flex flex-column gap-2">
    <li>
        <i class="bi bi-geo-alt me-2 text-brand"></i>
        HO : Sakhar Bhavan, 4th Floor, Plot No.230, Nariman Point, Mumbai - 400021.
    </li>
    <li>
        <i class="bi bi-telephone me-2 text-brand"></i>
        +91 22 69107600
    </li>
    <li>
        <i class="bi bi-envelope me-2 text-brand"></i>
        diot[at]maharashtratourism[dot]gov[dot]in
    </li>
    <li>
        <i class="bi bi-globe me-2 text-brand"></i>
        <a href="https://www.maharashtratourism.gov.in"
           class="text-secondary text-decoration-none"
           target="_blank">
            www[dot]maharashtratourism[dot]gov[dot]in
        </a>
    </li>
</ul>

        </div>
      </div>

    <hr class="border-secondary-subtle">
    <div class="d-flex justify-content-between small">
      <span>© <span id="year"></span> Maharashtra Tourism. All rights reserved.</span>
      <a href="{{ url('/') }}" class="text-decoration-none">Back to top ↑</a>
    </div>
  </div>
</footer>

<button id="toTop" class="btn btn-brand rounded-circle p-2" aria-label="Back to top">
  <i class="bi bi-chevron-up"></i>
</button>


