<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Web: list all admins
    public function index()
    {
        $admins = Admin::all();
        return view('admin.index', compact('admins'));
    }

    // Web: show create form
    public function create()
    {
        return view('admin.create');
    }

    // Web: store new admin
    public function store(Request $request)
    {
        $request->validate([
            'userName' => 'required|unique:admin,userName',
            'password' => 'required|min:6',
        ]);

        Admin::create([
            'userName' => $request->userName,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admins.index');
    }

    // Web: show edit form
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    // Web: update admin
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'userName' => 'required|unique:admin,userName,' . $admin->id,
            'password' => 'nullable|min:6',
        ]);

        $admin->userName = $request->userName;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('admins.index');
    }

    // Web: delete admin
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index');
    }

    // API: GET all
    public function indexApi()
    {
        return response()->json(Admin::all(), 200);
    }

    // API: GET one
    public function showApi($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        return response()->json($admin, 200);
    }

    // API: POST create
    public function storeApi(Request $request)
    {
        $request->validate([
            'userName' => 'required|unique:admin,userName',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::create([
            'userName' => $request->userName,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($admin, 201);
    }

    // API: PUT/PATCH update
    public function updateApi(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $request->validate([
            'userName' => 'sometimes|unique:admin,userName,' . $admin->id,
            'password' => 'sometimes|min:6',
        ]);

        if ($request->has('userName')) $admin->userName = $request->userName;
        if ($request->has('password')) $admin->password = Hash::make($request->password);

        $admin->save();

        return response()->json($admin, 200);
    }

    // API: DELETE
    public function destroyApi($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $admin->delete();
        return response()->json(['message' => 'Admin deleted'], 200);
    }
}
