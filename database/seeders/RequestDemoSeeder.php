<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RequestDemoSeeder extends Seeder
{
    /**
     * Seed demo users, medicines, and requests for Livewire views.
     */
    public function run(): void
    {
        // Users
        $john = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Demo',
                'password' => Hash::make('password'),
                'phone' => '0000000000',
                'location' => 'City A',
            ]
        );

        $jane = User::firstOrCreate(
            ['email' => 'jane@example.com'],
            [
                'name' => 'Jane Demo',
                'password' => Hash::make('password'),
                'phone' => '0000000001',
                'location' => 'City B',
            ]
        );

        // Category & Medicines
        $painRelief = Category::firstOrCreate(['name' => 'Pain Relief']);

        $paracetamol = Medicine::firstOrCreate(
            ['name' => 'Paracetamol', 'brand' => 'Acme'],
            [
                'category_id' => $painRelief->id,
                'form' => 'Tablet',
                'strength' => '500 mg',
                'condition_notes' => 'Good condition',
            ]
        );

        $ibuprofen = Medicine::firstOrCreate(
            ['name' => 'Ibuprofen', 'brand' => 'HealWell'],
            [
                'category_id' => $painRelief->id,
                'form' => 'Capsule',
                'strength' => '200 mg',
                'condition_notes' => 'Boxed',
            ]
        );

        // Requests for "My Requests" (belong to John)
        RequestModel::firstOrCreate(
            [
                'medicine_id' => $paracetamol->id,
                'requester_id' => $john->id,
                'message' => 'Headache relief needed',
            ],
            [
                'donor_id' => null,
                'quantity_remaining' => 3,
                'status' => 'pending',
            ]
        );

        RequestModel::firstOrCreate(
            [
                'medicine_id' => $ibuprofen->id,
                'requester_id' => $john->id,
                'message' => 'Post-workout soreness',
            ],
            [
                'donor_id' => $jane->id,
                'quantity_remaining' => 1,
                'status' => 'approved',
            ]
        );

        // Requests for "Pending Requests" (others requesting)
        RequestModel::firstOrCreate(
            [
                'medicine_id' => $paracetamol->id,
                'requester_id' => $jane->id,
                'message' => 'Fever management',
            ],
            [
                'donor_id' => null,
                'quantity_remaining' => 2,
                'status' => 'pending',
            ]
        );
    }
}


