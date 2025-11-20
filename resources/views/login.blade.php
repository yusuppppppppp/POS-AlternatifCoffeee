<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('{{ asset('images/login_bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
        }

        /* Overlay untuk kontras */
        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            top: 0;
            left: 0;
            z-index: 0;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
            max-height: 450px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -10px auto 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 90px;
            height: 90px;
            margin-top: -2px;
            object-fit: contain;
        }

        h2 {
            color: #2E4766;
            margin-bottom: 15px;
            font-size: 26px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #2E4766;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #adb5bd;
            font-size: 14px;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #274fc7c4, #1d3784cc);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: -20rem;
            left: 0;
            right: 0;
            z-index: 9999;
            display: flex;
            justify-content: center;
            transition: all 0.5s ease-in-out;
            padding: 15px 0;
            opacity: 0;
            visibility: hidden;
        }

        .notification.show {
            top: -7rem;
            opacity: 1;
            visibility: visible;
        }

        .notification-content {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 100%;
            max-width: 500px;
            z-index: 1;
            font-weight: 500;
        }

        .error-notification .notification-content {
            background-color: rgba(255, 255, 255, 0.25);
            color: #B91C1C;
            border-left: 4px solid rgb(161, 33, 33);
        }

        .notification svg {
            margin-right: 12px;
            flex-shrink: 0;
        }

        .notification span {
            font-size: 14px;
            line-height: 2;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 35px 25px;
                max-width: 320px;
            }

            .logo {
                width: 70px;
                height: 70px;
                margin-bottom: 20px;
            }

            .logo img {
                width: 60px;
                height: 60px;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            input[type="email"],
            input[type="password"] {
                padding: 11px 14px;
                font-size: 14px;
            }

            .login-btn {
                padding: 12px;
                font-size: 15px;
            }
        }

        /* Add this to your existing styles */
        .password-input-container {
            position: relative;
            width: 100%;
        }

        /* Update the password input styles to match the email input */
        #password {
            width: 100%;
            padding: 12px 40px 12px 16px;
            /* Add right padding for the eye icon */
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            -webkit-text-security: disc;
            /* For Safari */
        }

        /* Make sure the input doesn't change appearance when toggled */
        #password[type="text"] {
            -webkit-text-security: none;
            /* Show text when toggled */
        }

        #password:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Adjust the toggle button positioning */
        #togglePassword {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #adb5bd;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        #togglePassword:hover {
            color: #667eea;
        }

        #togglePassword:focus {
            outline: none;
        }

        /* Make sure the password container doesn't affect layout */
        .password-input-container {
            position: relative;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Overlay background -->
    <div class="overlay"></div>

    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>

        <h2>Login</h2>

        <form action="{{ route('logincheck') }}" method="POST">
            @if($errors->any())
                <div id="notification" class="notification error-notification">
                    <div class="notification-content">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-alert-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter Your Email Here"
                    value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input-container">
                    <input type="password" name="password" id="password" placeholder="Enter Your Password Here"
                        required>
                    <button type="button" id="togglePassword" aria-label="Toggle password visibility">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>

    <script>
        // Prevent back button access after logout
        window.history.forward();

        // Disable browser back button
        window.addEventListener('load', function () {
            window.history.pushState({ noBackExitsApp: true }, '');

            // Show notification if there are errors
            const notification = document.getElementById('notification');
            if (notification) {
                // Show notification after a short delay
                setTimeout(() => {
                    notification.classList.add('show');

                    // Auto-hide after 5 seconds
                    setTimeout(() => {
                        notification.classList.remove('show');

                        // Remove from DOM after animation completes
                        setTimeout(() => {
                            notification.remove();
                        }, 300);
                    }, 3000);
                }, 10);

                // Allow manual close
                notification.addEventListener('click', function () {
                    this.classList.remove('show');
                    setTimeout(() => this.remove(), 100);
                });
            }
        });

        window.addEventListener('popstate', function (event) {
            if (event.state && event.state.noBackExitsApp) {
                window.history.pushState({ noBackExitsApp: true }, '');
            }
        });

        // Clear any cached data
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            window.location.href = '{{ route("login") }}';
        }

        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the eye icon
                const eyeIcon = this.querySelector('svg');
                if (type === 'password') {
                    eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                } else {
                    eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                }
            });
        }
    </script>
</body>

</html>