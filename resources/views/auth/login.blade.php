<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dashboard System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3a8a;
            /* Biru navy */
            --primary-dark: #1e40af;
            /* Biru gelap */
            --primary-light: #3b82f6;
            /* Biru terang */
            --secondary-color: #60a5fa;
            /* Biru sekunder */
            --accent-color: #0ea5e9;
            /* Aksen biru muda */
            --background-color: #f0f9ff;
            /* Latar belakang biru muda */
            --text-color: #0f172a;
            /* Teks biru tua */
            --text-light: #ffffff;
            /* Teks putih */
            --text-muted: #64748b;
            /* Teks abu biru */
            --border-radius: 12px;
            --box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: var(--background-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: var(--border-radius);
            overflow: hidden;
            display: flex;
            box-shadow: var(--box-shadow);
            position: relative;
            min-height: 550px;
        }

        /* Left side - Welcome area */
        .welcome-area {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: clamp(30px, 5vw, 50px);
            color: var(--text-light);
            position: relative;
            overflow: hidden;
        }

        .welcome-area h1 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .welcome-area h2 {
            font-size: clamp(1rem, 2vw, 1.2rem);
            font-weight: 600;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .welcome-area p {
            font-size: clamp(0.9rem, 1.5vw, 0.95rem);
            opacity: 0.8;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .decoration-circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .decoration-circle-1 {
            width: 300px;
            height: 300px;
            bottom: -100px;
            left: -80px;
        }

        .decoration-circle-2 {
            width: 150px;
            height: 150px;
            top: -30px;
            right: -50px;
        }

        /* Right side - Login Form */
        .login-area {
            width: 55%;
            padding: clamp(30px, 5vw, 50px);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: clamp(0.85rem, 1.5vw, 0.95rem);
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: clamp(12px, 2vw, 15px) clamp(12px, 2vw, 15px) clamp(12px, 2vw, 15px) 45px;
            font-size: clamp(14px, 1.5vw, 15px);
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 111, 92, 0.1);
            background-color: #fff;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 14px;
            background: none;
            border: none;
            padding: 0;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            justify-content: space-between;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            accent-color: var(--primary-color);
        }

        .form-check-label {
            font-size: clamp(12px, 1.5vw, 14px);
            color: var(--text-muted);
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: clamp(12px, 2vw, 15px);
            font-size: clamp(14px, 1.5vw, 16px);
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            width: 100%;
        }

        .btn-primary {
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline {
            color: var(--text-color);
            background-color: transparent;
            border-color: #e1e1e1;
            margin-top: 15px;
        }

        .btn-outline:hover {
            background-color: #f5f5f5;
        }

        /* Responsive Design - Enhanced */
        @media (max-width: 900px) {
            .login-container {
                max-width: 600px;
                flex-direction: column;
                min-height: auto;
            }

            .welcome-area,
            .login-area {
                width: 100%;
            }

            .welcome-area {
                padding-bottom: 60px;
                min-height: 300px;
            }

            .login-area {
                padding-top: 40px;
                padding-bottom: 40px;
            }

            .decoration-circle-1 {
                width: 200px;
                height: 200px;
                bottom: -70px;
                left: -40px;
            }

            .decoration-circle-2 {
                width: 100px;
                height: 100px;
                top: -20px;
                right: -20px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .login-container {
                margin: 10px;
                border-radius: var(--border-radius);
            }

            .welcome-area {
                min-height: 250px;
                padding: 25px;
            }

            .login-area {
                padding: 25px;
            }

            .welcome-area h1 {
                margin-bottom: 10px;
            }

            .welcome-area h2 {
                margin-bottom: 15px;
            }

            .welcome-area p {
                margin-bottom: 15px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .btn {
                padding: 12px;
            }
        }

        @media (max-width: 400px) {
            .welcome-area {
                min-height: 220px;
                padding: 20px;
            }

            .login-area {
                padding: 20px;
            }

            .login-header {
                margin-bottom: 20px;
            }

            .decoration-circle-1 {
                width: 150px;
                height: 150px;
            }

            .decoration-circle-2 {
                width: 80px;
                height: 80px;
            }
        }

        /* Fix for very small devices */
        @media (max-height: 700px) and (max-width: 500px) {
            body {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .welcome-area {
                min-height: 180px;
                padding: 20px;
            }

            .welcome-area p:last-of-type {
                display: none;
                /* Hide the second paragraph on very small screens */
            }
        }

        /* Touch device optimizations */
        @media (hover: none) {

            .form-control,
            .btn {
                font-size: 16px;
                /* Prevent zoom on input on iOS */
            }

            .form-control {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .btn {
                padding: 14px;
            }

            /* Increase touch target sizes */
            .password-toggle {
                padding: 10px;
                right: 5px;
            }

            .form-check-input {
                width: 18px;
                height: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Side - Welcome Area -->
        <div class="welcome-area">
            <h1>SELAMAT DATANG</h1>
            <h2>Kasir Apotek Berkah Ibu</h2>
            <p>Kelola transaksi apotek Anda dengan mudah, cepat, dan aman. Sistem kami dirancang khusus untuk mendukung
                operasional Apotek Berkah Ibu.</p>
            <p>Login untuk mulai melayani pelanggan dan memastikan setiap transaksi tercatat dengan rapi dan efisien.
            </p>

            <!-- Decorative Elements -->
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-area">
            <div class="login-header">
                <h2>Masuk</h2>
                <p>Masukan email dan password di bawah ini</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="login-form">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @csrf

                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input id="email" type="email" class="form-control" name="email" placeholder="Email"
                        required autocomplete="email" autofocus>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password"
                        required autocomplete="current-password">
                    <button type="button" class="password-toggle" id="password-toggle">SHOW</button>
                </div>

                <div class="form-check">
                    <div class="remember-me">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingatkan saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Masuk
                </button>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');

            // Toggle password visibility
            passwordToggle.addEventListener('click', function() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    passwordToggle.textContent = 'HIDE';
                } else {
                    passwordField.type = 'password';
                    passwordToggle.textContent = 'SHOW';
                }
            });

            // Form submission
            const form = document.getElementById('login-form');
            form.addEventListener('submit', function(e) {
                // Add validation logic here if needed

                // Example of simple validation (can be expanded)
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                if (!email || !password) {
                    e.preventDefault();
                    alert('Harap isi semua field yang diperlukan');
                }
            });

            // Apply iOS specific fixes
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            if (isIOS) {
                document.querySelectorAll('input, select, textarea').forEach(function(el) {
                    el.style.fontSize = '16px'; // Prevents zoom on focus
                });
            }
        });
    </script>
</body>

</html>
