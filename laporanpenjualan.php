<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

$user_id = $_SESSION['user_id'];

// Ambil toko milik user ini
$query_store = "SELECT id, nama_toko FROM stores WHERE user_id = ?";
$stmt_store = $conn->prepare($query_store);
$stmt_store->bind_param("i", $user_id);
$stmt_store->execute();
$result_store = $stmt_store->get_result();

if ($result_store->num_rows === 0) {
    die("Toko tidak ditemukan. Silakan buka toko terlebih dahulu.");
}

$toko = $result_store->fetch_assoc();
$toko_id = $toko['id'];
$nama_toko = $toko['nama_toko'];

// Filter waktu
$filter = $_GET['filter'] ?? '';
$whereTanggal = '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

if ($filter === 'hari_ini') {
    $today = date('Y-m-d');
    $whereTanggal = "AND DATE(tanggal) = '$today'";
} elseif ($filter === 'minggu_ini') {
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    $whereTanggal = "AND DATE(tanggal) BETWEEN '$monday' AND '$sunday'";
}  elseif ($filter === 'bulan_ini') {
    $bulan = date('Y-m');
    $whereTanggal = "AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulan'";
} elseif ($filter === 'bulan_lalu') {
    $bulanLalu = date('Y-m', strtotime('first day of last month'));
    $whereTanggal = "AND DATE_FORMAT(tanggal, '%Y-%m') = '$bulanLalu'";
} elseif (!empty($from) && !empty($to)) {
    $whereTanggal = "AND DATE(tanggal) BETWEEN '$from' AND '$to'";
}

// Ambil data penjualan sesuai filter
$query = "SELECT * FROM penjualan WHERE toko_id = ? $whereTanggal ORDER BY tanggal DESC";
$stmt_penjualan = $conn->prepare($query);
$stmt_penjualan->bind_param("i", $toko_id);
$stmt_penjualan->execute();
$result = $stmt_penjualan->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - <?= htmlspecialchars($nama_toko) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            padding: 30px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        form select, form input, form button {
            padding: 7px 10px;
            margin: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .kembali {
            display: inline-block;
            margin-top: 20px;
            background: #007bff;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
        }
        .kembali:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Laporan Penjualan Toko: <?= htmlspecialchars($nama_toko) ?></h2>

    <!-- Filter Form -->
    <form method="GET">
        <label>Filter Waktu:</label>
        <select name="filter">
            <option value="">-- Semua --</option>
            <option value="hari_ini" <?= ($filter == 'hari_ini') ? 'selected' : '' ?>>Hari Ini</option>
            <option value="minggu_ini" <?= ($filter == 'minggu_ini') ? 'selected' : '' ?>>Minggu Ini</option>
            <option value="bulan_ini" <?= ($filter == 'bulan_ini') ? 'selected' : '' ?>>Bulan Ini</option>
            <option value="bulan_lalu" <?= ($filter == 'bulan_lalu') ? 'selected' : '' ?>>Bulan Lalu</option>

        </select>

        <label>Rentang Tanggal:</label>
        <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
        <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
        <button type="submit">Terapkan</button>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total Harga</th>
            <th>Pembeli</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>

        <?php
        $no = 1;
        while ($data = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$data['nama_produk']}</td>
                <td>{$data['jumlah']}</td>
                <td>Rp" . number_format($data['harga_satuan'], 0, ',', '.') . "</td>
                <td>Rp" . number_format($data['total_harga'], 0, ',', '.') . "</td>
                <td>{$data['nama_pembeli']}</td>
                <td>" . strtoupper($data['metode_pembayaran']) . "</td>
                <td>{$data['status']}</td>
                <td>{$data['tanggal']}</td>
            </tr>";
            $no++;
        }

        if ($no === 1) {
            echo "<tr><td colspan='9'>Belum ada penjualan untuk filter yang dipilih.</td></tr>";
        }
        ?>
    </table>

    <a class="kembali" href="dashboard_toko.php">← Kembali ke Dashboard</a>

</body>
</html>
