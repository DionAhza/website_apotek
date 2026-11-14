# CLAUDE.md — Pharmacy Management & E-Commerce

Dokumen ini adalah panduan utama untuk Claude Code ketika mengembangkan repository website apotek ini. Baca file ini dan periksa kode aktual sebelum melakukan perubahan.

## 1. Identitas dan tujuan project

Project ini adalah website apotek berbasis Laravel yang dikembangkan dari aplikasi pembelajaran CRUD menjadi aplikasi yang lebih matang: **Modern Pharmacy Management & E-Commerce Website**.

Tujuan pengembangan:
- Menghasilkan UI/UX modern, profesional, konsisten, responsif, dan mudah digunakan.
- Menyempurnakan fitur manajemen obat dan kategori.
- Memisahkan pengalaman admin dan pelanggan.
- Menambahkan fitur katalog, pencarian, filter, dan keranjang belanja secara bertahap.
- Menjaga kode tetap sederhana, mudah dibaca, aman, dan mudah dipelihara.

Jangan menganggap semua fitur yang disebut sebagai fitur yang sudah ada. Implementasikan secara bertahap dan tandai fitur yang belum selesai.

## 2. Stack teknologi yang terdeteksi

Pertahankan stack yang sudah ada kecuali ada alasan teknis kuat dan persetujuan pengguna:
- PHP 8.3+
- Laravel 13 (`composer.json` saat analisis menggunakan `laravel/framework: ^13.8`)
- Blade templating
- MySQL (pastikan konfigurasi aktual melalui `.env`, jangan pernah membaca atau menampilkan rahasia `.env`)
- Tailwind CSS 4 melalui `@tailwindcss/vite`
- Vite 8
- JavaScript native / ES modules
- SweetAlert2 sudah digunakan melalui CDN di beberapa halaman.

Jangan migrasi ke React, Vue, Inertia, Livewire, Bootstrap, atau framework CSS lain tanpa persetujuan. Jangan memasang dependency hanya untuk hal yang dapat diselesaikan dengan stack yang sudah ada.

Tidak menggunakan Laravel Breeze saat ini. Jangan menginstal Breeze atau mengganti autentikasi manual secara otomatis.

## 3. Kondisi repository saat ini

Struktur penting yang sudah ditemukan:

```text
app/
  Http/Controllers/
    AuthController.php
    CategoryController.php
    FirstController.php
    MedicineController.php
  Models/
    Category.php
    Medicine.php
    Obat.php
    User.php
database/
  migrations/
    create_users_table
    create_obats_table
    create_categories_table
    create_medicines_table
resources/
  css/app.css
  js/app.js
  views/
    auth/
    category/
    components/
    medicine/
    index.blade.php
routes/web.php
public/gambars/
```

Model dan relasi yang terdeteksi:
- `Category` memiliki banyak `Medicine` melalui `medicines()`.
- `Medicine` dimiliki satu `Category` melalui `category()`.
- `User` memiliki atribut `name`, `email`, `password`, dan `role`.
- Tabel `medicines` memiliki `name`, `price`, `description`, `expired`, `medicine_image`, `category_id`, dan timestamps.
- Tabel `categories` memiliki `name`, `description`, dan timestamps.
- Tabel `users` memiliki `role` dengan default `user`.

Controller yang ada:
- `AuthController`: register, login, logout.
- `MedicineController`: index, create, store, show, edit, update, destroy.
- `CategoryController`: index, create, store, destroy.
- `FirstController`: halaman publik dan about.

View yang ada:
- `index.blade.php` untuk halaman depan/katalog.
- `medicine/medicine.blade.php` untuk daftar obat.
- `medicine/create.blade.php`, `edit.blade.php`, `detail.blade.php`.
- `category/index.blade.php`, `category/create.blade.php`.
- `auth/login.blade.php`, `auth/register.blade.php`.
- Komponen navbar dan footer.

Catatan: ada model `Obat` dan tabel `obats` yang terpisah dari model `Medicine` dan tabel `medicines`. Jangan menggabungkan, menghapus, atau melakukan migrasi destruktif tanpa memeriksa pemakaian dan meminta persetujuan.

## 4. Aturan kerja wajib

