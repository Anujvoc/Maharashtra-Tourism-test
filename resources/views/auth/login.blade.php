<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Maharashtra-Tourism - Login</title>
  <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    body{
      font-family:'Poppins',sans-serif;
      background:linear-gradient(rgba(0,0,0,.1),rgba(0,0,0,.1)),
                 url('https://maharashtratourism.gov.in/wp-content/uploads/2025/01/Bhavani-Mandap-Kolhapur-1.jpg');
      background-size:cover;background-position:center;background-attachment:fixed;
      min-height:100vh;
      display:flex;
      /* align-items:center; */
      justify-content:flex-start;
      padding: 40px 5%; /* Added vertical padding */
    }

    /* body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(
      rgba(0, 0, 0, 0.25),
      rgba(0, 0, 0, 0.35)
    ),
    url('https://media2.thrillophilia.com/images/photos/000/152/587/original/1603951915_shutterstock_1242281908.jpg?w=753&h=450&dpr=1.5');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 0 5%;
} */
    .login-card{
      background:rgba(255,255,255,.15);
      backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
      border:1px solid rgba(255,255,255,.2);border-radius:20px;
      box-shadow:0 10px 30px rgba(0,0,0,.4);width:100%;max-width:420px;
    }
    .input-focus:focus{ box-shadow:0 0 0 3px rgba(249,115,22,.3); }
    .logo-container{ background:#fff;border-radius:12px;padding:8px;box-shadow:0 4px 12px rgba(0,0,0,.1); }
    @media (max-width:768px){
        body{
            justify-content:center;
            padding: 40px 20px;
        }
    }
  </style>
</head>
<body>
  <div class="login-card px-8 pt-8 pb-6 md:px-10 md:pt-10 md:pb-8 text-white my-auto">
    <!-- Logo + Title -->
    <div class="text-center mb-4">
      <div class="flex justify-center mb-3">
        <div class="logo-container">
          <img src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" alt=" Logo" class="w-20 h-auto">
        </div>
      </div>
      <h2 class="text-3xl font-bold">Maharashtra Tourism</h2>
    </div>




    <form method="POST" action="{{ route('login') }}" onsubmit="return validateLoginForm()" id="login-form" novalidate>
        @csrf

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium mb-1">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-300"></i>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full pl-10 pr-4 py-3 bg-white/20 border
                           @error('email') border-red-500 @else border-white/30 @enderror
                           text-white placeholder-gray-200 rounded-lg input-focus
                           focus:outline-none focus:border-orange-400 transition"
                    placeholder="your@email.com"
                    oncopy="return false" onpaste="return false" oncut="return false" oncontextmenu="return false" autocomplete="off"
                >
            </div>
            <span id="email-error" class="text-red-300 text-xs mt-1 hidden"></span>
            @error('email')
                <span class="text-red-300 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium mb-1">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-300"></i>
                </div>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full pl-10 pr-4 py-3 bg-white/20 border
                           @error('password') border-red-500 @else border-white/30 @enderror
                           text-white placeholder-gray-200 rounded-lg input-focus
                           focus:outline-none focus:border-orange-400 transition"
                    placeholder="••••••••"
                    oncopy="return false" onpaste="return false" oncut="return false" oncontextmenu="return false" autocomplete="off"
                >
            </div>
            <span id="password-error" class="text-red-300 text-xs mt-1 hidden"></span>
            @error('password')
                <span class="text-red-300 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>



        <!-- Captcha -->
        <div class="mb-6">
            <label for="captcha" class="block text-sm font-medium mb-1">Security Code</label>
            <div class="flex flex-col gap-3">
                <div class="relative flex items-center justify-between bg-white/20 rounded-lg overflow-hidden border border-white/30 p-1 w-full">
                    <span id="captcha-img" class="flex items-center h-12">
                        {!! captcha_img('flat') !!}
                    </span>
                    <button type="button" onclick="refreshCaptcha()"
                        class="px-4 h-12 text-orange-300 hover:text-white hover:bg-white/10 transition flex items-center justify-center border-l border-white/10 ml-auto"
                        title="Reload Image">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <input id="captcha" name="captcha" type="text" required
                    class="w-full px-4 py-3 bg-white/20 border border-white/30 text-white placeholder-gray-200 rounded-lg input-focus focus:outline-none focus:border-orange-400 transition"
                    placeholder="Enter security code">
            </div>

            <span id="captcha-error" class="text-red-300 text-xs mt-1 hidden"></span>
            @error('captcha')
                <span class="text-red-300 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 text-orange-400 focus:ring-orange-400 border-gray-300 rounded bg-white/30">
                <span class="ml-2 text-sm">Remember me</span>
            </label>
            <a href="#" class="text-sm text-orange-300 hover:text-orange-400 font-medium transition">Forgot password?</a>
        </div>

        <!-- Buttons -->
        <div class="grid grid-cols-2 gap-3">
            <button type="submit"
                class="col-span-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-transparent">
                Sign In
            </button>

            <a href="{{ url('/') }}"
                class="col-span-1 inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 rounded-lg font-medium py-3 px-4 transition focus:outline-none focus:ring-2 focus:ring-white/40">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </form>

    <div class="mt-4 text-center">
        <p class="text-gray-200 text-sm">
            Don't have an account?
            <a href="{{ url('/tourism/registration') }}" class="text-orange-300 hover:text-orange-400 font-medium transition">Sign up now</a>
        </p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        #captcha-img img {
            height: 100%;
            width: auto;
            border-radius: 4px;
        }
    </style>
    <script>
    function refreshCaptcha() {
        const captchaUrl = "{{ url('captcha/flat') }}";
        const img = document.querySelector('#captcha-img img');
        if (img) {
            img.src = captchaUrl + '?' + Math.random();
        } else {
            console.error('Captcha image not found');
        }
    }

    function validateLoginForm() {
        const email = document.getElementById('email').value.trim();
        const passwordField = document.getElementById('password');
        const password = passwordField.value;
        const captcha = document.getElementById('captcha').value.trim();

        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        const captchaError = document.getElementById('captcha-error');

        // reset
        emailError.classList.add('hidden');
        passwordError.classList.add('hidden');
        captchaError.classList.add('hidden');

        let ok = true;

        if (!email || !email.includes('@')) {
            emailError.textContent = 'Please enter a valid email address.';
            emailError.classList.remove('hidden');
            ok = false;
        }

        if (!password || password.length < 1) {
            passwordError.textContent = 'Password is required.';
            passwordError.classList.remove('hidden');
            ok = false;
        }

        if (!captcha) {
            captchaError.textContent = 'Please enter the captcha.';
            captchaError.classList.remove('hidden');
            ok = false;
        }

        if (!ok) return false;

        // Encrypt Password
        const keyRaw = "{{ $frontend_encryption_key }}";
        // Derive a 32-byte key using SHA256
        const key = CryptoJS.SHA256(keyRaw);
        const iv = CryptoJS.lib.WordArray.random(16);

        const encrypted = CryptoJS.AES.encrypt(password, key, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        });

        // Payload: iv (base64) : ciphertext (base64)
        const payload = iv.toString(CryptoJS.enc.Base64) + ':' + encrypted.ciphertext.toString(CryptoJS.enc.Base64);
        passwordField.value = payload;

        return true;
    }
    </script>


        {{-- <form method="POST" action="{{ route('login') }}" onsubmit="return validateLoginForm()" id="login-form" novalidate>
            @csrf
      <div class="mb-5">
        <label for="email" class="block text-sm font-medium mb-1">Email Address</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-envelope text-gray-300"></i>
          </div>
          <input id="email" name="email" type="email" required
            class="w-full pl-10 pr-4 py-3 bg-white/20 border border-white/30 text-white placeholder-gray-200 rounded-lg input-focus focus:outline-none focus:border-orange-400 transition"
            placeholder="your@email.com">
        </div>
        <div id="email-error" class="text-red-300 text-xs mt-1 hidden"></div>
      </div>

      <div class="mb-6">
        <label for="password" class="block text-sm font-medium mb-1">Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-lock text-gray-300"></i>
          </div>
          <input id="password" name="password" type="password" required
            class="w-full pl-10 pr-4 py-3 bg-white/20 border border-white/30 text-white placeholder-gray-200 rounded-lg input-focus focus:outline-none focus:border-orange-400 transition"
            placeholder="••••••••">
        </div>
        <div id="password-error" class="text-red-300 text-xs mt-1 hidden"></div>
      </div>

      <div class="flex items-center justify-between mb-6">
        <label class="flex items-center">
          <input id="remember_me" type="checkbox" name="remember"
            class="h-4 w-4 text-orange-400 focus:ring-orange-400 border-gray-300 rounded bg-white/30">
          <span class="ml-2 text-sm">Remember me</span>
        </label>
        <a href="#" class="text-sm text-orange-300 hover:text-orange-400 font-medium transition">Forgot password?</a>
      </div>

      <!-- Buttons row: Sign In (left) + Back to Home (right) -->
      <div class="grid grid-cols-2 gap-3">
        <button type="submit"
          class="col-span-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-transparent">
          Sign In
        </button>

        <a href="{{ url('/') }}"
          class="col-span-1 inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 rounded-lg font-medium py-3 px-4 transition focus:outline-none focus:ring-2 focus:ring-white/40">
          <i class="fas fa-arrow-left"></i>
          Back
        </a>
      </div>
    </form>

    <div class="mt-6 text-center">
      <p class="text-gray-200 text-sm">
        Don't have an account?
        <a href="#" class="text-orange-300 hover:text-orange-400 font-medium transition">Sign up now</a>
      </p>
    </div>

    <div class="mt-6 text-xs text-gray-300 text-center">
      © 2025 Tiffin Verse. All rights reserved.
    </div>
  </div>

  <script>
    document.getElementById('login-form').addEventListener('submit', function(e){
      e.preventDefault();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      document.getElementById('email-error').classList.add('hidden');
      document.getElementById('password-error').classList.add('hidden');

      let ok = true;
      if (!email || !email.includes('@')) {
        email_error.textContent = 'Please enter a valid email address.';
        email_error.classList.remove('hidden'); ok = false;
      }
      if (!password || password.length < 6) {
        password_error.textContent = 'Password must be at least 6 characters.';
        password_error.classList.remove('hidden'); ok = false;
      }
      if (ok) alert('Login successful! (Demo)');
    });
  </script> --}}
</body>
</html>
