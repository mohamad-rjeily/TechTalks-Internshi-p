<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileSettingsController extends Controller
{
public function profileSettings(Request $request)
{
    $user = Auth::user();
    $activeTab = $request->query('tab', 'profile'); 
    return view('profile.index', compact('user', 'activeTab'));
}
    public function updateProfileInformation(Request $request)
    {
        $user_id = Auth::id();
        $user = User::findOrFail($user_id);
        $request->validate([
            'name' => ['sometimes','string'],
            'email' => ['sometimes','email','unique:users,email,'.$user->id],
            'phone' => ['sometimes','string'],
            'location' => ['sometimes','string']
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location
        ]);
        return redirect()->route('profile_settings',['tab' => 'profile'])->with('update_profile_info','Profile updated successfully');
    }
    public function deleteUserAccount()
    {
        $user_id = Auth::id();
        $user = User::findOrFail($user_id);
        $user->delete();
        Auth::logout();
        return redirect()->route('loginPage')->with('delete_account','Your account has been deleted');
    }
    public function changePassword(Request $request)
    {
        $user_id = Auth::id();
        $user = User::findOrFail($user_id);
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required','string','min:8','confirmed']
        ]);
        if(!Hash::check($request->current_password,$user->password))
        {
            return redirect()->route('profile_settings',['tab' => 'password'])->with('current_password','The current password is wrong');
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->route('profile_settings',['tab' => 'password'])->with('password_changed','Password updated successfully');
    }

    public function updatePrivacySettings(Request $request)
{
    /** @var \App\Models\User $user */ //
    $user = Auth::user();

    $request->validate([
        // Note: La validation 'sometimes' n'est plus nécessaire car nous vérifions l'existence avec has()
        'profile_visibility' => ['required', 'string', 'in:public,private'],
    ]);

    // L'astuce est ici : on utilise $request->has() pour les booleans
    $user->update([
        'newsletter_opt_in' => $request->has('newsletter_opt_in'), // <-- Correction ici
        'profile_visibility' => $request->profile_visibility,
    ]);

    // Redirige vers la page des paramètres et active l'onglet "privacy"
    return redirect()->route('profile_settings', ['tab' => 'privacy'])->with('privacy_status', 'Privacy settings updated successfully.');
}
}
