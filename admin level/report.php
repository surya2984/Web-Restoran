<?php
include "../service/database.php";
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user level/login.php");
    exit;
}

// Query untuk mendapatkan data order
$sql = "SELECT id, username, nama_menu, jumlah, total_harga, payment_method, bank_name, ewallet_name, credit_card_number, status FROM payment";
$result = $db->query($sql);

// Query untuk statistik
$totalOrdersQuery = "SELECT COUNT(*) AS total FROM payment";
$totalCompleteQuery = "SELECT COUNT(*) AS total FROM payment WHERE status = 'Complete'";
$totalPendingQuery = "SELECT COUNT(*) AS total FROM payment WHERE status = 'Pending'";
$totalRevenueQuery = "SELECT SUM(total_harga) AS revenue FROM payment WHERE status = 'Complete'";

$totalOrders = $db->query($totalOrdersQuery)->fetch_assoc()['total'];
$totalComplete = $db->query($totalCompleteQuery)->fetch_assoc()['total'];
$totalPending = $db->query($totalPendingQuery)->fetch_assoc()['total'];
$totalRevenue = $db->query($totalRevenueQuery)->fetch_assoc()['revenue'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a class="nav-link" href="order.php">
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
            <li class="nav-item active">
                <a class="nav-link active" href="report.php">
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
                <h4 class="mt-2">Report</h4>
            </div>

            <!-- Chart and Metrics Section -->
            <div class="card mb-4">
                <div class="card-header">Order Overview</div>
                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart-card">
                            <canvas id="orderStatusChart" width="400" height="100"></canvas>
                        </div>
                        
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Orders</h5>
                                            <p class="card-text h4"><?= $totalOrders ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Completed Orders</h5>
                                            <p class="card-text h4"><?= $totalComplete ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Pending Orders</h5>
                                            <p class="card-text h4"><?= $totalPending ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Revenue</h5>
                                            <p class="card-text h4">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card mb-4">
                <div class="card-header">Order Details</div>
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
                            <th scope="col">Bank</th>
                            <th scope="col">E-Wallet</th>
                            <th scope="col">Credit Card</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <th scope='row'>{$row['id']}</th>
                                    <td>{$row['username']}</td>
                                    <td>{$row['nama_menu']}</td>
                                    <td>{$row['jumlah']}</td>
                                    <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                                    <td>{$row['payment_method']}</td>
                                    <td>" . (!empty($row['bank_name']) ? $row['bank_name'] : '-') . "</td>
                                    <td>" . (!empty($row['ewallet_name']) ? $row['ewallet_name'] : '-') . "</td>
                                    <td>" . (!empty($row['credit_card_number']) ? $row['credit_card_number'] : '-') . "</td>
                                    <td>{$row['status']}</td>
                                </tr>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctx = document.getElementById('orderStatusChart').getContext('2d');
    const orderStatusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Orders', 'Completed Orders', 'Pending Orders'],
            datasets: [{
                label: 'Number of Orders',
                data: [<?= $totalOrders ?>, <?= $totalComplete ?>, <?= $totalPending ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
