<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // =============================
    // API METHODS (JSON responses)
    // =============================

    /**
     * Get all users (API)
     */
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * Create a new user (API)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'location' => $request->location,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Show a single user by id (API)
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Update an existing user (API)
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'phone'    => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name'     => $request->name ?? $user->name,
            'email'    => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'phone'    => $request->phone ?? $user->phone,
            'location' => $request->location ?? $user->location,
        ]);

        return response()->json($user, 200);
    }

    /**
     * Delete a user (API)
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }

    // ===============================
    // WEB METHODS (Blade responses)
    // ===============================

    /**
     * Display users list page (Web)
     */
    public function indexWeb()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show create user form (Web)
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store user from web form (Web)
     */
    public function storeWeb(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'location' => $request->location,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Show single user page (Web)
     */
    public function showWeb($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show edit user form (Web)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update user from web form (Web)
     */
    public function updateWeb(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone'    => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'phone'    => $request->phone,
            'location' => $request->location,
        ]);

        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully!');
    }

    /**
     * Delete user from web (Web)
     */
    public function destroyWeb($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}