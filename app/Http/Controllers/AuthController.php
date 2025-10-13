<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Page d'inscription
    public function registerPage()
    {
        return view('auth.register');
    }

    // Page de login
    public function loginPage()
    {
        return view('auth.login');
    }

    // Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:50'],
            'email' => ['required','string','email','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'phone' => ['required','string','max:30'],
            'location' => ['required','string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'location' => $request->location,
        ]);

        Auth::login($user);
        event(new Registered($user));

        return redirect()->route('verification.notice');
    }

    // Page de vérification d'email
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    // Vérification de l'email
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('dashboard'); // Redirection vers DashboardController
    }

    // Renvoi du mail de vérification
    public function verifyHandler(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }

    // Login
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::attempt($fields))
        {
            // Redirection vers DashboardController pour calcul des stats
            return redirect()->route('dashboard'); 
        }

        return redirect()->back()->with('error','Wrong Email or Password');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginPage');
    }
}
