<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\HomepageContent;
use App\Models\Category;
use App\Models\Product;
use App\Models\SitePage;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Starlink Kenya Admin',
                'password' => Hash::make('admin123'),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]
        );

        if (User::count() < 32) {
            $extraUsers = 32 - User::count();

            for ($i = 1; $i <= $extraUsers; $i++) {
                User::create([
                    'name' => "Customer User {$i}",
                    'email' => "user{$i}@starlink-ke.test",
                    'password' => Hash::make(Str::random(12)),
                    'created_at' => now()->subDays(45)->subDays($i),
                    'updated_at' => now()->subDays(45)->subDays($i),
                ]);
            }
        }

        $starlinkCategory = Category::firstOrCreate(
            ['slug' => 'starlink-kenya-price'],
            [
                'name' => 'Starlink Kenya Price',
                'meta_description' => 'Pricing and packages for Starlink kits and accessories in Kenya.',
            ]
        );

        $kitsSubCategory = SubCategory::firstOrCreate(
            ['slug' => 'kits'],
            [
                'category_id' => $starlinkCategory->id,
                'name' => 'Kits',
            ]
        );

        if (Product::count() === 0) {

            Product::insert([
                ['name' => 'Starlink Standard Kit', 'slug' => 'starlink-standard-kit', 'price' => 49999, 'marked_price' => 60000, 'stock' => 30, 'quantity' => 30, 'category_id' => $starlinkCategory->id, 'sub_category_id' => $kitsSubCategory->id, 'meta_description' => 'Starlink Standard kit with router and cables.', 'description' => 'Standard Starlink package for homes and SMEs.', 'google_merchant' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Starlink Mini Kit', 'slug' => 'starlink-mini-kit', 'price' => 39999, 'marked_price' => 45000, 'stock' => 20, 'quantity' => 20, 'category_id' => $starlinkCategory->id, 'sub_category_id' => $kitsSubCategory->id, 'meta_description' => 'Compact Starlink mini hardware set.', 'description' => 'Portable Starlink setup for mobility.', 'google_merchant' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Starlink High Performance Kit', 'slug' => 'starlink-high-performance-kit', 'price' => 99999, 'marked_price' => 120000, 'stock' => 10, 'quantity' => 10, 'category_id' => $starlinkCategory->id, 'sub_category_id' => $kitsSubCategory->id, 'meta_description' => 'High-performance kit for enterprise setups.', 'description' => 'For heavy bandwidth and demanding environments.', 'google_merchant' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Roof Mount', 'slug' => 'roof-mount', 'price' => 4500, 'marked_price' => 6000, 'stock' => 50, 'quantity' => 50, 'category_id' => $starlinkCategory->id, 'sub_category_id' => $kitsSubCategory->id, 'meta_description' => 'Roof mount accessory.', 'description' => 'Secure roof installation mount.', 'google_merchant' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Pole Mount', 'slug' => 'pole-mount', 'price' => 5500, 'marked_price' => 7000, 'stock' => 40, 'quantity' => 40, 'category_id' => $starlinkCategory->id, 'sub_category_id' => $kitsSubCategory->id, 'meta_description' => 'Pole mount accessory.', 'description' => 'Durable pole mount for Starlink dish.', 'google_merchant' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        } else {
            Product::query()->whereNull('category_id')->update(['category_id' => $starlinkCategory->id]);
            Product::query()->whereNull('sub_category_id')->update(['sub_category_id' => $kitsSubCategory->id]);
            Product::query()->whereNull('slug')->get()->each(function (Product $product): void {
                $product->update([
                    'slug' => Str::slug($product->name).'-'.$product->id,
                ]);
            });
        }

        if (Order::count() === 0) {
            for ($i = 1; $i <= 20; $i++) {
                Order::create([
                    'order_number' => 'ORD-KE-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'customer_name' => "Customer {$i}",
                    'customer_email' => "customer{$i}@mail.test",
                    'phone' => '07'.random_int(10000000, 99999999),
                    'county' => collect(['Nairobi', 'Mombasa', 'Kiambu', 'Nakuru'])->random(),
                    'amount' => random_int(35000, 120000),
                    'status' => collect(['pending', 'processing', 'completed'])->random(),
                    'paid_at' => null,
                    'created_at' => now()->subDays(15 + $i),
                    'updated_at' => now()->subDays(15 + $i),
                ]);
            }
        }

        if (Invoice::count() === 0) {
            $firstOrder = Order::query()->first();

            Invoice::create([
                'invoice_number' => 'INV-KE-0001',
                'order_id' => $firstOrder?->id,
                'amount' => $firstOrder?->amount ?? 0,
                'status' => 'unpaid',
                'issued_at' => now()->subDays(14)->toDateString(),
                'due_at' => now()->subDays(7)->toDateString(),
                'paid_at' => null,
            ]);
        }

        HomepageContent::updateOrCreate(
            ['id' => 1],
            [
                'hero_header_title' => 'Starlink Kenya | Official Starlink Reseller and Installer in Kenya',
                'hero_header_description' => 'Starlink Kenya: Get expert Starlink installation services designed to deliver the strongest signal and maximum performance, even in remote and hard-to-reach areas.',
                'why_choose_title' => 'Why Starlink Kenya Is Ideal for You',
                'why_choose_description' => 'Tailored for the Kenyan market with expert installation and dependable support.',
                'youtube_video_url' => HomepageContent::defaultYoutubeVideoUrl(),
                'products_section_title' => 'Starlink Kits in Kenya',
                'home_page_content' => '<h2>Starlink Kenya: A Comprehensive Guide to Satellite Internet Connectivity</h2><p>Explore STARLINK KENYA, the satellite internet service transforming digital access across Kenya. Learn about speeds, installation, costs, benefits, challenges, and how it compares to fiber and mobile networks.</p>',
                'case_studies' => HomepageContent::defaultCaseStudies(),
            ]
        );

        SitePage::updateOrCreate(
            ['slug' => 'is-satellite-internet-affected-by-rain'],
            [
                'meta_title' => 'Is Satellite Internet Affected by Rain',
                'meta_description' => 'Understand weather impact on Starlink internet service.',
                'page_title' => 'Is Satellite Internet Affected by Rain',
                'image_alt_text' => 'Is Satellite Internet Affected by Rain',
                'heading_2' => 'How weather affects Starlink internet',
                'type' => 'Post',
                'page_description' => '<p>Rain fade can reduce signal quality on traditional satellite systems, but Starlink is engineered for stability in most weather conditions.</p>',
            ]
        );
    }
}
