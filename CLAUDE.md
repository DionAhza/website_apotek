# CLAUDE.md
# Panduan Pengembangan Website Apotek

Dokumen ini adalah pedoman utama untuk Claude Code atau AI coding assistant saat membaca, memperbaiki, dan mengembangkan repository ini. Tujuannya adalah menghasilkan website apotek yang **profesional, mudah digunakan masyarakat umum, aman, konsisten, dan benar-benar berfungsi**.

> Aturan paling penting: pahami kondisi kode yang sudah ada sebelum mengubahnya. Jangan mengarang fitur, jangan mengganti arah project, dan jangan melakukan perubahan besar tanpa persetujuan.

---

## 1. Identitas dan tujuan produk

Project ini adalah **website apotek** berbasis Laravel. Arah produk adalah website yang dapat digunakan oleh dua kelompok pengguna:

1. **Pelanggan / masyarakat umum** — mencari informasi produk, menjelajahi katalog, melihat detail produk, dan menggunakan fitur belanja jika fitur tersebut sudah tersedia.
2. **Admin / pengelola apotek** — mengelola data obat, kategori, dan operasional katalog melalui area admin.

Website harus terasa seperti layanan apotek sungguhan, bukan sekadar tugas CRUD atau template dashboard.

### Hasil yang ingin dicapai

- Tampilan modern, bersih, terpercaya, dan mudah dipahami.
- Pengalaman pelanggan sederhana, termasuk bagi pengguna yang tidak terbiasa dengan teknologi.
- Navigasi yang jelas antara halaman publik dan halaman admin.
- Informasi produk tersusun rapi dan mudah ditemukan.
- Tampilan responsif pada ponsel, tablet, dan desktop.
- Formulir, tombol, pencarian, filter, dan navigasi bekerja sesuai fungsinya.
- Data yang ditampilkan berasal dari database, bukan angka atau konten palsu.
- Kode Laravel tetap mudah dirawat dan dikembangkan.

### Bukan tujuan project

Jangan mengubah project menjadi:
- Sistem rumah sakit, klinik, rekam medis, atau konsultasi dokter.
- Marketplace multi-penjual.
- Sistem pembayaran atau pengiriman sungguhan tanpa permintaan dan integrasi yang jelas.
- Aplikasi berbasis React/Vue atau framework lain.
- Sistem rekomendasi diagnosis, resep, atau dosis obat otomatis.

Fitur baru di luar arah website apotek harus dibahas terlebih dahulu dengan pengguna.

---

## 2. Teknologi dan batasan teknis

Ikuti teknologi yang sudah digunakan repository. Berdasarkan pemeriksaan awal:

- PHP `^8.3`
- Laravel `^13.8`
- Blade
- MySQL (konfigurasi aktual mengikuti environment)
- Tailwind CSS 4 melalui Vite
- Vite 8
- JavaScript native / ES Modules
- SweetAlert2 digunakan pada beberapa halaman melalui CDN

### Aturan stack

- Pertahankan Laravel + Blade + Tailwind CSS + Vite.
- Jangan migrasi ke Bootstrap, React, Vue, Livewire, Inertia, atau framework lain tanpa persetujuan.
- Jangan memasang package baru jika kebutuhan dapat diselesaikan dengan teknologi yang sudah tersedia.
- Periksa `composer.json`, `package.json`, dan kode aktual sebelum menyimpulkan teknologi yang terpasang.
- Jangan mengasumsikan CDN selalu tersedia. Jika project sudah memiliki dependency lokal, prioritaskan asset lokal.
- Jangan menghapus dependency atau konfigurasi yang masih digunakan.

---

## 3. Peta repository dan kondisi awal

Struktur penting yang telah ditemukan:

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
  seeders/

public/
  gambars/
  images/

resources/
  css/app.css
  js/app.js
  views/
    auth/
    category/
    components/
    medicine/
    index.blade.php
    about.blade.php

routes/
  web.php
