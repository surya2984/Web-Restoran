<?php
session_start();
include "../service/database.php";
$isLoggedIn = isset($_SESSION['username']); // Periksa apakah sesi login sudah ada

if (isset($_POST['logout'])) {
    session_unset(); // Menghapus data sesi
    session_destroy(); // Menghancurkan data sesi
    header('location:../index.php');
}

if (isset($_POST['submit_order'])) {
  $username = $_SESSION['username'];
  // Ambil data dari form
  $gambar = $_POST['gambar'];
  $nama_menu = $_POST['nama_menu'];
  $jumlah = (int)$_POST['jumlah'];
  $harga = (int)$_POST['harga'];
  $total_harga = $jumlah * $harga;

   // Query untuk menyimpan data
   $sql = "INSERT INTO orders (username,gambar, nama_menu, jumlah, harga, total_harga) 
   VALUES ('$username', '$gambar', '$nama_menu', $jumlah, $harga, $total_harga)";

    if ($db->query($sql) === TRUE) {
    // Jika berhasil, arahkan ke halaman konfirmasi atau keranjang
    header("location: keranjang.php");
    } else {
    // Jika gagal, tampilkan pesan kesalahan
    echo "Error: " . $sql . "<br>" . $db->error;
    }

    $db->close();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pemesanan</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0&icon_names=shopping_cart_checkout"
    />

    <style>
      body::-webkit-scrollbar{
        display: none; /* Menyembunyikan scrollbar */
      }
    </style>


  </head>
  <body>
    <!-- Navigasi -->

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
                  <a class="dropdown-item" href="./daftar menu - pembuka.php"
                    >Menu pembuka</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./daftar menu - utama.php"
                    >Menu utama</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./daftar menu - penutup.php"
                    >Menu penutup</a
                  >
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <a class="dropdown-item" href="./daftar menu - minuman.php"
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
                  <a class="dropdown-item" href="./layanan - pemesanan.php"
                    >Pemesanan</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./layanan - reservasi.php"
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

    <!-- Menu pembuka -->

    <div class="row d-flex justify-content-center m-5 pt-3">
        <div class="col-md-3">
          <div class="card">
         
            <img
              src="../aset/Tape goreng.webp"
              class="card-img-top"
              alt="Tape Goreng"
            />
            <div class="card-body">
              <h5 class="card-title">Tape Goreng</h5>

              <span class="price">Rp. 37.000</span>

              <hr />

              <form action="layanan - pemesanan.php" method="POST">
                <input type="hidden" name="gambar" value="../aset/Tape goreng.webp">
                <input type="hidden" name="nama_menu" value="Tape Goreng">
                <input type="hidden" name="harga" value="37000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>

              
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card">
            <img
              src="../aset/Lumpia Rendang.webp"
              class="card-img-top"
              alt="Lumpia Rendang"
            />
            <div class="card-body">
              <h5 class="card-title">Lumpia Rendang</h5>

              <span class="price">Rp. 33.000</span>

              <hr />

              <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="gambar" value="../aset/Lumpia Rendang.webp">
                <input type="hidden" name="nama_menu" value="Lumpia Rendang">
                <input type="hidden" name="harga" value="33000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>


            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card">
            <img
              src="../aset/Rujak Serut Modern.webp"
              class="card-img-top"
              alt="Rujak Serut Modern"
            />
            <div class="card-body">
              <h5 class="card-title">Rujak Serut Modern</h5>

              <span class="price">Rp. 35.000</span>

              <hr />

              <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="gambar" value="../aset/Rujak Serut Modern.webp">
                <input type="hidden" name="nama_menu" value="Rujak Serut Modern">
                <input type="hidden" name="harga" value="35000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card">
            <img
              src="../aset/Keripik Tempe Saus Mentai.webp"
              class="card-img-top"
              alt="Tempe Saus Mentai"
            />
            <div class="card-body">
              <h5 class="card-title">Tempe saus Mentai</h5>

              <span class="price">Rp. 35.000</span>

              <hr />

              <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="gambar" value="../aset/Keripik Tempe Saus Mentai.webp">
                <input type="hidden" name="nama_menu" value="Tempe Saus Mentai">
                <input type="hidden" name="harga" value="35000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Menu Utama -->

    <div class="row d-flex justify-content-center m-5 pt-3">
        <div class="col-md-3">
            <div class="card">
                <img src="../aset/Nasi Goreng Kampung with Wagyu Beef.webp" class="card-img-top" alt="Nasi goreng" />
                <div class="card-body">
                    <h5 class="card-title">Nasi Goreng wagyu Beef</h5>  
                    <span class="price">Rp. 48.000</span>

                    <hr />

                    <form action="layanan - pemesanan.php" method="POST">
                
                  <input type="hidden" name="gambar" value="../aset/Nasi Goreng Kampung with Wagyu Beef.webp">
                  <input type="hidden" name="nama_menu" value="Nasi Goreng Wagyu Beef">
                  <input type="hidden" name="harga" value="48000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
          <div class="card">
              <img src="../aset/Ayam Bakar Madu dengan Salad Mangga Muda.webp" class="card-img-top" alt="Ayam Bakar" />
              <div class="card-body">
                  <h5 class="card-title">Ayam Bakar Madu salad</h5>
                  <span class="price">Rp. 45.000</span>

                  <hr />

                  <form action="layanan - pemesanan.php" method="POST">
                
                  <input type="hidden" name="gambar" value="../aset/Ayam Bakar Madu dengan Salad Mangga Muda.webp">
                  <input type="hidden" name="nama_menu" value="Ayam Bakar Madu Salad">
                  <input type="hidden" name="harga" value="45000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>


              </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <img src="../aset/Iga Bakar Saus Kopi Nusantara.webp" class="card-img-top" alt="Iga bakar" />
                <div class="card-body">

                    <h5 class="card-title">Iga Bakar</h5>
                    <span class="price">Rp. 95.000</span>

                    <hr />

                    <form action="layanan - pemesanan.php" method="POST">
                      
                      <input type="hidden" name="gambar" value="../aset/Iga Bakar Saus Kopi Nusantara.webp">
                      <input type="hidden" name="nama_menu" value="Iga Bakar">
                      <input type="hidden" name="harga" value="95000">
                        <div class="d-flex justify-content-center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <button type="button" class="minus btn btn-primary">-</button>
                          <button type="button" class="num btn btn-outline-primary" name="jumlah">
                            01
                          </button>
                          <button type="button" class="plus btn btn-primary rounded-end">+</button>
                          <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                        </div>
                      </div>

                      <div class="d-grid mx-auto mt-3">
                          <?php if ($isLoggedIn): ?>
                              <!-- Jika sudah login, arahkan ke halaman keranjang -->
                              <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                          <?php else: ?>
                              <!-- Jika belum login, munculkan modal -->
                              <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                          <?php endif; ?>
                      </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

                </div>
          </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <img src="../aset/Mie Godog Fusion dengan Udang Tempura.webp" class="card-img-top" alt="Mie godog" />
                <div class="card-body">
                    <h5 class="card-title">Mie Godog Fusion</h5>
                    
                    <span class="price">Rp. 25.000</span>

                    <hr />

                    <form action="layanan - pemesanan.php" method="POST">
                
                    <input type="hidden" name="gambar" value="../aset/Mie Godog Fusion dengan Udang Tempura.webp">
                    <input type="hidden" name="nama_menu" value="Mie Godog Fusion">
                    <input type="hidden" name="harga" value="25000">
                      <div class="d-flex justify-content-center">
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="minus btn btn-primary">-</button>
                        <button type="button" class="num btn btn-outline-primary" name="jumlah">
                          01
                        </button>
                        <button type="button" class="plus btn btn-primary rounded-end">+</button>
                        <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                      </div>
                    </div>

                    <div class="d-grid mx-auto mt-3">
                        <?php if ($isLoggedIn): ?>
                            <!-- Jika sudah login, arahkan ke halaman keranjang -->
                            <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                        <?php else: ?>
                            <!-- Jika belum login, munculkan modal -->
                            <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                        <?php endif; ?>
                    </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

                </div>
          </div>
        </div>

    </div>
    
    <!-- Menu Penutup -->
    <div class="row d-flex justify-content-center m-5 pt-3">
      <div class="col-md-3">
        <div class="card">
          <img
            src="../aset/Klepon lava cake.webp"
            class="card-img-top"
            alt="Klepon"
          />
          <div class="card-body">
            <h5 class="card-title">Klepon Lava Cake</h5>
            <span class="price">Rp. 24.000</span>

            <hr />

            <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="nama_menu" value="Klepon Lava Cake">
                <input type="hidden" name="harga" value="24000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <img
            src="../aset/Puding gula jawa.webp"
            class="card-img-top"
            alt="Puding"
          />
          <div class="card-body">
            <h5 class="card-title">Puding Gula Jawa</h5>
            <span class="price">Rp. 38.000</span>

            <hr />

            <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="gambar" value="../aset/Puding gula jawa.webp">
                <input type="hidden" name="nama_menu" value="Puding Gula Jawa">
                <input type="hidden" name="harga" value="38000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <img
            src="../aset/Es Doger Panna Cotta.webp"
            class="card-img-top"
            alt="Es doger"
          />
          <div class="card-body">
            <h5 class="card-title">Es doger panna cotta</h5>

            <span class="price">Rp. 22.000</span>
            <hr />

            <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="nama_menu" value="Es Doger Panna Cotta">
                <input type="hidden" name="harga" value="22000">
                <input type="hidden" name="gambar" value="../aset/Es Doger Panna Cotta.webp">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <img
            src="../aset/Pisang Goreng Cheese Fondue.webp"
            class="card-img-top"
            alt="Pisang goreng"
          />
          <div class="card-body">
            <h5 class="card-title">Pisang goreng Cheese</h5>

            <span class="price">Rp. 33.000</span>
            <hr />

            <form action="layanan - pemesanan.php" method="POST">
                
                <input type="hidden" name="gambar" value="../aset/Pisang Goreng Cheese Fondue.webp">
                <input type="hidden" name="nama_menu" value="Pisang Goreng Cheese">
                <input type="hidden" name="harga" value="33000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>
          </div>
        </div>
      </div>

      <!-- Minuman -->

      

    <div class="row d-flex justify-content-center m-5 pt-3">
            <div class="col-md-3">
              <div class="card">
                <img
                  src="../aset/Es Teh Tarik Rempah.webp"
                  class="card-img-top"
                  alt="Es Teh"
                />
                <div class="card-body">
                  <h5 class="card-title">Es Teh Tarik Rempah</h5>
                  <span class="price">Rp. 15.000</span>
                  <hr>

                  <form action="layanan - pemesanan.php" method="POST">
                
                  <input type="hidden" name="gambar" value="../aset/Es Teh Tarik Rempah.webp">
                  <input type="hidden" name="nama_menu" value="Es Teh Tarik Rempah">
                  <input type="hidden" name="harga" value="15000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

                </div>
              </div>
            </div>
      
            <div class="col-md-3">
              <div class="card">
                <img
                  src="../aset/Wedang Jahe Lemon Mint.webp"
                  class="card-img-top"
                  alt="Wedang jahe"
                />
                <div class="card-body">
                  <h5 class="card-title">Wedang Jahe Lemon Mint</h5>
                  
                  <span class="price">Rp. 20.000</span>
                  <hr>

                  <form action="layanan - pemesanan.php" method="POST">
                
                  <input type="hidden" name="gambar" value="../aset/Wedang Jahe Lemon Mint.webp">
                  <input type="hidden" name="nama_menu" value="Wedang Jahe Lemon Mint">
                  <input type="hidden" name="harga" value="20000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

                </div>
              </div>
            </div>
      
            <div class="col-md-3">
              <div class="card">
                <img
                  src="../aset/Kopi susu ubi ungu.jpeg"
                  class="card-img-top"
                  alt="Kopi"
                />
                <div class="card-body">
                  <h5 class="card-title">Kopi Susu Ubi Ungu</h5>
                  
                  <span class="price">Rp. 19.000</span>

                  <hr>

                  <form action="layanan - pemesanan.php" method="POST">
                
                  <input type="hidden" name="gambar" value="../aset/Kopi susu ubi ungu.jpeg">
                  <input type="hidden" name="nama_menu" value="Kopi Susu Ubi Ungu">
                  <input type="hidden" name="harga" value="19000">
                  <div class="d-flex justify-content-center">
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="minus btn btn-primary">-</button>
                    <button type="button" class="num btn btn-outline-primary" name="jumlah">
                      01
                    </button>
                    <button type="button" class="plus btn btn-primary rounded-end">+</button>
                    <input type="hidden" name="jumlah" class="jumlah-hidden" value="1">
                  </div>
                </div>

                <div class="d-grid mx-auto mt-3">
                    <?php if ($isLoggedIn): ?>
                        <!-- Jika sudah login, arahkan ke halaman keranjang -->
                        <button type="submit" name="submit_order" class="btn btn-primary col-50">Tambahkan ke Keranjang</button>
                    <?php else: ?>
                        <!-- Jika belum login, munculkan modal -->
                        <button type="button" class="btn btn-primary col-50" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambahkan ke Keranjang</button>
                    <?php endif; ?>
                </div>
                
              </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Pengingat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Login terlebih dahulu untuk melanjutkan transaksi dan nikmati berbagai hidangan lezat kami. Selamat berkuliner!
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="./login.php">
                            <button type="button" class="btn btn-primary">Masuk</button>
                        </a>
                        
                        </div>
                    </div>
                    </div>
                </div>

                </div>
              </div>
            </div>
          </div>

        <!-- Footer -->
        <footer style="text-align: center">
            <p>&copy; 2024 Reservasi Online. All Rights Reserved.</p>
        </footer>

    <script>
      const plusButtons = document.querySelectorAll(".plus");
      const minusButtons = document.querySelectorAll(".minus");
      const numElements = document.querySelectorAll(".num");
      const hiddenInputs = document.querySelectorAll(".jumlah-hidden");

      plusButtons.forEach((plus, index) => {
        plus.addEventListener("click", () => {
          let a = parseInt(numElements[index].innerText);
          a++;
          a = a < 10 ? "0" + a : a;
          numElements[index].innerText = a;
          hiddenInputs[index].value = a; // Perbarui nilai hidden input
        });
      });

      minusButtons.forEach((minus, index) => {
        minus.addEventListener("click", () => {
          let a = parseInt(numElements[index].innerText);
          if (a > 1) {
            a--;
            a = a < 10 ? "0" + a : a;
            numElements[index].innerText = a;
            hiddenInputs[index].value = a; // Perbarui nilai hidden input
          }
        });
      });

    </script>
  </body>

</html>
