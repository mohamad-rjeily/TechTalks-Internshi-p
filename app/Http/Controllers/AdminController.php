<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Category;
use App\Models\Report;
use App\Models\AuditLog;
use App\Models\Request as MedicineRequest;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ===================== ADMIN DASHBOARD =====================
    
    public function dashboard()
    {
        // Get statistics for the dashboard with error handling
        try {
            $totalMedicines = Medicine::count();
            $pendingRequests = MedicineRequest::where('status', 'pending')->count();
            $activeUsers = User::count(); // Remove status filter since User model doesn't have status field
            $openReports = Report::where('status', 'open')->count();
        } catch (\Exception $e) {
            // If there's any database error, use default values
            $totalMedicines = 0;
            $pendingRequests = 0;
            $activeUsers = 0;
            $openReports = 0;
        }

        // Get recent activity from audit logs
        try {
            $recentActivity = AuditLog::with('actor')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($log) {
                    return [
                        'action_type' => $log->action_type,
                        'details' => $log->detail, // Note: your model uses 'detail' not 'details'
                        'actor_name' => $log->actor ? $log->actor->name : 'System',
                        'created_at' => $log->created_at,
                    ];
                });
        } catch (\Exception $e) {
            $recentActivity = collect([]);
        }

        // Get urgent requests (example: requests created in last 24 hours)
        try {
            $urgentRequests = MedicineRequest::with(['requester', 'medicine'])
                ->where('created_at', '>=', now()->subDay())
                ->where('status', 'pending')
                ->take(5)
                ->get()
                ->map(function ($request) {
                    return [
                        'user_name' => $request->requester ? $request->requester->name : 'Unknown',
                        'medicine_name' => $request->medicine ? $request->medicine->name : 'Unknown',
                        'urgency' => 'within 2 days',
                        'id' => $request->id,
                    ];
                });
        } catch (\Exception $e) {
            $urgentRequests = collect([]);
        }

        // Monthly statistics for chart
        $monthlyDonations = [];
        $monthlyRequests = [];
        
        try {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthlyDonations[] = Donation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $monthlyRequests[] = MedicineRequest::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        } catch (\Exception $e) {
            // If there's any database error, use default values
            $monthlyDonations = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $monthlyRequests = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        }

        // Debug: Ensure variables are arrays
        $monthlyDonations = is_array($monthlyDonations) ? $monthlyDonations : [];
        $monthlyRequests = is_array($monthlyRequests) ? $monthlyRequests : [];

        return view('admin.layouts.dashboard', compact(
            'totalMedicines',
            'pendingRequests', 
            'activeUsers',
            'openReports',
            'recentActivity',
            'urgentRequests',
            'monthlyDonations',
            'monthlyRequests'
        ));
    }

    // ===================== ADMIN CRUD =====================

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

    // ===================== API METHODS =====================

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