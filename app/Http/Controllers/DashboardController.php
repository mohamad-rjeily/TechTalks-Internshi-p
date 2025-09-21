<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Donation;
use App\Models\Requests;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user() ;
          if (!$user) {
        // Create a fake user for testing
        $user = (object)['id' => 1]; // temporary dummy user
    }
        $totalDonations = Donation::where('donor_id', $user->id)->count();
        $totalRequests = Requests::where('requester_id', $user->id)->count();
        $successfulDonations = Donation::where('donor_id', $user->id) ->count();  // “trust score” is based on successful donations or requests

        $totalDonationsNonZero = $totalDonations > 0 ? $totalDonations : 1; // avoid division by zero
        $trustScore = round(($successfulDonations / $totalDonationsNonZero) * 100);


        // 1. User’s medicines, that aren't expired
        $medicines = Medicine::where('user_id', $user->id)
            ->whereDate('expiry_date', '>', Carbon::now())
            ->get();

        // 2. Expiry alerts (30-day alert & 7-day alert)
        $expiryAlerts30 = Medicine::where('user_id', $user->id)
         ->whereDate('expiry_date', '>', Carbon::now())
         ->whereDate('expiry_date', '<=', Carbon::now()->addDays(30))
         ->get();

        $expiryAlerts7 = Medicine::where('user_id', $user->id)
         ->whereDate('expiry_date', '>', Carbon::now())
         ->whereDate('expiry_date', '<=', Carbon::now()->addDays(7))
         ->get();


        // 3. Recent requests (sent & received)
        $recentRequests = Requests::where('requester_id', $user->id)
            ->orWhere('donor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Urgent nearby requests (low remaining quantity & sorted by urgency)
        $urgentRequests = Requests::where('status', 'pending')
            ->where('quantity_remaining', '<=', 3)
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Quick stats
        $stats = [
            'total_donations' => $totalDonations,
            'total_requests' => $totalRequests,
            'trust_score' => $trustScore,

        ];

         // Pass data to Blade view instead of returning JSON
         return view('dashboard', compact('medicines', 'recentRequests', 'urgentRequests', 'stats', 'expiryAlerts7', 'expiryAlerts30'));
      }
    }
