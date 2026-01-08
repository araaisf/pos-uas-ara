<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;

class SultanSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan pengecekan foreign key biar bersih
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Daftar Barang Mewah (Kategori => Daftar Barang)
        $catalog = [
            'High Jewelry' => [
                [
                    'name' => 'Pink Diamond Eternity Ring',
                    'price' => 150000000,
                    'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'name' => 'Sapphire Royal Necklace',
                    'price' => 285000000,
                    'image' => 'https://images.unsplash.com/photo-1599643478518-17488fbbcd75?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'name' => 'Emerald Cut Tennis Bracelet',
                    'price' => 45000000,
                    'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=600&auto=format&fit=crop'
                ],
            ],
            'Luxury Bags' => [
                [
                    'name' => 'Himalayan Croco Birkin',
                    'price' => 550000000,
                    'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'name' => 'Chanel Classic Flap White',
                    'price' => 120000000,
                    'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'name' => 'Lady Dior Mini Rose',
                    'price' => 85000000,
                    'image' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?q=80&w=600&auto=format&fit=crop'
                ],
            ],
            'Designer Shoes' => [
                [
                    'name' => 'Cinderella Crystal Heels',
                    'price' => 24000000,
                    'image' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=600&auto=format&fit=crop'
                ],
                [
                    'name' => 'Red Bottoms Stiletto',
                    'price' => 18500000,
                    'image' => 'https://images.unsplash.com/photo-1511556820780-d912e42b4980?q=80&w=600&auto=format&fit=crop'
                ],
            ],
            'Silk Scarves' => [
                [
                    'name' => 'Hermes Silk Scarf',
                    'price' => 8500000,
                    'image' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?q=80&w=600&auto=format&fit=crop'
                ],
            ],
            'Perfume' => [
                [
                    'name' => 'Baccarat Rouge 540',
                    'price' => 6500000,
                    'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=600&auto=format&fit=crop'
                ]
            ]
        ];

        foreach ($catalog as $categoryName => $products) {
            // Buat Kategori
            $cat = Category::create(['name' => $categoryName]);

            // Buat Produk di kategori tersebut
            foreach ($products as $prod) {
                Product::create([
                    'category_id' => $cat->id,
                    'name' => $prod['name'],
                    'price' => $prod['price'],
                    'stock' => rand(5, 20), // Stok acak
                    'image' => $prod['image'] // Link gambar online
                ]);
            }
        }
    }
}