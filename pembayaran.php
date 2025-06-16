<?php
include 'koneksi.php';
session_start();

$produk_id = $_POST['produk_id'] ?? $_SESSION['produk_id'] ?? '';
$nama = $_POST['nama'] ?? $_SESSION['nama'] ?? '';
$harga = $_POST['harga'] ?? $_SESSION['harga'] ?? '';

$_SESSION['produk_id'] = $produk_id;
$_SESSION['nama'] = $nama;
$_SESSION['harga'] = $harga;
$totalHarga = $_SESSION['harga'] * $_SESSION['jumlah_beli'];

$result = mysqli_query($conn, "SELECT stock, gambar, tipe_gambar FROM produk WHERE id = $produk_id");
$data = mysqli_fetch_assoc($result);
$stok_db = $data['stock'];
$tipe = $data['tipe_gambar'];
$img = base64_encode($data['gambar']);

if (!isset($_SESSION['jumlah_beli'])) {
    $_SESSION['jumlah_beli'] = 1;
}

$alert = '';
if (isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];

    if ($aksi == 'tambah') {
        if ($_SESSION['jumlah_beli'] < $stok_db) {
            $_SESSION['jumlah_beli']++;
        } else {
            $alert = 'Jumlah melebihi stok tersedia!';
        }
    }

    if ($aksi == 'kurang') {
        if ($_SESSION['jumlah_beli'] > 1) {
            $_SESSION['jumlah_beli']--;
        } else {
            $alert = 'Jumlah minimal pembelian adalah 1.';
        }
    }

    header("Location: pembayaran.php?alert=" . urlencode($alert));
    exit;
}

$jumlah_beli = $_SESSION['jumlah_beli'];
$stok_sisa = $stok_db - $jumlah_beli;

if (isset($_GET['alert'])) {
    $alert = $_GET['alert'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Pembayaran</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 420px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 15px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
            user-select: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .card {
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info p {
            margin: 8px 0;
            font-size: 16px;
            color: #333;
        }

        .stok-area {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .stok-area form {
            display: inline;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 18px;
            user-select: none;
        }

        .btn-tambah {
            background: #28a745;
            color: white;
        }

        .btn-kurang {
            background: #dc3545;
            color: white;
        }

        .btn-bayar {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-bayar:hover {
            background: #0056b3;
        }

        .alert {
            background: #ffe0e0;
            color: #c00;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
        }

        .jumlah-text {
            font-size: 20px;
            font-weight: 600;
            width: 40px;
            text-align: center;
            user-select: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Tombol Kembali di atas card -->
        <a href="index.php" class="btn-secondary">← Kembali ke Homepage</a>

        <div class="card">
            <h2>Konfirmasi Pembelian</h2>

            <?php if ($alert): ?>
                <div class="alert"><?= htmlspecialchars($alert) ?></div>
            <?php endif; ?>

            <img src="data:<?= $tipe ?>;base64,<?= $img ?>" alt="<?= htmlspecialchars($nama) ?>" />
            <div class="info">
                <p><strong>Nama Produk:</strong> <?= htmlspecialchars($nama) ?></p>
                <p><strong>Harga per item:</strong> IDR <?= number_format($harga) ?></p>
                <p><strong>Sisa Stok:</strong> <?= $stok_sisa ?></p>
                <p><strong>Jumlah yang dibeli:</strong> <?= $jumlah_beli ?></p>
                <p><strong>Total Bayar:</strong> <b>IDR <?= number_format($jumlah_beli * $harga) ?></b></p>
            </div>

            <div class="stok-area">
                <form method="post">
                    <input type="hidden" name="aksi" value="kurang" />
                    <button class="btn btn-kurang" type="submit">−</button>
                </form>

                <span class="jumlah-text"><?= $jumlah_beli ?></span>

                <form method="post">
                    <input type="hidden" name="aksi" value="tambah" />
                    <button class="btn btn-tambah" type="submit">+</button>
                </form>
            </div>

            <form action="prosesBayar.php" method="post">
                <input type="hidden" name="produk_id" value="<?= $produk_id ?>" />
                <input type="hidden" name="harga" value="<?= $harga ?>" />
                <input type="hidden" name="jumlah" value="<?= $jumlah_beli ?>" />
                <button type="submit" class="btn-bayar">Bayar Sekarang</button>
            </form>

        </div>
    </div>
</body>

</html>