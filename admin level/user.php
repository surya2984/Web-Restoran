<?php
include "../service/database.php";
session_start();

  if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
      header("Location: ../user level/login.php");
      exit;
  }

  $sql = "SELECT id, username, email, role FROM users";
  $result = $db->query($sql);


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $role = $_POST['role'];

    // Validasi mengubah role 
    if (!empty($id) && !empty($role)) {
      // Query update role di database
      $sql = "UPDATE users SET role = ? WHERE id = ?";
      $stmt = $db->prepare($sql);
      $stmt->bind_param("si", $role, $id);

      if ($stmt->execute()) {
          // Redirect ke halaman utama dengan pesan sukses
          header("Location: user.php?message=success");
          exit();
      } else {
          echo "data gagal diubah";
          exit();
      }
  } elseif (isset($_POST['id'])) {

    $id = intval($_POST['id']);
  
    // Query untuk menghapus data
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
  
    if ($stmt->execute()) {
        // Redirect ke halaman sebelumnya dengan pesan sukses
        header("Location: user.php?status=success&message=User deleted successfully");
    } else {
        // Redirect ke halaman sebelumnya dengan pesan error
        header("Location: user.php?status=error&message=Failed to delete user");
    }
  
    $stmt->close();
    $db->close();
  }
} 

    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Users</title>
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
                <a class="nav-link active" href="user.php">
                  <i class="bi bi-person-circle m-2"></i>User
                </a>
                <hr />
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="mt-2">Users</h4>
          </div>

          <div class="card mb-4">
            <div class="card-header">Daftar Pengguna</div>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>

                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <th scope='row'>{$row['id']}</th>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <button type='button' class='btn btn-primary btn-sm' 
                                            data-toggle='modal' 
                                            data-target='#editUserModal-{$row['id']}'>
                                        Edit
                                    </button>

                                    
                                    <form action='user.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\");'>
                                            Delete
                                        </button>
                                    </form>

                                    
                                </td>
                            </tr>";

                            // Modal untuk setiap pengguna
                            echo "
                            <div class='modal fade' id='editUserModal-{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='editUserModalLabel' aria-hidden='true'>
                              <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                  <form action='user.php' method='POST'>
                                    <div class='modal-header'>
                                      <h5 class='modal-title' id='editUserModalLabel'>Edit User</h5>
                                      <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                      </button>
                                    </div>
                                    <div class='modal-body'>
                                      <input type='hidden' name='id' value='{$row['id']}'>
                                      <div class='form-group'>
                                        <label for='userName'>Name</label>
                                        <input type='text' class='form-control' id='userName' name='username' value='{$row['username']}' readonly>
                                      </div>
                                      <div class='form-group'>
                                        <label for='userRole'>Role</label>
                                        <select class='form-control' id='userRole' name='role'>
                                          <option value='admin' " . ($row['role'] === 'admin' ? 'selected' : '') . ">Admin</option>
                                          <option value='user' " . ($row['role'] === 'user' ? 'selected' : '') . ">User</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class='modal-footer'>
                                      <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                      <button type='submit' class='btn btn-primary'>Save Changes</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>";
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<?php
$db->close();
?>
