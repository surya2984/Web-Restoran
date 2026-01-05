<?php
session_start();

// Pastikan pengguna login
if (!isset($_SESSION['username'])) {
    header('location:../index.php');
    exit;
}

// Pastikan data pembayaran tersedia di sesi
if (!isset($_SESSION['reservasi_details'])) {
    echo "<script>alert('Data pembayaran tidak tersedia.'); window.location.href = 'payment - reservasi.php';</script>";
    exit;
}

$username = $_SESSION['username'];
$reservasi_details = $_SESSION['reservasi_details'];
// $data_reservasi = is_array($reservasi_details['data_reservasi'][0]) ? $reservasi_details['data_reservasi'] : [$reservasi_details['data_reservasi']];
$total_harga = $reservasi_details['total_harga'];
$payment_method = $reservasi_details['payment_method'];
$bank_name = $reservasi_details['bank_name'];
$ewallet_name = $reservasi_details['ewallet_name'];
$credit_card_number = $reservasi_details['credit_card_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .print-button {
            margin-bottom: 20px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
        table th, table td {
            text-align: center;
        }
        .nota-header {
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4 p-3 bg-success text-white rounded">Nota Pembayaran</h1>

        <h5>Nama Pemesan: <?= htmlspecialchars($nama_lengkap); ?></h5>

        <!-- Ringkasan Pesanan -->
        <h5>Detail Pesanan</h5>
        <table class="table table-bordered">
            <thead>
                <tr class="nota-header">
                    <th>Paketan</th>
                    <th>Jumlah Orang</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservasi as $reservasi): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservasi['paketan']); ?></td>
                        <td><?= htmlspecialchars($reservasi['jumlah_orang']); ?></td>
                        <td>Rp <?= number_format($reservasi['total_harga'], 0, ',', '.'); ?></td>
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
        <h5>Metode Pembayaran</h5>
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

        <!-- Tombol Cetak -->
        <button class="btn btn-primary print-button" onclick="window.print();">Cetak Nota</button>
    </div>
</body>
</html>