<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

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
            max-width: 500px;
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
                    <h1 class="h4 text-gray-900">Create an Account</h1>
                </div>

                <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <input type="text" 
            class="form-control @error('name') is-invalid @enderror"
            name="name" value="{{ old('name') }}" required autofocus
            placeholder="Full Name">
        @error('name')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <input type="email" 
            class="form-control @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}" required
            placeholder="Email Address">
        @error('email')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <input type="password" 
            class="form-control @error('password') is-invalid @enderror"
            name="password" required
            placeholder="Password">
        @error('password')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <input type="password" 
            class="form-control"
            name="password_confirmation" required
            placeholder="Confirm Password">
    </div>

    <!-- Pilihan Role -->
    <div class="form-group">
        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
            <option value="" disabled selected>Pilih Role</option>
            <option value="resident">Admin</option>
            <option value="petugas">Petugas</option>
            <option value="member">Member</option>
        </select>
        @error('role')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary btn-block">
        Register Account
    </button>
</form>


                <hr>

                <div class="text-center">
                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
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
