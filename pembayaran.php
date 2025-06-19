<?php
include 'koneksi.php';
session_start();

// Inisialisasi jumlah_beli di session jika belum ada
if (!isset($_SESSION['jumlah_beli'])) {
    $_SESSION['jumlah_beli'] = 1;
}

// Ambil produk_id dari POST atau session
$produk_id = $_POST['produk_id'] ?? $_SESSION['produk_id'] ?? '';

if (empty($produk_id)) {
    die("ID produk kosong.");
}

// Pastikan produk_id adalah integer
$produk_id = (int)$produk_id;

// Simpan ke session agar bisa dipakai ulang
$_SESSION['produk_id'] = $produk_id;

// Jika ada input nama dan harga dari POST, simpan juga ke session
$nama = $_POST['nama'] ?? $_SESSION['nama'] ?? '';
$harga = $_POST['harga'] ?? $_SESSION['harga'] ?? '0';

// Simpan nama dan harga ke session
$_SESSION['nama'] = $nama;
$_SESSION['harga'] = $harga;

// Pastikan harga float
$harga = (float)$harga;

// Ambil jumlah beli dari session
$jumlah_beli = $_SESSION['jumlah_beli'];

// Query produk berdasarkan produk_id
$query = "SELECT stock, gambar, tipe_gambar, store_id FROM produk WHERE id = $produk_id";
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

// Ambil data produk
$data = mysqli_fetch_assoc($result);

// Jika data produk tidak ditemukan, tampilkan pesan dan hentikan script
if (!$data) {
    die("Produk tidak ditemukan.");
}

// Ambil data stok dan gambar dari hasil query
$stok_db = (int)$data['stock'];
$stok_sisa = $stok_db - $jumlah_beli;
$tipe = $data['tipe_gambar'];
$img = base64_encode($data['gambar']);

// Simpan store_id dan nama_toko ke session
$_SESSION['store_id'] = $data['store_id'];

$resultToko = mysqli_query($conn, "SELECT nama_toko FROM stores WHERE id = {$data['store_id']}");
$dataToko = mysqli_fetch_assoc($resultToko);
$_SESSION['nama_toko'] = $dataToko['nama_toko'] ?? '';

// Hitung total harga
$totalHarga = $harga * $jumlah_beli;

// Tangani aksi tambah/kurang jumlah beli
$alert = '';
if (isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];

    if ($aksi == 'tambah') {
        if ($_SESSION['jumlah_beli'] < $stok_db) {
            $_SESSION['jumlah_beli']++;
        } else {
            $alert = "Jumlah melebihi stok tersedia!";
        }
    }

    if ($aksi == 'kurang') {
        if ($_SESSION['jumlah_beli'] > 1) {
            $_SESSION['jumlah_beli']--;
        } else {
            $alert = "Jumlah minimal pembelian adalah 1.";
        }
    }

    // Redirect supaya alert muncul dan aksi di-refresh
    header("Location: pembayaran.php?alert=" . urlencode($alert));
    exit;
}

// Ambil pesan alert dari GET jika ada
$alert = $_GET['alert'] ?? '';

// Selanjutnya bisa tampilkan halaman pembayaran dengan data di atas
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Konfirmasi Pembelian</title>
    <style>
        .alert {
            color: red;
            margin-bottom: 1em;
        }

        .card {
            max-width: 400px;
            margin: auto;
            padding: 1em;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
        }

        .card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 1em;
        }

        .btn {
            display: inline-block;
            padding: 0.5em 1em;
            margin: 0.5em 0.2em;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .btn-tambah {
            background-color: #4CAF50;
            color: white;
        }

        .btn-kurang {
            background-color: #f44336;
            color: white;
        }

        .btn-bayar {
            background-color: #4300FF;
            color: white;
            width: 100%;
            font-weight: bold;
            margin-top: 1em;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Konfirmasi Pembelian</h2>

        <?php if ($alert): ?>
            <div class="alert"><?= htmlspecialchars($alert) ?></div>
        <?php endif; ?>

        <img src="data:<?= htmlspecialchars($tipe) ?>;base64,<?= $img ?>" alt="<?= htmlspecialchars($nama) ?>" />

        <p><strong>Nama Produk:</strong> <?= htmlspecialchars($nama) ?></p>
        <p><strong>Harga per item:</strong> IDR <?= number_format($harga, 2, ',', '.') ?></p>
        <p><strong>Sisa Stok:</strong> <?= $stok_sisa ?></p>
        <p><strong>Jumlah yang dibeli:</strong> <?= $jumlah_beli ?></p>
        <p><strong>Total Bayar:</strong> <b>IDR <?= number_format($totalHarga, 2, ',', '.') ?></b></p>

        <form action="pembayaran.php" method="post" style="display:inline;">
            <input type="hidden" name="produk_id" value="<?= $produk_id ?>">
            <input type="hidden" name="aksi" value="kurang">
            <button type="submit" class="btn btn-kurang">- Kurangi</button>
        </form>

        <form action="pembayaran.php" method="post" style="display:inline;">
            <input type="hidden" name="produk_id" value="<?= $produk_id ?>">
            <input type="hidden" name="aksi" value="tambah">
            <button type="submit" class="btn btn-tambah">+ Tambah</button>
        </form>

        <form action="prosesBayar.php" method="post">
            <input type="hidden" name="produk_id" value="<?= $produk_id ?>">
            <input type="hidden" name="jumlah_beli" value="<?= $jumlah_beli ?>">
            <button type="submit" class="btn btn-bayar">Bayar Sekarang</button>
        </form>
    </div>

</body>

</html>