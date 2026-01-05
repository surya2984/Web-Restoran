<?php

  include "../service/database.php";
  session_start();

  //validasi supaya ketika sudah login , tidak bisa login kembali
  if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    header("location: ../index.php");
  }

  //pesan notif
  $pesan_login = "";

  if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

  $sql = "SELECT * FROM users 
  WHERE email='$email' AND password='$password' ";

  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $_SESSION["username"] = $data["username"];
    $_SESSION["role"] = $data["role"]; // Menyimpan role user
    $_SESSION["is_login"] = true;

    // Arahkan ke dashboard berdasarkan role
      if ($data["role"] === "admin") {
          header("location: ../admin level/dashboard.php");
      } else {
          header("location: ../index.php");
      }
    } else {
        $pesan_login = "Akun tidak ditemukan";
    }

  $db->close();
  }
?>

<!DOCTYPE html>
<html lang="en">
  
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <style>
      /* Menambahkan latar belakang gambar */
      body {
        background-image: url("../Aset/Backround\ login\ &\ signin.jpg");
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      /* Membuat form transparan */
      .login-container {
        background-color: rgba(255, 255, 255, 0.8); /* Transparansi */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <!-- Login Container -->
    <div class="login-container">
      <h3 class="text-center mb-4">Login</h3>

      <!-- pesan -->
      <?php if (!empty($pesan_login)): ?>
        <div class="alert alert-warning" role="alert">
          <?= $pesan_login ?>
        </div>
      <?php endif; ?>

      <form action="login.php" method="POST">

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
          />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Enter your password"
            required
          />
        </div>

        <div class="form-text">Your password must be 8-20 characters long.</div>
        <hr />

        <div class="d-grid">
          <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>  
        </div>

        <div class="text-center mt-3">
          <span>Belum mempunyai akun?</span>
          <a href="./signup.php">Sign Up</a>
        </div>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
