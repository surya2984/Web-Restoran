<?php
include "../service/database.php";
session_start();

  if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
      header("Location: ../user level/login.php");
      exit;
  }

      $total_order_dan_sales = "SELECT 
                        COUNT(id) AS total_orders, 
                        SUM(total_harga) AS total_sales 
                    FROM payment";
      
      $total_reservasi = "SELECT 
                        COUNT(id) AS total_reservasi                       
                    FROM data_reservasi";
      
      
      $result_order_sales = $db->query($total_order_dan_sales);

      // Pastikan data tersedia untuk orders dan sales
      $total_orders = 0;
      $total_sales = 0;
      if ($result_order_sales && $row_totals = $result_order_sales->fetch_assoc()) {
          $total_orders = $row_totals['total_orders'];
          $total_sales = $row_totals['total_sales'];
      }
      
      // Eksekusi query untuk total reservasi
      $result_reservasi = $db->query($total_reservasi);
      
      // Pastikan data tersedia untuk reservasi
      $total_reservasi = 0;
      if ($result_reservasi && $row_reservasi = $result_reservasi->fetch_assoc()) {
          $total_reservasi = $row_reservasi['total_reservasi'];
      }


      $sql = "SELECT id, username, nama_menu, jumlah, total_harga, payment_method, bank_name, ewallet_name, credit_card_number, status FROM payment";
    

      $result = $db->query($sql);
    
    
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $nama_menu = $_POST['nama_menu'];
        $jumlah = $_POST['jumlah'];
        $total_harga = $_POST['total_harga'];
        $payment_method = $_POST['payment_method'];
        $bank_name = $_POST['bank_name'];
        $ewallet_name = $_POST['ewallet_name'];
        $credit_card_number = $_POST['credit_card_number'];
        $status = $_POST['status'];
    
    $db->close();
    
  }

    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
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
                <a class="nav-link active" href="dashboard.php">
                  <i class="bi bi-speedometer2 m-2"></i>Dashboard</a
                >
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
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
          >
            <h4 class="mt-2">Dashboard</h4>
          </div>

          <!-- Metrics Section -->
          <div class="card-metrics mb-4">
          <div class="card text-white bg-primary">
            <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <p class="card-text">Rp <?= number_format($total_sales, 0, ',', '.') ?></p>
            </div>
          </div>

            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text"><?= $total_orders ?> Orders</p>
                </div>
            </div>

            <div class="card text-white bg-warning">
              <div class="card-body">
                <h5 class="card-title">New Reservations</h5>
                <p class="card-text"><?= $total_reservasi ?> Reserver</p>
              </div>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="card mb-4">
            <div class="card-header">Orders</div>
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
                  <th scope="col">Bank Name</th>
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
                              <td>{$row['total_harga']}</td>
                              
                              <td>{$row['payment_method']}</td>
                              <td>" . (!empty($row['bank_name']) ? $row['bank_name'] : '-') . "</td>
                              <td>" . (!empty($row['ewallet_name']) ? $row['ewallet_name'] : '-') . "</td>
                              <td>" . (!empty($row['credit_card_number']) ? $row['credit_card_number'] : '-') . "</td>
                              <td>{$row['status']}</td>
                              <td>
              
                              </td>
                              
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
