<?php
 session_start();

  if(isset($_POST['logout'])){
    session_unset(); //menclear kan data
    session_destroy(); // menghancurkan data
    header('location:../index.php');
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />


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
      </div>
    </nav>

    <div class="container mt-5 pt-5">
      <!-- About Us Section: Gambar di kiri, teks di kanan -->
      <div class="row align-items-center my-4">
        <div class="col-lg-5 col-12">
          <img
            src="../Aset/Restoran.jpg"
            alt="Gambar About Us 1"
            class="img-fluid rounded"
          />
        </div>
        <div class="col-lg-7 col-12">
          <p>
            Kami adalah perusahaan yang berdedikasi untuk memberikan layanan
            terbaik. Kami selalu berusaha untuk memenuhi kebutuhan pelanggan
            dengan cara yang inovatif dan berkualitas. Kami percaya bahwa setiap
            tantangan adalah kesempatan untuk berkembang dan belajar lebih
            banyak. Tim kami siap memberikan layanan yang memuaskan dan hasil
            yang optimal untuk setiap proyek.
          </p>
        </div>
      </div>

      <!-- About Us Section: Gambar di kanan, teks di kiri -->
      <div class="row align-items-center my-4 flex-lg-row-reverse">
        <div class="col-lg-5 col-12">
          <img
            src="../Aset/Restoran.jpg"
            alt="Gambar About Us 2"
            class="img-fluid rounded"
          />
        </div>
        <div class="col-lg-7 col-12">
          <p>
            Visi kami adalah menjadi pemimpin dalam industri dengan memberikan
            solusi inovatif dan layanan yang unggul. Kami berkomitmen untuk
            selalu memenuhi harapan pelanggan kami dengan cara yang
            berkelanjutan dan efisien. Dengan pendekatan yang berbasis pada
            nilai-nilai integritas dan profesionalisme, kami yakin dapat
            mencapai tujuan kami dan terus berkembang dalam industri.
          </p>
        </div>
      </div>

      <!-- About Us Section: Gambar di kiri, teks di kanan -->
      <div class="row align-items-center my-4">
        <div class="col-lg-5 col-12">
          <img
            src="../Aset/Restoran.jpg"
            alt="Gambar About Us 3"
            class="img-fluid rounded"
          />
        </div>
        <div class="col-lg-7 col-12">
          <p>
            Tim kami terdiri dari para profesional berpengalaman yang memiliki
            keahlian di berbagai bidang. Kami bekerja dengan semangat kolaborasi
            untuk mencapai hasil terbaik dan memastikan setiap proyek berjalan
            dengan lancar. Dengan latar belakang yang kuat dan pengalaman yang
            luas, kami siap menghadapi tantangan dan mencapai kesuksesan bersama
            klien kami.
          </p>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
