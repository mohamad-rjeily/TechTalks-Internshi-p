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

        return redirect()->route('reports.index')->with('success','Report created successfully');
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

        return redirect()->route('reports.index')->with('success','Report updated successfully');
    }

    public function destroyWeb($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('reports.index')->with('success','Report deleted successfully');
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
