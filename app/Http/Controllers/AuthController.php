<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
class AuthController extends Controller
{
    public function registerPage()
    {
        return view('auth.register');
    }
    public function loginPage()
    {
        return view('auth.login');
    }
    public function register(Request $request)
    {
       $fields =  $request->validate([
            'name' => ['required','string','max:50'],
            'email' => ['required','string','email','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'phone' => ['required','string','max:30'],
            'location' => ['required','string'],
        ]);
        $user = User::create($fields);
        Auth::login($user);
        event(new Registered($user));
        return redirect()->route('verification.notice');

    }
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }


    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('home');
    }


    public function verifyHandler(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        if(Auth::attempt($fields))
        {
            return redirect()->route('home');
        }
        return redirect()->back()->with('error','Wrong Email or Password');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginPage');
    }
    public function adminDashboard()
    {
        return view('admindashboard');
    }
}
