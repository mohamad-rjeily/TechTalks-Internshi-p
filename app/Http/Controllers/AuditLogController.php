<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

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

    public function indexWeb()
    {
        $logs = AuditLog::with(['actor', 'target'])->paginate(15);
        return view('audit_logs.index', compact('logs'));
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
