@extends('frontend.layouts.app')

@section('title', 'Tourist Villa Registration')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root{
        --primary:#4a6cf7;
        --primary-dark:#2f4fd8;
        --ok:#22c55e;
        --error:#ef4444;
        --muted:#8b8b8b;
    }
    *{box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{background:#f5f7fb}
    .auth-wrap{
        min-height:calc(100vh - 120px);
        display:grid;
        grid-template-columns: 1.2fr 1fr;
        gap:28px;
        padding:32px 16px;
    }
    /* Left hero */
    .hero{
        position:relative;
        border-radius:20px;
        overflow:hidden;
        background:#c3cfe2;
        box-shadow:0 15px 45px rgba(15, 23, 42, .08);
        isolation:isolate;
    }
    .hero::before{
        content:"";
        position:absolute; inset:0;
        background:url('https://c.ndtvimg.com/2023-05/k213ieo_maharashtra-day-2023_625x300_01_May_23.jpg?downsize=773:435') center/cover no-repeat;
        filter:grayscale(10%) brightness(0.75);
        transform:scale(1.02);
    }
    .hero::after{
        /* soft glass overlay */
        content:"";
        position:absolute; inset:auto 0 0 0; height:38%;
        background:linear-gradient(180deg,rgba(0,0,0,0) 0%, rgba(0,0,0,.45) 65%);
    }
    .hero-content{
        position:relative;
        color:#fff;
        padding:28px;
        display:flex;
        flex-direction:column;
        height:100%;
        justify-content:space-between;
        z-index:1;
    }
    .brand{
        display:flex; align-items:center; gap:12px;
        background:rgba(255,255,255,.12);
        border:1px solid rgba(255,255,255,.25);
        backdrop-filter: blur(6px);
        border-radius:14px;
        padding:10px 14px;
        width:max-content;
    }
    .brand img{width:42px;height:42px;object-fit:contain;filter:drop-shadow(0 2px 6px rgba(0,0,0,.25))}
    .hero h1{font-size:32px; font-weight:700; line-height:1.15; margin-top:10px}
    .hero p{opacity:.9}
    .hero-bullets{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px}
    .hero-bullets span{
        font-size:12px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);
        padding:6px 10px;border-radius:999px
    }

    /* Right card */
    .card{
        background:#fff; border-radius:20px;
        box-shadow:0 20px 55px rgba(2,6,23,.08);
        padding:26px;
        position:relative;
        overflow:hidden;
    }
    .progress-bar{height:4px;background:#eef1f7;border-radius:3px;overflow:hidden;margin-bottom:18px}
    .progress{height:100%;width:0;background:linear-gradient(90deg,var(--primary),#3b5bdb);transition:width .35s ease}

    .steps{display:flex;justify-content:space-between;margin-bottom:18px}
    .step{
        width:30px;height:30px;border-radius:50%;display:grid;place-items:center;
        background:#e8ecf7;color:#6b7280;font-weight:600;font-size:14px;position:relative
    }
    .step.active{background:var(--primary);color:#fff}
    .step.completed{background:var(--ok);color:#fff}
    .step.completed::after{content:"\f00c";font-family:"Font Awesome 6 Free";font-weight:900;position:absolute;right:-10px;top:-10px;background:#fff;color:var(--ok);border-radius:999px;border:2px solid var(--ok);padding:4px;font-size:10px}

    .form-section{display:none;animation:fade .25s ease}
    .form-section.active{display:block}
    @keyframes fade{from{opacity:.5;transform:translateY(4px)} to{opacity:1;transform:none}}

    .form-title{font-weight:600;font-size:20px;margin:6px 0 16px;color:#111827}
    .row{display:grid;grid-template-columns:1fr;gap:16px}
    .form-group{position:relative}
    .label{display:block;font-size:13px;color:#4b5563;margin-bottom:6px}
    .control{
        width:100%;padding:12px 42px 12px 14px;border:1px solid #e5e7eb;border-radius:12px;
        background:#f9fafb;font-size:15px;transition:border .2s, box-shadow .2s
    }
    .control:focus{outline:none;border-color:var(--primary);background:#fff;box-shadow:0 0 0 4px rgba(74,108,247,.12)}
    .icon-right{position:absolute;right:12px;top:37px;font-size:16px;color:#9ca3af}
    .valid .icon-right{color:var(--ok)}
    .invalid .icon-right{color:var(--error)}
    .hint{font-size:12px;color:#6b7280;margin-top:6px}
    .msg{display:none;margin-top:6px;font-size:13px}
    .msg.error{display:block;color:var(--error)}
    .otp-box{display:flex;gap:10px}
    .otp{width:48px;height:48px;text-align:center;border:1px solid #e5e7eb;border-radius:10px;font-size:18px;background:#fafafa}
    .otp:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 4px rgba(74,108,247,.12);background:#fff}
    .timer{text-align:center;color:#ef4444;margin-top:8px;font-size:13px}
    .resend{cursor:pointer;text-align:center;margin-top:8px;color:var(--primary);font-size:13px}
    .btn{width:100%;padding:12px 14px;border:0;border-radius:12px;cursor:pointer;font-weight:600;font-size:15px}
    .btn-primary{background:linear-gradient(135deg,var(--primary),#3b5bdb);color:#fff;box-shadow:0 10px 20px rgba(59,91,219,.25)}
    .btn-primary:hover{filter:brightness(.98);transform:translateY(-1px)}
    .btn-ghost{background:#f2f4f8;color:#4b5563}
    .stack{display:flex;gap:10px;margin-top:6px}
    .strength{height:6px;background:#e5e7eb;border-radius:6px;overflow:hidden;margin-top:6px}
    .strength > b{display:block;height:100%;width:0;background:#ef4444;transition:width .25s, background .25s}

    @media (max-width:1024px){ .auth-wrap{grid-template-columns:1fr} .hero{min-height:280px} }
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
    <section class="card">
        <div class="progress-bar"><div class="progress" id="progress"></div></div>

        <div class="steps">
            <div class="step active" id="step1">1</div>
            <div class="step" id="step2">2</div>
            <div class="step" id="step3">3</div>
            <div class="step" id="step4">4</div>
        </div>

        {{-- STEP 1: BASIC --}}
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
            </div>
            <button class="btn btn-primary" id="to-email">Continue</button>
        </div>

        {{-- STEP 2: EMAIL OTP --}}
        <div class="form-section" id="section2">
            <h2 class="form-title">Email Verification</h2>
            <p class="hint">We’ll send a 4-digit OTP to <b id="echo-email"></b></p>

            <div class="stack">
                <button class="btn btn-primary" id="send-email-otp"><i class="fa-solid fa-paper-plane"></i> Send OTP</button>
                <button class="btn btn-ghost" id="back-to-basic">Back</button>
            </div>

            <div id="email-otp-wrap" style="display:none;margin-top:14px">
                <label class="label">Enter OTP</label>
                <div class="otp-box">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                </div>
                <div class="timer" id="email-timer">02:00</div>
                <div class="resend" id="resend-email">Resend OTP</div>
                <div class="msg" id="email-otp-msg"></div>

                <button class="btn btn-primary" style="margin-top:10px" id="verify-email">Verify & Continue</button>
            </div>
        </div>

        {{-- STEP 3: PHONE OTP (optional but included) --}}
        <div class="form-section" id="section3">
            <h2 class="form-title">Phone Verification</h2>

            <div class="form-group" id="grp-phone">
                <label class="label">Phone Number</label>
                <input id="phone" type="tel" class="control" placeholder="10-digit mobile number">
                <i class="fa-regular fa-circle-check icon-right"></i>
                <div class="msg" id="phone-msg"></div>
            </div>

            <div class="stack">
                <button class="btn btn-primary" id="send-phone-otp"><i class="fa-solid fa-paper-plane"></i> Send OTP</button>
                <button class="btn btn-ghost" id="back-to-email">Back</button>
            </div>

            <div id="phone-otp-wrap" style="display:none;margin-top:14px">
                <label class="label">Enter OTP</label>
                <div class="otp-box">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                </div>
                <div class="timer" id="phone-timer">02:00</div>
                <div class="resend" id="resend-phone">Resend OTP</div>
                <div class="msg" id="phone-otp-msg"></div>

                <button class="btn btn-primary" style="margin-top:10px" id="verify-phone">Verify & Continue</button>
            </div>
        </div>

        {{-- STEP 4: PASSWORD + AADHAR --}}
        <div class="form-section" id="section4">
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
                <input id="aadhar" type="text" class="control" placeholder="12-digit Aadhar">
                <i class="fa-regular fa-circle-check icon-right"></i>
                <div class="msg" id="aadhar-msg"></div>
            </div>

            <div class="stack">
                <button class="btn btn-primary" id="send-aadhar-otp"><i class="fa-solid fa-paper-plane"></i> Send Aadhar OTP</button>
                <button class="btn btn-ghost" id="back-to-phone">Back</button>
            </div>

            <div id="aadhar-otp-wrap" style="display:none;margin-top:14px">
                <label class="label">Enter OTP</label>
                <div class="otp-box">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                    <input class="otp" maxlength="1">
                </div>
                <div class="timer" id="aadhar-timer">02:00</div>
                <div class="resend" id="resend-aadhar">Resend OTP</div>
                <div class="msg" id="aadhar-otp-msg"></div>
                <div class="row" style="margin-top:10px">
                    <div class="form-group" style="margin-top:4px">
                        <label class="label">Date of Birth</label>
                        <input id="dob" type="date" class="control" disabled>
                        <i class="fa-regular fa-calendar icon-right"></i>
                    </div>
                </div>

                <button class="btn btn-primary" style="margin-top:10px" id="finish">Verify & Register</button>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Helpers
    const $ = sel => document.querySelector(sel);
    const $$ = sel => document.querySelectorAll(sel);

    const progress = $('#progress');
    const steps = $$('.step');
    const sections = $$('.form-section');

    const REG = {
        email:/^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        phone:/^\d{10}$/,
        aadhar:/^\d{12}$/
    };

    function goto(step){
        progress.style.width = `${(step-1)*33.33}%`;
        steps.forEach((s,i)=>{
            s.classList.toggle('active', i===step-1);
            if(i < step-1) s.classList.add('completed');
        });
        sections.forEach((sec,i)=>sec.classList.toggle('active', i===step-1));
    }

    function mark(groupId, ok, msgId=null, text=""){
        const grp = document.getElementById(groupId);
        grp.classList.remove('valid','invalid');
        grp.classList.add(ok?'valid':'invalid');
        if(msgId){
            const m = document.getElementById(msgId);
            if(ok){ m.style.display='none'; }
            else{ m.textContent = text; m.className='msg error'; }
        }
        return ok;
    }

    function otpFrom(container){
        let v=''; container.querySelectorAll('.otp').forEach(i=>v+=i.value); return v;
    }
    function otpTimer(el, sec=120){
        const out = document.getElementById(el);
        let t=sec; out.style.color='#ef4444';
        const int = setInterval(()=>{
            const m=String(Math.floor(t/60)).padStart(2,'0');
            const s=String(t%60).padStart(2,'0');
            out.textContent = `${m}:${s}`;
            if(--t<0){ clearInterval(int); out.textContent='OTP expired'; }
        },1000);
    }
    function wireOTPInputs(scope){
        scope.querySelectorAll('.otp').forEach(inp=>{
            inp.addEventListener('input',function(){
                if(this.value.length===1){
                    const nx=this.nextElementSibling; if(nx && nx.classList.contains('otp')) nx.focus();
                }
            });
            inp.addEventListener('keydown',function(e){
                if(e.key==='Backspace' && !this.value){
                    const pv=this.previousElementSibling; if(pv && pv.classList.contains('otp')) pv.focus();
                }
            });
        });
    }

    // STEP 1 validations
    $('#username').addEventListener('input', e=>{
        const ok = e.target.value.trim().length>=3;
        mark('grp-username', ok, 'username-msg', 'At least 3 characters required');
    });

    $('#email').addEventListener('input', e=>{
        const ok = REG.email.test(e.target.value.trim());
        mark('grp-email', ok, 'email-msg', 'Enter a valid email address');
    });

    $('#to-email').addEventListener('click', ()=>{
        if(!mark('grp-username', $('#username').value.trim().length>=3,'username-msg','At least 3 characters'))
            return;
        if(!mark('grp-email', REG.email.test($('#email').value.trim()), 'email-msg','Enter a valid email'))
            return;
        $('#echo-email').textContent = $('#email').value.trim();
        goto(2);
    });

    // STEP 2 Email OTP
    $('#back-to-basic').addEventListener('click', ()=>goto(1));

    $('#send-email-otp').addEventListener('click', ()=>{
        $('#email-otp-wrap').style.display='block';
        otpTimer('email-timer',120);
        wireOTPInputs($('#email-otp-wrap'));
        const m=$('#email-otp-msg'); m.style.display='block'; m.textContent='OTP sent to your email.'; m.className='msg';
    });

    $('#resend-email').addEventListener('click', ()=>otpTimer('email-timer',120));

    $('#verify-email').addEventListener('click', ()=>{
        const v = otpFrom($('#email-otp-wrap'));
        const m=$('#email-otp-msg');
        if(v==='1234'){ m.className='msg'; m.style.color='#22c55e'; m.textContent='Email verified ✓'; goto(3); }
        else { m.className='msg error'; m.textContent='Invalid OTP. Try again.'; }
    });

    // STEP 3 Phone
    $('#back-to-email').addEventListener('click', ()=>goto(2));
    $('#phone').addEventListener('input', e=>{
        mark('grp-phone', REG.phone.test(e.target.value), 'phone-msg', 'Enter 10-digit number');
    });

    $('#send-phone-otp').addEventListener('click', ()=>{
        if(!mark('grp-phone', REG.phone.test($('#phone').value),'phone-msg','Enter 10-digit number')) return;
        $('#phone-otp-wrap').style.display='block';
        otpTimer('phone-timer',120);
        wireOTPInputs($('#phone-otp-wrap'));
        const m=$('#phone-otp-msg'); m.style.display='block'; m.textContent='OTP sent to your phone.'; m.className='msg';
    });

    $('#resend-phone').addEventListener('click', ()=>otpTimer('phone-timer',120));

    $('#verify-phone').addEventListener('click', ()=>{
        const v = otpFrom($('#phone-otp-wrap'));
        const m=$('#phone-otp-msg');
        if(v==='1234'){ m.className='msg'; m.style.color='#22c55e'; m.textContent='Phone verified ✓'; goto(4); }
        else { m.className='msg error'; m.textContent='Invalid OTP. Try again.'; }
    });

    // STEP 4 Password + Aadhar
    function strength(pwd){
        let s=0; if(pwd.length>=8) s+=20; if(/[A-Z]/.test(pwd)) s+=20; if(/[a-z]/.test(pwd)) s+=20; if(/\d/.test(pwd)) s+=20; if(/[^A-Za-z0-9]/.test(pwd)) s+=20; return s;
    }
    $('#password').addEventListener('input', e=>{
        const v=e.target.value; const pct=strength(v); const bar=$('#strength-bar');
        bar.style.width=pct+'%';
        bar.style.background = pct<40?'#ef4444':(pct<80?'#f59e0b':'#22c55e');
        mark('grp-pass', pct>=60);
    });

    $('#confirm-password').addEventListener('input', ()=>{
        const ok = $('#confirm-password').value && $('#confirm-password').value === $('#password').value;
        mark('grp-cpass', ok, 'cpass-msg', 'Passwords do not match');
    });

    $('#aadhar').addEventListener('input', e=>{
        mark('grp-aadhar', REG.aadhar.test(e.target.value),'aadhar-msg','Enter 12-digit Aadhar number');
    });

    $('#back-to-phone').addEventListener('click', ()=>goto(3));

    $('#send-aadhar-otp').addEventListener('click', ()=>{
        if(!mark('grp-pass', strength($('#password').value)>=60)) return;
        if(!mark('grp-cpass', $('#confirm-password').value === $('#password').value,'cpass-msg','Passwords do not match')) return;
        if(!mark('grp-aadhar', REG.aadhar.test($('#aadhar').value),'aadhar-msg','Enter 12-digit Aadhar')) return;

        $('#aadhar-otp-wrap').style.display='block';
        otpTimer('aadhar-timer',120);
        wireOTPInputs($('#aadhar-otp-wrap'));
        const m=$('#aadhar-otp-msg'); m.style.display='block'; m.textContent='OTP sent to your registered mobile.'; m.className='msg';
    });

    $('#resend-aadhar').addEventListener('click', ()=>otpTimer('aadhar-timer',120));

    $('#finish').addEventListener('click', ()=>{
        const v = otpFrom($('#aadhar-otp-wrap'));
        const m=$('#aadhar-otp-msg');
        if(v==='1234'){
            m.className='msg'; m.style.color='#22c55e'; m.textContent='Aadhar verified ✓';
            // Demo: autofill DOB from Aadhar response
            const dob = document.getElementById('dob'); dob.value='1990-01-15'; dob.disabled=false;

            // TODO: Submit via AJAX or normal POST to your controller
            setTimeout(()=>alert('Registration completed successfully!'), 500);
        }else{
            m.className='msg error'; m.textContent='Invalid OTP. Try again.';
        }
    });

    // Initial section
    goto(1);
</script>
<script>
    $(function () {
      // 1) Set CSRF header for all jQuery AJAX calls
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Small helper for JSON POSTs
      function postJSON(url, payload) {
        return $.ajax({
          url: url,
          method: 'POST',
          data: JSON.stringify(payload),
          contentType: 'application/json', // send JSON
          dataType: 'json'                 // expect JSON back
        });
      }

      /* -------------------- Send OTP (email) -------------------- */
      postJSON('/registration/send-otp', {
        type: 'email',
        value: 'user@example.com' // or $('#email').val()
      })
      .done(function (res) {
        console.log('send-otp OK:', res);
        // UI success handling here
      })
      .fail(function (xhr) {
        console.error('send-otp ERR:', xhr.responseJSON || xhr.responseText);
        // UI error handling here
      });

      /* -------------------- Verify OTP -------------------- */
      postJSON('/registration/verify-otp', {
        type: 'email',
        value: 'user@example.com', // or $('#email').val()
        otp: '1234'
      })
      .done(function (res) {
        console.log('verify-otp OK:', res);
      })
      .fail(function (xhr) {
        console.error('verify-otp ERR:', xhr.responseJSON || xhr.responseText);
      });

      /* -------------------- Final Register -------------------- */
      postJSON('/registration/register', {
        name: 'Raja',
        username: 'raja12',
        email: 'raja@example.com',
        phone: '9876543210',
        password: 'Secret123!',
        password_confirmation: 'Secret123!',
        aadhar: '123412341234'
      })
      .done(function (res) {
        console.log('register OK:', res);
        // maybe redirect or show success UI
      })
      .fail(function (xhr) {
        console.error('register ERR:', xhr.responseJSON || xhr.responseText);
        // show validation errors from xhr.responseJSON.errors if present
      });
    });
    </script>
@endpush
