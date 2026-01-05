<?php
session_start();
include "../service/database.php"; // Sambungkan ke database

// Pastikan pengguna login
if (!isset($_SESSION['username'])) {
    header('location:../index.php');
    exit;
}
$username = $_SESSION['username'];

// Ambil data pesanan dari keranjang
$sql = "SELECT nama_menu, jumlah, total_harga FROM orders WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Hitung total harga dan simpan data pesanan
$total_harga = 0;
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
    $total_harga += $row['total_harga'];
}
$stmt->close();

// Proses kode promo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_promo'])) {

    
    $kode_promo = $_POST['kode_promo'] ?? '';

    // Cek validitas kode promo di database
    $promo_query = "SELECT diskon FROM promo WHERE kode_promo = ? AND NOW() BETWEEN tanggal_mulai AND tanggal_berakhir";
    $stmt_promo = $db->prepare($promo_query);
    $stmt_promo->bind_param("s", $kode_promo);
    $stmt_promo->execute();
    $result_promo = $stmt_promo->get_result();

    if ($result_promo->num_rows > 0) {
        $promo = $result_promo->fetch_assoc();
        $diskon = $promo['diskon'];
    
        // Simpan diskon dalam sesi untuk digunakan di tahap pembayaran
        $_SESSION['diskon_promo'] = $diskon;
    
        // Hitung total harga setelah diskon
        $total_harga -= ($total_harga * $diskon / 100);
        $_SESSION['total_harga_diskon'] = $total_harga;
    
        $success_message = "Kode promo berhasil digunakan! Anda mendapatkan diskon {$diskon}%.";
    }
     else {
        $error_message = "Kode promo tidak valid atau sudah kadaluarsa.";
    }
    $stmt_promo->close();
}

