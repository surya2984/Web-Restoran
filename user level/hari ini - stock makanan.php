<?php
  include "../service/database.php";
 session_start();

  if(isset($_POST['logout'])){
    session_unset(); //menclear kan data
    session_destroy(); // menghancurkan data
    header('location:../index.php');
  }

  $sql = "SELECT * FROM menu";
  $result = $db->query($sql);
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hari ini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" />

    <style>
      body::-webkit-scrollbar{
        display: none; /* Menyembunyikan scrollbar */
      }
    </style>

</head>
<body>
   
    <!-- Navbar -->
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


        <div class="row d-flex justify-content-center  align-items-stretch m-5 pt-5 mb-3">
        <?php
        if ($result->num_rows > 0) {
            // Loop melalui semua data menu
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="../admin level/Aset/' . $row['image'] . '" class="card-img-top" alt="' . htmlspecialchars($row['menu']) . '">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['menu']) . '</h5>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <p class="card-text"><b>' . ($row['stok'] > 0 ? 'Tersedia' : 'Habis') . '</b></p>
                            </div>
                            
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p class="text-center">Tidak ada menu yang tersedia.</p>';
        }
        ?>
    </div>
    
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Footer -->
        <footer style="text-align: center">
          <p>&copy; 2024 Reservasi Online. All Rights Reserved.</p>
      </footer>

  </body>
</html>