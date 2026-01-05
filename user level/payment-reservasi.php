<?php
session_start();
include "../service/database.php"; // Sambungkan ke database

// Pastikan pengguna login
if (!isset($_SESSION['username'])) {
    header('location:../index.php');
    exit;
}

$username = $_SESSION['username'];

// Periksa apakah sesi reservasi tersedia
if (!isset($_SESSION['reservasi'])) {
    echo "<script>alert('Tidak ada data reservasi yang ditemukan. Silakan lakukan reservasi terlebih dahulu.'); window.location.href = 'layanan_reservasi.php';</script>";
    exit;
}

// Ambil data reservasi dari sesi
$reservasi = $_SESSION['reservasi'];

// Validasi data sesi sebelum proses pembayaran
if (
    empty($reservasi['nama_lengkap']) ||
    empty($reservasi['no_telephone']) ||
    empty($reservasi['jumlah_orang']) ||
    empty($reservasi['tanggal']) ||
    empty($reservasi['jam']) ||
    empty($reservasi['makanan'])
) {
    echo "<script>alert('Data reservasi tidak lengkap. Silakan periksa kembali.');</script>";
    exit;
}



// Pastikan total_harga tidak null
if (!isset($reservasi['total_harga']) || $reservasi['total_harga'] === null) {
    $reservasi['total_harga'] = 0; // Default nilai jika tidak ada total_harga
}


// Jika form pembayaran dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bayar'])) {
    $payment_method = $_POST['payment_method'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $ewallet_name = $_POST['ewallet_name'] ?? null;
    $credit_card_number = $_POST['credit_card_number'] ?? null;

    // Validasi metode pembayaran
    $valid_payment_methods = ['Transfer Bank', 'E-Wallet', 'Kartu Kredit'];
    if (!in_array($payment_method, $valid_payment_methods)) {
        echo "<script>alert('Metode pembayaran tidak valid.');</script>";
        exit;
    }

    try {
        // Simpan data ke database
        $sql = "INSERT INTO data_reservasi 
            (nama_lengkap, no_telephone, jumlah_orang, tanggal, jam, makanan, total_harga, payment_method, bank_name, ewallet_name, credit_card_number, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query: " . $db->error);
        }

        // Bind parameter ke statement
        $stmt->bind_param(
            "ssisssisssi",
            $reservasi['nama_lengkap'],        // varchar
            $reservasi['no_telephone'],       // varchar
            $reservasi['jumlah_orang'],       // int
            $reservasi['tanggal'],            // date
            $reservasi['jam'],                // time
            $reservasi['makanan'],            // varchar
            $reservasi['total_harga'],        // int
            $payment_method,                  // enum
            $bank_name,                       // varchar (nullable)
            $ewallet_name,                    // varchar (nullable)
            $credit_card_number               // int (nullable)
        );

        if ($stmt->execute()) {
                // Simpan detail pembayaran ke sesi
                $_SESSION['reservasi_details'] = [
                    'nama_lengkap' => $reservasi['nama_lengkap'],
                    'makanan' => $reservasi['makanan'],
                    'total_harga' => $reservasi['total_harga'],
                    'tanggal' => $reservasi['tanggal'],
                    'jumlah_orang' => $reservasi['jumlah_orang'],
                    'no_telephone' => $reservasi['no_telephone'],
                    'jam' => $reservasi['jam'],
                    'payment_method' => $payment_method,
                    'bank_name' => $bank_name,
                    'ewallet_name' => $ewallet_name,
                    'credit_card_number' => $credit_card_number
                ];
    
            header('Location: nota-reservasi.php');
            
            exit;
         
        } else {
            throw new Exception("Gagal menyimpan data reservasi: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "');</script>";
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
            // Membuat nomor VA acak
            return Math.floor(100000000000 + Math.random() * 900000000000);
        }
        $(document).ready(function () {
            $('#payment_method').on('change', function () {
                const method = $(this).val();
                $('.payment-options').hide(); // Sembunyikan semua opsi tambahan
                $('#va-number').text(''); // Reset nomor VA
                $('#va-display').hide(); // Sembunyikan bagian nomor VA

                if (method === 'Transfer Bank') {
                    $('#bank-options').show();
                } else if (method === 'E-Wallet') {
                    $('#ewallet-options').show();
                } else if (method === 'Kartu Kredit') {
                    $('#credit-card-options').show();
                }
            });

            $('.bank-selector, .ewallet-selector').on('change', function () {
                const selected = $(this).val();
                if (selected) {
                    const vaNumber = generateVA(); // Nomor VA baru
                    $('#va-number').text(vaNumber);
                    $('#va-display').show(); // Tampilkan nomor VA
                }
            });
        });
    </script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-3 mt-3 p-2 bg-primary text-white rounded">Pembayaran</h1>

        <!-- Nama Pemesan -->
        <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($reservasi['nama_lengkap']); ?></p>

        <!-- Ringkasan Pesanan -->
        <h3>Ringkasan Pesanan</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>No. Telephone</th>
                    <th>Jumlah Orang</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Paketan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($reservasi['nama_lengkap']); ?></td>
                    <td><?= htmlspecialchars($reservasi['no_telephone']); ?></td>
                    <td><?= htmlspecialchars($reservasi['jumlah_orang']); ?> orang</td>
                    <td><?= htmlspecialchars($reservasi['tanggal']); ?></td>
                    <td><?= htmlspecialchars($reservasi['jam']); ?></td>
                    <td><?= htmlspecialchars($reservasi['makanan']); ?></td>
                    <td><?= htmlspecialchars(number_format($reservasi['total_harga'], 0, ',', '.')); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Form Pilihan Metode Pembayaran -->
        <h3>Pilih Metode Pembayaran</h3>
        <form method="POST" action="payment-reservasi.php">
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
                    </select>
                </div>
            </div>

            <!-- Opsi Kartu Kredit -->
            <div id="credit-card-options" class="payment-options" style="display: none;">
                <div class="form-group">
                    <label for="credit_card_number">Nomor Kartu Kredit:</label>
                    <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" placeholder="Masukkan nomor kartu Anda">
                </div>
            </div>

            <!-- Nomor VA -->
            <div id="va-display" style="display: none; margin-top: 10px;">
                <p>Nomor Virtual Account Anda: <strong id="va-number"></strong></p>
            </div>
            
            
            <div class="button-container">
                <button type="submit" name="bayar" class="btn btn-primary mt-3">Bayar</button>
                <a href="layanan - reservasi.php" class="btn btn-secondary mt-3" style="color: white; text-decoration: none;">Kembali</a>

            </div>
        </form>
    </div>
</body>
</html>