```

### Model dan relasi yang telah teridentifikasi

- `Category` memiliki banyak `Medicine` melalui relasi `medicines()`.
- `Medicine` dimiliki satu `Category` melalui relasi `category()`.
- `User` memiliki atribut `name`, `email`, `password`, dan `role`.
- Data `Medicine` menggunakan atribut `name`, `price`, `description`, `expired`, `medicine_image`, dan `category_id`.
- Terdapat model `Obat` dan tabel `obats` yang terpisah dari `Medicine` dan `medicines`.

**Penting:** keberadaan model/tabel `Obat` dan `Medicine` tidak boleh dianggap duplikat yang aman dihapus. Telusuri pemakaiannya terlebih dahulu. Jangan menggabungkan, menghapus, atau memigrasikan data keduanya tanpa persetujuan.

### Kondisi awal yang perlu diaudit

Pemeriksaan awal menemukan beberapa hal yang harus diperiksa dan ditangani bertahap:

- Route manajemen obat saat ini berada di middleware `auth`, tetapi belum terlihat pembatasan khusus role admin.
- Route hapus obat menggunakan HTTP GET. Aksi penghapusan harus dipindahkan ke HTTP DELETE setelah memeriksa seluruh pemanggilnya.
- Controller obat belum menerapkan validasi lengkap dan upload gambar masih menggunakan nama file asli.
- Penghapusan kategori saat ini ikut menghapus seluruh obat terkait. Perilaku ini berisiko kehilangan data dan tidak boleh dipertahankan tanpa kebijakan bisnis yang disetujui.
- Beberapa daftar data menggunakan `all()` dan perlu dievaluasi untuk pagination serta eager loading.
- Halaman publik masih memiliki konten placeholder dan tautan yang belum memiliki tujuan nyata.
- Periksa apakah detail produk publik memang dapat dibuka oleh pelanggan tanpa login.
- Periksa proses logout, validasi, session, dan otorisasi.

Daftar ini adalah temuan awal, bukan izin untuk mengubah semuanya sekaligus. Verifikasi kembali kondisi file sebelum bertindak.

---

## 4. Prinsip produk: utamakan pelanggan

Setiap perubahan halaman publik harus menjawab pertanyaan: **apakah pelanggan apotek dapat memahami dan menggunakan halaman ini dengan mudah?**

### Prinsip UX

- Gunakan bahasa Indonesia yang jelas, sopan, dan tidak teknis.
- Hindari istilah developer pada antarmuka pelanggan.
- Prioritaskan tampilan mobile karena pelanggan mungkin mengakses melalui ponsel.
- Buat hierarki visual yang jelas: judul, informasi utama, harga, kategori, dan aksi.
- Jangan memenuhi halaman dengan animasi, dekorasi, atau card yang tidak membantu.
- Gunakan ukuran teks yang nyaman dibaca dan kontras yang memadai.
- Berikan feedback ketika aksi berhasil, gagal, sedang diproses, atau belum memiliki data.
- Jangan membuat tombol yang terlihat aktif tetapi tidak melakukan apa pun.
- Jangan menggunakan tautan `#` sebagai tujuan akhir fitur.
- Sediakan empty state yang menjelaskan kondisi dan langkah berikutnya.
- Pastikan formulir mudah digunakan, memiliki label, dan menampilkan pesan kesalahan yang jelas.

### Pengalaman pelanggan yang diharapkan

Jika sesuai dengan tahap pengembangan dan data yang tersedia, halaman publik dapat mencakup:

- Beranda dengan identitas apotek, penjelasan layanan, dan akses katalog.
- Katalog produk dengan foto, nama, harga, dan kategori.
- Pencarian produk.
- Filter kategori dan pengurutan.
- Halaman detail produk.
- Informasi kontak, alamat, jam operasional, dan lokasi apotek jika datanya diberikan pemilik.
- Keranjang dan pemesanan hanya setelah diminta serta dirancang alurnya dengan jelas.

Jangan mengarang nama apotek, alamat, nomor telepon, jam buka, diskon, sertifikasi, stok, testimoni, atau klaim layanan. Gunakan data yang sudah ada atau minta informasi yang belum tersedia.

---

## 5. Arah desain visual

Gunakan karakter visual **modern, bersih, ramah, dan terpercaya** yang sesuai dengan bisnis apotek.

