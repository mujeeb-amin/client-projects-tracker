<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Authentication') - Client Projects Tracker
    </title>

    {{-- Studio CSS from resources --}}
    @vite([
        'resources/css/studio/vendor.min.css',
        'resources/css/studio/app.min.css',
        'resources/css/studio/custom.css'
    ])

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body {
            background: #f2f3f5;
        }


        #app {
            width: 100%;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        .login,
        .register {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
            height: 100dvh;
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .login-content,
        .register-content {
            width: 100%;
            max-width: 450px;
            padding: 35px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .form-control {
            border-radius: 8px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(52, 143, 226, 0.15);
        }

        .btn-theme {
            border-radius: 8px;
        }

        @media (max-width: 576px) {
            .login,
            .register {
                padding: 15px;
            }

            .login-content,
            .register-content {
                padding: 25px 20px;
            }

            h1 {
                font-size: 26px;
            }
        }
        /* Move Login and Register Forms Up */
@media (min-width: 768px) {
    .login-content,
    .register-content {
        transform: translateY(-30px);
    }
}
    </style>

    @stack('styles')

</head>

<body>

    <div id="app" class="app app-without-header">
        @yield('content')
    </div>

    @vite([
        'resources/js/studio/vendor.min.js',
        'resources/js/studio/app.min.js'
    ])

    @stack('scripts')

</body>
 
</html>