Sebelum coding:
1. Baca `CLAUDE.md` ini.
2. Periksa file terkait dan pahami alur route → controller → model → view.
3. Periksa versi dependency dan konvensi yang sudah dipakai.
4. Identifikasi dampak perubahan pada database dan fitur lain.
5. Buat rencana singkat untuk pekerjaan yang cukup besar.

Saat coding:
- Utamakan perubahan kecil, terarah, dan konsisten dengan struktur Laravel.
- Jangan menimpa file yang tidak terkait.
- Jangan menghapus fitur yang sudah berjalan tanpa alasan dan persetujuan.
- Jangan membuat data dummy permanen di controller.
- Jangan menulis query database langsung di Blade.
- Gunakan route bernama (`route('...')`) dan helper Laravel, bukan URL hardcoded jika memungkinkan.
- Gunakan Eloquent relationship dan eager loading untuk mencegah N+1 query.
- Gunakan Form Request jika validasi sudah cukup kompleks; untuk validasi sederhana boleh memakai `$request->validate()`.
- Gunakan mass assignment secara aman dan periksa `$fillable` model.
- Tampilkan error validasi dekat field terkait.
- Gunakan `old()` agar input tidak hilang setelah validasi gagal.
- Gunakan `@csrf` pada semua form yang mengubah data.
- Gunakan method spoofing `@method('PUT')`, `@method('PATCH')`, dan `@method('DELETE')` sesuai route.
- Gunakan pagination untuk daftar data.
- Berikan empty state ketika data kosong.
- Pastikan semua tombol dan tautan memiliki aksi nyata. Jangan membuat tombol dekoratif seolah-olah fitur sudah bekerja.
- Jangan menggunakan `@php` berlebihan untuk logika bisnis.
- Jangan menaruh seluruh JavaScript dalam satu file Blade jika lebih tepat diletakkan di `resources/js`.
- Jangan menggunakan `any`-style improvisasi atau kode abstrak berlebihan. Pilih solusi yang mudah dipahami developer Laravel tingkat pemula-menengah.

Setelah coding:
1. Periksa seluruh file yang berubah.
2. Jalankan test yang relevan (`php artisan test`).
3. Jalankan `npm run build` jika asset frontend berubah.
4. Jalankan pemeriksaan route (`php artisan route:list`) bila routing berubah.
5. Laporkan test yang berhasil/gagal dan hal yang belum dapat diverifikasi.
6. Jangan mengklaim fitur berhasil jika belum diuji.

## 5. Arah desain UI/UX

Buat desain dengan karakter:
- Modern, bersih, terpercaya, dan profesional.
- Cocok untuk brand apotek/farmasi.
- Tidak terlihat seperti template admin generik.
- Mengutamakan keterbacaan, hierarki informasi, dan kemudahan penggunaan.
- Konsisten pada desktop, tablet, dan mobile.

Arah visual:
- Gunakan warna utama emerald/teal atau hijau farmasi yang elegan.
- Gunakan warna netral untuk latar dan permukaan.
- Gunakan warna status secara konsisten: success, warning, danger, info.
- Hindari terlalu banyak gradient, shadow tebal, border berlebihan, dan animasi yang mengganggu.
- Gunakan spacing, radius, ukuran font, dan komponen yang konsisten.
- Gunakan ikon yang konsisten. Jika memakai library ikon baru, minta persetujuan terlebih dahulu; alternatifnya gunakan SVG inline sederhana.
- Pastikan kontras teks memadai dan fokus keyboard terlihat.

Komponen UI yang perlu distandarkan:
- Navbar publik.
- Sidebar/dashboard navigation untuk admin.
- Footer.
- Page header dan breadcrumb.
- Button primary, secondary, danger, dan ghost.
- Input, select, textarea, label, dan pesan error.
- Card, badge status, table, modal/confirmation, alert, dan pagination.
- Loading state, empty state, dan error state.

Gunakan Blade layout dan reusable components:
- Buat layout publik, misalnya `resources/views/layouts/app.blade.php`.
- Buat layout dashboard/admin terpisah bila diperlukan.
- Gunakan `@extends`, `@section`, `@yield`, dan Blade components secara konsisten.
- Hindari duplikasi struktur HTML head, navbar, footer, dan asset Vite di banyak halaman.
- Pastikan menu mobile benar-benar bisa dibuka dan ditutup.

