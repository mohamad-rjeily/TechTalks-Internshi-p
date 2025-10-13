<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MedShare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Dark/Purple Admin Theme */
        body {
            /* Background Gradient: Darker, more serious administrative purple */
            background: linear-gradient(135deg, #1e3a8a 0%, #4c1d95 100%); /* Indigo to Deep Purple */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif; /* Using a clean, modern font */
            color: #f3f4f6;
        }
        .login-container {
            max-width: 420px;
            width: 100%;
            padding: 20px;
        }
        .login-card {
            /* Card Style: Slightly translucent dark card */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            overflow: hidden;
        }
        .login-header {
            /* Header Style: Bright, professional gradient strip */
            background: linear-gradient(90deg, #8b5cf6 0%, #a78bfa 100%);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 3px solid #6366f1;
        }
        .card-body {
            padding: 30px;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #f3f4f6;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: #a78bfa;
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(167, 139, 250, 0.4);
        }
        .form-control::placeholder {
            color: #d1d5db; /* Light gray placeholder */
        }
        .input-group-text {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-right: none;
            color: #a78bfa; /* Icon color */
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            /* Button Gradient: Accent color */
            background: linear-gradient(90deg, #f97316 0%, #fb923c 100%); /* Orange accent */
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.6);
            color: white;
        }
        .text-muted {
            color: #a78bfa !important;
        }
        .text-danger {
            color: #f87171 !important;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield me-2"></i>
                MedShare Admin Panel
            </div>

            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    
                    <h5 class="text-center mb-4 text-white">Secure Login</h5>

                    <!-- Username Field -->
                    <div class="mb-3">
                        <label for="userName" class="form-label visually-hidden">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" 
                                   class="form-control @error('userName') is-invalid @enderror" 
                                   id="userName" 
                                   name="userName" 
                                   value="{{ old('userName') }}" 
                                   placeholder="Enter your Username"
                                   required 
                                   autofocus>
                        </div>
                        @error('userName')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="form-label visually-hidden">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-white" for="remember">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-lock me-1"></i>
                        For Administrators Only
                    </small>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('loginPage') }}" class="text-white text-decoration-none opacity-75 hover:opacity-100 transition duration-150">
                <i class="fas fa-arrow-left me-2"></i>Back to Main Site
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