### Panduan visual

- Warna utama dapat menggunakan emerald, teal, atau hijau farmasi yang elegan.
- Gunakan latar netral dan permukaan putih/terang agar informasi mudah dibaca.
- Gunakan warna status secara konsisten untuk sukses, peringatan, bahaya, dan informasi.
- Gunakan tipografi yang mudah dibaca.
- Gunakan spacing dan radius yang konsisten.
- Shadow secukupnya; hindari bayangan tebal dan border berlebihan.
- Hindari gradient berlebihan, efek kaca yang mengganggu, dan animasi berlebihan.
- Gunakan ikon dengan gaya konsisten.
- Jangan menambahkan library ikon baru tanpa alasan dan persetujuan.
- Pastikan hover, focus, active, disabled, dan loading state terlihat jelas.

### Komponen yang perlu konsisten

- Navbar publik dan navigasi mobile.
- Footer.
- Hero section.
- Tombol primary, secondary, danger, dan ghost.
- Form input, select, textarea, label, dan error message.
- Product card dan product detail.
- Badge kategori/status.
- Table dan pagination admin.
- Sidebar admin.
- Modal/confirmation dan alert.
- Loading, empty, success, dan error state.

### Aturan implementasi UI

- Gunakan layout Blade untuk menghindari duplikasi HTML.
- Pertimbangkan layout publik dan layout admin secara terpisah.
- Gunakan Blade components untuk elemen yang berulang.
- Jangan melakukan redesign seluruh halaman sekaligus.
- Selesaikan satu halaman atau satu alur, lalu periksa hasilnya di ukuran mobile dan desktop.
- Jangan mengubah desain halaman yang tidak terkait dengan permintaan pengguna.

---

## 6. Peta fitur dan urutan prioritas

Kembangkan aplikasi secara bertahap. Jangan melompat ke fitur lanjutan ketika fondasi masih bermasalah.

### Tahap 0 — Audit dan stabilitas

- Petakan route, middleware, controller, model, migration, dan view.
- Periksa halaman yang dapat dibuka guest, user login, dan admin.
- Periksa error Laravel dan asset build.
- Identifikasi link, tombol, atau form yang belum berfungsi.
- Catat risiko keamanan dan risiko kehilangan data.
- Buat laporan dan rencana perubahan sebelum mengedit banyak file.

### Tahap 1 — Keamanan dan hak akses

- Bedakan akses pelanggan dan admin.
- Pelanggan tidak boleh mengakses halaman pengelolaan data.
- Semua aksi admin harus dilindungi di sisi server, bukan hanya menyembunyikan tombol.
- Registrasi publik hanya membuat role pelanggan/user.
- Role admin tidak boleh dipilih dari form registrasi publik.
- Periksa proses login dan logout.
- Gunakan validasi server-side pada seluruh input.
- Lindungi seluruh form perubahan data dengan CSRF.
- Aksi hapus harus menggunakan HTTP DELETE atau mekanisme yang sesuai, bukan GET.
- Jangan menjalankan perubahan database destruktif.

### Tahap 2 — Fondasi tampilan dan halaman publik

- Rapikan layout publik.
- Rapikan navbar dan footer.
- Buat beranda dengan konten yang benar-benar relevan.
- Pastikan katalog dapat dibuka pelanggan.
- Rapikan card produk dan halaman detail.
- Tambahkan fallback jika gambar produk tidak tersedia.
- Gunakan format harga Rupiah.
- Tampilkan informasi produk secara jelas dan konsisten.
- Tambahkan pencarian, filter, sorting, dan pagination sesuai kebutuhan.

### Tahap 3 — Dashboard admin

- Buat dashboard yang mudah dipahami pengelola.
- Tampilkan ringkasan data dari database sebenarnya.
- Tampilkan navigasi manajemen obat dan kategori.
- Buat tabel responsif, pencarian, filter, dan pagination bila dibutuhkan.
- Berikan konfirmasi sebelum penghapusan.
- Jangan menampilkan statistik hardcoded seolah-olah data asli.

### Tahap 4 — Manajemen produk dan kategori

