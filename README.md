# 🐝 BeeMa - Portal Manajemen Bimbingan & Konsultasi

## 1. Judul dan Ide Aplikasi

### Judul Aplikasi

**BeeMa** - Portal Manajemen Bimbingan dan Konsultasi (Consultation Management Portal)

### Ide Aplikasi

BeeMa adalah aplikasi web terintegrasi yang dirancang untuk memudahkan manajemen sesi bimbingan dan konsultasi antara dosen dan mahasiswa. Aplikasi ini menyediakan platform terpusat untuk:

-   Penjadwalan konsultasi yang efisien
-   Pengajuan permintaan bimbingan dan konsultasi
-   Manajemen file lampiran
-   Pelacakan status persetujuan dan penolakan
-   Komunikasi dua arah yang terstruktur

Aplikasi ini menargetkan pengguna dengan tiga peran utama:

1. **Admin** - Mengelola penugasan dosen-mahasiswa
2. **Dosen** - Membuat jadwal dan mengelola permintaan dari mahasiswa
3. **Mahasiswa** - Membuat jadwal booking dan mengajukan konsultasi

---

## Teknologi Stack

-   **Framework:** Laravel 12.40.2
-   **Database:** MySQL
-   **Frontend:** Blade Template Engine, CSS3, JavaScript ES6
-   **Build Tool:** Vite v7.3.0
-   **UI Framework:** Bootstrap Icons, Custom CSS
-   **Authentication:** Laravel Sanctum

---

## Instalasi

1. Clone repository
2. Install dependencies: `composer install` dan `npm install`
3. Setup `.env` file dengan meng-copy dari .env.example
4. Generate key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Run seeders: `php artisan db:seed`
7. Build assets: `npm run build`
8. Start server npm simulataneously with laravel: `npm run dev`
9. Start server laravel: `php artisan serve`

---

## Struktur Database

**User Roles:**

-   Admin - Mengelola penugasan
-   Dosen - Membuat jadwal dan mengelola permintaan
-   Mahasiswa - Membuat booking dan permintaan konsultasi

**Entities:**

-   Users - Data pengguna dengan role
-   Schedules - Jadwal konsultasi dosen
-   Bookings - Reservasi jadwal oleh mahasiswa
-   CounselingRequests - Permintaan konsultasi khusus
-   Assignments - Penugasan dosen-mahasiswa dari admin

---


## 2. Penjelasan Fitur Aplikasi

### 2.1 Autentikasi (Authentication)

**Deskripsi:**
Sistem autentikasi penuh dengan manajemen akun multi-role yang aman.

**Fitur Utama:**

-   **Registrasi Pengguna:**

    -   Formulir pendaftaran dengan validasi email dan password (minimum 8 karakter)
    -   Pemilihan role saat pendaftaran (Admin, Dosen, atau Mahasiswa)
    -   Hashing password dengan algoritma bcrypt untuk keamanan maksimal
    -   Pencegahan duplikasi email dengan unique constraint

-   **Login Pengguna:**

    -   Autentikasi email dan password
    -   Redirect otomatis ke dashboard sesuai role pengguna
    -   Session management untuk melacak pengguna yang login

-   **Logout:**

    -   Pembersihan session pengguna
    -   Redirect ke halaman landing

-   **Proteksi Route:**
    -   Middleware `auth` untuk melindungi route yang memerlukan login
    -   Middleware `CheckRole` untuk membatasi akses berdasarkan role pengguna
    -   Pencegahan akses tidak sah dengan response HTTP 403

**File Terkait:**

-   `app/Http/Controllers/AuthController.php` - Logic autentikasi
-   `app/Http/Middleware/CheckRole.php` - Validasi role pengguna
-   `resources/views/auth/login.blade.php` - Form login
-   `resources/views/auth/register.blade.php` - Form registrasi

---

### 2.2 CRUD Utama (Create, Read, Update, Delete)

**Deskripsi:**
Implementasi operasi CRUD lengkap untuk entities utama aplikasi.

**Entitas dan CRUD Operations:**

#### A. **Jadwal (Schedules)**

-   **Create:** Dosen dapat membuat jadwal konsultasi baru
-   **Read:** Tampilan daftar jadwal dengan filter dan detail
-   **Update:** Edit jadwal yang sudah dibuat (tanggal, waktu, lokasi, kuota, status)
-   **Delete:** Hapus jadwal yang tidak diperlukan

#### B. **Booking/Permintaan Jadwal**

