<?php
session_start();
include "../service/database.php"; // Sambungkan ke database

// Pastikan pengguna login
if (!isset($_SESSION['username'])) {
    header("Location:../index.php");
    exit;
}

$username = $_SESSION['username'];

// Menghapus pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_menu'])) {
  $nama_menu = $_POST['nama_menu'];
  $sql_delete = "DELETE FROM orders WHERE username = ? AND nama_menu = ?";
  $stmt_delete = $db->prepare($sql_delete);
  $stmt_delete->bind_param("ss", $username, $nama_menu);
  if ($stmt_delete->execute()) {
      // Redirect agar pengguna melihat data terbaru
      header("Location: keranjang.php");
      exit;
  } else {
      echo "Terjadi kesalahan saat menghapus pesanan.";
  }
  $stmt_delete->close();
}

// Ambil data pesanan dari database
$sql = "SELECT gambar, nama_menu, jumlah, harga, total_harga FROM orders WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-3 mt-3 p-2 bg-primary text-white rounded">Pesanan Anda</h1>

        <!-- Tombol Kembali -->
        <div class="mb-3">
            <button class="btn btn-secondary" onclick="window.history.back();">Kembali</button>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Item Pesanan -->
                <div class="row border p-3 mb-3 align-items-center">
                    <div class="col-md-2">
                        <!-- Gambar menu (opsional, tambahkan logika jika ada gambar terkait) -->
                        <img src="<?= htmlspecialchars($row['gambar']); ?>" class="img-fluid rounded">
                    </div>
                    <div class="col-md-7">
                        <h5><?= htmlspecialchars($row['nama_menu']); ?></h5>
                        <p>Harga Satuan: Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                        <p>Jumlah: <?= htmlspecialchars($row['jumlah']); ?></p>
                        <p>Total: Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></p>
                    </div>
                    <div class="col-md-3 text-right d-flex justify-content-end align-items-center">
                        <!-- Form untuk menghapus pesanan -->
                        <form action="keranjang.php" method="post" class="mr-2">
                            <input type="hidden" name="nama_menu" value="<?= htmlspecialchars($row['nama_menu']); ?>">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>

                        
                    </div>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Keranjang Anda kosong.</p>
        <?php endif; ?>

        <!-- Tutup koneksi -->
        <?php
        $stmt->close();
        $db->close();
        ?>

        <!-- Form untuk checkout -->
        <form action="payment.php">
            <button type="submit" class="btn btn-success w-100">Checkout</button>
        </form>
    </div>

    <!-- Footer -->
    <footer style="text-align: center">
        <p>&copy; 2024 Reservasi Online. All Rights Reserved.</p>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgjpFWfHgvZ7q5iw8qZg61a8rRJXZBe5zpoI1N8mEMKNjaBfGQ2" crossorigin="anonymous"></script>
</body>
</html>