- CRUD obat/produk.
- CRUD kategori.
- Validasi nama, harga, deskripsi, tanggal kedaluwarsa, kategori, dan gambar sesuai kebutuhan.
- Gunakan `findOrFail()` atau route model binding.
- Gunakan Eloquent relationship.
- Gunakan eager loading untuk menghindari N+1 query.
- Gunakan pagination pada daftar panjang.
- Tangani kategori yang masih digunakan produk dengan aman.
- Jangan menghapus produk terkait secara otomatis ketika kategori dihapus tanpa persetujuan dan kebijakan yang jelas.

### Tahap 5 — Fitur belanja (opsional, hanya jika diminta)

Rancang dan implementasikan bertahap:

1. Keranjang belanja.
2. Tambah, ubah jumlah, dan hapus item.
3. Ringkasan belanja.
4. Alur checkout.
5. Konfirmasi pesanan.
6. Riwayat pesanan.

Ketentuan:
- Harga dan total wajib dihitung ulang di server berdasarkan database.
- Jangan percaya harga atau total dari browser.
- Validasi ketersediaan produk di server.
- Jelaskan apakah keranjang tersedia untuk guest, pelanggan login, atau keduanya.
- Jangan membuat checkout palsu.
- Jangan menyatakan pembayaran berhasil tanpa integrasi dan verifikasi pembayaran yang nyata.

### Tahap 6 — Fitur lanjutan (opsional)

Jangan implementasikan otomatis. Tunggu instruksi pengguna:
- Manajemen stok.
- Notifikasi stok menipis.
- Laporan penjualan.
- Export PDF/Excel.
- Email/WhatsApp notification.
- Payment gateway.
- Integrasi kurir.
- API.
- Audit log.

---

## 7. Aturan khusus informasi farmasi

Website ini adalah sarana informasi dan pengelolaan produk apotek, bukan pengganti tenaga kesehatan.

- Jangan membuat diagnosis atau menyarankan obat berdasarkan gejala.
- Jangan membuat rekomendasi dosis, aturan pakai, atau kombinasi obat otomatis.
- Jangan mengarang indikasi, kontraindikasi, efek samping, komposisi, izin edar, atau klaim khasiat.
- Jangan mengubah informasi pada kemasan atau sumber resmi menjadi klaim yang tidak didukung.
- Informasi produk harus berasal dari data yang diberikan dan diverifikasi pengelola apotek atau sumber resmi yang sesuai.
- Jangan menyatakan produk tersedia jika stok belum dikelola atau datanya tidak tersedia.
- Jangan menampilkan label “aman untuk semua orang”, “pasti sembuh”, atau klaim medis mutlak.
- Untuk informasi yang memerlukan penilaian medis, arahkan pelanggan berkonsultasi dengan apoteker atau tenaga kesehatan yang berwenang.
- Jika kelak ada fitur penjualan obat dengan ketentuan khusus, minta pemilik menentukan kebijakan dan pastikan alur mematuhi peraturan yang berlaku. Jangan mengasumsikan semua produk dapat dijual bebas.

---

## 8. Standar backend Laravel

### Routing

- Gunakan nama route yang konsisten.
- Kelompokkan route berdasarkan middleware dan prefix.
- Pisahkan route publik, pelanggan, dan admin sesuai kebutuhan.
- Gunakan HTTP method sesuai aksi.
- Jangan menggunakan GET untuk mengubah atau menghapus data.
- Periksa semua pemanggil route sebelum mengganti URI atau nama route agar tidak merusak tautan yang sudah ada.

### Controller

- Controller mengatur alur request, bukan menampung seluruh logika aplikasi.
- Validasi request sebelum menyimpan.
- Gunakan `findOrFail()` atau route model binding.
- Gunakan redirect dan flash message yang konsisten.
- Hindari query atau logika bisnis kompleks di Blade.
- Gunakan Form Request jika validasi mulai kompleks.
- Jangan memasukkan data dummy permanen ke controller.

### Model dan database

