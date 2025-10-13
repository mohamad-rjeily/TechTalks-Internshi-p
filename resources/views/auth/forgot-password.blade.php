@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4>Reset Password</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf
                        <p class="text-center">Enter your email address to receive a password reset link.</p>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" class="form-control"  name="email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Send Password Reset Link</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small><a href="{{ route('loginPage') }}" class="text-decoration-none">Back to Login Page</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection