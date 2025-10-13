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
    // ===================== ADMIN AUTHENTICATION =====================

    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // If admin is already logged in, redirect to dashboard
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find admin by username
        $admin = Admin::where('userName', $request->userName)->first();

        // Check if admin exists and password is correct
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Store admin info in session
            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->userName,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $admin->userName . '!');
        }

        return back()->withErrors([
            'userName' => 'Invalid username or password.',
        ])->withInput($request->only('userName'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        // Remove admin session data
        session()->forget(['admin_id', 'admin_username']);

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }

    // ===================== ADMIN DASHBOARD =====================
    public function dashboard()
    {
        // Defensive calculation of summary statistics
        try {
            $totalMedicines = Medicine::count();
            $pendingRequests = MedicineRequest::where('status', 'pending')->count();
            $activeUsers = User::count();
            $openReports = Report::where('status', 'open')->count();
        } catch (\Exception $e) {
            // Log the error if necessary, but default to 0 for display
            $totalMedicines = $pendingRequests = $activeUsers = $openReports = 0;
        }

        // Fetch recent audit activity
        try {
            $recentActivity = AuditLog::with('actor')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($log) {
                    return [
                        'action_type' => $log->action_type,
                        'details'     => $log->detail,
                        // Assumes 'actor' relationship exists on AuditLog and links to a User or Admin model with a 'name'
                        'actor_name'  => $log->actor ? $log->actor->name : 'System',
                        'created_at'  => $log->created_at,
                    ];
                });
        } catch (\Exception $e) {
            $recentActivity = collect([]);
        }

        // Fetch urgent requests (e.g., pending requests made in the last day)
        try {
            $urgentRequests = MedicineRequest::with(['requester', 'medicine'])
                ->where('created_at', '>=', now()->subDays(2)) // Adjusted to last 2 days for more data
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($request) {
                    return [
                        'user_name'     => $request->requester ? $request->requester->name : 'Unknown',
                        'medicine_name' => $request->medicine ? $request->medicine->name : 'Unknown',
                        'urgency'       => $request->created_at->diffForHumans() . ' ago',
                        'id'            => $request->id,
                    ];
                });
        } catch (\Exception $e) {
            $urgentRequests = collect([]);
        }

        // Prepare monthly data for charts (last 12 months)
        $monthlyDonations = [];
        $monthlyRequests = [];

        try {
            // Iterate over the last 12 months
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
            $monthlyDonations = $monthlyRequests = array_fill(0, 12, 0);
        }

        return view('admin.dashboard', compact(
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

    // ===================== ADMIN CRUD (WEB) =====================
    /**
     * Display a listing of the Admin users (Web).
     */
    public function index()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new Admin (Web).
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created Admin in storage (Web).
     */
    public function store(Request $request)
    {
        $request->validate([
            'userName' => 'required|unique:admins,userName',
            'password' => 'required|min:6',
        ]);

        Admin::create([
            'userName' => $request->userName,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'New admin created successfully!');
    }

    /**
     * Show the form for editing the specified Admin (Web).
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified Admin in storage (Web).
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'userName' => 'required|unique:admins,userName,' . $admin->id,
            'password' => 'nullable|min:6',
        ]);

        $admin->userName = $request->userName;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user ' . $admin->userName . ' updated successfully!');
    }

    /**
     * Remove the specified Admin from storage (Web).
     * Added critical self-deletion and last-admin checks.
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $currentAdminId = session('admin_id');

        // Check 1: Prevent self-deletion
        if ($admin->id == $currentAdminId) {
            return back()->with('error', 'You cannot delete your own admin account while logged in.');
        }

        // Check 2: Prevent deleting the last remaining admin
        if (Admin::count() <= 1) {
            return back()->with('error', 'Cannot delete the last remaining admin account. Please create another admin first.');
        }

        $userName = $admin->userName;
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user ' . $userName . ' deleted successfully.');
    }

    // ===================== API METHODS =====================
    public function indexApi()
    {
        return response()->json(Admin::all(), 200);
    }

    public function showApi($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        return response()->json($admin, 200);
    }

    public function storeApi(Request $request)
    {
        $request->validate([
            'userName' => 'required|unique:admins,userName',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::create([
            'userName' => $request->userName,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($admin, 201);
    }

    public function updateApi(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $request->validate([
            'userName' => 'sometimes|unique:admins,userName,' . $admin->id,
            'password' => 'sometimes|min:6',
        ]);

        if ($request->has('userName')) $admin->userName = $request->userName;
        if ($request->has('password')) $admin->password = Hash::make($request->password);

        $admin->save();

        return response()->json($admin, 200);
    }

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
