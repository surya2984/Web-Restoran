# 🍽️ Nuansa Lama - Sistem Reservasi & Pemesanan Restoran

**Nuansa Lama** adalah aplikasi web berbasis PHP Native yang dirancang untuk mempermudah operasional restoran. Aplikasi ini memungkinkan pelanggan untuk melihat menu, melakukan pemesanan makanan (delivery/takeaway), dan melakukan reservasi meja secara online dengan pengecekan ketersediaan jadwal secara real-time.

## ✨ Fitur Utama

### 👤 User (Pelanggan)
* **Autentikasi Pengguna:** Sistem Login dan Signup aman untuk pelanggan.
* **Katalog Menu:** Melihat daftar menu berdasarkan kategori (Pembuka, Utama, Penutup, Minuman) yang diambil dinamis dari database.
* **Sistem Keranjang (Ordering):** Menambahkan menu ke keranjang, menghapus item, dan menghitung total harga otomatis.
* **Reservasi Meja:**
    * Memilih paket makanan, tanggal, jam, dan jumlah orang.
    * Pengecekan ketersediaan slot waktu secara real-time (mencegah *overbooking*).
* **Informasi Stok & Jadwal:** Melihat stok makanan yang tersedia hari ini dan jadwal reservasi yang sudah terisi.

### 🛡️ Admin (Tersirat dalam kode)
* **Manajemen Menu:** (Create, Read, Update, Delete menu).
* **Dashboard Admin:** Redirect khusus untuk role admin saat login.

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman:** PHP (Native)
* **Database:** MySQL
* **Frontend Framework:** Bootstrap 5 (Responsive Design)
* **Styling:** CSS Custom & Google Fonts
* **Server:** Apache (via XAMPP/Laragon)

## 📂 Struktur Folder

```text
/project-root
│
├── admin level/          # Halaman khusus Admin (Dashboard, Aset gambar menu)
├── Aset/                 # Gambar-gambar umum (Background, Promo, dll)
├── service/
│   └── database.php      # Koneksi konfigurasi database
├── user level/           # Halaman yang diakses user (file yang diupload)
│   ├── daftar menu - utama.php
│   ├── daftar menu - minuman.php
│   ├── keranjang.php
│   ├── layanan - reservasi.php
│   ├── login.php
│   └── ... (file lainnya)
├── index.php             # Landing page utama
└── README.md
