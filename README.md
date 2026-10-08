<div align="center">

# 🏫 Website SMP Negeri Satu Atap I Sidamulih

Website profil sekolah yang **modern, formal, elegan, responsif, dan colorful** — lengkap dengan **panel admin custom** untuk mengelola seluruh konten halaman publik.

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)

**NPSN 20253310** &bull; Akreditasi **B** &bull; Jl. Karanganyar No.127 Kalijati, Sidamulih, Pangandaran, Jawa Barat

</div>

---

## ✨ Fitur Halaman Publik

| Halaman | Isi |
|---|---|
| 🏠 **Beranda** | Hero gradasi, statistik sekolah, sambutan kepala sekolah, keunggulan, ekstrakurikuler, berita terbaru, galeri, CTA PPDB |
| 🏫 **Profil** | Identitas (NPSN, akreditasi), sejarah, visi-misi, guru & tendik (dengan foto), peta Google Maps yang bisa diubah dari admin |
| 📚 **Akademik** | Kurikulum Merdeka, jadwal ekstrakurikuler, kalender pendidikan |
| 📰 **Berita** | Pencarian, filter kategori, halaman detail |
| 📢 **Pengumuman** | Info resmi sekolah + penanda "Penting" |
| 🖼️ **Galeri** | Dokumentasi kegiatan |
| 📝 **PPDB** | Formulir online bernomor otomatis, **cek status**, **cetak bukti pendaftaran** (+ banner ucapan selamat bagi yang diterima) |
| ✉️ **Kontak** | Info kontak + formulir pesan (notifikasi otomatis hilang 3 detik) |

Plus: `sitemap.xml` otomatis, favicon logo sekolah, meta Open Graph, timezone **WIB (Asia/Jakarta)**.

## 🛠️ Fitur Panel Admin (`/login`)

- 📊 **Dashboard** — statistik konten + grafik pertumbuhan siswa per tahun (L/P + % tumbuh)
- 📰 Berita, 📢 Pengumuman, 🏷️ Kategori berita
- 👩‍🏫 Guru & Tendik — CRUD + foto + **impor/ekspor Excel**
- 🖼️ Galeri, ⚽ Ekstrakurikuler, 🎒 Data Siswa per tahun
- 📝 PPDB masuk — filter status, verifikasi, **ekspor Excel**, cetak
- ✉️ Pesan masuk — tandai dibaca
- ⚙️ Pengaturan — identitas, kontak, visi-misi, sambutan, info PPDB, URL Maps, medsos
- 👤 Role **Admin / Operator / Guru**, kelola pengguna, halaman **Profil Saya** (nama, foto, sandi)

## 🚀 Cara Menjalankan (Windows + XAMPP)

> ⚠️ Laravel 13 butuh **PHP 8.3+**. PHP bawaan XAMPP 8.1 tidak cukup — install PHP 8.4 (mis. via `winget install PHP.PHP.8.4`) lalu gunakan binary-nya untuk perintah di bawah.

```bash
cd "C:\xampp\htdocs\Website Sekolah"

# 1. Install dependensi (sekali saja)
"C:\Program Files\...\php.exe" C:\composer\composer.phar install
npm install

# 2. Siapkan environment & database
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build

# 3. Jalankan
php artisan serve --port=8000
```

Buka **http://localhost:8000** &nbsp;•&nbsp; Admin: **http://localhost:8000/login**

🔑 Akun awal — email `admin@satap1sidamulih.sch.id`, sandi `password`
> ⚠️ **Segera ganti** lewat menu Profil Saya setelah masuk pertama kali.

## 🧪 Tes & Kualitas

```bash
php artisan test          # 40+ feature test (publik + admin + role)
vendor/bin/pint           # format kode Laravel Pint
```

## 🗂️ Struktur Singkat

```
app/Http/Controllers/{Auth,Admin}  → login, dashboard, CRUD konten, PPDB, user
app/Models                         → Setting, News, Category, Announcement, Teacher, ...
database/{migrations,seeders}      → skema + data awal sekolah (NPSN 20253310)
resources/views/{pages,admin}      → Blade + Tailwind v4 (tanpa build-step berat)
routes/web.php                     → route publik + admin per-role
tests/Feature                      → SchoolWebsiteTest, AdminPanelTest
```

## 📄 Lisensi

Proyek pembelajaran/open-source untuk SMP Negeri Satu Atap I Sidamulih.

---

<div align="center">

**Credit by Farhan Ale** 🧑‍💻

Dibangun dengan ❤️ menggunakan Laravel 13 + Tailwind CSS v4

</div>
