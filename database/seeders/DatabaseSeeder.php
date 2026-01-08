<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan Foreign Key Check biar bisa hapus bersih
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Setting::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Buat User Admin (Owner)
        User::create([
            'name' => 'Owner Cantik',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // 3. Buat Setting Toko
        Setting::create([
            'shop_name' => 'Glow & Co.',
            'shop_slogan' => 'Beauty Inside, Shine Outside',
            'shop_address' => 'Mall Olympic Garden, Malang'
        ]);

        // 4. Data Barang (Pakai Link Gambar Baru)
        $catalog = [
            'Skincare' => [
                ['name' => 'Hydrating Toner Rose', 'price' => 125000, 'tags' => 'toner,bottle'],
                ['name' => 'Niacinamide Serum 10%', 'price' => 89000, 'tags' => 'serum,skincare'],
                ['name' => 'Sunscreen SPF 50++', 'price' => 65000, 'tags' => 'sunscreen,cream'],
                ['name' => 'Night Cream Glow', 'price' => 150000, 'tags' => 'facecream'],
                ['name' => 'Micellar Water 200ml', 'price' => 45000, 'tags' => 'water,cleanser'],
                ['name' => 'Sheet Mask Aloe Vera', 'price' => 15000, 'tags' => 'facemask'],
                ['name' => 'Acne Spot Gel', 'price' => 35000, 'tags' => 'tube,cream'],
            ],
            'Makeup' => [
                ['name' => 'Velvet Lip Tint Red', 'price' => 55000, 'tags' => 'lipstick'],
                ['name' => 'Cushion High Coverage', 'price' => 180000, 'tags' => 'makeup,powder'],
                ['name' => 'Waterproof Mascara', 'price' => 75000, 'tags' => 'mascara'],
                ['name' => 'Eyebrow Pencil Brown', 'price' => 30000, 'tags' => 'pencil,makeup'],
                ['name' => 'Blush On Peach', 'price' => 45000, 'tags' => 'blushon'],
                ['name' => 'Setting Spray Matte', 'price' => 95000, 'tags' => 'spray,bottle'],
                ['name' => 'Eyeshadow Palette Nude', 'price' => 120000, 'tags' => 'eyeshadow'],
            ],
            'Haircare' => [
                ['name' => 'Keratin Shampoo 500ml', 'price' => 85000, 'tags' => 'shampoo'],
                ['name' => 'Hair Vitamin Oil', 'price' => 12000, 'tags' => 'oil,bottle'],
                ['name' => 'Creambath Ginseng', 'price' => 45000, 'tags' => 'hairmask'],
                ['name' => 'Hair Tonic Spray', 'price' => 60000, 'tags' => 'haircare'],
                ['name' => 'Catokan Curly Portable', 'price' => 250000, 'tags' => 'hairiron'],
                ['name' => 'Sisir Detangler', 'price' => 35000, 'tags' => 'comb'],
                ['name' => 'Jepit Rambut Korea', 'price' => 15000, 'tags' => 'hairclip'],
            ],
            'Accessories' => [
                ['name' => 'Kalung Titanium Butterfly', 'price' => 45000, 'tags' => 'necklace'],
                ['name' => 'Cincin Gold Plated', 'price' => 25000, 'tags' => 'ring,jewelry'],
                ['name' => 'Anting Pearl Drop', 'price' => 30000, 'tags' => 'earring'],
                ['name' => 'Gelang Beads Aesthetic', 'price' => 15000, 'tags' => 'bracelet'],
                ['name' => 'Jam Tangan Analog', 'price' => 150000, 'tags' => 'wristwatch'],
                ['name' => 'Kacamata Fashion', 'price' => 50000, 'tags' => 'sunglasses'],
                ['name' => 'Ikat Rambut Scrunchie', 'price' => 5000, 'tags' => 'fabric'],
            ],
            'Fashion Bags' => [
                ['name' => 'Tote Bag Canvas', 'price' => 45000, 'tags' => 'totebag'],
                ['name' => 'Sling Bag Leather', 'price' => 120000, 'tags' => 'handbag'],
                ['name' => 'Dompet Lipat Mini', 'price' => 55000, 'tags' => 'wallet'],
                ['name' => 'Backpack Mini Pastel', 'price' => 135000, 'tags' => 'backpack'],
                ['name' => 'Shoulder Bag Retro', 'price' => 95000, 'tags' => 'purse'],
                ['name' => 'Card Holder Custom', 'price' => 25000, 'tags' => 'leather'],
                ['name' => 'Clutch Pesta', 'price' => 150000, 'tags' => 'bag'],
            ],
            'Footwear' => [
                ['name' => 'Flatshoes Ballet', 'price' => 85000, 'tags' => 'shoes'],
                ['name' => 'Sendal Heels Kaca', 'price' => 125000, 'tags' => 'heels'],
                ['name' => 'Sneakers White', 'price' => 180000, 'tags' => 'sneakers'],
                ['name' => 'Sandal Rumah Fluffy', 'price' => 35000, 'tags' => 'sandals'],
                ['name' => 'Boots Korean Style', 'price' => 210000, 'tags' => 'boots'],
                ['name' => 'Mary Jane Shoes', 'price' => 140000, 'tags' => 'leathershoes'],
                ['name' => 'Kaos Kaki Motif', 'price' => 10000, 'tags' => 'socks'],
            ],
            'Fragrance' => [
                ['name' => 'Eau De Parfum Vanilla', 'price' => 75000, 'tags' => 'perfume'],
                ['name' => 'Body Mist Cherry', 'price' => 45000, 'tags' => 'bodyspray'],
                ['name' => 'Solid Perfume', 'price' => 30000, 'tags' => 'cosmetic'],
                ['name' => 'Reed Diffuser Lavender', 'price' => 65000, 'tags' => 'diffuser'],
                ['name' => 'Scented Candle Rose', 'price' => 50000, 'tags' => 'candle'],
                ['name' => 'Pocket Parfum', 'price' => 20000, 'tags' => 'spray'],
                ['name' => 'Hair Mist Fresh', 'price' => 35000, 'tags' => 'bottle'],
            ],
        ];

        foreach ($catalog as $categoryName => $items) {
            $cat = Category::create(['name' => $categoryName]);
            foreach ($items as $item) {
                // Kita pakai LoremFlickr + Random Lock biar gambarnya beda-beda
                $randomLock = rand(1, 10000);
                Product::create([
                    'name' => $item['name'],
                    'category_id' => $cat->id,
                    'price' => $item['price'],
                    'stock' => rand(15, 100), // Stok random
                    // Format Link: loremflickr.com/lebar/tinggi/kata_kunci?lock=angka_unik
                    'image' => "https://loremflickr.com/400/400/{$item['tags']}?lock={$randomLock}"
                ]);
            }
        }
    }
}