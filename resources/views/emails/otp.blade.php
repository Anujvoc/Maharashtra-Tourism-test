<!DOCTYPE html>
<html>

<head>
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header img {
            height: 60px;
        }

        .content {
            color: #333333;
            line-height: 1.6;
            font-size: 16px;
        }

        .otp-box {
            background-color: #ebf5ff;
            color: #4a6cf7;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin: 30px 0;
            letter-spacing: 5px;
            border: 2px dashed #4a6cf7;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888888;
            margin-top: 40px;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png"
                alt="Maharashtra Tourism">
            <h2 style="margin: 10px 0 0; color: #4a6cf7;">Maharashtra Tourism</h2>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>You requested a One-Time Password (OTP) to register for Maharashtra Tourism.</p>
            <p>Please use the following code to verify your email address:</p>

            <div class="otp-box">{{ $otp }}</div>

            <p>This code is valid for 10 minutes. Do not share this OTP with anyone.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Maharashtra Tourism. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>

</html>