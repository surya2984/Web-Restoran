<?php
// Koneksi ke database
include "../service/database.php";
session_start();

// Definisikan kapasitas maksimum per slot
$kapasitasMaksimum = 20;

// Ambil data reservasi dari tabel data_reservasi
$query = "SELECT tanggal, jam, SUM(jumlah_orang) AS total_orang FROM data_reservasi GROUP BY tanggal, jam";
$result = $db->query($query);

// Array untuk menyimpan data jumlah orang per slot waktu
$dataReservasi = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tanggal = $row['tanggal'];
        $jam = $row['jam'];
        $totalOrang = (int)$row['total_orang'];

        // Simpan data ke array berdasarkan tanggal dan jam
        $dataReservasi[$tanggal][$jam] = $totalOrang;
    }
}

// Ambil jam unik dari tabel data_reservasi
$queryJam = "SELECT DISTINCT jam FROM data_reservasi ORDER BY jam ASC";
$resultJam = $db->query($queryJam);
$slotWaktu = [];

if ($resultJam->num_rows > 0) {
    while ($row = $resultJam->fetch_assoc()) {
        $slotWaktu[] = $row['jam']; // Simpan jam ke dalam array
    }
}

// Ambil semua tanggal unik dari tabel data_reservasi
$queryTanggal = "SELECT DISTINCT tanggal FROM data_reservasi ORDER BY tanggal ASC";
$resultTanggal = $db->query($queryTanggal);
$tanggalReservasi = [];

if ($resultTanggal->num_rows > 0) {
    while ($row = $resultTanggal->fetch_assoc()) {
        $tanggalReservasi[] = $row['tanggal'];
    }
}

// Jika tidak ada tanggal di database, tambahkan tanggal default (hari ini)
if (empty($tanggalReservasi)) {
    $tanggalReservasi[] = date('Y-m-d');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .status-tersedia {
            color: #198754;
            font-weight: bold;
        }
        .status-penuh {
            color: #dc3545;
            font-weight: bold;
        }
        .header-title {
            font-weight: bold;
            color: #343a40;
        }

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
    </nav>


    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="header-title">Ketersediaan Jadwal Reservasi</h1>
            <p class="text-muted">Informasi real-time tentang Jadwal reservasi yang tersedia</p>
        </div>

        <?php foreach ($tanggalReservasi as $tanggal): ?>
            <div class="mb-5 table-container">
                <h2 class="mb-4">Tanggal: <?php echo date('d-m-Y', strtotime($tanggal)); ?></h2>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Jumlah Orang</th>
                            <th>Detail Reservasi</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php 
    $adaReservasi = false; // Penanda apakah ada reservasi
    foreach ($slotWaktu as $jam): 
        $totalOrang = $dataReservasi[$tanggal][$jam] ?? 0;
        if ($totalOrang > 0): 
            $adaReservasi = true;
            $status = $totalOrang >= $kapasitasMaksimum ? 'Penuh' : 'Tersedia';
    ?>
        <tr>
            <td><?php echo $jam; ?></td>
            <td class="<?php echo $status === 'Penuh' ? 'status-penuh' : 'status-tersedia'; ?>">
                <?php echo $status; ?>
            </td>
            <td><?php echo $totalOrang; ?>/<?php echo $kapasitasMaksimum; ?></td>
            <td>
                <?php 
           
                $detailQuery = "SELECT nama_lengkap, jumlah_orang FROM data_reservasi WHERE tanggal = '$tanggal' AND jam = '$jam'";
                $detailResult = $db->query($detailQuery);
                if ($detailResult->num_rows > 0) {
                    while ($detailRow = $detailResult->fetch_assoc()) {
                        echo "<span class='d-block'><strong>" . $detailRow['nama_lengkap'] . "</strong> (" . $detailRow['jumlah_orang'] . " orang)</span>";
                    }
                }
                ?>
            </td>
        </tr>
    <?php 
        endif; 
    endforeach;

    // Jika tidak ada reservasi pada tanggal tertentu
    if (!$adaReservasi): 
    ?>
        <tr>
            <td colspan="4" class="text-center text-muted">Tidak ada reservasi untuk tanggal ini</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>