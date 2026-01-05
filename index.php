<?php
 session_start();

  if(isset($_POST['logout'])){
    session_unset(); //menclear kan data
    session_destroy(); // menghancurkan data
    header('location:index.php');
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

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
              <a class="nav-link active" aria-current="page" href="./index.php"
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
                  <a class="dropdown-item" href="./user level/daftar menu - pembuka.php"
                    >Menu pembuka</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./user level/daftar menu - utama.php"
                    >Menu utama</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./user level/daftar menu - penutup.php"
                    >Menu penutup</a
                  >
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <a class="dropdown-item" href="./user level/daftar menu - minuman.php"
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
                  <a class="dropdown-item" href="./user level/layanan - pemesanan.php"
                    >Pemesanan</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./user level/layanan - reservasi.php"
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
                    href="./user level/hari ini - stock makanan.php"
                    >Stok makanan</a
                  >
                </li>
                <li>
                  <a class="dropdown-item" href="./user level/hari ini - jadwal.php"
                    >Jadwal reservasi</a
                  >
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="./user level/promo.php"
                >Promo</a
              >
            </li>

            <li class="nav-item">
              <a
                class="nav-link active"
                aria-current="page"
                href="./user level/tentang kamii.php"
                >Tentang Kami</a
              >
            </li>
          </ul>
        </div>

        <!-- Tombol Login dan Signup di kanan -->

        <div class="d-flex">
          <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
              <form action="index.php" method="POST">
                  <button
                      type="submit"
                      name="logout"
                      class="btn btn-outline-danger"
                  >
                      Logout
                  </button>
              </form>
          <?php else: ?>
              <a class="btn btn-outline-primary me-2" href="./user level/login.php">Login</a>
              <a class="btn btn-primary" href="./user level/signup.php">Signup</a>
          <?php endif; ?>
      </div>

        
      </div>
    </nav>

    <!-- Caraousel (gambar gerak gerak) -->

    <div id="carouselExampleCaptions" class="carousel slide">
      <div class="carousel-indicators">
        <button
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide-to="0"
          class="active"
          aria-current="true"
          aria-label="Slide 1"
        ></button>
        <button
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide-to="1"
          aria-label="Slide 2"
        ></button>
        <button
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide-to="2"
          aria-label="Slide 3"
        ></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img
            src="./Aset/1.jpg"
            class="d-block w-100"
            alt="banner1"
            style="height: fit-content"
          />
          <div class="carousel-caption d-none d-md-block">
            <h5>Suasana Nyaman untuk Semua</h5>
            <p>
              Datang dan nikmati suasana hangat dan ramah kami, tempat yang
              sempurna untuk berkumpul bersama teman dan keluarga sambil
              menikmati hidangan lezat.
            </p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="./Aset/2.jpg" class="d-block w-100" alt="banner2" />
          <div class="carousel-caption d-none d-md-block">
            <h5>Menu Spesial Setiap Hari</h5>
            <p>
              Temukan menu spesial kami yang berubah setiap hari! Dari makanan
              pembuka hingga pencuci mulut, kami selalu memiliki sesuatu yang
              baru untuk Anda coba.
            </p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="./Aset/3.jpg" class="d-block w-100" alt="banner3" />
          <div class="carousel-caption d-none d-md-block">
            <h5>Rasakan Cita Rasa Autentik</h5>
            <p>
              Kami menyajikan hidangan tradisional dengan sentuhan modern yang
              menggugah selera. Bergabunglah bersama kami untuk pengalaman
              kuliner yang tak terlupakan!
            </p>
          </div>
        </div>
      </div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <!-- card -->

    <div class="container mt-5">
      <h2 class="text-center mb-4">Menu Spesial</h2>
      <div class="row">
        <div class="col-md-3 mb-3">
          <a href="./user level/daftar menu - pembuka.php" style="text-decoration: none">
            <div class="card">
              <img
                src="./Aset/Lumpia Rendang.webp"
                class="card-img-top"
                alt="Makanan Pembuka"
              />
              <div class="card-body">
                <h5 class="card-title">Makanan Pembuka</h5>
                <p class="card-text">
                  Hidangan yang disajikan di awal untuk membangkitkan selera.
                </p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="./user level/daftar menu - utama.php" style="text-decoration: none">
            <div class="card">
              <img
                src="./Aset/Nasi Goreng Kampung with Wagyu Beef.webp"
                class="card-img-top"
                alt="Makanan Berat"
              />
              <div class="card-body">
                <h5 class="card-title">Makanan Berat</h5>
                <p class="card-text">
                  Makanan dengan porsi besar dan nutrisi yang padat.
                </p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="./user level/daftar menu - penutup.php" style="text-decoration: none">
            <div class="card">
              <img
                src="./Aset/Klepon lava cake.webp"
                class="card-img-top"
                alt="Pencuci Mulut"
              />
              <div class="card-body">
                <h5 class="card-title">Pencuci Mulut</h5>
                <p class="card-text">
                  Pencuci mulut yang disajikan setelah makan utama.
                </p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="./user level/daftar menu - minuman.php" style="text-decoration: none">
            <div class="card">
              <img
                src="./Aset/Es doger.webp"
                class="card-img-top"
                alt="Minuman"
              />
              <div class="card-body">
                <h5 class="card-title">Minuman</h5>
                <p class="card-text">
                  Segarkan diri Anda dengan berbagai minuman kami.
                </p>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center">
      <p>&copy; 2024 Reservasi Online. All Rights Reserved.</p>
    </footer>
  </body>
</html>
