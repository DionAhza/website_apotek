<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Membuat akun admin secara terkontrol dari .env (BUKAN dari form register publik).
 *
 *   ADMIN_NAME=Administrator
 *   ADMIN_EMAIL=admin@contoh.com
 *   ADMIN_PASSWORD=isi-password-kuat-Anda
 *
 * Jalankan: php artisan db:seed --class=AdminSeeder
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD belum diisi di .env. Akun admin tidak dibuat.');

            return;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = env('ADMIN_NAME', 'Administrator');
        $user->role = User::ROLE_ADMIN;

        // Password hanya diisi saat akun baru dibuat; akun yang sudah ada tidak direset diam-diam.
        if (! $user->exists) {
            $user->password = $password;
        }

        $user->save();

        $this->command?->info("Akun admin siap: {$email}");
    }
}
