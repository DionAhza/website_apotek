<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Hanya membuat akun admin (jika ADMIN_EMAIL & ADMIN_PASSWORD diisi di .env).
     * Data contoh: php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
