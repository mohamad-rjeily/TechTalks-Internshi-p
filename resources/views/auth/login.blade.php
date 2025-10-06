@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success text-white text-center rounded-top-4 py-3">
                    <h4 class="mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-circle-fill text-white me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                        Login
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                            @error('email')
                                <div class="alert alert-danger mt-2" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="alert alert-danger mt-2" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Login</button>
                        </div>
                        
                        @if(session('error'))
                            <div class="alert alert-danger mt-3" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if(session('delete_account'))
                            <div class="alert alert-success mt-3" role="alert">
                                {{ session('delete_account') }}
                            </div>
                        @endif
                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-success">Forgot Your Password?</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light border-0 rounded-bottom-4 py-3">
                    <small class="text-muted">Don't have an account? <a href="{{ route('registerPage') }}" class="text-decoration-none text-success fw-bold">Sign Up Here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #e9f7ef;
    }
</style>
@endsection