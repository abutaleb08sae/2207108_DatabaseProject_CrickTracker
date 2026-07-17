<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CrickTracker KUET</title>
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .auth-card { 
            border: none; 
            border-radius: 16px; 
            background: #fff; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 420px; 
            padding: 35px; 
        }
        .auth-brand { 
            text-align: center; 
            font-weight: 700; 
            letter-spacing: 1px; 
            color: #1e293b; 
            margin-bottom: 25px; 
        }
        .btn-auth { 
            background: linear-gradient(135deg, #1e3a8a, #3b82f6); 
            border: none; 
            color: white; 
            font-weight: 600; 
            padding: 12px; 
            border-radius: 8px; 
            transition: all 0.3s; 
        }
        .btn-auth:hover { 
            background: linear-gradient(135deg, #1d4ed8, #2563eb); 
            color: white; 
            transform: translateY(-1px); 
        }
        .form-control:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25); 
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-brand fs-3 text-uppercase">
            <i class="fa-solid fa-chart-line text-primary me-2"></i>CrickTracker
            <div class="fs-6 text-muted fw-normal mt-1">KUET Sports Portal Login</div>
        </div>

        <!-- Laravel Session Status / Errors -->
        @if (session('status'))
            <div class="alert alert-success small py-2" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger small py-2" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold text-secondary">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="name@kuet.ac.bd">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label small fw-bold text-secondary mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none text-primary">Forgot?</a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-4 form-check">
                <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                <label class="form-check-label small text-muted" for="remember_me">Keep me logged in</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-auth w-100 mb-3">
                Sign In <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>

            <!-- Registration Link -->
            <div class="text-center small text-muted">
                Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Register here</a>
            </div>
        </form>
    </div>

</body>
</html>