- Pertahankan relasi yang sudah digunakan.
- Periksa nama kolom, tipe data, nullable, dan foreign key sebelum mengubah skema.
- Gunakan `$fillable` atau `$guarded` secara aman.
- Jangan mengubah migration lama yang mungkin sudah dijalankan di database bersama/production; buat migration baru.
- Jangan menjalankan `migrate:fresh`, `db:wipe`, atau perintah destruktif pada database pengguna.
- Jangan menghapus atau mengubah data massal tanpa persetujuan.
- Gunakan transaction ketika beberapa perubahan database harus berhasil sebagai satu kesatuan.
- Gunakan eager loading ketika menampilkan relasi.

### Validasi

Validasi harus sesuai dengan aturan bisnis dan skema aktual. Contoh atribut produk:

- Nama: wajib, string, panjang wajar.
- Harga: angka valid dan tidak negatif.
- Deskripsi: nullable atau wajib sesuai kebutuhan.
- Tanggal kedaluwarsa: tanggal valid jika memang digunakan.
- Kategori: harus merujuk kategori yang ada.
- Gambar: file gambar dengan tipe dan ukuran yang diizinkan.

Jangan menyalin contoh aturan tanpa memeriksa kebutuhan dan tipe kolom.

### Upload gambar

- Validasi tipe dan ukuran file di server.
- Gunakan Laravel Storage.
- Jangan memakai nama file asli dari browser sebagai nama penyimpanan.
- Simpan path file pada database.
- Jangan menghapus file lama sebelum file baru berhasil disimpan.
- Pertahankan gambar lama jika pengguna tidak mengunggah gambar baru saat edit.
- Pastikan cara menampilkan URL gambar sesuai disk yang digunakan.
- Jelaskan kebutuhan `php artisan storage:link` jika menggunakan disk public.

---

## 9. Autentikasi, otorisasi, dan privasi

- Gunakan mekanisme autentikasi Laravel yang sudah ada kecuali ada alasan kuat untuk mengubahnya.
- Regenerate session setelah login.
- Saat logout, invalidate session dan regenerate CSRF token sesuai praktik Laravel.
- Password wajib di-hash.
- Jangan pernah menyimpan password plaintext.
- Jangan pernah menampilkan password, token, atau secret pada log maupun halaman.
- Jangan percaya role yang dikirim dari browser.
- Pastikan otorisasi diperiksa pada route/controller/policy/middleware di server.
- Jangan menampilkan data pribadi pelanggan kepada pengguna lain.
- Jangan commit file `.env`, API key, credential, atau data pelanggan.
- Jangan membaca, mencetak, atau membagikan nilai rahasia dari `.env`.

---

## 10. Standar frontend dan aksesibilitas

- Gunakan semantic HTML: `header`, `nav`, `main`, `section`, `footer`, `form`, `label`, dan `button`.
- Setiap input memiliki label yang jelas.
- Gunakan `button` untuk aksi dan `a` untuk navigasi.
- Gambar konten memiliki `alt` yang relevan.
- Pastikan navigasi dan form dapat digunakan dengan keyboard.
- Gunakan focus state yang terlihat.
- Pastikan kontras teks memadai.
- Hindari teks terlalu kecil.
- Cegah horizontal overflow pada layar kecil.
- Berikan feedback untuk loading, disabled, success, dan error.
- Hormati preferensi `prefers-reduced-motion` jika menggunakan animasi.
- Escape output Blade dengan `{{ }}` secara default.
- Hindari `{!! !!}` kecuali konten HTML tepercaya dan sudah disanitasi.

---

## 11. Aturan kualitas kode

- Ikuti gaya penamaan dan pola yang sudah digunakan repository.
- Gunakan nama variabel dan method yang jelas.
- Hindari duplikasi kode.
- Hindari membuat abstraksi berlebihan untuk fitur sederhana.
- Jangan menulis query database langsung di Blade.
- Jangan menaruh seluruh JavaScript di Blade jika lebih tepat berada di `resources/js`.
- Jangan menambahkan package tanpa alasan yang jelas.
- Jangan mengubah file yang tidak berhubungan dengan permintaan.
- Jangan menghapus kode lama sebelum memastikan tidak digunakan.
- Jangan menutupi error dengan menghapus validasi atau menonaktifkan proteksi.
- Jangan membuat data contoh seolah-olah data produksi.

