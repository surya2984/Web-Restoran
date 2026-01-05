<?php
include "../service/database.php";
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user level/login.php");
    exit;
}

// Handle form submission untuk update status pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    if (!empty($id) && !empty($status)) {
        $sql = "UPDATE payment SET status = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            header("Location: order.php?message=success");
            exit();
        } else {
            echo "Gagal memperbarui status pesanan: " . $stmt->error;
            exit();
        }
    }
}

// Query untuk mengambil data pesanan
$sql = "SELECT id, username, nama_menu, jumlah, total_harga, payment_method, bank_name, ewallet_name, credit_card_number, status FROM payment";
$result = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
        }
        .nav-link.active {
            background-color: #e9ecef;
            border-radius: 5px;
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
                                <i class="bi bi-speedometer2 m-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="order.php">
                                <i class="bi bi-bag-plus m-2"></i>Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reservasi.php">
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
                    <h4 class="mt-2">Orders</h4>
                </div>

                <!-- Notifikasi -->
                <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
                    <div class="alert alert-success" role="alert">
                        Status pesanan berhasil diperbarui!
                    </div>
                <?php endif; ?>

                <!-- Tabel Pesanan -->
                <div class="card mb-4">
                    <div class="card-header">Daftar Pesanan</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Order</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <th scope="row"><?= $row['id'] ?></th>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_menu']) ?></td>
                                        <td><?= $row['jumlah'] ?></td>
                                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                        <td><?= htmlspecialchars($row['status']) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editOrderModal-<?= $row['id'] ?>">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editOrderModal-<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="order.php" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Status Pesanan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control" name="status">
                                                                <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                                <option value="Complete" <?= $row['status'] === 'Complete' ? 'selected' : '' ?>>Complete</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$db->close();
?>
