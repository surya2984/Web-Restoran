<?php
include "../service/database.php";
session_start();

// Cek login dan role admin
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user level/login.php");
    exit;
}

// Ambil data reservasi dari database
$sql = "SELECT id, nama_lengkap, no_telephone, jumlah_orang, tanggal, jam, makanan FROM data_reservasi";
$result = $db->query($sql);

// Proses POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Hapus reservasi
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']); // Konversi id ke integer untuk keamanan
        $sql = "DELETE FROM data_reservasi WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: reservasi.php?status=success&message=Reservation deleted successfully");
        } else {
            header("Location: reservasi.php?status=error&message=Failed to delete reservation");
        }

        $stmt->close();
    }

    // Tambah reservasi baru
    if (isset($_POST['simpan'])) {
        $nama_lengkap = $_POST['nama_lengkap'];
        $no_telephone = $_POST['no_telephone'];
        $jumlah_orang = $_POST['jumlah_orang'];
        $tanggal = $_POST['tanggal'];
        $jam = $_POST['jam'];
        $makanan = $_POST['makanan'];

        $sql = "INSERT INTO data_reservasi (nama_lengkap, no_telephone, jumlah_orang, tanggal, jam, makanan) 
        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssisss", $nama_lengkap, $no_telephone, $jumlah_orang, $tanggal, $jam, $makanan);

        if ($stmt->execute()) {
            header("Location: reservasi.php?status=success&message=Reservation added successfully");
        } else {
            header("Location: reservasi.php?status=error&message=Failed to add reservation");
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservasi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
        }
        .nav-link.active {
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .card-metrics {
            display: flex;
            justify-content: space-between;
        }
        .card-metrics .card {
            flex: 1;
            margin-right: 10px;
        }
        .card-metrics .card:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item mt-4 mx-auto">
                <h4>Nuansa Lama</h4>
                <hr />
              </li>
              <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                  <i class="bi bi-speedometer2 m-2"></i>Dashboard</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="order.php">
                  <i class="bi bi-bag-plus m-2"></i>Orders
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="reservasi.php">
                  <i class="bi bi-calendar-check m-2"></i>Reservation
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php">
                  <i class="bi bi-grid m-2"></i>Menu
                </a>
                <hr>
              </li>
          
              <li class="nav-item">
                <a class="nav-link" href="promosi.php">
                  <i class="bi-megaphone m-2"></i>Promotion
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="report.php">
                  <i class="bi-file-earmark-text m-2"></i>Report
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="user.php">
                  <i class="bi bi-person-circle m-2"></i>User
                </a>
                <hr />
              </li>
            </ul>
          </div>
        </nav>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h4 class="mt-2">Reservation</h4>
                </div>

                <div class="mb-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addReservationModal">
                        Add New Reservation
                    </button>
                </div>

                <!-- Tabel Reservasi -->
                <div class="card mb-4">
                    <div class="card-header">Orders</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Order</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['nama_lengkap']}</td>
                                            <td>{$row['no_telephone']}</td>
                                            <td>{$row['jumlah_orang']}</td>
                                            <td>{$row['tanggal']}</td>
                                            <td>{$row['jam']}</td>
                                            <td>{$row['makanan']}</td>
                                            <td>
                                                <form action='reservasi.php' method='POST' style='display:inline;'>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</button>
                                                </form>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No reservations found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

        </div>
    </div>

    <!-- Add Reservation Modal -->
    <div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="addReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReservationModalLabel">Add New Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="reservasi.php" method="POST">
                        <div class="form-group">
                            <label for="customerName">Customer Name</label>
                            <input type="text" class="form-control" name="nama_lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" class="form-control" name="no_telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="numberOfPeople">Number of People</label>
                            <input type="number" class="form-control" name="jumlah_orang" required>
                        </div>
                        <div class="form-group">
                            <label for="reservationDate">Date</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="reservationTime">Time</label>
                            <input type="time" class="form-control" name="jam" required>
                        </div>
                        <div class="form-group">
                            <label for="orderItems">Order</label>
                          
                            <select name="makanan" class="form-select" id="Fmakanan" required>
                              <option selected>Pilih Paketan</option>
                              <option value="Paket 1">Paket 1</option>
                              <option value="Paket 2">Paket 2</option>
                              <option value="Paket 3">Paket 3</option>
                              <option value="Paket 4">Paket 4</option>
                            </select>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