---

## 12. Cara kerja wajib untuk setiap permintaan

Ikuti alur ini setiap kali menerima tugas.

### A. Pahami permintaan

1. Baca dokumen ini.
2. Identifikasi hasil yang diminta pengguna.
3. Periksa file dan implementasi yang berkaitan.
4. Bedakan fitur yang sudah ada, belum ada, dan baru diminta.
5. Jika permintaan berpotensi mengubah alur bisnis atau database, jelaskan dampaknya terlebih dahulu.

### B. Rencanakan

Sebelum perubahan yang cukup besar, sampaikan secara singkat:
- Apa yang akan dikerjakan.
- File yang kemungkinan diubah.
- Dampak terhadap fitur lain.
- Apakah perlu migration atau dependency baru.

Jika permintaan kecil dan jelas, tidak perlu membuat laporan panjang.

### C. Implementasikan

- Kerjakan hanya ruang lingkup yang diminta.
- Lakukan perubahan sekecil mungkin tetapi tetap berkualitas.
- Pertahankan fitur yang sudah berjalan.
- Jangan menambah fitur tambahan hanya karena terlihat menarik.
- Jangan mengubah identitas website dari apotek menjadi produk lain.
- Jangan mengganti stack.
- Jika ada informasi bisnis yang belum diketahui, jangan mengarangnya.

### D. Verifikasi

Setelah coding:
- Periksa ulang seluruh file yang berubah.
- Periksa route yang terdampak.
- Periksa validasi dan otorisasi.
- Periksa tampilan mobile dan desktop jika memungkinkan.
- Jalankan test yang relevan.
- Jalankan build frontend jika asset berubah.
- Jangan mengklaim sesuatu berhasil jika belum diuji.

Perintah yang dapat digunakan setelah memastikan environment aman:

```bash
php artisan route:list
php artisan test
npm run build
```

Jangan menjalankan test yang berpotensi menghapus atau mengubah database development/production. Gunakan database testing terpisah.

### E. Laporkan hasil

Setiap penyelesaian tugas harus menyebutkan:
1. Fitur/perbaikan yang dilakukan.
2. File yang diubah.
3. Perubahan database atau dependency, jika ada.
4. Cara mencoba hasilnya.
5. Test yang dijalankan beserta hasilnya.
6. Hal yang belum selesai atau belum dapat diverifikasi.

---

## 13. Larangan penting

Claude Code **tidak boleh** melakukan hal berikut tanpa persetujuan eksplisit:

- Mengganti framework atau stack utama.
- Menghapus tabel, kolom, model, route, atau fitur yang sudah ada.
- Menjalankan migration destruktif atau menghapus data.
- Mengubah role banyak pengguna.
- Menghapus seluruh produk yang terhubung ke kategori.
- Mengintegrasikan pembayaran, kurir, atau layanan pihak ketiga.
- Mengarang informasi apotek atau informasi medis.
- Menambahkan fitur besar di luar permintaan.
- Mengubah seluruh desain website sekaligus.
- Mengklaim fitur sudah siap produksi hanya karena halaman berhasil ditampilkan.

---

## 14. Prompt awal untuk memulai pekerjaan

Jika pengguna meminta memperbagus website secara keseluruhan, jangan langsung mengubah semua file. Mulai dengan audit dan rekomendasi.

Gunakan urutan berikut:

1. Baca `CLAUDE.md`.
2. Audit struktur dan kondisi website.
3. Jalankan aplikasi jika environment memungkinkan.
4. Identifikasi masalah tampilan, responsivitas, alur pelanggan, keamanan, dan fungsi.
5. Buat daftar prioritas berdasarkan dampak terhadap pelanggan.
6. Tampilkan rencana pengerjaan bertahap.
7. Mulai implementasi hanya setelah ruang lingkup tahap pertama jelas.

**Target akhir:** website apotek yang dapat dipahami masyarakat umum, nyaman digunakan di ponsel maupun desktop, memiliki informasi produk yang jelas, serta menyediakan area admin yang aman dan mudah dikelola. Semua fitur harus nyata, konsisten, dan dapat diverifikasi.