-   **Create:** Mahasiswa dapat membuat booking untuk jadwal dosen
-   **Read:** Daftar booking dengan status (pending, approved, rejected)
-   **Update:** Dosen dapat mengubah status booking (approved/rejected)
-   **Delete:** Sistem otomatis membersihkan booking yang dihapus

#### C. **Permintaan Konsultasi (Counseling Requests)**

-   **Create:** Mahasiswa mengajukan permintaan konsultasi khusus dengan deskripsi
-   **Read:** Dosen melihat daftar permintaan konsultasi masuk
-   **Update:** Dosen dapat menerima atau menolak dengan catatan penolakan
-   **Delete:** Pembersihan permintaan yang sudah diproses

#### D. **Penugasan Dosen-Mahasiswa (Assignments)**

-   **Create:** Admin membuat penugasan dosen ke mahasiswa (bulk assignment)
-   **Read:** Admin melihat daftar penugasan dengan filter pencarian
-   **Update:** Edit penugasan jika ada perubahan
-   **Delete:** Hapus penugasan yang tidak valid

#### E. **User Management (untuk Admin)**

-   **Create:** Pendaftaran pengguna baru melalui sistem
-   **Read:** Daftar semua pengguna dengan filter berdasarkan role
-   **Update:** Edit profil dan role pengguna
-   **Delete:** Hapus akun pengguna (jika diperlukan)

**File Terkait:**

-   `app/Http/Controllers/DosenController.php` - CRUD Jadwal & Permintaan
-   `app/Http/Controllers/MahasiswaController.php` - CRUD Booking & Konsultasi
-   `app/Http/Controllers/AdminController.php` - CRUD Penugasan
-   `app/Models/Schedule.php`, `Booking.php`, `CounselingRequest.php`, `Assignment.php`

---

### 2.3 Upload File (File Management)

**Deskripsi:**
Sistem manajemen file untuk lampiran dokumen dalam booking dan permintaan konsultasi.

**Fitur Utama:**

-   **Upload File Booking:**

    -   Mahasiswa dapat mengunggah file pendukung saat membuat booking
    -   Validasi tipe file (document, pdf, image)
    -   Limit ukuran file (max 10MB)
    -   Penyimpanan file terstruktur di folder `storage/app/private/`

-   **Upload File Konsultasi:**

    -   Mahasiswa dapat mengunggah dokumen saat mengajukan permintaan konsultasi
    -   Dukungan file multiple format
    -   Secure storage dengan path tersembunyi

-   **Download File:**

    -   Dosen dapat mendownload file dari booking/konsultasi
    -   Mahasiswa dapat mendownload file yang mereka upload
    -   Route protected dengan authorization check
    -   Nama file ditampilkan dengan truncation jika terlalu panjang

-   **Visualisasi File:**
    -   Icon download (Bootstrap SVG) pada tampilan card
    -   Nama file ter-truncate dengan ellipsis pada UI
    -   Preview informasi file dalam modal detail

**File Terkait:**

-   `app/Http/Controllers/DosenController.php` - Download file counseling
-   `app/Http/Controllers/MahasiswaController.php` - Upload booking & counseling files
-   `routes/web.php` - Route download file
-   File upload views dengan form enctype="multipart/form-data"

---

### 2.4 Localization (Multi-Bahasa)

**Deskripsi:**
Dukungan multi-bahasa untuk user interface yang dapat diakses oleh pengguna berbahasa Inggris dan Indonesia.

**Fitur Utama:**

-   **Dukungan Bahasa:**

    -   English (EN) - Bahasa Inggris
    -   Indonesian (ID) - Bahasa Indonesia

-   **Language Switching:**

    -   Dropdown/button selector bahasa di header aplikasi
    -   Route `/lang/{locale}` untuk switching bahasa
    -   Penyimpanan preferensi bahasa di session

-   **Translation Keys:**

    -   76+ translation keys untuk seluruh aplikasi
    -   Keys diorganisir dalam file `messages.php` per bahasa
    -   Dynamic translation di blade template menggunakan helper `__('messages.key')`

-   **Lokalisasi Konten:**
    -   Pesan error dan success dalam bahasa pengguna
    -   Label form dan tombol dalam bahasa pengguna
    -   Pesan validasi dalam bahasa pengguna
    -   Format tanggal dan waktu sesuai locale

**Isi File Localization:**

-   Navigasi (Login, Register, Logout, Back)
-   Form labels (Name, Email, Password, Role, File, Message)
-   Action buttons (Save, Cancel, Accept, Reject, Download, Search)
-   Status badges (Pending, Approved, Rejected, Open, Closed)
-   Messages (Success, Error, Welcome, Validation messages)
-   Role names (Admin, Dosen, Mahasiswa)

