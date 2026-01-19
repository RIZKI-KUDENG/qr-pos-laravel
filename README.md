# QR POS Laravel 🚀

Sistem Point of Sale (POS) modern berbasis web yang mendukung Multi-tenancy, pemesanan via QR Code meja, dan manajemen dapur (Kitchen Display System). Dibangun menggunakan **Laravel**, **FilamentPHP**, dan **Livewire**.

Aplikasi ini memungkinkan pemilik restoran untuk mengelola menu, meja, dan pesanan secara efisien, sementara pelanggan dapat memesan langsung dari meja mereka dengan memindai kode QR.

## ✨ Fitur Utama

Berikut adalah fitur-fitur unggulan yang tersedia dalam aplikasi:

### 🏢 Manajemen & Admin (Owner Panel)
* **Multi-tenancy:** Dukungan untuk pengelolaan data tenant/cabang yang terisolasi.
* **Dashboard Analitik:** Statistik penjualan, grafik jam sibuk (*Peak Hours*), dan ringkasan pendapatan.
* **Manajemen Produk & Kategori:** Pengelolaan menu makanan/minuman dengan varian harga dan gambar.
* **Manajemen Meja (QR Table):** Pembuatan meja digital dan generasi QR Code unik untuk setiap meja.
* **Manajemen Pesanan:** Melihat dan mengubah status pesanan (Pending, Cooking, Ready, Paid).

### 📱 Client Side (Pelanggan)
* **Scan to Order:** Pelanggan memindai QR meja untuk melihat menu digital.
* **Keranjang Belanja:** Menambah item ke keranjang dan melakukan checkout mandiri.
* **Status Pesanan:** Pelanggan dapat memantau status pesanan mereka secara real-time.

### 🧑‍🍳 Operasional (Dapur & Kasir)
* **Kitchen Display System (KDS):** Tampilan layar dapur real-time berbasis Livewire untuk koki melihat pesanan masuk.
* **POS Kasir:** Antarmuka kasir untuk memproses pembayaran dan cetak struk.
* **Cetak Struk:** Fitur print struk pesanan thermal.

## 🛠️ Teknologi yang Digunakan

Project ini dibangun di atas stack teknologi modern:

* **Backend Framework:** [Laravel 12](https://laravel.com)
* **Admin Panel:** [FilamentPHP v4](https://filamentphp.com)
* **Fullstack Interactions:** [Livewire](https://livewire.laravel.com)
* **Frontend Logic:** [Alpine.js](https://alpinejs.dev)
* **Styling:** [Tailwind CSS](https://tailwindcss.com)
* **Database:** MySQL / MariaDB
* **Build Tool:** Vite

## 📋 Prasyarat Instalasi

Sebelum memulai, pastikan server atau komputer lokal Anda memiliki:

* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL Database
* Git

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lokal:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/rizki-kudeng/qr-pos-laravel.git](https://github.com/rizki-kudeng/qr-pos-laravel.git)
    cd qr-pos-laravel
    ```

2.  **Install Dependencies**
    Install paket PHP dan JavaScript:
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Setup Database & Storage**
    Jalankan migrasi database dan seeder, serta tautkan storage publik:
    ```bash
    php artisan migrate --seed
    php artisan storage:link
    ```

6.  **Build Assets**
    ```bash
    npm run build
    ```

7.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Aplikasi dapat diakses di `http://localhost:8000`.

## 📂 Susunan Project

Struktur direktori utama yang penting untuk dipahami:

* `app/Filament`: Berisi resource Filament (Admin & Owner Panel) seperti *Resources*, *Pages*, dan *Widgets*.
    * `Owner/Resources`: Logika manajemen Produk, Kategori, Meja, dan Order untuk pemilik restoran.
* `app/Livewire`: Komponen reaktif seperti `KitchenDisplay`, `ProductManager`, dan `Ordermanager`.
* `app/Models`: Model Eloquent database (`Product`, `Order`, `QrTable`, `Tenant`, dll).
* `resources/views/client`: Tampilan frontend untuk pelanggan (Menu digital, Cart, POS).
* `routes/web.php`: Definisi rute aplikasi.

## 📖 Contoh Penggunaan

1.  **Login Owner:**
    Akses `/owner` (atau path yang dikonfigurasi di Filament) dan login menggunakan akun yang dibuat di seeder.
2.  **Setup Menu:**
    Masuk ke menu **Categories** untuk buat kategori, lalu ke **Products** untuk tambah menu makanan.
3.  **Buat QR Meja:**
    Masuk ke menu **QR Tables**, buat meja baru. Anda dapat mengunduh/mencetak QR Code tersebut.
4.  **Simulasi Pesanan:**
    Buka URL dari hasil scan QR (misal: `/menu/{uuid}`). Pilih makanan -> Checkout.
5.  **Dapur & Kasir:**
    * Buka halaman **Kitchen Display** untuk melihat pesanan masuk.
    * Buka halaman **POS** atau **Orders** di panel admin untuk memproses pembayaran.

## 🤝 Kontribusi

Kontribusi selalu diterima! Jika Anda ingin berkontribusi:

1.  Fork repository ini.
2.  Buat branch fitur baru (`git checkout -b fitur-keren`).
3.  Commit perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4.  Push ke branch tersebut (`git push origin fitur-keren`).
5.  Buat Pull Request.

## 📄 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE). Silakan gunakan dan modifikasi sesuai kebutuhan Anda.