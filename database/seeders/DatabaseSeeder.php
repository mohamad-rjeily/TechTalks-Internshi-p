<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Medicine;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed a basic user for ownership if needed
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User']
        );

        // Seed categories
        $categories = collect([
            'Pain Relief',
            'Cold & Flu',
            'Digestive Health',
        ])->map(function ($name) {
            return Category::firstOrCreate(['name' => $name]);
        });

        // Seed medicines
        $examples = [
            ['name' => 'Paracetamol', 'brand' => 'Acme', 'form' => 'Tablet', 'strength' => '500 mg', 'category' => 'Pain Relief', 'photo_path' => ''],
            ['name' => 'Ibuprofen', 'brand' => 'HealWell', 'form' => 'Capsule', 'strength' => '200 mg', 'category' => 'Pain Relief', 'photo_path' => ''],
            ['name' => 'Cough Syrup', 'brand' => 'Soothe', 'form' => 'Syrup', 'strength' => '100 ml', 'category' => 'Cold & Flu', 'photo_path' => ''],
        ];

        foreach ($examples as $item) {
            $category = Category::where('name', $item['category'])->first();

            Medicine::firstOrCreate(
                [
                    'name' => $item['name'],
                    'brand' => $item['brand'],
                ],
                [
                    'category_id' => $category->id,
                    'form' => $item['form'],
                    'strength' => $item['strength'],
                    'condition_notes' => 'Good condition',
                    'photo_path' => $item['photo_path'],
                ]
            );
        }
    }
}
