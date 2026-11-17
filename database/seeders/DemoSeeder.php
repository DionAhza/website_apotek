<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

/**
 * DATA CONTOH untuk pengembangan lokal. Jangan dijalankan di production.
 * Isi deskripsi/harga hanyalah contoh dan harus diganti dengan data apotek yang sebenarnya.
 *
 * Jalankan: php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            'Obat Bebas' => 'Obat yang dapat dibeli tanpa resep dokter.',
            'Flu & Batuk' => 'Obat untuk meredakan gejala flu dan batuk.',
            'Vitamin & Suplemen' => 'Pendukung daya tahan tubuh sehari-hari.',
            'Perawatan Tubuh' => 'Perlengkapan kesehatan dan perawatan harian.',
        ])->map(fn ($description, $name) => Category::firstOrCreate(['name' => $name], ['description' => $description]));

        $items = [
            ['Panadol', 'Obat Bebas', 12000, '+18 months', 'panadol.webp'],
            ['Paramex', 'Obat Bebas', 5000, '+14 months', 'paramex.jpg'],
            ['Ultraflu', 'Flu & Batuk', 9500, '+3 months', 'ultraflu.jpg'],
            ['Vitamin C 500 mg', 'Vitamin & Suplemen', 35000, '+24 months', null],
            ['Multivitamin Harian', 'Vitamin & Suplemen', 68000, '+20 months', null],
            ['Masker Medis (isi 50)', 'Perawatan Tubuh', 30000, '+30 months', null],
            ['Hand Sanitizer 100 ml', 'Perawatan Tubuh', 15000, '+45 days', null],
            ['Obat Batuk Sirup', 'Flu & Batuk', 18000, '-10 days', null],
        ];

        foreach ($items as [$name, $category, $price, $expires, $image]) {
            Medicine::firstOrCreate(['name' => $name], [
                'category_id' => $categories[$category]->id,
                'price' => $price,
                'description' => 'Data contoh. Baca aturan pakai pada kemasan dan konsultasikan dengan apoteker bila perlu.',
                'expired' => now()->modify($expires)->toDateString(),
                'medicine_image' => $image,
            ]);
        }
    }
}