Jangan melakukan redesign dengan mengubah semua halaman sekaligus. Selesaikan satu area, verifikasi, lalu lanjutkan.

## 6. Peta fitur dan prioritas pengembangan

### Prioritas 0 — Stabilitas dan keamanan fondasi

Sebelum menambahkan fitur besar:
- Audit seluruh route dan middleware.
- Pastikan halaman admin tidak dapat diakses user biasa.
- Periksa proses register agar user publik tidak bisa memilih role admin.
- Perbaiki validasi register, login, obat, dan kategori.
- Pastikan login melakukan session regeneration.
- Pastikan logout mengakhiri session dan regenerate CSRF token sesuai praktik Laravel.
- Gunakan route name dan HTTP method yang tepat.
- Ganti penghapusan data melalui GET menjadi DELETE.
- Tangani data tidak ditemukan dengan `findOrFail()`.
- Periksa file upload: validasi MIME/type, ukuran, dan nama file.
- Simpan gambar melalui Laravel Storage, bukan memindahkan file langsung ke folder publik dengan nama asli.
- Saat gambar diganti atau data dihapus, tangani file lama dengan aman.
- Jangan menghapus semua obat ketika kategori dihapus secara diam-diam. Tampilkan pesan bahwa kategori masih dipakai, atau gunakan kebijakan relasi yang disetujui. Jangan mengubah foreign key atau menghapus data secara massal tanpa persetujuan.
- Jangan menampilkan exception, SQL, atau informasi sensitif kepada pengguna.

### Prioritas 1 — Fondasi UI dan halaman publik

Buat pengalaman publik yang rapi:
- Homepage dengan hero section yang jelas.
- Katalog obat dengan card konsisten.
- Kategori obat.
- Detail obat yang informatif.
- Pencarian nama obat.
- Filter berdasarkan kategori.
- Sorting harga/nama bila relevan.
- Pagination.
- Empty state dan gambar fallback ketika foto tidak tersedia.
- Format harga Rupiah.
- Format tanggal kedaluwarsa yang mudah dibaca.
- Navbar responsif dengan status login yang jelas.

Halaman publik jangan menampilkan tombol admin seperti edit, hapus, atau tambah obat.

### Prioritas 2 — Dashboard admin

Buat dashboard admin dengan:
- Sidebar responsif.
- Ringkasan jumlah obat.
- Ringkasan jumlah kategori.
- Informasi obat yang mendekati tanggal kedaluwarsa.
- Daftar obat terbaru.
- Navigasi manajemen obat dan kategori.
- Tabel yang rapi, responsif, dan mudah dipindai.
- Search, filter, pagination.
- Confirmation dialog sebelum penghapusan.

Statistik harus berasal dari database sebenarnya, bukan angka hardcoded.

### Prioritas 3 — Manajemen obat dan kategori

Lengkapi CRUD:
- Create, read, update, delete obat.
- Create, read, update, delete kategori.
- Form dengan label yang jelas.
- Gunakan nama input deskriptif: `name`, `description`, `price`, `expired`, `category_id`, `medicine_image`.
- Validasi server-side.
- Preview gambar sebelum upload bila sesuai.
- Pertahankan gambar lama jika user tidak mengunggah gambar baru saat edit.
- Tampilkan kategori melalui relasi `category`.
- Tangani kategori yang masih digunakan oleh obat.
- Gunakan pagination dan eager loading.

Pertimbangkan penggunaan route resource setelah memeriksa route yang ada dan menjaga kompatibilitas link/form lama.

### Prioritas 4 — Role dan akses pengguna

Saat ini tabel users memiliki `role` default `user`. Kembangkan otorisasi secara aman:
- `admin`: mengelola obat, kategori, dan dashboard.
- `user`: menjelajahi katalog dan detail obat.
- Role tidak boleh ditentukan dari form register publik.
- Jangan hanya menyembunyikan tombol di frontend; semua otorisasi wajib diperiksa di server.
- Implementasikan middleware/authorization policy yang sesuai dengan versi Laravel project.
- Jangan mengubah akun atau role database secara massal.

Bila belum ada admin, jelaskan cara membuat admin secara aman melalui seeder lokal atau perintah yang terkontrol. Jangan menyediakan registrasi admin publik.

