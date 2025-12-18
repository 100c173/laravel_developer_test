<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $products = [
            [
                'title' => [
                    'en' => 'Wireless Headphones',
                    'ar' => 'سماعات لاسلكية',
                ],
                'description' => [
                    'en' => 'High quality wireless headphones with noise cancellation.',
                    'ar' => 'سماعات لاسلكية عالية الجودة مع خاصية إلغاء الضوضاء.',
                ],
                'price' => 199.99,
            ],
            [
                'title' => [
                    'en' => 'Smart Watch',
                    'ar' => 'ساعة ذكية',
                ],
                'description' => [
                    'en' => 'Smart watch with health tracking and notifications.',
                    'ar' => 'ساعة ذكية لمتابعة الصحة والإشعارات.',
                ],
                'price' => 149.50,
            ],
            [
                'title' => [
                    'en' => 'Gaming Keyboard',
                    'ar' => 'لوحة مفاتيح للألعاب',
                ],
                'description' => [
                    'en' => 'Mechanical keyboard designed for professional gamers.',
                    'ar' => 'لوحة مفاتيح ميكانيكية مخصصة للاعبين المحترفين.',
                ],
                'price' => 89.00,
            ],
        ];

        foreach ($products as $product) {

            Product::create([
                'title' => $product['title'],
                'description' => $product['description'],
                'slug' => Str::slug($product['title']['en']),
                'price' => $product['price'],
            ]);
        }
    }
}
