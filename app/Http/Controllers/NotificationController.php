<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    // ========================= WEB =========================
    public function indexWeb()
    {
        $notifications = Notification::with('user')->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    public function createWeb()
    {
        return view('notifications.create');
    }

    public function storeWeb(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => ['required', Rule::in(['expire_reminder','request_received','request_status'])],
            'payload' => 'required|array',
        ]);

        Notification::create($validated);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully');
    }

    public function showWeb($id)
    {
        $notification = Notification::findOrFail($id);
        return view('notifications.show', compact('notification'));
    }

    public function editWeb($id)
    {
        $notification = Notification::findOrFail($id);
        return view('notifications.edit', compact('notification'));
    }

    public function updateWeb(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => ['required', Rule::in(['expire_reminder','request_received','request_status'])],
            'payload' => 'required|array',
            'read_at' => 'nullable|date',
        ]);

        $notification = Notification::findOrFail($id);
        $notification->update($validated);

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully');
    }

    public function destroyWeb($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully');
    }

    public function markAsReadWeb($id)
{
    $notification = Notification::findOrFail($id);
    if (!$notification->read_at) {
        $notification->read_at = now();
        $notification->save();
    }

    return response()->json([
        'status' => 'success',
        'read_at' => $notification->read_at
    ]);

}


    // ========================= API =========================
    public function indexApi($userId)
    {
        $notifications = Notification::where('user_id', $userId)->get();
        return response()->json($notifications);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => ['required', Rule::in(['expire_reminder','request_received','request_status'])],
            'payload' => 'required|array',
        ]);

        $validated['payload'] = json_encode($validated['payload']);

        $notification = Notification::create($validated);
        $notification->payload = json_decode($notification->payload, true);

        return response()->json($notification, 201);
    }

    public function showApi($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->payload = json_decode($notification->payload, true);
        return response()->json($notification);
    }

    public function updateApi(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => ['required', Rule::in(['expire_reminder','request_received','request_status'])],
            'payload' => 'required|array',
            'read_at' => 'nullable|date',
        ]);

        $notification = Notification::findOrFail($id);

        $validated['payload'] = json_encode($validated['payload']);
        $notification->update($validated);

        $notification->payload = json_decode($notification->payload, true);
        return response()->json($notification);
    }

    public function markAsReadApi($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();

        $notification->payload = json_decode($notification->payload, true);
        return response()->json($notification);
    }

    public function destroyApi($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