### Prioritas 5 — Keranjang belanja (hanya setelah fondasi stabil)

Jika pengguna meminta fitur e-commerce, implementasikan bertahap:
- Add to cart.
- Update quantity.
- Remove item.
- Cart summary.
- Validasi jumlah dan ketersediaan item di server.
- Harga dihitung dari database, bukan dipercaya dari request browser.
- Keranjang harus memiliki perilaku yang jelas untuk guest dan user login.
- Jangan membuat checkout/pembayaran palsu.
- Jangan menyatakan transaksi berhasil tanpa integrasi pembayaran dan verifikasi server.

### Prioritas 6 — Fitur lanjutan opsional

Jangan implementasikan otomatis. Tunggu permintaan pengguna:
- Riwayat pesanan.
- Checkout.
- Payment gateway.
- Manajemen stok/inventory.
- Notifikasi stok menipis.
- Laporan penjualan.
- Export PDF/Excel.
- Audit log.
- API.
- Email notification.

Untuk aplikasi farmasi, jangan membuat klaim medis atau rekomendasi dosis otomatis. Informasi obat harus bersumber dari data yang dimasukkan dan diverifikasi pengelola apotek.

## 7. Standar backend Laravel

### Routing
- Gunakan nama route yang konsisten, misalnya `medicines.index`, `medicines.create`, `medicines.store`, `medicines.show`, `medicines.edit`, `medicines.update`, `medicines.destroy`.
- Kelompokkan route berdasarkan middleware dan prefix.
- Route publik hanya untuk fitur yang memang boleh diakses publik.
- Route admin harus dilindungi autentikasi dan otorisasi.
- Jangan menggunakan GET untuk aksi yang mengubah data.

### Controller
- Controller fokus mengatur alur request.
- Validasi request sebelum menyimpan.
- Gunakan `findOrFail()` atau route model binding.
- Gunakan redirect dengan flash message yang konsisten.
- Hindari mengulang logika yang sama di beberapa controller.
- Jangan menaruh logika tampilan atau query kompleks di controller jika dapat dibuat lebih rapi.

### Model dan database
- Pertahankan relasi `Category hasMany Medicine` dan `Medicine belongsTo Category`.
- Pastikan nama foreign key dan relasi konsisten.
- Periksa nullable dan constraint sebelum mengubah migration.
- Jangan mengedit migration yang sudah pernah dijalankan pada database bersama/production sebagai cara memperbaiki skema. Buat migration baru.
- Jangan menjalankan `migrate:fresh`, `db:wipe`, atau perintah destruktif pada database pengguna.
- Sebelum perubahan skema, jelaskan dampak dan minta persetujuan jika berisiko kehilangan data.
- Gunakan eager loading seperti `Medicine::with('category')`.
- Pertimbangkan index database untuk kolom yang sering dicari/filter setelah mengukur kebutuhan.

### Validasi contoh
Gunakan aturan yang sesuai kebutuhan aktual, misalnya:
- nama obat: required, string, batas panjang wajar.
- deskripsi: required/string atau nullable sesuai kebutuhan.
- harga: required, numeric/integer, minimum 0.
- expired: required, date.
- category_id: required, exists:categories,id.
- gambar: nullable, image, format yang diizinkan, ukuran maksimum yang wajar.

Sesuaikan validasi dengan aturan bisnis dan tipe kolom. Jangan menyalin aturan secara membabi buta.

### Upload gambar
- Gunakan disk `public` Laravel Storage.
- Simpan path file pada database.
- Tampilkan dengan `Storage::url()` atau helper yang sesuai.
- Validasi file di server.
- Jangan percaya nama file dari browser.
- Jangan menghapus file lama sebelum penyimpanan file baru berhasil.
- Pastikan instruksi `php artisan storage:link` dicantumkan bila diperlukan.

### Authentication dan authorization
- Gunakan `Auth` Laravel.
- Regenerate session setelah login.
- Logout harus invalidate session dan regenerate token.
- Gunakan middleware untuk membatasi akses.
- Cegah privilege escalation.
- Jangan pernah menyimpan password plaintext.
- Jangan mengirim password atau token ke log.

## 8. Standar frontend dan aksesibilitas

