<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // =============================
    // API METHODS
    // =============================

    // GET /api/medicines
    public function index()
    {
        return response()->json(Medicine::all(), 200);
    }

    // POST /api/medicines
    public function store(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|integer|exists:categories,id',
            'name'            => 'required|string|max:255',
            'brand'           => 'nullable|string|max:255',
            'form'            => 'nullable|string|max:50',
            'strength'        => 'nullable|string|max:50',
            'condition_notes' => 'nullable|string',
            'photo_path'      => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::create($request->all());

        return response()->json($medicine, 201);
    }

    // GET /api/medicines/{id}
    public function show($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        return response()->json($medicine, 200);
    }

    // PUT /api/medicines/{id}
    public function update(Request $request, $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $request->validate([
            'category_id'     => 'sometimes|integer|exists:categories,id',
            'name'            => 'sometimes|string|max:255',
            'brand'           => 'nullable|string|max:255',
            'form'            => 'nullable|string|max:50',
            'strength'        => 'nullable|string|max:50',
            'condition_notes' => 'nullable|string',
            'photo_path'      => 'nullable|string|max:255',
        ]);

        $medicine->update($request->all());

        return response()->json($medicine, 200);
    }

    // DELETE /api/medicines/{id}
    public function destroy($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $medicine->delete();

        return response()->json(['message' => 'Medicine deleted'], 200);
    }

    // =============================
    // WEB METHODS (Blade)
    // =============================

    public function indexWeb()
    {
        $medicines = Medicine::with('category')->get();
        return view('admin.layouts.medicines.index', compact('medicines'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.layouts.medicines.create', compact('categories'));
    }

    public function storeWeb(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|integer|exists:categories,id',
            'name'            => 'required|string|max:255',
            'brand'           => 'nullable|string|max:255',
            'form'            => 'nullable|string|max:255',
            'strength'        => 'nullable|string|max:255',
            'condition_notes' => 'nullable|string',
        ]);

        Medicine::create($request->all());

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine created successfully!');
    }

    public function showWeb($id)
    {
        $medicine = Medicine::with('category')->findOrFail($id);
        return view('admin.layouts.medicines.show', compact('medicine'));
    }

    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.layouts.medicines.edit', compact('medicine', 'categories'));
    }

    public function updateWeb(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'category_id'     => 'required|integer|exists:categories,id',
            'name'            => 'required|string|max:255',
            'brand'           => 'nullable|string|max:255',
            'form'            => 'nullable|string|max:255',
            'strength'        => 'nullable|string|max:255',
            'condition_notes' => 'nullable|string',
        ]);

        $medicine->update($request->all());

        return redirect()->route('admin.medicines.show', $medicine->id)->with('success', 'Medicine updated successfully!');
    }

    public function destroyWeb($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted successfully!');
    }
}
