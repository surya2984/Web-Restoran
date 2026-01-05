<?php
session_start();
include "../service/database.php";

$isLoggedIn = isset($_SESSION['username']); // Periksa apakah sesi login sudah ada

if (isset($_POST['reservasi'])) {
    // Harga paket makanan
    $paket_harga = [
        'Paket 1' => 100000,
        'Paket 2' => 115000,
        'Paket 3' => 150000,
        'Paket 4' => 200000,
    ];

    // Ambil data input pengguna
    $makanan = $_POST['makanan'];
    $harga = isset($paket_harga[$makanan]) ? $paket_harga[$makanan] : 0;

          // Ambil jumlah orang sebagai angka
      $jumlah_orang = intval($_POST['jumlah_orang']);

      // Hitung total harga berdasarkan harga paket dan jumlah orang
      $total_harga = $harga * $jumlah_orang;

      // Simpan data reservasi ke sesi
      $_SESSION['reservasi'] = [
          'nama_lengkap' => $_POST['nama_lengkap'],
          'no_telephone' => $_POST['no_telephone'],
          'jumlah_orang' => $jumlah_orang,
          'tanggal' => $_POST['tanggal'],
          'jam' => $_POST['jam'],
          'makanan' => $makanan,
          'total_harga' => $total_harga,
      ];


    // Arahkan ke halaman nota pembayaran
    header('Location: payment-reservasi.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_unset(); // Hapus semua data sesi
    session_destroy(); // Hancurkan sesi
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      body::-webkit-scrollbar{
        display: none; /* Menyembunyikan scrollbar */
      }
    </style>
</head>
<body>

<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <!-- Logo berada di kiri -->
        <a class="navbar-brand mb-1" href="#">Nuansa Lama</a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menunya di tengah -->

        <div
          class="collapse navbar-collapse justify-content-center"
          id="navbarSupportedContent"
        >
          <ul class="navbar-nav mx-auto mb-1 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="../index.php"
                >Home</a
              >
            </li>

            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href=""
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Daftar Menu
              </a>
              
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="daftar menu - pembuka.php"
                    >Menu pembuka</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="daftar menu - utama.php"
                    >Menu utama</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="daftar menu - penutup.php"
                    >Menu penutup</a
                  >
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <a class="dropdown-item" href="daftar menu - minuman.php"
                    >Minuman</a
                  >
                </li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Layanan
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="layanan - pemesanan.php"
                    >Pemesanan</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="layanan - reservasi.php"
                    >Reservasi</a
                  >
                </li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Hari Ini
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a
                    class="dropdown-item"
                    href="./hari ini - stock makanan.php"
                    >Stok makanan</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./hari ini - jadwal.php"
                    >Jadwal reservasi</a
                  >
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="./promo.php"
                >Promo</a
              >
            </li>

            <li class="nav-item">
              <a
                class="nav-link active"
                aria-current="page"
                href="./tentang kamii.php"
                >Tentang Kami</a
              >
            </li>
          </ul>
        </div>

        <!-- Tombol Login dan Signup di kanan -->

        <div class="d-flex">
          <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
              <form action="../index.php" method="POST">
                  <button
                      type="submit"
                      name="logout"
                      class="btn btn-outline-danger"
                  >
                      Logout
                  </button>
              </form>
          <?php else: ?>
              <a class="btn btn-outline-primary me-2" href="login.php">Login</a>
              <a class="btn btn-primary" href="signup.php">Signup</a>
          <?php endif; ?>
      </div>

      </div>
    </nav>

    <div class="container my-5">
        <br>
        <form action="layanan - reservasi.php" method="POST">
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" required>
            </div>
            <div class="mb-3">
                <label for="no_telephone" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" name="no_telephone" required>
            </div>
            
            <div class="mb-3">
                <label for="jumlah_orang" class="form-label">Jumlah Orang</label>
                <select class="form-select" name="jumlah_orang" required>
                    <option value="5">5 orang</option>
                    <option value="10">10 orang</option>
                    <option value="15">15 orang</option>
                    <option value="20">20 orang</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="jam" class="form-label">Jam</label>
                <input type="time" class="form-control" name="jam" required>
            </div>
            <div class="mb-3">
                
                <img src="../Aset/paket.jpg" class="img-fluid w-20" alt="...">
            </div>

            <div class="mb-3">
                <label for="makanan" class="form-label">Pilih Paketan</label>
                <select class="form-select" name="makanan" required>
                    <option value="Paket 1">Paket 1 - Rp. 100.000</option>
                    <option value="Paket 2">Paket 2 - Rp. 115.000</option>
                    <option value="Paket 3">Paket 3 - Rp. 150.000</option>
                    <option value="Paket 4">Paket 4 - Rp. 200.000</option>
                </select>
            </div>
            <button type="submit" name="reservasi" class="btn btn-primary w-100">Reservasi Sekarang</button>
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</html>