- Gunakan semantic HTML: `header`, `nav`, `main`, `section`, `footer`, `form`, `label`, `button`.
- Setiap input harus punya label yang terhubung.
- Setiap gambar konten harus memiliki alt yang relevan.
- Gunakan button untuk aksi dan anchor untuk navigasi.
- Pastikan semua fitur penting dapat digunakan dengan keyboard.
- Gunakan `aria-label` atau atribut aksesibilitas bila dibutuhkan.
- Hindari ukuran teks terlalu kecil.
- Pastikan layout tidak overflow pada layar kecil.
- Gunakan state visual untuk hover, focus, disabled, loading, success, dan error.
- Animasi harus singkat dan tidak menghambat interaksi.
- Jangan memakai alert browser mentah jika pola UI aplikasi sudah menggunakan komponen/alert yang konsisten.

## 9. Kualitas kode dan keamanan

- Escape output Blade dengan `{{ }}` secara default.
- Hindari `{!! !!}` kecuali konten HTML memang tepercaya dan sudah disanitasi.
- Validasi semua data dari client.
- Jangan mempercayai harga, role, path file, atau ID relasi yang dikirim client.
- Lindungi operasi tulis dengan CSRF.
- Batasi akses berdasarkan role di server.
- Jangan commit `.env`, credential, API key, atau data pengguna.
- Jangan menaruh secret di JavaScript frontend.
- Hindari SQL raw kecuali diperlukan dan gunakan binding parameter.
- Perhatikan mass assignment.
- Periksa N+1 query.
- Jangan menambahkan dependency tanpa alasan.
- Jangan mengubah konfigurasi production hanya demi mempermudah development.

## 10. Testing dan verifikasi

Tambahkan atau perbarui test untuk perilaku penting:
- Guest tidak dapat membuka halaman admin.
- User biasa tidak dapat mengakses fitur admin.
- Admin dapat membuka dashboard.
- Register tidak dapat membuat admin.
- Login dan logout berjalan benar.
- Validasi create/update obat.
- Upload gambar valid dan file tidak valid ditolak.
- CRUD kategori.
- Kategori yang masih memiliki obat tidak menyebabkan data obat hilang.
- Pencarian/filter/pagination katalog.
- Route DELETE tidak dapat dipicu melalui GET.

Gunakan database testing terpisah. Jangan menjalankan test yang berpotensi menghapus atau mengubah database development/production pengguna.

Perintah verifikasi yang dapat dipakai setelah memastikan environment aman:
```bash
php artisan route:list
php artisan test
npm run build
```

Jika environment belum siap atau dependency belum terpasang, jelaskan kondisi tersebut. Jangan mengklaim test lulus tanpa menjalankannya.

## 11. Cara Claude mengerjakan permintaan

Untuk setiap permintaan fitur:
1. Jelaskan singkat pemahaman kebutuhan.
2. Periksa file yang terkait.
3. Buat daftar file yang akan diubah.
4. Implementasikan dengan perubahan sekecil mungkin.
5. Periksa konsistensi route, controller, model, migration, dan view.
6. Jalankan test/build yang relevan.
7. Berikan ringkasan:
   - Fitur yang dibuat/diperbaiki.
   - File yang diubah.
   - Perubahan database, jika ada.
   - Cara mencoba fitur.
   - Test yang dijalankan dan hasilnya.
   - Catatan atau pekerjaan yang belum selesai.

Jika kebutuhan ambigu, tanyakan satu pertanyaan yang paling penting sebelum melakukan perubahan besar.

Jangan mengerjakan semua prioritas sekaligus. Pilih tahap sesuai instruksi pengguna dan jaga aplikasi tetap dapat dijalankan setelah setiap tahap.

## 12. Prompt awal yang disarankan untuk Claude Code

Setelah membaca `CLAUDE.md`, lakukan audit repository terlebih dahulu. Jangan langsung mengubah kode.

Buat laporan:
1. Kondisi arsitektur saat ini.
2. Fitur yang sudah berfungsi dan yang belum.
3. Bug, risiko keamanan, dan masalah UX yang ditemukan.
4. Rencana pengembangan bertahap berdasarkan Prioritas 0–6.
5. File yang kemungkinan perlu diubah pada tahap pertama.

Setelah laporan, tunggu persetujuan sebelum melakukan perubahan besar atau migration database.
