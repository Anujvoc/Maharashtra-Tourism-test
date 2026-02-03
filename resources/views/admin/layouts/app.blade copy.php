<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Maharashtra Tourism | @yield('title')</title>
  <meta name="description" content="Official Maharashtra Tourism landing page – discover destinations, experiences, and plan your trip."/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>

  {{-- Favicon (your provided PNG) --}}
  <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --brand:#ff6a00; --brand-dark:#d45400; --ink:#152238; --muted:#6c757d; --bg:#fffaf5;
    }
    body{font-family:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji";color:var(--ink);background:var(--bg);}
    .navbar{box-shadow:0 4px 18px rgba(0,0,0,.06);background:#ffffffba;backdrop-filter:blur(8px);}
    .navbar .nav-link{font-weight:600}
    .btn-brand{background:var(--brand);color:#fff;border:none}
    .btn-brand:hover{background:var(--brand-dark);color:#fff}
    .section-title{font-weight:800;letter-spacing:.5px}
    .eyebrow{color:var(--brand);font-weight:700;text-transform:uppercase;letter-spacing:1.8px;font-size:.85rem}
    .card{border:none;box-shadow:0 8px 30px rgba(0,0,0,.06)}
    .card-img-top{height:220px;object-fit:cover}
    .chip{display:inline-block;padding:.4rem .7rem;border-radius:999px;background:#fff;color:#333;box-shadow:0 4px 14px rgba(0,0,0,.08);font-weight:600;font-size:.9rem}
    footer{background:#101826;color:#cbd5e1}
    footer a{color:#e2e8f0;text-decoration:none}
    footer a:hover{color:#fff}
    #toTop{position:fixed;right:1rem;bottom:1rem;display:none;z-index:9999}
    .shadow-soft{box-shadow:0 10px 35px rgba(0,0,0,.08)}
  </style>

  @stack('styles')
</head>
<body>

  {{-- Navbar --}}
  @include('frontend.layouts.navbar')

  {{-- Page content --}}
  @yield('content')

  {{-- Footer --}}
  @include('frontend.layouts.footer')

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>

  <script>
    // Dynamic year
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    // Back to top button
    const toTop = document.getElementById('toTop');
    if (toTop) {
      window.addEventListener('scroll', () => {
        toTop.style.display = window.scrollY > 600 ? 'inline-flex' : 'none';
      });
      toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  </script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @stack('scripts')
</body>
</html>
