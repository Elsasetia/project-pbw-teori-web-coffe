# Web Coffee — Laravel 12 (Blade + Tailwind + MySQL)

Paket ini adalah **overlay**: hanya berisi file yang kamu tambahkan/ubah di atas
project Laravel 12 baru + Laravel Breeze. Jadi kamu tidak perlu mengunduh seluruh
folder `vendor` dari saya, cukup ikuti langkah di bawah.

## 1. Prasyarat

- PHP 8.2 atau lebih baru (ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `gd` atau `imagick` tidak wajib)
- Composer 2
- Node.js 18+ dan npm
- MySQL / MariaDB (XAMPP sudah cukup)

Cek dengan `php -v`, `composer -V`, `node -v`.

## 2. Instalasi langkah demi langkah

```bash
# 1. Buat project Laravel 12
composer create-project laravel/laravel web-coffee
cd web-coffee

# 2. Pasang Breeze (autentikasi: login, register, reset password, profil)
composer require laravel/breeze --dev
php artisan breeze:install blade
#   - Dark mode?      -> No
#   - Testing framework -> PHPUnit (atau Pest, bebas)

# 3. Pasang dependensi front-end
npm install
```

**4. Salin isi paket ini ke dalam project** (folder `app`, `database`, `resources`, `routes`).
Pilih *Replace/Timpa* kalau diminta. File bawaan yang ikut tertimpa:

| File | Alasan |
|---|---|
| `app/Models/User.php` | tambah `role`, `phone`, `address`, `isAdmin()`, relasi |
| `routes/web.php` | seluruh route toko & admin (tetap memuat route Breeze) |
| `database/seeders/DatabaseSeeder.php` | akun demo + menu contoh |

**5. Atur `.env`**

```env
APP_NAME="Web Coffee"
APP_URL=http://localhost:8000      # harus sama dengan alamat `php artisan serve`, kalau tidak gambar upload tidak muncul
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta          # supaya "hari ini" di dashboard sesuai waktu Indonesia

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_coffee
DB_USERNAME=root
DB_PASSWORD=
```

Buat databasenya (phpMyAdmin atau terminal):

```bash
mysql -u root -e "CREATE DATABASE web_coffee CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
```

**6. Daftarkan alias middleware `admin`** di `bootstrap/app.php`:

```php
use App\Http\Middleware\EnsureUserIsAdmin;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => EnsureUserIsAdmin::class,
    ]);
})
```

**7. Migrasi, seed, dan jalankan**

```bash
php artisan migrate --seed
php artisan storage:link        # supaya gambar upload bisa diakses publik

# dua terminal:
npm run dev
php artisan serve
```

Buka http://localhost:8000

| Peran | Email | Password |
|---|---|---|
| Admin | admin@webcoffee.test | password123 |
| Pelanggan | budi@webcoffee.test | password123 |

Pelanggan baru yang mendaftar lewat form otomatis berperan `customer`.
Untuk menjadikan seseorang admin: ubah kolom `role` di tabel `users` menjadi `admin`.

## 3. Struktur folder

```
app/
├── Enums/                  UserRole, OrderStatus, PaymentMethod
├── Exceptions/             CheckoutException, InsufficientStockException
├── Http/
│   ├── Controllers/        Home, Product, Cart, Checkout, Order, Feedback
│   │   └── Admin/          Dashboard, Product, Category, Order, Feedback
│   ├── Middleware/         EnsureUserIsAdmin
│   └── Requests/           CartItem, Checkout, Feedback, Product, Category, UpdateOrderStatus
├── Models/                 User, Category, Product, Order, OrderItem, Feedback
│   └── Concerns/           GeneratesSlug (trait)
├── Policies/               OrderPolicy
├── Services/               CartService, OrderService, ProductService, DashboardService
└── Support/                CartLine (DTO)
database/
├── migrations/             6 file (users+role, categories, products, orders, order_items, feedback)
└── seeders/                DatabaseSeeder
resources/views/
├── components/             button, input, select, textarea, field, flash, money, stars,
│   │                       status-badge, product-card
│   └── layouts/            shop, admin
├── home, products/, cart/, checkout/, orders/, feedback/
└── admin/                  dashboard, products/, categories/, orders/, feedback/
routes/web.php
```

