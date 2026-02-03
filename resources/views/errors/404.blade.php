<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 - Page Not Found | Maharashtra Tourism</title>
    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png"
        sizes="32x32" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)),
                url('https://maharashtratourism.gov.in/wp-content/uploads/2025/01/Bhavani-Mandap-Kolhapur-1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-card {
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .logo-container {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="error-card p-10 text-white">
        <!-- Logo -->
        <div class="mb-6">
            <div class="logo-container">
                <img src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png"
                    alt="Maharashtra Tourism Logo" class="w-24 h-auto">
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold mb-2">Maharashtra Tourism</h1>
        <h2 class="text-xl font-medium mb-6 text-orange-200">Page Not Found</h2>

        <!-- 404 Visual -->
        <div class="text-8xl font-bold text-orange-400 mb-6 drop-shadow-lg">
            404
        </div>

        <!-- Message -->
        <p class="text-gray-100 mb-8 max-w-sm mx-auto">
            Oops! The page you are looking for might have been removed, had its name changed, or is temporarily
            unavailable.
        </p>

        <!-- Home Button -->
        <a href="{{ url('/') }}"
            class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-6 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-transparent shadow-lg transform hover:scale-105">
            <i class="fas fa-home"></i>
            Go to Homepage
        </a>
    </div>
</body>

</html>