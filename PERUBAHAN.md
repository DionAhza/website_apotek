# Upgrade Website Apotek — Catatan Perubahan

Zip ini berisi **hanya file yang berubah/baru**. Cara pakai: ekstrak di **root project** Anda dan pilih *Replace/Timpa* bila diminta. Semua kode mengikuti aturan di `CLAUDE.md`. Tidak ada migration baru dan tidak ada perubahan skema database.

## Langkah setelah ekstrak

```bash
# 1. Hapus file lama yang sudah digantikan (lihat daftar di bawah)
rm -rf resources/views/medicine resources/views/category resources/views/page2.blade.php

# 2. Tambahkan ke .env Anda (lihat .env.example), lalu buat akun admin
#    ADMIN_NAME="Administrator"
#    ADMIN_EMAIL=emailanda@contoh.com
#    ADMIN_PASSWORD=password-kuat-anda
php artisan db:seed --class=AdminSeeder

# 3. (Opsional, hanya untuk development) data contoh
php artisan db:seed --class=DemoSeeder

# 4. Pastikan gambar upload baru bisa tampil
php artisan storage:link

# 5. Build asset & jalankan
npm install && npm run build
php artisan serve
```

Setelah akun admin dibuat, login lewat `/login` → otomatis diarahkan ke `/admin`.
Setelah admin terbuat, Anda boleh mengosongkan `ADMIN_PASSWORD` di `.env`.

## Yang diperbaiki

**Keamanan & fondasi (Prioritas 0)**
- Semua halaman admin dipindah ke `/admin/...` dan dilindungi middleware `auth` + `admin` (dicek di server). User biasa mendapat 403.
- Register tidak bisa membuat admin (role selalu `user`, input `role` diabaikan). Ada konfirmasi password.
- Login: session regenerate, throttle 10 percobaan/menit, pesan error di dekat field. Logout: invalidate session + regenerate CSRF token.
- Hapus obat sekarang `DELETE` (sebelumnya GET `/obat/delete/{id}`).
- Hapus kategori **tidak lagi menghapus semua obatnya**. Jika masih dipakai, ditolak dengan pesan jelas.
- Upload gambar: divalidasi (jpg/png/webp, maks 2 MB), disimpan lewat Laravel Storage dengan nama acak. Saat edit tanpa gambar baru, gambar lama dipertahankan; file lama dihapus hanya setelah yang baru tersimpan.
- Gambar lama di `public/gambars` tetap tampil dan tidak pernah dihapus aplikasi.
- Semua input form memakai Form Request + route model binding (`404` bila data tidak ada, bukan error 500).

**UI/UX (Prioritas 1–3)**
- Layout publik & admin terpisah (`resources/views/layouts`), komponen Blade reusable, tema hijau emerald, responsif, menu mobile bisa dibuka/tutup, skip-link, fokus keyboard terlihat.
- Beranda baru, **katalog** dengan pencarian nama, filter kategori, sorting, pagination (9/halaman), halaman detail + produk sekategori, empty state, gambar fallback, harga format Rupiah, tanggal Indonesia, badge kedaluwarsa.
- **Dashboard admin**: jumlah obat/kategori, obat segera kedaluwarsa (≤ 90 hari), obat sudah kedaluwarsa, daftar obat terbaru — semua dari database.
- CRUD obat & kategori lengkap (kategori kini bisa di-edit), pencarian/filter/pagination di admin, preview gambar, dialog konfirmasi sebelum hapus.

**Role (Prioritas 4)**: `admin` (dashboard + kelola) dan `user` (katalog). Admin hanya dibuat lewat `AdminSeeder`.

## Kompatibilitas
- URL lama tetap jalan: `/obat` → `/admin/obat`, `/category` → `/admin/kategori`, `/obat/{id}` → `/katalog/{id}`.
- Model `Obat` / tabel `obats` **tidak disentuh**.
- Fitur keranjang/checkout **belum dibuat** (Prioritas 5–6, menunggu permintaan Anda).

## File lama yang boleh dihapus (sudah digantikan)
`resources/views/medicine/*`, `resources/views/category/*`, `resources/views/page2.blade.php`

## Verifikasi yang sudah dijalankan
- `php artisan test` → **49 test lulus** (akses guest/user/admin, register tidak bisa jadi admin, login/logout, validasi obat, upload valid & tidak valid, CRUD kategori, kategori terpakai tidak menghapus obat, DELETE via GET ditolak, pencarian/filter/sort/pagination katalog).
- Smoke test HTTP pada server lokal: halaman publik, login admin, semua halaman admin, 403 untuk user biasa.
- Belum diverifikasi: tampilan visual di browser (tidak ada browser di lingkungan saya) — mohon dicek di layar desktop & HP. Test upload gambar membutuhkan ekstensi PHP **GD**.

## Catatan
- Font "Instrument Sans" tetap diambil lewat plugin Vite (`vite.config.js` tidak diubah), jadi `npm run build` butuh internet.
- Kolom deskripsi obat bersifat data masukan admin; aplikasi tidak membuat klaim medis atau saran dosis otomatis. Data `DemoSeeder` hanya contoh.
- `.env.example`: `APP_NAME` jadi "Apotek Sehat", `APP_LOCALE=id`, dan ada variabel `ADMIN_*`. File `.env` Anda sendiri tidak ikut di zip.