## 4. Skema database

```
users ──< orders ──< order_items >── products >── categories
  └──< feedback
```

| Tabel | Kolom penting | Catatan |
|---|---|---|
| `users` | `role` (admin/customer), `phone`, `address` | ditambah lewat migrasi tersendiri, tabel bawaan Laravel tidak diubah |
| `categories` | `name`, `slug` | |
| `products` | `category_id`, `name`, `slug`, `price`, `stock`, `image`, `is_active` | harga disimpan sebagai integer Rupiah |
| `orders` | `order_code`, `user_id`, `status`, `payment_method`, `total`, data pemesan | status & metode bayar dipetakan ke enum PHP |
| `order_items` | `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal` | nama & harga disalin saat checkout, jadi riwayat tetap benar walau menu diubah/dihapus |
| `feedback` | `user_id` (boleh kosong), `name`, `rating`, `message`, `is_approved` | tamu tanpa akun boleh mengirim |

Aturan hapus: kategori dihapus → menu jadi tanpa kategori; menu dihapus → riwayat
pesanan tetap ada (`product_id` jadi `NULL`); pesanan dihapus → item ikut terhapus.

## 5. Alur satu request (checkout)

```
POST /checkout
  → middleware auth
  → CheckoutRequest            validasi nama, telepon, metode bayar
  → CheckoutController@store   ± 10 baris, hanya memanggil service
  → OrderService::placeOrder   transaksi DB + kunci baris stok + hitung total
  → Order / OrderItem / Product (Eloquent)
  → redirect ke detail pesanan
```

Controller tidak berisi logika bisnis. Kalau stok tidak cukup, `OrderService`
melempar `InsufficientStockException`; controller menangkapnya dan menampilkan
pesannya ke pelanggan. Tidak ada pesanan setengah jadi karena semuanya dalam satu transaksi.

## 6. Keputusan desain

**Service layer, tanpa Repository.** Eloquent sudah merupakan lapisan akses data.
Membungkusnya lagi dalam repository menambah banyak file tanpa manfaat nyata di
project sebesar ini. Logika yang melibatkan banyak langkah (checkout, ubah status
+ kembalikan stok, upload gambar, statistik) ada di `app/Services`. Query baca
sederhana tetap lewat *scope* Eloquent (`active()`, `search()`, `inCategory()`).
Kalau suatu saat butuh ganti sumber data, repository bisa ditambahkan di balik service tanpa mengubah controller.

**Pesanan batal itu final.** Membatalkan pesanan mengembalikan stok. Mengaktifkan
kembali pesanan yang sudah batal akan butuh cek stok ulang, jadi sengaja tidak diizinkan.

**Keranjang di session.** Yang disimpan hanya `product_id => jumlah`; harga dan stok
dibaca ulang dari database setiap kali. Keranjang tamu tetap ada setelah login.

**Fitur POS/kasir dari versi PHP native tidak dibawa.** Brief fitur utamanya tidak
menyebutnya. Kalau mau, tambahkan kolom `order_type` di `orders` dan satu
controller admin yang memakai `OrderService` yang sama.

## 7. Masalah umum

| Gejala | Penyebab / solusi |
|---|---|
| `Vite manifest not found` | jalankan `npm run dev` (atau `npm run build`) |
| `Target class [admin] does not exist` | langkah 6 (alias middleware) belum dilakukan |
| Gambar upload tidak muncul | jalankan `php artisan storage:link` dan cek `APP_URL` di `.env` |
| `SQLSTATE ... Access denied` | cek `DB_USERNAME` / `DB_PASSWORD` di `.env` |
| Error 419 (Page Expired) | refresh halaman, atau jalankan `php artisan config:clear` |
| Pesan validasi masih bahasa Inggris | opsional: `composer require laravel-lang/common --dev` lalu `php artisan lang:add id` |
| Mau reset data | `php artisan migrate:fresh --seed` |