**File Terkait:**

-   `resources/lang/en/messages.php` - Terjemahan Inggris
-   `resources/lang/id/messages.php` - Terjemahan Indonesia
-   `app/Http/Middleware/SetLocale.php` - Middleware set locale
-   Routes language switcher di `routes/web.php`

---

### 2.5 Session (Session Management)

**Deskripsi:**
Manajemen session untuk melacak status pengguna dan menyimpan data sementara selama sesi aktif.

**Fitur Utama:**

-   **User Session:**

    -   Autentikasi pengguna disimpan dalam session
    -   Akses user info via `Auth::user()`
    -   Session timeout untuk keamanan

-   **Flash Messages:**

    -   Pesan success untuk action berhasil
    -   Pesan error untuk action gagal
    -   Warning untuk informasi penting
    -   Info untuk notifikasi umum
    -   Flash messages otomatis dihapus setelah ditampilkan

-   **Locale Storage:**

    -   Preferensi bahasa pengguna disimpan di session
    -   Persistence bahasa pilihan di `session(['locale' => $locale])`
    -   Middleware `SetLocale` membaca dari session setiap request

-   **Redirect dengan Data:**

    -   `with()` method untuk mengirim flash data
    -   `redirectTo()` untuk smart redirect berdasarkan role
    -   Error bag untuk menampilkan validation errors

-   **Session Protection:**
    -   CSRF token protection di setiap form
    -   Token regeneration untuk setiap login
    -   Session timeout untuk idle users

**Implementasi:**

```php
// Flash message
return redirect()->route('route')->with('success', 'Action berhasil');

// Store locale
session(['locale' => $locale]);

// Check authenticated user
if (Auth::check()) { ... }

// Get user role
$user->role; // admin, dosen, or mahasiswa
```

**File Terkait:**

-   `app/Http/Controllers/AuthController.php` - Session handling
-   `app/Http/Middleware/SetLocale.php` - Locale session
-   `routes/web.php` - Session redirect logic
-   Views dengan `@if(session('success'))`

---

### 2.6 Middleware (Request/Response Pipeline)

**Deskripsi:**
Middleware untuk filtering request dan menerapkan logika di berbagai tahap pipeline request-response.

**Middleware yang Diimplementasikan:**

#### A. **CheckRole Middleware**

**Lokasi:** `app/Http/Middleware/CheckRole.php`

**Fungsi:**

-   Memverifikasi bahwa pengguna yang sudah login memiliki role yang sesuai
-   Mencegah akses ke route yang restricted untuk role tertentu
-   Response 403 Unauthorized jika role tidak match

**Penggunaan:**

```php
Route::middleware('auth', 'role:dosen')->group(function () {
    // Routes hanya untuk dosen
});
```

**Flow:**

1. Cek apakah user sudah authenticated
2. Validasi role user dengan role yang diizinkan
3. Jika match, lanjutkan ke next middleware/controller
4. Jika tidak match, abort dengan 403 error

#### B. **SetLocale Middleware**

**Lokasi:** `app/Http/Middleware/SetLocale.php`

**Fungsi:**

-   Mengatur locale aplikasi berdasarkan preferensi user di session
-   Default locale adalah 'en' jika tidak ada preferensi
-   Menerapkan locale ke seluruh aplikasi

**Penggunaan:**

-   Applied globally di `bootstrap/app.php`
-   Otomatis membaca dari session setiap request

#### C. **Built-in Laravel Middleware:**

-   **auth:** Melindungi route dari akses tidak authenticated
-   **guest:** Melindungi route agar hanya guest yang bisa akses (login, register)
-   **verified:** Email verification (optional)
-   **throttle:** Rate limiting untuk mencegah abuse

**Middleware Stack:**

```php
// Group 1: Guest routes (tidak perlu login)
Route::middleware('guest')->group(function () {
    Route::get('/login', ...);
    Route::post('/login', ...);
});

// Group 2: Authenticated routes (perlu login)
Route::middleware('auth')->group(function () {
    // Routes untuk semua authenticated users

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {...});

    // Dosen-only routes
    Route::middleware('role:dosen')->group(function () {...});

    // Mahasiswa-only routes
    Route::middleware('role:mahasiswa')->group(function () {...});
});
```

**File Terkait:**

-   `app/Http/Middleware/CheckRole.php` - Role validation
-   `app/Http/Middleware/SetLocale.php` - Locale setting
-   `bootstrap/app.php` - Middleware registration
-   `routes/web.php` - Middleware usage in routes

---

