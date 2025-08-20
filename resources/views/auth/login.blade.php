<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- Custom fonts -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            max-width: 400px;
            width: 100%;
            border-radius: 1rem;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900">Login Now!</h1>
                </div>

                <!-- Laravel login form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <input type="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autofocus
                            placeholder="Enter Email Address...">
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" 
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required placeholder="Password">
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>
                </form>

                <hr>

                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('register') }}">Create an Account!</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS dependencies -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

</body>
</html>
