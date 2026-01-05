<?php
include "../service/database.php";
session_start();

  if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
      header("Location: ../user level/login.php");
      exit;
  }

  $sql = "SELECT id, image, menu, kategori, deskripsi, harga, stok FROM menu";
  $result = $db->query($sql);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $image = $_POST['image'] ?? null;
    $menu = $_POST['menu'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $aksinya = $_POST['aksinya'] ?? null;
    
    //untuk mendelete
    if ($aksinya === 'delete') {

      $id = intval($_POST['id']);
      
      // Ambil nama file gambar dari database
    $sql = "SELECT image FROM menu WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = './Aset/' . $row['image'];

        // Hapus file gambar jika ada
        if (file_exists($image_path) && !empty($row['image'])) {
            unlink($image_path); // Fungsi untuk menghapus file
        }
    }
    $stmt->close();

      // Query untuk menghapus data
      $sql = "DELETE FROM menu WHERE id = ?";
      $stmt = $db->prepare($sql);
      $stmt->bind_param("i", $id);
    
      if ($stmt->execute()) {
          // Redirect ke halaman sebelumnya dengan pesan sukses
          header("Location: menu.php?status=success&message=User deleted successfully");
      } else {
          // Redirect ke halaman sebelumnya dengan pesan error
          header("Location: menu.php?status=error&message=Failed to delete user");
      }
    
      $stmt->close();
      $db->close();
    }

    //menupdate data
    if ($aksinya === 'update') {
    $id = $_POST['id'];
    $menu = $_POST['menu'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $image_query = "";

    // Periksa apakah ada file gambar yang diunggah
    if (!empty($_FILES['image']['name'])) {
        $allowed_extension = ['png', 'jpg', 'webp', 'jpeg'];
        $image = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_extension)) {
            $new_image_name = md5(uniqid($image, true) . time()) . '.' . $ext;
            $file_tmp = $_FILES['image']['tmp_name'];

            if (move_uploaded_file($file_tmp, "./Aset/$new_image_name")) {
                $image_query = ", image = ?";
            } else {
                echo "Gagal mengunggah file gambar.";
                exit();
            }
        } else {
            echo "Format file tidak didukung. Hanya PNG dan JPG yang diperbolehkan.";
            exit();
        }
    }

    // Query update data di database
    $sql = "UPDATE menu SET menu = ?, kategori = ?, deskripsi = ?, harga = ?, stok = ? $image_query WHERE id = ?";
    $stmt = $db->prepare($sql);

    // Bind parameter sesuai dengan keberadaan file gambar
    if ($image_query) {
        $stmt->bind_param("sssiisi", $menu, $kategori, $deskripsi, $harga, $stok, $new_image_name, $id);
    } else {
        $stmt->bind_param("sssiii", $menu, $kategori, $deskripsi, $harga, $stok, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman utama dengan pesan sukses
        header("Location: menu.php?message=success");
        exit();
    } else {
        echo "Data gagal diubah.";
        exit();
    }

    $stmt->close();
    $db->close();
}


    //menambah data
    if (isset($_POST['simpan'])) {
      // Ambil data dari form
      $id = $_POST['id'] ?? null;
      $menu = $_POST['menu'] ?? null;
      $kategori = $_POST['kategori'] ?? null;
      $deskripsi = $_POST['deskripsi'] ?? null;
      $harga = $_POST['harga'] ?? null;
      $stok = $_POST['stok'] ?? null;
  
      // Validasi file gambar
      $allowed_extension = array('png', 'jpg', 'jpeg', 'webp');
      $nama_file = $_FILES['file']['name']; // Nama file asli
      $ukuran_file = $_FILES['file']['size'];
      $file_tmp = $_FILES['file']['tmp_name'];
  
      $file_ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
      $image = md5(uniqid($nama_file, true) . time()) . '.' . $file_ext; // Nama file unik
  
      // Periksa apakah file valid
      if (in_array($file_ext, $allowed_extension)) {
          if ($ukuran_file < 15000000) {
              $upload_dir = './Aset/';
              if (!is_dir($upload_dir)) {
                  mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
              }
              $upload_path = $upload_dir . $image;
  
              if (move_uploaded_file($file_tmp, $upload_path)) {
                  // Jika file berhasil diunggah, simpan ke database
                  $sql = "INSERT INTO menu (id, image, menu, kategori, deskripsi, harga, stok) 
                          VALUES ('$id', '$image', '$menu', '$kategori', '$deskripsi', '$harga', '$stok')";
  
                  if ($db->query($sql)) {
                      $pesan_register = "Data menu berhasil disimpan.";
                      header("Location: menu.php?message=success");
                      exit();
                  } else {
                      $pesan_register = "Gagal menyimpan data menu: " . $db->error;
                  }
              } else {
                  $pesan_register = "Gagal mengunggah file.";
              }
          } else {
              $pesan_register = "Ukuran file terlalu besar. Maksimal 15MB.";
          }
      } else {
          $pesan_register = "Ekstensi file tidak diperbolehkan. Hanya PNG, JPG, dan JPEG.";
      }
  
      $db->close();
  }  
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu</title>
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
                <a class="nav-link active" href="menu.php">
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
            <h4 class="mt-2">Menu</h4>
          </div>

          <div class="mb-3">
             <button class="btn btn-success" data-toggle="modal" data-target="#addMenuModal">Add New Menu</button>
          </div>
          

          <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <form action="menu.php" method="POST" id="addMenuForm" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMenuModalLabel">Add New Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="menu">Menu Name</label>
                                <input type="text" class="form-control" id="menu" name="menu" required>
                            </div>
                            <div class="form-group">
                                <label for="kategori">Category</label>
                                <select class="form-control" id="kategori" name="kategori" required>
                                    <option value="menu pembuka">Menu Pembuka</option>
                                    <option value="menu utama">Menu Utama</option>
                                    <option value="menu penutup">Menu penutup</option>
                                    <option value="Minuman">Minuman</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Description</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control" id="harga" name="harga" required>
                            </div>
                            <div class="form-group">
                                <label for="stok">Stok makanan</label>
                                <input type="text" class="form-control" id="stok" name="stok" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="simpan" class="btn btn-primary">Save Menu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Menu table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Menu ID</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Nama Menu</th>
                    <th scope="col">kategori</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <th scope='row'>{$row['id']}</th>
                                <td><img src='./Aset/{$row['image']}' alt='Menu Image' style='width: 100px; height: auto;'></td>
                                <td>{$row['menu']}</td>
                                <td>{$row['kategori']}</td>
                                <td>{$row['deskripsi']}</td>
                                <td>Rp.{$row['harga']}</td>
                                <td>{$row['stok']}</td>

                                <td>
                                    <button type='button' class='btn btn-primary btn-sm' 
                                            data-toggle='modal' 
                                            data-target='#editUserModal-{$row['id']}'>
                                        Edit
                                    </button>

                                    
                                    <form action='menu.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='aksinya' value='delete'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\");'>
                                            Delete
                                        </button>
                                    </form>

                                    
                                </td>
                            </tr>";

                            // Modal untuk setiap menu
                      echo "
                      <div class='modal fade' id='editUserModal-{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='editUserModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <form action='menu.php' method='POST' enctype='multipart/form-data'>
                              <div class='modal-header'>
                                <h5 class='modal-title' id='editUserModalLabel'>Edit Menu</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span>
                                </button>
                              </div>

                              <div class='modal-body'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <div class='form-group'>
                                  <label for='menuName'>Nama Menu</label>
                                  <input type='text' class='form-control' id='menuName' name='menu' value='{$row['menu']}' required>
                                </div>

                                <div class='form-group'>
                                <label for='kategori'>Category</label>
                                    <select class='form-control' id='kategori' name='kategori' required>
                                        <option value='menu pembuka'>Menu Pembuka</option>
                                        <option value='menu utama'>Menu Utama</option>
                                        <option value='menu penutup'>Menu penutup</option>
                                        <option value='minuman'>Minuman</option>
                                    </select>
                                 </div>

                                
                                <div class='form-group'>
                                  <label for='menuDescription'>Deskripsi</label>
                                  <textarea class='form-control' id='menuDescription' name='deskripsi' rows='3' required>{$row['deskripsi']}</textarea>
                                </div>

                                <div class='form-group'>
                                  <label for='harga'>Harga</label>
                                  <input type='text' class='form-control' id='harga' name='harga' value={$row['harga']} required>
                                </div>

                                 <div class='form-group'>
                                  <label for='stok'>Stok makanan</label>
                                  <input type='text' class='form-control' id='stok' name='stok' value={$row['stok']} required>
                                </div>

                                <div class='form-group'>
                                  <label for='menuImage'>Gambar Menu</label>
                                  <input type='file' class='form-control-file' id='menuImage' name='image'>
                                  <small class='form-text text-muted'>Kosongkan jika tidak ingin mengganti gambar.</small>
                                </div>
                              </div>
                              <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                <input type='hidden' name='aksinya' value='update'>
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



          
        </main>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
