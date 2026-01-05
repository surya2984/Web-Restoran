<?php

  include "../service/database.php";
  session_start();

  //validasi supaya ketika sudah daftar , tidak bisa daftar kembali
  if(isset($_SESSION["is_login"])){
    header("location: ../index.php");
  }

  //pesan notif
  $pesan_register = "";

  if(isset($_POST['signup'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "INSERT INTO users (username, email, password) 
    VALUES ('$username', '$email', '$password')";

    if($db->query($sql)){

      $pesan_register = "daftar akun berhasil silahkan login";
      header("location: login.php");
    }else{
      $pesan_register = "daftar akun gagal silahkan coba lagi";
    }

    $db->close();
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up Page</title>
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
      .signup-container {
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

    <!-- Sign Up Container -->
    <div class="signup-container">
      <h3 class="text-center mb-4">Sign Up</h3>
      
      <!-- pesan -->
      <?php if (!empty($pesan_register)): ?>
        <div class="alert alert-primary" role="alert">
          <?= $pesan_register ?>
        </div>
      <?php endif; ?>

      <form action="signup.php" method="POST">
        <div class="mb-3">
          <label for="fullName" class="form-label">Full Name</label>
          
          <input
            type="text"
            class="form-control"
            id="username"
            name="username"
            placeholder="Enter your full name"
            required
          />
        </div>
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

        <div class="form-text mb-3">
          Your password must be 8-20 characters long.
        </div>

        <hr />
        <div class="d-grid">
          <button type="submit" name="signup" class="btn btn-primary btn-block">Daftar sekarang</button>  
        </div>

        <div class="text-center mt-3">
          <span>Sudah mempunyai akun?</span>
          <a href="./login.php">Login</a>
        </div>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
