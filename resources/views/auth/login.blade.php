@extends('layout.app')

@section('title', 'Login')

@section('content')

<!-- Main Container -->

<div class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
<div class="max-w-md w-full space-y-8">
<div class="bg-white shadow-2xl rounded-2xl overflow-hidden transform transition duration-300 hover:scale-[1.01] border border-gray-100">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center rounded-t-2xl py-6">
            <h2 class="text-3xl font-extrabold flex items-center justify-center tracking-tight">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="h-7 w-7 mr-3 text-indigo-200" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
                Welcome Back!
            </h2>
            <p class="text-indigo-200 text-sm mt-1">Sign in to access your MedShare account</p>
        </div>

        <!-- Form Body -->
        <div class="p-8 sm:p-10">
            <form class="mt-4 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="text-sm font-medium text-gray-700 block mb-1">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
                        placeholder="you@example.com"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="text-sm font-medium text-gray-700 block mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <div class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Session Messages (Success/Error) -->
                @if(session('error'))
                    <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg font-medium shadow-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('delete_account'))
                    <div class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg font-medium shadow-sm" role="alert">
                        {{ session('delete_account') }}
                    </div>
                @endif
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-700 transition duration-150 ease-in-out">
                            Forgot Password?
                        </a>
                    </div>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-lg font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out shadow-lg hover:shadow-xl transform hover:scale-[1.01]">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2V7a3 3 0 00-6 0v2h6z" clip-rule="evenodd" />
                        </svg>
                        Sign In
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer Link -->
        <div class="px-8 py-4 bg-gray-50 text-center rounded-b-2xl border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('registerPage') }}" class="font-bold text-indigo-600 hover:text-indigo-700 transition duration-150 ease-in-out">
                    Create an Account
                </a>
            </p>
        </div>
    </div>
</div>

</div>
@endsection