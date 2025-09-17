<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    // -------- Web Methods --------

    public function index()
    {
        $donations = Donation::with(['medicine', 'donor', 'recipient'])->get();
        return view('donations.index', compact('donations'));
    }

    public function create()
    {
        return view('donations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'donor_id' => 'required|exists:users,id',
            'recipient_id' => 'nullable|exists:users,id',
            'quantity' => 'required|integer',
            'status' => 'required|in:available,unavailable',
            'expiry_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $donation = Donation::create([
            'medicine_id'   => $request->medicine_id,
            'donor_id'      => $request->donor_id,
            'recipient_id'  => $request->recipient_id,
            'quantity'      => $request->quantity,
            'status'        => $request->status,
            'expiry_date'   => $request->expiry_date,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('donations.index')->with('success', 'Donation created successfully!');
    }

    public function edit(Donation $donation)
    {
        return view('donations.edit', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
    {
        $request->validate([
            'medicine_id' => 'sometimes|exists:medicines,id',
            'donor_id' => 'sometimes|exists:users,id',
            'recipient_id' => 'sometimes|exists:users,id',
            'quantity' => 'sometimes|integer',
            'status' => 'sometimes|in:available,unavailable',
            'expiry_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        $donation->update($request->only([
            'medicine_id', 'donor_id', 'recipient_id', 'quantity', 'status', 'expiry_date', 'notes'
        ]));

        return redirect()->route('donations.index')->with('success', 'Donation updated successfully!');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();
        return redirect()->route('donations.index')->with('success', 'Donation deleted successfully!');
    }

    // -------- API Methods --------

    public function apiIndex()
    {
        return response()->json(Donation::with(['medicine', 'donor', 'recipient'])->get(), 200);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'donor_id' => 'required|exists:users,id',
            'recipient_id' => 'nullable|exists:users,id',
            'quantity' => 'required|integer',
            'status' => 'required|in:available,unavailable',
            'expiry_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $donation = Donation::create([
            'medicine_id'   => $request->medicine_id,
            'donor_id'      => $request->donor_id,
            'recipient_id'  => $request->recipient_id,
            'quantity'      => $request->quantity,
            'status'        => $request->status,
            'expiry_date'   => $request->expiry_date,
            'notes'         => $request->notes,
        ]);

        return response()->json($donation, 201);
    }

    public function apiShow($id)
    {
        $donation = Donation::with(['medicine', 'donor', 'recipient'])->findOrFail($id);
        return response()->json($donation, 200);
    }

    public function apiUpdate(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        $request->validate([
            'medicine_id' => 'sometimes|exists:medicines,id',
            'donor_id' => 'sometimes|exists:users,id',
            'recipient_id' => 'sometimes|exists:users,id',
            'quantity' => 'sometimes|integer',
            'status' => 'sometimes|in:available,unavailable',
            'expiry_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        $donation->update($request->only([
            'medicine_id', 'donor_id', 'recipient_id', 'quantity', 'status', 'expiry_date', 'notes'
        ]));

        return response()->json($donation, 200);
    }

    public function apiDestroy($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->delete();
        return response()->json(['message' => 'Donation deleted successfully'], 200);
    }
}
