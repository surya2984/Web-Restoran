<?php
session_start();

// Pastikan data reservasi tersedia
if (!isset($_SESSION['reservasi_details'])) {
    echo "<script>alert('Data reservasi tidak ditemukan.'); window.location.href = 'payment-reservasi.php';</script>";
    exit;
}

$reservasi = $_SESSION['reservasi_details'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Reservasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nota-container {
            margin: 50px auto;
            max-width: 600px;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .nota-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .nota-header h1 {
            margin-bottom: 0;
        }
        .nota-header p {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .nota-summary table {
            width: 100%;
        }
        .nota-summary th, .nota-summary td {
            padding: 10px;
        }
        .btn-print {
            text-align: center;
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
    <div class="nota-container">
        <div class="nota-header">
            <h1>Nota Reservasi</h1>
            <p>Terima kasih atas reservasi Anda!</p>
        </div>
        <div class="nota-summary">
            <h4>Detail Reservasi</h4>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?= htmlspecialchars($reservasi['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td><?= htmlspecialchars($reservasi['no_telephone']); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Orang</th>
                        <td><?= htmlspecialchars($reservasi['jumlah_orang']); ?> orang</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?= htmlspecialchars($reservasi['tanggal']); ?></td>
                    </tr>
                    <tr>
                        <th>Jam</th>
                        <td><?= htmlspecialchars($reservasi['jam']); ?></td>
                    </tr>
                    <tr>
                        <th>Pesanan</th>
                        <td><?= htmlspecialchars($reservasi['makanan']); ?></td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td>Rp<?= htmlspecialchars(number_format($reservasi['total_harga'], 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td><?= htmlspecialchars($reservasi['payment_method']); ?></td>
                    </tr>
                    <?php if ($reservasi['bank_name']): ?>
                        <tr>
                            <th>Bank</th>
                            <td><?= htmlspecialchars($reservasi['bank_name']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($reservasi['ewallet_name']): ?>
                        <tr>
                            <th>E-Wallet</th>
                            <td><?= htmlspecialchars($reservasi['ewallet_name']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($reservasi['credit_card_number']): ?>
                        <tr>
                            <th>Kartu Kredit</th>
                            <td><?= htmlspecialchars($reservasi['credit_card_number']); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol -->
        <div class="button-container align-item-center">
            <button class="btn btn-success" onclick="window.print();">Cetak Nota</button>
            <button class="btn btn-secondary" onclick="window.history.back();">Kembali</button>
        </div>
    </div>
</body>
</html>
