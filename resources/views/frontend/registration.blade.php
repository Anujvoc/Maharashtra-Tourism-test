@extends('frontend.layouts.app')

@section('title', 'Tourist Villa Registration')

@push('styles')
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary: #4a6cf7;
      --primary-dark: #2f4fd8;
      --ok: #22c55e;
      --error: #ef4444;
      --muted: #8b8b8b;
    }

    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif
    }

    body {
      background: #f5f7fb
    }

    .auth-wrap {
      min-height: calc(100vh - 120px);
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 28px;
      padding: 32px 16px;
    }

    /* Left hero */
    .hero {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      background: #c3cfe2;
      box-shadow: 0 15px 45px rgba(15, 23, 42, .08);
      isolation: isolate;
    }

    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url('https://c.ndtvimg.com/2023-05/k213ieo_maharashtra-day-2023_625x300_01_May_23.jpg?downsize=773:435') center/cover no-repeat;
      filter: grayscale(10%) brightness(0.75);
      transform: scale(1.02);
    }

    .hero::after {
      /* soft glass overlay */
      content: "";
      position: absolute;
      inset: auto 0 0 0;
      height: 38%;
      background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, .45) 65%);
    }

    .hero-content {
      position: relative;
      color: #fff;
      padding: 28px;
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: space-between;
      z-index: 1;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 255, 255, .12);
      border: 1px solid rgba(255, 255, 255, .25);
      backdrop-filter: blur(6px);
      border-radius: 14px;
      padding: 10px 14px;
      width: max-content;
    }

    .brand img {
      width: 42px;
      height: 42px;
      object-fit: contain;
      filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .25))
    }

    .hero h1 {
      font-size: 32px;
      font-weight: 700;
      line-height: 1.15;
      margin-top: 10px
    }

    .hero p {
      opacity: .9
    }

    .hero-bullets {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px
    }

    .hero-bullets span {
      font-size: 12px;
      background: rgba(255, 255, 255, .12);
      border: 1px solid rgba(255, 255, 255, .25);
      padding: 6px 10px;
      border-radius: 999px
    }

    /* Right card */
    .card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 55px rgba(2, 6, 23, .08);
      padding: 26px;
      position: relative;
      overflow: hidden;
    }

    .progress-bar {
      height: 4px;
      background: #eef1f7;
      border-radius: 3px;
      overflow: hidden;
      margin-bottom: 18px
    }

    .progress {
      height: 100%;
      width: 0;
      background: linear-gradient(90deg, var(--primary), #3b5bdb);
      transition: width .35s ease
    }

    .steps {
      display: flex;
      justify-content: space-between;
      margin-bottom: 18px
    }

    .step {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: #e8ecf7;
      color: #6b7280;
      font-weight: 600;
      font-size: 14px;
      position: relative
    }

    .step.active {
      background: var(--primary);
      color: #fff
    }

    .step.completed {
      background: var(--ok);
      color: #fff
    }

    .step.completed::after {
      content: "\f00c";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      position: absolute;
      right: -10px;
      top: -10px;
      background: #fff;
      color: var(--ok);
      border-radius: 999px;
      border: 2px solid var(--ok);
      padding: 4px;
      font-size: 10px
    }

    .form-section {
      display: none;
      animation: fade .25s ease
    }

    .form-section.active {
      display: block
    }

    @keyframes fade {
      from {
        opacity: .5;
        transform: translateY(4px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    .form-title {
      font-weight: 600;
      font-size: 20px;
      margin: 6px 0 16px;
      color: #111827
    }

    .row {
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px
    }

    .form-group {
      position: relative
    }

    .label {
      display: block;
      font-size: 13px;
      color: #4b5563;
      margin-bottom: 6px
    }

    .control {
      width: 100%;
      padding: 12px 42px 12px 14px;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      background: #f9fafb;
      font-size: 15px;
      transition: border .2s, box-shadow .2s
    }

    .control:focus {
      outline: none;
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(74, 108, 247, .12)
    }

    .icon-right {
      position: absolute;
      right: 12px;
      top: 37px;
      font-size: 16px;
      color: #9ca3af
    }

    .valid .icon-right {
      color: var(--ok)
    }

    .invalid .icon-right {
      color: var(--error)
    }

    .hint {
      font-size: 12px;
      color: #6b7280;
      margin-top: 6px
    }

    .msg {
      display: none;
      margin-top: 6px;
      font-size: 13px
    }

    .msg.error {
      display: block;
      color: var(--error)
    }

    .otp-box {
      display: flex;
      gap: 10px
    }

    .otp {
      width: 48px;
      height: 48px;
      text-align: center;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      font-size: 18px;
      background: #fafafa
    }

    .otp:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(74, 108, 247, .12);
      background: #fff
    }

    .timer {
      text-align: center;
      color: #ef4444;
      margin-top: 8px;
      font-size: 13px
    }

    .resend {
      cursor: pointer;
      text-align: center;
      margin-top: 8px;
      color: var(--primary);
      font-size: 13px
    }

    .btn {
      width: 100%;
      padding: 12px 14px;
      border: 0;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 600;
      font-size: 15px
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), #3b5bdb);
      color: #fff;
      box-shadow: 0 10px 20px rgba(59, 91, 219, .25)
    }

    .btn-primary:hover {
      filter: brightness(.98);
      transform: translateY(-1px)
    }

    .btn-ghost {
      background: #f2f4f8;
      color: #4b5563
    }

    .stack {
      display: flex;
      gap: 10px;
      margin-top: 6px
    }

    .strength {
      height: 6px;
      background: #e5e7eb;
      border-radius: 6px;
      overflow: hidden;
      margin-top: 6px
    }

    .strength>b {
      display: block;
      height: 100%;
      width: 0;
      background: #ef4444;
      transition: width .25s, background .25s
    }

    .btn-danger {
      background: var(--error);
      color: #fff;
      box-shadow: 0 10px 20px rgba(239, 68, 68, .25)
    }

    .btn-danger:hover {
      filter: brightness(.95);
      transform: translateY(-1px)
    }

    @media (max-width:1024px) {
      .auth-wrap {
        grid-template-columns: 1fr
      }

      .hero {
        min-height: 280px
      }
    }
  </style>
@endpush

@section('content')
  <div class="auth-wrap container">
    <!-- Left: Hero with logo & background -->
    <section class="hero">
      <div class="hero-content">
        <div class="brand">
          <img src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" alt="Logo">
          <strong>Maharashtra Tourism</strong>
        </div>
        <div>
          <h1>Registration Form</h1>
          <p>Please fill in the form below</p>
          <div class="hero-bullets">
            <span><i class="fa-solid fa-shield-check"></i> Secure</span>
            <span><i class="fa-solid fa-bolt"></i> OTP Verify</span>
            <span><i class="fa-solid fa-user-check"></i> Quick Onboarding</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Right: Card -->
    <!-- Right: Card -->
    <section class="card">
      <div class="progress-bar">
        <div class="progress" id="progress"></div>
      </div>

      <div class="steps" style="justify-content: space-around;">
        <div class="step active" id="step1">1</div>
        <div class="step" id="step2">2</div>
        <div class="step" id="step3">3</div>
      </div>

      {{-- STEP 1: BASIC INFORMATION (Username, Email, Phone) --}}
      <div class="form-section active" id="section1">
        <h2 class="form-title">Basic Information</h2>
        <div class="row">
          <div class="form-group" id="grp-username">
            <label class="label">Username</label>
            <input id="username" type="text" class="control" placeholder="Enter your username">
            <i class="fa-regular fa-circle-check icon-right"></i>
            <div class="msg" id="username-msg"></div>
          </div>

          <div class="form-group" id="grp-email">
            <label class="label">Email Address</label>
            <input id="email" type="email" class="control" placeholder="name@example.com">
            <i class="fa-regular fa-circle-check icon-right"></i>
            <div class="msg" id="email-msg"></div>
          </div>

          <div class="form-group" id="grp-phone">
            <label class="label">Phone Number</label>
            <input id="phone" type="tel" class="control" placeholder="10-digit mobile number" maxlength="10">
            <i class="fa-regular fa-circle-check icon-right"></i>
            <div class="msg" id="phone-msg"></div>
          </div>
        </div>

        <div class="stack" style="margin-top:20px;">
          <button class="btn btn-primary" id="step1-next">
            <i class="fa-solid fa-paper-plane"></i> Send OTP & Continue
          </button>
          <button class="btn btn-danger" id="step1-reset" type="button" style="width: 30%;">
            <i class="fa-solid fa-rotate-right"></i> Reset
          </button>
        </div>
      </div>

      {{-- STEP 2: VERIFICATION (Combined OTP) --}}
      <div class="form-section" id="section2">
        <h2 class="form-title">Verification</h2>
        <p class="hint">We sent a verification code to <b id="echo-email"></b> and your phone.</p>

        <div id="otp-wrap" style="margin-top:14px">
          <label class="label">Enter OTP</label>
          <div class="otp-box">
            <input class="otp" maxlength="1">
            <input class="otp" maxlength="1">
            <input class="otp" maxlength="1">
            <input class="otp" maxlength="1">
          </div>
          <div class="timer" id="otp-timer">02:00</div>
          <div class="resend" id="resend-otp">Resend OTP</div>
          <div class="msg" id="otp-msg"></div>

          <div class="stack" style="margin-top:20px;">
            <button class="btn btn-primary" id="verify-otp">Verify & Continue</button>
            <button class="btn btn-ghost" id="back-to-step1">Back</button>
          </div>
        </div>
      </div>

      {{-- STEP 3: SECURITY & AADHAR --}}
      <div class="form-section" id="section3">
        <h2 class="form-title">Security & Aadhar</h2>

        <div class="form-group" id="grp-pass">
          <label class="label">Password</label>
          <input id="password" type="password" class="control" placeholder="Create a strong password">
          <i class="fa-regular fa-circle-check icon-right"></i>
          <div class="strength"><b id="strength-bar"></b></div>
          <div class="hint" id="pass-req">8+ chars, upper, lower, number, special</div>
        </div>

        <div class="form-group" id="grp-cpass">
          <label class="label">Confirm Password</label>
          <input id="confirm-password" type="password" class="control" placeholder="Re-enter password">
          <i class="fa-regular fa-circle-check icon-right"></i>
          <div class="msg" id="cpass-msg"></div>
        </div>

        <div class="form-group" id="grp-aadhar">
          <label class="label">Aadhar Number</label>
          <input id="aadhar" type="text" class="control" placeholder="12-digit Aadhar" maxlength="12">
          <i class="fa-regular fa-circle-check icon-right"></i>
          <div class="msg" id="aadhar-msg"></div>
        </div>

        <div class="form-group" id="grp-captcha">
          <label class="label">Security Check</label>
          <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
            <span id="captcha-img">{!! captcha_img('flat') !!}</span>
            <button type="button" class="btn btn-ghost" id="refresh-captcha"
              style="width: auto; padding: 0 10px; height: 46px;" title="Refresh Captcha">
              <i class="fa-solid fa-rotate"></i>
            </button>
          </div>
          <input id="captcha" type="text" class="control" placeholder="Enter the code shown above">
          <div class="msg" id="captcha-msg"></div>
        </div>

        <div class="stack" style="margin-top:20px;">
          <button class="btn btn-primary" id="btn-register">Register</button>
          {{-- <button class="btn btn-ghost" id="back-to-step2">Back</button> --}}
        </div>
      </div>
    </section>
  </div>
@endsection

@push('scripts')
  <script>
    // Vanilla Helpers
    const qs = sel => document.querySelector(sel);
    const qsa = sel => document.querySelectorAll(sel);
    const progress = qs('#progress');
    const steps = qsa('.step');
    const sections = qsa('.form-section');

    const REG = {
      email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
      phone: /^\d{10}$/,
      aadhar: /^\d{12}$/
    };

    function goto(step) {
      // progress bar width: step 1 (0%), step 2 (50%), step 3 (100%)
      progress.style.width = `${(step - 1) * 50}%`;

      steps.forEach((s, i) => {
        s.classList.toggle('active', i === step - 1);
        if (i < step - 1) s.classList.add('completed');
      });

      sections.forEach((sec, i) => {
        sec.classList.toggle('active', i === step - 1);
      });
    }

    function mark(groupId, ok, msgId = null, text = "") {
      const grp = document.getElementById(groupId);
      if (!grp) return false;
      grp.classList.remove('valid', 'invalid');
      grp.classList.add(ok ? 'valid' : 'invalid');
      if (msgId) {
        const m = document.getElementById(msgId);
        if (ok) {
          m.style.display = 'none';
        } else {
          m.textContent = text;
          m.className = 'msg error';
          m.style.display = 'block';
        }
      }
      return ok;
    }

    /* ---------------------- Real-time Validation UI ---------------------- */
    if (qs('#username')) qs('#username').addEventListener('input', e => {
      mark('grp-username', e.target.value.trim().length >= 3, 'username-msg', 'At least 3 characters required');
    });

    if (qs('#email')) qs('#email').addEventListener('input', e => {
      mark('grp-email', REG.email.test(e.target.value.trim()), 'email-msg', 'Enter a valid email address');
    });

    if (qs('#phone')) qs('#phone').addEventListener('input', e => {
      mark('grp-phone', REG.phone.test(e.target.value.trim()), 'phone-msg', 'Enter 10-digit number');
    });

    function strength(pwd) {
      let s = 0;
      if (pwd.length >= 8) s += 20;
      if (/[A-Z]/.test(pwd)) s += 20;
      if (/[a-z]/.test(pwd)) s += 20;
      if (/\d/.test(pwd)) s += 20;
      if (/[^A-Za-z0-9]/.test(pwd)) s += 20;
      return s;
    }

    if (qs('#password')) qs('#password').addEventListener('input', e => {
      const v = e.target.value;
      const pct = strength(v);
      const bar = qs('#strength-bar');
      if (bar) {
        bar.style.width = pct + '%';
        bar.style.background = pct < 40 ? '#ef4444' : (pct < 80 ? '#f59e0b' : '#22c55e');
      }
      mark('grp-pass', pct >= 60);
    });

    if (qs('#confirm-password')) qs('#confirm-password').addEventListener('input', () => {
      const ok = qs('#confirm-password').value && qs('#confirm-password').value === qs('#password').value;
      mark('grp-cpass', ok, 'cpass-msg', 'Passwords do not match');
    });

    if (qs('#aadhar')) qs('#aadhar').addEventListener('input', e => {
      mark('grp-aadhar', REG.aadhar.test(e.target.value), 'aadhar-msg', 'Enter 12-digit Aadhar number');
    });


    // OTP Helpers
    function otpFrom(container) {
      let v = '';
      container.querySelectorAll('.otp').forEach(i => v += i.value);
      return v;
    }

    let timerInt = null;
    function otpTimer(el, sec = 120) {
      const out = document.getElementById(el);
      if (timerInt) clearInterval(timerInt);
      let t = sec;
      out.style.color = '#ef4444';
      out.textContent = `02:00`;

      timerInt = setInterval(() => {
        const m = String(Math.floor(t / 60)).padStart(2, '0');
        const s = String(t % 60).padStart(2, '0');
        out.textContent = `${m}:${s}`;
        if (--t < 0) {
          clearInterval(timerInt);
          out.textContent = 'OTP expired';
        }
      }, 1000);
    }

    function wireOTPInputs(scope) {
      scope.querySelectorAll('.otp').forEach(inp => {
        inp.addEventListener('input', function () {
          if (this.value.length === 1) {
            const nx = this.nextElementSibling;
            if (nx && nx.classList.contains('otp')) nx.focus();
          }
        });
        inp.addEventListener('keydown', function (e) {
          if (e.key === 'Backspace' && !this.value) {
            const pv = this.previousElementSibling;
            if (pv && pv.classList.contains('otp')) pv.focus();
          }
        });
      });
    }

    /* ---------------------- Logic ---------------------- */
    $(function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      function postJSON(url, payload) {
        return $.ajax({
          url,
          method: 'POST',
          data: JSON.stringify(payload),
          contentType: 'application/json',
          dataType: 'json'
        });
      }

      /* Reset Step 1 */
      $('#step1-reset').on('click', function () {
        $('#username').val('').trigger('input');
        $('#email').val('').trigger('input');
        $('#phone').val('').trigger('input');
        $('.msg').hide().removeClass('error');
        $('.form-group').removeClass('valid invalid');
      });

      /* STEP 1 -> 2 : Send OTP */
      $('#step1-next').on('click', function () {
        const uOK = mark('grp-username', $('#username').val().trim().length >= 3, 'username-msg', 'At least 3 characters');
        const eOK = mark('grp-email', REG.email.test($('#email').val().trim()), 'email-msg', 'Enter a valid email');
        const pOK = mark('grp-phone', REG.phone.test($('#phone').val().trim()), 'phone-msg', 'Enter 10-digit number');

        if (!uOK || !eOK || !pOK) {
          Swal.fire({ icon: 'error', title: 'Invalid Fields', text: 'Please correct the errors in the form.' });
          return;
        }

        const email = $('#email').val().trim();
        const phone = $('#phone').val().trim();

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Sending...');

        postJSON('/registration/send-otp', { email: email, phone: phone })
          .done(res => {
            btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Send OTP & Continue');
            $('#echo-email').text(email);

            // Setup Step 2 UI
            otpTimer('otp-timer', 120);
            wireOTPInputs(document.getElementById('otp-wrap'));

            Swal.fire({
              icon: 'success',
              title: 'OTP Sent',
              text: res.message || 'OTP has been sent to your email and phone.'
            });

            goto(2);
          })
          .fail(xhr => {
            btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Send OTP & Continue');
            const msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to send OTP.';
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
          });
      });

      /* Resend OTP */
      $('#resend-otp').on('click', function () {
        const email = $('#email').val().trim();
        const phone = $('#phone').val().trim();
        if (!email || !phone) return;

        otpTimer('otp-timer', 120);
        postJSON('/registration/send-otp', { email: email, phone: phone })
          .done(res => {
            Swal.fire({ icon: 'success', title: 'OTP Resent', text: 'New OTP sent.' });
          })
          .fail(xhr => {
            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON.message });
          });
      });

      /* STEP 2 -> 3 : Verify OTP */
      $('#verify-otp').on('click', function () {
        const otp = otpFrom(document.getElementById('otp-wrap'));
        if (otp.length < 4) {
          Swal.fire({ icon: 'error', title: 'Invalid OTP', text: 'Please enter 4 digits.' });
          return;
        }

        const btn = $(this);
        btn.prop('disabled', true).text('Verifying...');

        postJSON('/registration/verify-otp', { otp: otp })
          .done(res => {
            btn.prop('disabled', false).text('Verify & Continue');
            Swal.fire({ icon: 'success', title: 'Verified', text: 'OTP Verified successfully.' });
            goto(3);
          })
          .fail(xhr => {
            btn.prop('disabled', false).text('Verify & Continue');
            $('#otp-msg').show().addClass('error').text(xhr.responseJSON.message || 'Invalid OTP');
            Swal.fire({ icon: 'error', title: 'Invalid OTP', text: xhr.responseJSON.message });
          });
      });

      $('#back-to-step1').on('click', () => goto(1));

      /* Captcha Refresh */
      $('#refresh-captcha').on('click', function () {
        $('#captcha-img img').attr('src', '/captcha/flat?' + Math.random());
      });

      /* STEP 3 : Register */
      $('#btn-register').on('click', function () {
        const pwdOK = strength($('#password').val()) >= 60;
        const cpwOK = $('#confirm-password').val() === $('#password').val();

        // Aadhar is optional now. If enter, must be valid.
        const aadharVal = $('#aadhar').val().trim();
        let adhOK = true;
        if (aadharVal.length > 0) {
          adhOK = REG.aadhar.test(aadharVal);
          mark('grp-aadhar', adhOK, 'aadhar-msg', 'Enter 12-digit Aadhar');
        } else {
          mark('grp-aadhar', true); // clear errors
        }

        mark('grp-pass', pwdOK);
        mark('grp-cpass', cpwOK, 'cpass-msg', 'Passwords do not match');

        if (!pwdOK || !cpwOK || !adhOK) {
          Swal.fire({ icon: 'error', title: 'Fix Errors', text: 'Please ensure Password and Aadhar (if provided) are valid.' });
          return;
        }

        const payload = {
          username: $('#username').val().trim(),
          email: $('#email').val().trim(),
          phone: $('#phone').val().trim(),
          password: $('#password').val(),
          password_confirmation: $('#confirm-password').val(),
          aadhar: aadharVal || null,
          captcha: $('#captcha').val().trim()
        };

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Registering...');

        postJSON('/registration/register', payload)
          .done(res => {
            Swal.fire({
              icon: 'success',
              title: 'Registration Complete',
              text: 'Your account has been created successfully.',
              confirmButtonText: 'Go to Login'
            }).then(() => {
              window.location.href = res.redirect_url || '/login';
            });
          })
          .fail(xhr => {
            btn.prop('disabled', false).html('Register');
            const data = xhr.responseJSON || {};
            let text = data.message || 'Registration failed.';

            // show validation errors if any
            if (data.errors) {
              const k = Object.keys(data.errors)[0];
              if (k) text = data.errors[k][0];
            }
            Swal.fire({ icon: 'error', title: 'Registration Failed', text: text });

            // Refresh captcha on failure
            $('#refresh-captcha').click();
            $('#captcha').val('');
          });
      });

    });
  </script>
@endpush