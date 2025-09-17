<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{
    // ========================= WEB =========================
    public function indexWeb()
    {
        $requests = RequestModel::with(['medicine', 'requester', 'donor'])->latest()->get();
        return view('requests.index', compact('requests'));
    }

    public function createWeb()
    {
        $medicines = Medicine::all();
        $users = User::all();
        return view('requests.create', compact('medicines', 'users'));
    }

    public function storeWeb(HttpRequest $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'requester_id' => 'required|exists:users,id',
            'donor_id' => 'nullable|exists:users,id',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_remaining' => 'required|integer|min:0',
            'message' => 'nullable|string',
            'status' => ['required', Rule::in(['pending','approved','rejected','cancelled'])],
        ]);

        RequestModel::create($validated);

        return redirect()->route('requests.index')->with('success','Request created successfully');
    }

    public function showWeb($id)
    {
        $request = RequestModel::with(['medicine', 'requester', 'donor'])->findOrFail($id);
        return view('requests.show', compact('request'));
    }

    public function editWeb($id)
    {
        $request = RequestModel::findOrFail($id);
        $medicines = Medicine::all();
        $users = User::all();
        return view('requests.edit', compact('request', 'medicines', 'users'));
    }

    public function updateWeb(HttpRequest $request, $id)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'requester_id' => 'required|exists:users,id',
            'donor_id' => 'nullable|exists:users,id',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_remaining' => 'required|integer|min:0',
            'message' => 'nullable|string',
            'status' => ['required', Rule::in(['pending','approved','rejected','cancelled'])],
        ]);

        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update($validated);

        return redirect()->route('requests.index')->with('success','Request updated successfully');
    }

    public function destroyWeb($id)
    {
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->delete();

        return redirect()->route('requests.index')->with('success','Request deleted successfully');
    }

    // ========================= API =========================
    public function indexApi()
    {
        $requests = RequestModel::with(['medicine', 'requester', 'donor'])->latest()->get();
        return response()->json($requests);
    }

    public function storeApi(HttpRequest $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'requester_id' => 'required|exists:users,id',
            'donor_id' => 'nullable|exists:users,id',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_remaining' => 'required|integer|min:0',
            'message' => 'nullable|string',
            'status' => ['required', Rule::in(['pending','approved','rejected','cancelled'])],
        ]);

        $requestModel = RequestModel::create($validated);

        return response()->json($requestModel, 201);
    }

    public function showApi($id)
    {
        $requestModel = RequestModel::with(['medicine', 'requester', 'donor'])->findOrFail($id);
        return response()->json($requestModel);
    }

    public function updateApi(HttpRequest $request, $id)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'requester_id' => 'required|exists:users,id',
            'donor_id' => 'nullable|exists:users,id',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_remaining' => 'required|integer|min:0',
            'message' => 'nullable|string',
            'status' => ['required', Rule::in(['pending','approved','rejected','cancelled'])],
        ]);

        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update($validated);

        return response()->json($requestModel);
    }

    public function destroyApi($id)
    {
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->delete();

        return response()->json(['message'=>'Request deleted successfully']);
    }
}
