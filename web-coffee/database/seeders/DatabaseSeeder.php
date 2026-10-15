<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Password di-hash otomatis oleh cast 'hashed' pada model User.
        User::firstOrCreate(
            ['email' => 'admin@webcoffee.test'],
            ['name' => 'Admin Web Coffee', 'password' => 'password123', 'role' => UserRole::Admin],
        );

        $budi = User::firstOrCreate(
            ['email' => 'budi@webcoffee.test'],
            ['name' => 'Budi Pelanggan', 'password' => 'password123', 'role' => UserRole::Customer],
        );

        $menu = [
            'Kopi panas' => [
                ['Espresso', 'Satu shot pekat dengan crema tebal. Disajikan panas.', 18000, 50],
                ['Kopi tubruk', 'Seduhan tradisional, bubuk kopi diseduh langsung di gelas.', 12000, 60],
                ['Cappuccino', 'Espresso, susu panas, dan busa susu yang tebal.', 24000, 40],
            ],
            'Kopi dingin' => [
                ['Es kopi susu gula aren', 'Kopi, susu segar, dan gula aren cair di atas es.', 22000, 40],
                ['Cold brew', 'Diseduh dingin selama 12 jam, rasanya halus dan tidak pahit.', 25000, 30],
            ],
            'Non-kopi' => [
                ['Matcha latte', 'Matcha dengan susu, bisa dipesan panas atau dingin.', 24000, 25],
                ['Coklat panas', 'Coklat pekat dengan sedikit krim di atasnya.', 20000, 35],
            ],
            'Camilan' => [
                ['Croissant mentega', 'Berlapis, renyah di luar dan lembut di dalam.', 15000, 20],
                ['Kentang goreng', 'Dengan saus sambal atau mayones.', 17000, 4],
            ],
        ];

        foreach ($menu as $categoryName => $products) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($products as [$name, $description, $price, $stock]) {
                Product::firstOrCreate(
                    ['name' => $name],
                    [
                        'category_id' => $category->id,
                        'description' => $description,
                        'price' => $price,
                        'stock' => $stock,
                        'is_active' => true,
                    ],
                );
            }
        }

        if (Feedback::count() === 0) {
            Feedback::create([
                'user_id' => $budi->id,
                'name' => $budi->name,
                'rating' => 5,
                'message' => 'Es kopi susunya pas manisnya. Pesan dari rumah, sampai di sana tinggal ambil.',
            ]);

            Feedback::create([
                'name' => 'Sinta',
                'rating' => 4,
                'message' => 'Cold brew-nya enak. Semoga menu camilannya ditambah.',
            ]);
        }
    }
}
