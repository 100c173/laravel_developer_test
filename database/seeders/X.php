<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class X extends Seeder
{
    public function run(): void
    {
        $baseProducts = [
            [
                'en' => 'Wireless Headphones',
                'ar' => 'سماعات لاسلكية',
                'price' => 199.99,
            ],
            [
                'en' => 'Smart Watch',
                'ar' => 'ساعة ذكية',
                'price' => 149.50,
            ],
            [
                'en' => 'Gaming Keyboard',
                'ar' => 'لوحة مفاتيح للألعاب',
                'price' => 89.00,
            ],
            [
                'en' => 'Bluetooth Speaker',
                'ar' => 'مكبر صوت بلوتوث',
                'price' => 120.00,
            ],
            [
                'en' => 'Wireless Mouse',
                'ar' => 'فأرة لاسلكية',
                'price' => 45.99,
            ],
        ];

        // إنشاء 30 منتج
        for ($i = 1; $i <= 30; $i++) {

            $product = $baseProducts[$i % count($baseProducts)];

            Product::create([
                'title' => [
                    'en' => $product['en'] . " {$i}",
                    'ar' => $product['ar'] . " {$i}",
                ],
                'description' => [
                    'en' => "Description for {$product['en']} {$i}",
                    'ar' => "وصف المنتج {$product['ar']} {$i}",
                ],
                'slug' => Str::slug($product['en'] . " {$i}"),
                'price' => $product['price'] + rand(1, 50),
            ]);
        }
    }
}
