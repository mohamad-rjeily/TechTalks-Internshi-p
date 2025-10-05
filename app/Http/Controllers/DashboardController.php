<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\User;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function indexWeb()
    {
        $user = Auth::user();
        if (!$user) {
            $user = (object)['id' => 1]; // temporary dummy user for testing
        }

        // --- Quick Stats ---
        $totalDonations = Donation::where('donor_id', $user->id)->count();
        $totalRequests = RequestModel::where('requester_id', $user->id)->count();

        // Fulfilled donations = donations that have been requested and taken
        $fulfilledDonations = Donation::where('donor_id', $user->id)
            ->where('status', 'unavailable')
            ->count();

        $totalDonationsNonZero = $totalDonations > 0 ? $totalDonations : 1;
        $trustScore = round(($fulfilledDonations / $totalDonationsNonZero) * 100);

        // --- 1. User’s available medicines (not expired) ---
        $userMedicines = Donation::where('donor_id', $user->id)
            ->where('status', 'available')
            ->whereDate('expiry_date', '>', Carbon::now())
            ->with('medicine')
            ->get();

        // --- 2. Expiry alerts ---
        $expiryAlerts30 = Donation::where('donor_id', $user->id)
            ->where('status', 'available')
            ->whereDate('expiry_date', '>', Carbon::now())
            ->whereDate('expiry_date', '<=', Carbon::now()->addDays(30))
            ->with('medicine')
            ->get();

        $expiryAlerts7 = Donation::where('donor_id', $user->id)
            ->where('status', 'available')
            ->whereDate('expiry_date', '>', Carbon::now())
            ->whereDate('expiry_date', '<=', Carbon::now()->addDays(7))
            ->with('medicine')
            ->get();

        // --- 3. Recent activity (donations & requests) ---
        $donationsActivity = Donation::where('donor_id', $user->id)
            ->with('medicine')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'type' => 'donation',
                    'medicine_name' => $donation->medicine->name ?? 'Unknown Medicine',
                    'quantity' => $donation->quantity,
                    'expiry_date' => $donation->expiry_date,
                    'created_at' => $donation->created_at,
                    'status' => $donation->status,
                ];
            });

        $requestsActivity = RequestModel::where('requester_id', $user->id)
            ->with('medicine')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'request',
                    'medicine_name' => $request->medicine->name ?? 'Unknown Medicine',
                    'quantity' => $request->quantity_remaining,
                    'created_at' => $request->created_at,
                    'status' => $request->status,
                ];
            });

        $recentActivity = $donationsActivity->merge($requestsActivity)
            ->sortByDesc('created_at')
            ->take(5);

        // --- 4. Open requests for medicines user can donate ---
        $availableMedicineIds = $userMedicines->pluck('medicine_id')->toArray();

        $openRequests = RequestModel::whereIn('medicine_id', $availableMedicineIds)
            ->where('requester_id', '!=', $user->id)
            ->where('status', 'pending')
            ->with('medicine', 'requester')
            ->orderBy('created_at', 'desc')
            ->get();

        // --- 5. Last 5 available donations from other users ---
        $availableDonations = Donation::where('status', 'available')
            ->where('donor_id', '!=', $user->id)
            ->whereDate('expiry_date', '>', Carbon::now())
            ->with('medicine', 'donor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- 6. Urgent nearby requests ---
        $urgentRequests = RequestModel::where('status', 'pending')
            ->where('quantity_remaining', '<=', 3)
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Quick stats array ---
        $stats = [
            'total_donations' => $totalDonations,
            'total_requests' => $totalRequests,
            'trust_score' => $trustScore,
            'fulfilled_donations' => $fulfilledDonations,
        ];

        // --- Notifications ---
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notif) {
                $message = '';
                // If payload is array already (casted in model), leave it; otherwise decode JSON
                $payload = is_string($notif->payload) ? json_decode($notif->payload, true) : $notif->payload;
                $notifUser = User::find($notif->user_id);

                 switch ($notif->type) {
            case 'expire_reminder':
                $message = "⚠ {$payload['medicine_name']} expires on {$payload['expiry_date']}";
                $color = 'bg-red-50';
                break;
            case 'request_received':
                $requesterName = $payload['requester_name'] ?? 'Someone';
                $message = "📥 {$requesterName} requested {$payload['medicine_name']}";
                $color = 'bg-yellow-50';
                break;
            case 'request_status':
                $message = "✅ Your request for {$payload['medicine_name']} is now {$payload['status']}";
                $color = 'bg-green-50';
                break;
            default:
                $message = 'Notification';
                $color = 'bg-gray-50';
                break;
        }

                return [
                    'id' => $notif->id,
                    'message' => $message,
                    'read_at' => $notif->read_at,
                    'created_at' => $notif->created_at->format('d M Y H:i'),
                ];
            });

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('dashboard', compact(
            'userMedicines',
            'availableDonations',
            'recentActivity',
            'openRequests',
            'urgentRequests',
            'stats',
            'expiryAlerts7',
            'expiryAlerts30',
            'notifications',
            'unreadCount'

        ));
    }
}
