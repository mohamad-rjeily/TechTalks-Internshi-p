<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    // ========================= WEB =========================
    public function indexWeb()
    {
        $reports = Report::with('reporter')->latest()->get();
        return view('reports.index', compact('reports'));
    }

    public function createWeb()
    {
        $users = User::all();
        return view('reports.create', compact('users'));
    }

    public function storeWeb(Request $request)
    {
        $validated = $request->validate([
            'reported_id' => 'required|exists:users,id',
            'target_id' => 'required',
            'reason' => 'required|string',
            'status' => ['required', Rule::in(['open','resolved'])],
            'admin_note' => 'nullable|string',
        ]);

        Report::create($validated);

        return redirect()->route('admin.reports.index')->with('success','Report created successfully');
    }

    public function showWeb($id)
    {
        $report = Report::with('reporter')->findOrFail($id);
        return view('reports.show', compact('report'));
    }

    public function editWeb($id)
    {
        $report = Report::findOrFail($id);
        $users = User::all();
        return view('reports.edit', compact('report','users'));
    }

    /**
     * Handles full report editing (e.g., changing reason, target).
     */
    public function updateWeb(Request $request, $id)
    {
        $validated = $request->validate([
            'reported_id' => 'required|exists:users,id',
            'target_id' => 'required',
            'reason' => 'required|string',
            'status' => ['required', Rule::in(['open','resolved'])],
            'admin_note' => 'nullable|string',
        ]);

        $report = Report::findOrFail($id);
        $report->update($validated);

        return redirect()->route('admin.reports.index')->with('success','Report updated successfully');
    }

    /**
     * New dedicated method to resolve a report with minimal input.
     * This fixes the validation error from the "Resolve" modal form.
     */
    public function resolveWeb(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->update([
            'status' => 'resolved',
            'admin_notes' => $request->input('admin_note'), // Corrected to use 'admin_notes'
        ]);
    
        return redirect()->route('admin.reports.index')->with('success', 'Report resolved successfully.');
    }

    public function destroyWeb($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success','Report deleted successfully');
    }

    // ========================= API =========================
    public function indexApi()
    {
        $reports = Report::with('reporter')->latest()->get();
        return response()->json($reports);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'reported_id' => 'required|exists:users,id',
            'target_id' => 'required',
            'reason' => 'required|string',
            'status' => ['required', Rule::in(['open','resolved'])],
            'admin_note' => 'nullable|string',
        ]);

        $report = Report::create($validated);

        return response()->json($report, 201);
    }

    public function showApi($id)
    {
        $report = Report::with('reporter')->findOrFail($id);
        return response()->json($report);
    }

    public function updateApi(Request $request, $id)
    {
        // For API, we keep the original validation scope since API requests are usually full JSON payloads
        $validated = $request->validate([
            'reported_id' => 'required|exists:users,id',
            'target_id' => 'required',
            'reason' => 'required|string',
            'status' => ['required', Rule::in(['open','resolved'])],
            'admin_note' => 'nullable|string',
        ]);

        $report = Report::findOrFail($id);
        $report->update($validated);

        return response()->json($report);
    }

    public function destroyApi($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }
}
