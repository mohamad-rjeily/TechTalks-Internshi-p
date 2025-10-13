<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    // ===================== API =====================
    
    public function indexApi()
    {
        return response()->json(AuditLog::with(['actor', 'target'])->get(), 200);
    }

    public function storeApi(Request $request)
    {
        $request->validate([
            'actor_id' => 'required|exists:users,id',
            'action_type' => 'required|string|max:100',
            'target_type' => 'required|string|max:100',
            'target_id' => 'nullable|exists:users,id',
            'detail' => 'nullable|array',
        ]);

        $log = AuditLog::create($request->all());
        return response()->json($log, 201);
    }

    public function showApi($id)
    {
        $log = AuditLog::with(['actor', 'target'])->find($id);
        if (!$log) return response()->json(['message' => 'Audit log not found'], 404);
        return response()->json($log, 200);
    }

    public function updateApi(Request $request, $id)
    {
        $log = AuditLog::find($id);
        if (!$log) return response()->json(['message' => 'Audit log not found'], 404);

        $request->validate([
            'actor_id' => 'nullable|exists:users,id',
            'action_type' => 'nullable|string|max:100',
            'target_type' => 'nullable|string|max:100',
            'target_id' => 'nullable|exists:users,id',
            'detail' => 'nullable|array',
        ]);

        $log->update($request->all());
        return response()->json($log, 200);
    }

    public function destroyApi($id)
    {
        $log = AuditLog::find($id);
        if (!$log) return response()->json(['message' => 'Audit log not found'], 404);

        $log->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    // ===================== WEB =====================

    /**
     * Display a listing of the audit logs for the web interface.
     */
    public function indexWeb(Request $request): View
    {
        // Fetch all logs to get total counts before pagination
        $allLogs = AuditLog::get();

        // Calculate statistics counts
        $totalLogs = $allLogs->count();
        $todayLogs = $allLogs->where('created_at', '>=', now()->startOfDay())->count();
        $weekLogs = $allLogs->where('created_at', '>=', now()->startOfWeek())->count();
        $criticalLogs = $allLogs->whereIn('action_type', ['deleted', 'banned', 'suspended'])->count();

        // Start a base query for the logs table
        $query = AuditLog::with('actor');

        // Apply action type filter
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        // Apply target type filter
        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        // Apply date from filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Apply date to filter
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply search filter (searches in actor name and action/target types)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('actor', function($actorQuery) use ($searchTerm) {
                    $actorQuery->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('action_type', 'like', '%' . $searchTerm . '%')
                ->orWhere('target_type', 'like', '%' . $searchTerm . '%')
                ->orWhere('target_id', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply ordering and pagination to the filtered query
        $logs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Pass all variables to the view
        return view('admin.audit-logs.index', compact(
            'logs', 
            'totalLogs', 
            'todayLogs', 
            'weekLogs', 
            'criticalLogs'
        ));
    }

    public function createWeb()
    {
        return view('audit_logs.create');
    }

    public function storeWeb(Request $request)
    {
        $request->validate([
            'actor_id' => 'required|exists:users,id',
            'action_type' => 'required|string|max:100',
            'target_type' => 'required|string|max:100',
            'target_id' => 'nullable|exists:users,id',
            'detail' => 'nullable|array',
        ]);

        AuditLog::create($request->all());
        return redirect()->route('audit_logs.index')->with('success', 'Audit log created.');
    }

    public function showWeb($id)
    {
        $log = AuditLog::with(['actor', 'target'])->findOrFail($id);
        return view('audit_logs.show', compact('log'));
    }

    public function editWeb($id)
    {
        $log = AuditLog::findOrFail($id);
        return view('audit_logs.edit', compact('log'));
    }

    public function updateWeb(Request $request, $id)
    {
        $log = AuditLog::findOrFail($id);

        $request->validate([
            'actor_id' => 'nullable|exists:users,id',
            'action_type' => 'nullable|string|max:100',
            'target_type' => 'nullable|string|max:100',
            'target_id' => 'nullable|exists:users,id',
            'detail' => 'nullable|array',
        ]);

        $log->update($request->all());
        return redirect()->route('audit_logs.index')->with('success', 'Audit log updated.');
    }

    public function destroyWeb($id)
    {
        $log = AuditLog::findOrFail($id);
        $log->delete();
        return redirect()->route('audit_logs.index')->with('success', 'Audit log deleted.');
    }
}