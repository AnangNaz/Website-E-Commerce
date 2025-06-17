<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT id, nama_toko FROM stores WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "Anda belum membuka toko. <a href='bukatoko.php'>Buka toko</a>";
    header('location: bukatoko.php');
}

$toko = $result->fetch_assoc();
$store_id = $toko['id'];
$nama_toko = $toko['nama_toko'];

$produk_result = $conn->query("SELECT * FROM produk WHERE store_id = $store_id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Toko</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h2 {
            color: #333;
        }

        .button-group a {
            text-decoration: none;
            margin-left: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .button-group a:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
            color: #333;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        td a {
            text-decoration: none;
            color: #2196F3;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Dashboard Toko: <?php echo htmlspecialchars($nama_toko); ?></h2>
        <div class="button-group">
            <a href="formupload.php">➕ Tambah Produk</a>
            <a href="index.php">🏠 Home</a>
            <a href="laporanpenjualan.php">Laporan Penjualan</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $produk_result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php
                        $img = base64_encode($row['gambar']);
                        $tipe = htmlspecialchars($row['tipe_gambar']);
                        ?>
                        <img src="data:<?php echo $tipe; ?>;base64,<?php echo $img; ?>" alt="Produk">
                    </td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td>IDR <?php echo number_format($row['harga'], 2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><?php echo $row['rating']; ?> ⭐</td>
                    <td>
                        <a href="editProduk.php?id=<?php echo $row['id']; ?>">✏️ Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>

</html>