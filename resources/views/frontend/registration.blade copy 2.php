<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            max-width: 60px;
            max-height: 60px;
        }

        .logo-container h1 {
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .form-title {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }

        .form-control:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
            background-color: #fff;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 40px;
            color: #999;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a6cf7 0%, #3b5bdb 100%);
            color: white;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3b5bdb 0%, #2f4fd8 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 91, 219, 0.3);
        }

        .btn-secondary {
            background: #f1f3f4;
            color: #555;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background: #e4e7e9;
        }

        .otp-section {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .otp-input:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.2);
        }

        .timer {
            text-align: center;
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .resend-otp {
            text-align: center;
            font-size: 14px;
            color: #4a6cf7;
            cursor: pointer;
            margin-top: 10px;
        }

        .resend-otp:hover {
            text-decoration: underline;
        }

        .success-message {
            color: #27ae60;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .progress-bar {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: #4a6cf7;
            width: 0%;
            transition: width 0.5s ease;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #777;
            font-weight: 500;
        }

        .step.active {
            background: #4a6cf7;
            color: white;
        }

        .step.completed {
            background: #27ae60;
            color: white;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .password-strength {
            height: 5px;
            border-radius: 2px;
            margin-top: 8px;
            background: #e0e0e0;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background 0.3s;
        }

        .password-requirements {
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        .requirement {
            margin-bottom: 3px;
        }

        .requirement.met {
            color: #27ae60;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .footer-text a {
            color: #4a6cf7;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="logo">
                <img src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" alt="Maharashtra Tourism Logo">
            </div>
            <h1>User Registration</h1>
        </div>

        <div class="card">
            <div class="progress-bar">
                <div class="progress" id="progress"></div>
            </div>

            <div class="step-indicator">
                <div class="step active" id="step1">1</div>
                <div class="step" id="step2">2</div>
                <div class="step" id="step3">3</div>
                <div class="step" id="step4">4</div>
            </div>

            <!-- Step 1: Basic Information -->
            <div class="form-section active" id="section1">
                <h2 class="form-title">Basic Information</h2>

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" class="form-control" placeholder="Enter your username">
                    <i class="fas fa-user input-icon"></i>
                    <div class="error-message" id="username-error">Username must be at least 3 characters</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email">
                    <i class="fas fa-envelope input-icon"></i>
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>

                <button class="btn btn-primary" id="next-to-phone">Continue</button>
            </div>

            <!-- Step 2: Phone Verification -->
            <div class="form-section" id="section2">
                <h2 class="form-title">Phone Verification</h2>

                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" id="phone" class="form-control" placeholder="Enter your phone number">
                    <i class="fas fa-phone input-icon"></i>
                    <div class="error-message" id="phone-error">Please enter a valid 10-digit phone number</div>
                </div>

                <button class="btn btn-primary" id="send-phone-otp">Send OTP</button>

                <div class="otp-section" id="phone-otp-section">
                    <div class="form-group">
                        <label class="form-label">Enter OTP</label>
                        <div class="otp-inputs">
                            <input type="text" class="otp-input" maxlength="1">
                            <input type="text" class="otp-input" maxlength="1">
                            <input type="text" class="otp-input" maxlength="1">
                            <input type="text" class="otp-input" maxlength="1">
                        </div>
                        <div class="timer" id="phone-timer">02:00</div>
                        <div class="resend-otp" id="resend-phone-otp">Resend OTP</div>
                        <div class="success-message" id="phone-otp-success">Phone verified successfully!</div>
                        <div class="error-message" id="phone-otp-error">Invalid OTP. Please try again.</div>
                    </div>

                    <button class="btn btn-primary" id="verify-phone-otp">Verify OTP</button>
                </div>

                <button class="btn btn-secondary" id="back-to-basic">Back</button>
            </div>

            <!-- Step 3: Password Setup -->
            <div class="form-section" id="section3">
                <h2 class="form-title">Create Password</h2>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Create a strong password">
                    <i class="fas fa-lock input-icon"></i>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="password-strength-bar"></div>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="req-length">At least 8 characters</div>
                        <div class="requirement" id="req-uppercase">One uppercase letter</div>
                        <div class="requirement" id="req-lowercase">One lowercase letter</div>
                        <div class="requirement" id="req-number">One number</div>
                        <div class="requirement" id="req-special">One special character</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" class="form-control" placeholder="Confirm your password">
                    <i class="fas fa-lock input-icon"></i>
                    <div class="error-message" id="confirm-password-error">Passwords do not match</div>
                </div>

                <button class="btn btn-primary" id="next-to-aadhar">Continue</button>
                <button class="btn btn-secondary" id="back-to-phone">Back</button>
            </div>

            <!-- Step 4: Aadhar Verification -->
            <div class="form-section" id="section4">
                <h2 class="form-title">Aadhar Verification</h2>

                <div class="form-group">
                    <label class="form-label" for="aadhar">Aadhar Number</label>
                    <input type="text" id="aadhar" class="form-control" placeholder="Enter your 12-digit Aadhar number">
                    <i class="fas fa-id-card input-icon"></i>
                    <div class="error-message" id="aadhar-error">Please enter a valid 12-digit Aadhar number</div>
                </div>

                <button class="btn btn-primary" id="send-aadhar-otp">Send OTP</button>

                <div class="otp-section" id="aadhar-otp-section">
                    <div class="form-group">
                        <label class="form-label">Enter OTP</label>
                        <div class="otp-inputs">
                            <input type="text" class="otp-input" maxlength="1">
                            <input type="text" class="otp-input" maxlength="1">
                            <input type="text" class="otp-input" maxlength="1">
                            <input type="text" class="otp-input" maxlength="1">
                        </div>
                        <div class="timer" id="aadhar-timer">02:00</div>
                        <div class="resend-otp" id="resend-aadhar-otp">Resend OTP</div>
                        <div class="success-message" id="aadhar-otp-success">Aadhar verified successfully!</div>
                        <div class="error-message" id="aadhar-otp-error">Invalid OTP. Please try again.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="dob">Date of Birth</label>
                        <input type="date" id="dob" class="form-control" disabled>
                        <i class="fas fa-calendar input-icon"></i>
                    </div>

                    <button class="btn btn-primary" id="verify-aadhar-otp">Verify OTP</button>
                </div>

                <button class="btn btn-secondary" id="back-to-password">Back</button>
            </div>
        </div>

        <div class="footer-text">
            Already have an account? <a href="#">Sign In</a>
        </div>
    </div>

    <script>
        // DOM Elements
        const progress = document.getElementById('progress');
        const steps = document.querySelectorAll('.step');
        const sections = document.querySelectorAll('.form-section');

        // Navigation buttons
        document.getElementById('next-to-phone').addEventListener('click', () => navigateToStep(2));
        document.getElementById('back-to-basic').addEventListener('click', () => navigateToStep(1));
        document.getElementById('back-to-phone').addEventListener('click', () => navigateToStep(2));
        document.getElementById('next-to-aadhar').addEventListener('click', () => navigateToStep(4));
        document.getElementById('back-to-password').addEventListener('click', () => navigateToStep(3));

        // OTP functionality
        document.getElementById('send-phone-otp').addEventListener('click', sendPhoneOTP);
        document.getElementById('verify-phone-otp').addEventListener('click', verifyPhoneOTP);
        document.getElementById('resend-phone-otp').addEventListener('click', resendPhoneOTP);

        document.getElementById('send-aadhar-otp').addEventListener('click', sendAadharOTP);
        document.getElementById('verify-aadhar-otp').addEventListener('click', verifyAadharOTP);
        document.getElementById('resend-aadhar-otp').addEventListener('click', resendAadharOTP);

        // Password strength checker
        document.getElementById('password').addEventListener('input', checkPasswordStrength);

        // Form validation
        document.getElementById('username').addEventListener('blur', validateUsername);
        document.getElementById('email').addEventListener('blur', validateEmail);
        document.getElementById('phone').addEventListener('blur', validatePhone);
        document.getElementById('aadhar').addEventListener('blur', validateAadhar);
        document.getElementById('confirm-password').addEventListener('blur', validateConfirmPassword);

        // OTP input auto-focus
        document.querySelectorAll('.otp-input').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.length === 1) {
                    const next = this.nextElementSibling;
                    if (next && next.classList.contains('otp-input')) {
                        next.focus();
                    }
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0) {
                    const prev = this.previousElementSibling;
                    if (prev && prev.classList.contains('otp-input')) {
                        prev.focus();
                    }
                }
            });
        });

        // Navigation function
        function navigateToStep(step) {
            // Update progress bar
            progress.style.width = `${(step - 1) * 33.33}%`;

            // Update step indicators
            steps.forEach((s, index) => {
                if (index < step - 1) {
                    s.classList.add('completed');
                    s.classList.remove('active');
                } else if (index === step - 1) {
                    s.classList.add('active');
                    s.classList.remove('completed');
                } else {
                    s.classList.remove('active', 'completed');
                }
            });

            // Show appropriate section
            sections.forEach((section, index) => {
                if (index === step - 1) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
        }

        // OTP Functions
        function sendPhoneOTP() {
            if (!validatePhone()) return;

            document.getElementById('phone-otp-section').style.display = 'block';
            startTimer('phone-timer', 120);
            showMessage('phone-otp-success', 'OTP sent to your phone!');
        }

        function verifyPhoneOTP() {
            const otp = getOTPValue('phone-otp-section');

            // Static OTP for demo
            if (otp === '1234') {
                showMessage('phone-otp-success', 'Phone verified successfully!');
                hideMessage('phone-otp-error');
                setTimeout(() => navigateToStep(3), 1000);
            } else {
                showMessage('phone-otp-error', 'Invalid OTP. Please try again.');
                hideMessage('phone-otp-success');
            }
        }

        function resendPhoneOTP() {
            startTimer('phone-timer', 120);
            showMessage('phone-otp-success', 'OTP resent to your phone!');
        }

        function sendAadharOTP() {
            if (!validateAadhar()) return;

            document.getElementById('aadhar-otp-section').style.display = 'block';
            startTimer('aadhar-timer', 120);
            showMessage('aadhar-otp-success', 'OTP sent to your registered mobile!');
        }

        function verifyAadharOTP() {
            const otp = getOTPValue('aadhar-otp-section');

            // Static OTP for demo
            if (otp === '1234') {
                showMessage('aadhar-otp-success', 'Aadhar verified successfully!');
                hideMessage('aadhar-otp-error');

                // Auto-fill DOB from Aadhar (demo)
                document.getElementById('dob').value = '1990-01-15';
                document.getElementById('dob').disabled = false;

                // In a real app, you would submit the form here
                setTimeout(() => {
                    alert('Registration completed successfully!');
                    // Reset form
                    document.querySelectorAll('input').forEach(input => input.value = '');
                    navigateToStep(1);
                }, 1500);
            } else {
                showMessage('aadhar-otp-error', 'Invalid OTP. Please try again.');
                hideMessage('aadhar-otp-success');
            }
        }

        function resendAadharOTP() {
            startTimer('aadhar-timer', 120);
            showMessage('aadhar-otp-success', 'OTP resent to your registered mobile!');
        }

        // Helper Functions
        function getOTPValue(sectionId) {
            const inputs = document.querySelectorAll(`#${sectionId} .otp-input`);
            let otp = '';
            inputs.forEach(input => otp += input.value);
            return otp;
        }

        function startTimer(timerId, duration) {
            let timer = duration, minutes, seconds;
            const timerElement = document.getElementById(timerId);

            const interval = setInterval(() => {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerElement.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    timerElement.textContent = "OTP expired";
                    timerElement.style.color = "#e74c3c";
                }
            }, 1000);
        }

        function showMessage(elementId, message) {
            const element = document.getElementById(elementId);
            element.textContent = message;
            element.style.display = 'block';
        }

        function hideMessage(elementId) {
            document.getElementById(elementId).style.display = 'none';
        }

        // Validation Functions
        function validateUsername() {
            const username = document.getElementById('username').value;
            const errorElement = document.getElementById('username-error');

            if (username.length < 3) {
                showMessage('username-error', 'Username must be at least 3 characters');
                return false;
            } else {
                hideMessage('username-error');
                return true;
            }
        }

        function validateEmail() {
            const email = document.getElementById('email').value;
            const errorElement = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                showMessage('email-error', 'Please enter a valid email address');
                return false;
            } else {
                hideMessage('email-error');
                return true;
            }
        }

        function validatePhone() {
            const phone = document.getElementById('phone').value;
            const errorElement = document.getElementById('phone-error');
            const phoneRegex = /^\d{10}$/;

            if (!phoneRegex.test(phone)) {
                showMessage('phone-error', 'Please enter a valid 10-digit phone number');
                return false;
            } else {
                hideMessage('phone-error');
                return true;
            }
        }

        function validateAadhar() {
            const aadhar = document.getElementById('aadhar').value;
            const errorElement = document.getElementById('aadhar-error');
            const aadharRegex = /^\d{12}$/;

            if (!aadharRegex.test(aadhar)) {
                showMessage('aadhar-error', 'Please enter a valid 12-digit Aadhar number');
                return false;
            } else {
                hideMessage('aadhar-error');
                return true;
            }
        }

        function validateConfirmPassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const errorElement = document.getElementById('confirm-password-error');

            if (password !== confirmPassword) {
                showMessage('confirm-password-error', 'Passwords do not match');
                return false;
            } else {
                hideMessage('confirm-password-error');
                return true;
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            const requirements = {
                length: document.getElementById('req-length'),
                uppercase: document.getElementById('req-uppercase'),
                lowercase: document.getElementById('req-lowercase'),
                number: document.getElementById('req-number'),
                special: document.getElementById('req-special')
            };

            let strength = 0;
            let totalRequirements = 0;
            let metRequirements = 0;

            // Check length
            if (password.length >= 8) {
                requirements.length.classList.add('met');
                strength += 20;
                metRequirements++;
            } else {
                requirements.length.classList.remove('met');
            }
            totalRequirements++;

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                requirements.uppercase.classList.add('met');
                strength += 20;
                metRequirements++;
            } else {
                requirements.uppercase.classList.remove('met');
            }
            totalRequirements++;

            // Check lowercase
            if (/[a-z]/.test(password)) {
                requirements.lowercase.classList.add('met');
                strength += 20;
                metRequirements++;
            } else {
                requirements.lowercase.classList.remove('met');
            }
            totalRequirements++;

            // Check number
            if (/[0-9]/.test(password)) {
                requirements.number.classList.add('met');
                strength += 20;
                metRequirements++;
            } else {
                requirements.number.classList.remove('met');
            }
            totalRequirements++;

            // Check special character
            if (/[^A-Za-z0-9]/.test(password)) {
                requirements.special.classList.add('met');
                strength += 20;
                metRequirements++;
            } else {
                requirements.special.classList.remove('met');
            }
            totalRequirements++;

            // Update strength bar
            strengthBar.style.width = `${strength}%`;

            // Update color based on strength
            if (strength < 40) {
                strengthBar.style.background = '#e74c3c';
            } else if (strength < 80) {
                strengthBar.style.background = '#f39c12';
            } else {
                strengthBar.style.background = '#27ae60';
            }
        }
    </script>
</body>
</html>
