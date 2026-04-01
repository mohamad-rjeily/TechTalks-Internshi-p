<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $medicine = Medicine::first();
        
        if ($user && $medicine) {
            // Create available donation (not expired)
            Donation::create([
                'medicine_id' => $medicine->id,
                'donor_id' => $user->id,
                'quantity' => 5,
                'status' => 'available',
                'expiry_date' => now()->addDays(30),
                'notes' => 'Available donation - expires in 30 days'
            ]);

            // Create expired donation
            Donation::create([
                'medicine_id' => $medicine->id,
                'donor_id' => $user->id,
                'quantity' => 3,
                'status' => 'available',
                'expiry_date' => now()->subDays(5),
                'notes' => 'Expired donation - expired 5 days ago'
            ]);

            // Create unavailable donation
            Donation::create([
                'medicine_id' => $medicine->id,
                'donor_id' => $user->id,
                'quantity' => 2,
                'status' => 'unavailable',
                'expiry_date' => now()->addDays(15),
                'notes' => 'Unavailable donation'
            ]);
        }
    }
}