// Proses pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bayar'])) {
    $payment_method = $_POST['payment_method'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $ewallet_name = $_POST['ewallet_name'] ?? null;
    $credit_card_number = $_POST['credit_card_number'] ?? null;

    if ($payment_method && count($orders) > 0) {
        $sql_payment = "INSERT INTO payment (
            username, nama_menu, jumlah, total_harga, payment_method, bank_name, ewallet_name, credit_card_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_payment = $db->prepare($sql_payment);

        if ($stmt_payment) {
            foreach ($orders as $order) {
                // Hitung diskon untuk setiap item
                $diskon_persen = $_SESSION['diskon_promo'] ?? 0; // Ambil diskon dari sesi (diatur saat kode promo diterapkan)
                $total_harga_item = $order['total_harga']; // Total harga awal item
                $total_harga_diskon_item = $total_harga_item - ($total_harga_item * $diskon_persen / 100); // Total setelah diskon
            
                // Simpan ke database
                $stmt_payment->bind_param(
                    "ssddssss",
                    $username,
                    $order['nama_menu'],
                    $order['jumlah'],
                    $total_harga_diskon_item,
                    $payment_method,
                    $bank_name,
                    $ewallet_name,
                    $credit_card_number
                );
                $stmt_payment->execute();
            }

            $stmt_payment->close();

            // Hapus pesanan dari keranjang
            $sql_delete = "DELETE FROM orders WHERE username = ?";
            $stmt_delete = $db->prepare($sql_delete);
            if ($stmt_delete) {
                $stmt_delete->bind_param("s", $username);
                $stmt_delete->execute();
                $stmt_delete->close();
            }

            $_SESSION['payment_details'] = [
                'username' => $username,
                'orders' => $orders,
                'total_harga' => $total_harga_diskon,
                'payment_method' => $payment_method,
                'bank_name' => $bank_name,
                'ewallet_name' => $ewallet_name,
                'credit_card_number' => $credit_card_number
            ];

            echo "<script>alert('Pembayaran berhasil!');</script>";
            header("Location: nota.php");
            exit;
        } else {
            echo "<script>alert('Kesalahan saat memproses pembayaran.');</script>";
        }
    } else {
        echo "<script>alert('Pilih metode pembayaran yang valid.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function generateVA() {
            return Math.floor(100000000000 + Math.random() * 900000000000);
        }
        $(document).ready(function () {
            $('#payment_method').on('change', function () {
                const method = $(this).val();
                $('.payment-options').hide();
                $('#va-number').text('');
                $('#va-display').hide();

                if (method === 'Transfer Bank') {
                    $('#bank-options').show();
                } else if (method === 'E-Wallet') {
                    $('#ewallet-options').show();
                } else if (method === 'Kartu Kredit') {
                    $('#credit-card-options').show();
                }
            });

            $('#bank_name, #ewallet_name').on('change', function () {
                const vaNumber = generateVA();
                $('#va-number').text('Nomor VA: ' + vaNumber);
                $('#va-display').show();
            });
        });
    </script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-3 mt-3 p-2 bg-primary text-white rounded">Pembayaran</h1>

        <!-- Pesan promo -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message; ?></div>
        <?php endif; ?>

        <!-- Ringkasan Pesanan -->
        <h3>Ringkasan Pesanan</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['nama_menu']); ?></td>
                        <td><?= htmlspecialchars($order['jumlah']); ?></td>
                        <td>Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th>Rp <?= number_format($total_harga, 0, ',', '.'); ?></th>
                </tr>
            </tfoot>
        </table

        <!-- Form Kode Promo -->
        <form method="POST">
            <div class="form-group">
                <label for="kode_promo">Kode Promo:</label>
                <input type="text" class="form-control" id="kode_promo" name="kode_promo" placeholder="Masukkan kode promo">
            </div>
            <button type="submit" name="apply_promo" class="btn btn-primary">Gunakan Sekarang</button>
        </form>

        <!-- Form Pembayaran -->
        
        <h3>Pilih Metode Pembayaran</h3>
        <form method="POST" action="payment.php">
            <div class="form-group">
                <label for="payment_method">Metode Pembayaran:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="">Pilih Metode</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="Kartu Kredit">Kartu Kredit</option>
                </select>
            </div>
            <!-- Opsi Transfer Bank -->
            <div id="bank-options" class="payment-options" style="display: none;">
                <div class="form-group">
                    <label for="bank_name">Pilih Bank:</label>
                    <select class="form-control bank-selector" id="bank_name" name="bank_name">
                        <option value="">Pilih Bank</option>
                        <option value="BCA">BCA</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BRI">BRI</option>
                        <option value="BNI">BNI</option>
                        <option value="CIMB Niaga">CIMB Niaga</option>
                        <option value="Permata">Permata</option>
                        <option value="Danamon">Danamon</option>
                        <option value="Mega">Mega</option>
                        <option value="OCBC NISP">OCBC NISP</option>
                    </select>
                </div>
            </div>
            <!-- Opsi E-Wallet -->
            <div id="ewallet-options" class="payment-options" style="display: none;">
                <div class="form-group">
                    <label for="ewallet_name">Pilih E-Wallet:</label>
                    <select class="form-control ewallet-selector" id="ewallet_name" name="ewallet_name">
                        <option value="">Pilih E-Wallet</option>
                        <option value="Gopay">Gopay</option>
                        <option value="OVO">OVO</option>
                        <option value="DANA">DANA</option>
                        <option value="LinkAja">LinkAja</option>
                        <option value="ShopeePay">ShopeePay</option>
                    </select>
                </div>
            </div>
            <!-- Opsi Kartu Kredit -->
            <div id="credit-card-options" class="payment-options" style="display: none;">
                <div class="form-group">
                    <label for="credit_card_number">Nomor Kartu Kredit:</label>
                    <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" placeholder="Masukkan nomor kartu Anda">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="3 digit di belakang kartu Anda">
                </div>
            </div>

            <!-- Nomor VA -->
            <div id="va-display" style="display: none; margin-top: 10px;">
                <p>Nomor Virtual Account Anda: <strong id="va-number"></strong></p>
            </div>
            <div class="button-container">
                <button type="submit" name="bayar" class="btn btn-primary mt-3 mb-5">Bayar</button>
                <a href="layanan - pemesanan.php" class="btn btn-secondary mt-3 mb-5" style="color: white; text-decoration: none;">Kembali</a>
                
            </div>

        </form>
    </div>


    <script>
    document.getElementById('payment_method').addEventListener('change', function () {
        const selectedMethod = this.value;
        document.querySelectorAll('.payment-options').forEach(option => {
            option.style.display = 'none'; // Sembunyikan semua opsi
        });

        if (selectedMethod === 'Transfer Bank') {
            document.getElementById('bank-options').style.display = 'block';
        } else if (selectedMethod === 'E-Wallet') {
            document.getElementById('ewallet-options').style.display = 'block';
        } else if (selectedMethod === 'Kartu Kredit') {
            document.getElementById('credit-card-options').style.display = 'block';
        }
    });
</script>

</body>
</html>