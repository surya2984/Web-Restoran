<?php
session_start();

// Pastikan pengguna login
if (!isset($_SESSION['username'])) {
    header('location:../index.php');
    exit;
}

// Pastikan data pembayaran tersedia di sesi
if (!isset($_SESSION['payment_details'])) {
    header('location:payment.php');
    exit;
}

$username = $_SESSION['username'];
$payment_details = $_SESSION['payment_details'];
$orders = $payment_details['orders'];
$total_harga = $_SESSION['total_harga_diskon'] ?? $total_harga;
// $total_harga = $payment_details['total_harga'];
$payment_method = $payment_details['payment_method'];
$bank_name = $payment_details['bank_name'];
$ewallet_name = $payment_details['ewallet_name'];
$credit_card_number = $payment_details['credit_card_number'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nota-container {
            margin: 50px auto;
            max-width: 800px;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .nota-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .nota-header h1 {
            color: #28a745;
        }
        .nota-header p {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .table {
            margin-top: 20px;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        .button-container .btn {
            margin: 0 10px;
        }
        @media print {
            .button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container nota-container">
        <div class="nota-header">
            <h1>Nota Pembayaran</h1>
            <p>Terima kasih telah memesan!</p>
        </div>

        <!-- Nama Pemesan -->
        <h5>Nama Pemesan: <?= htmlspecialchars($username); ?></h5>

        <!-- Detail Pesanan -->
        <h5 class="mt-4">Detail Pesanan</h5>
        <table class="table table-bordered">
            <thead class="thead-dark">
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
        </table>

        <!-- Detail Pembayaran -->
        <h5 class="mt-4">Metode Pembayaran</h5>
        <p><strong>Metode:</strong> <?= htmlspecialchars($payment_method); ?></p>
        <?php if ($bank_name): ?>
            <p><strong>Bank:</strong> <?= htmlspecialchars($bank_name); ?></p>
        <?php endif; ?>
        <?php if ($ewallet_name): ?>
            <p><strong>E-Wallet:</strong> <?= htmlspecialchars($ewallet_name); ?></p>
        <?php endif; ?>
        <?php if ($credit_card_number): ?>
            <p><strong>Kartu Kredit (4 digit terakhir):</strong> <?= substr(htmlspecialchars($credit_card_number), -4); ?></p>
        <?php endif; ?>

        <!-- Tombol -->
        <div class="button-container">
            <button class="btn btn-success" onclick="window.print();">Cetak Nota</button>
            <button class="btn btn-secondary" onclick="window.history.back();">Kembali</button>
        </div>
    </div>
</body>
</html>
