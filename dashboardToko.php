<?php
session_start();
include("koneksi.php");

// Cek login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil ID toko berdasarkan user
$query = $conn->prepare("SELECT id, nama_toko FROM stores WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "Anda belum membuka toko. <a href='bukatoko.php'>Buka toko</a>";
    exit();
}

$toko = $result->fetch_assoc();
$store_id = $toko['id'];
$nama_toko = $toko['nama_toko'];

// Ambil produk milik toko ini
$produk_result = $conn->query("SELECT * FROM produk WHERE store_id = $store_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Toko</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>Dashboard Toko: <?php echo htmlspecialchars($nama_toko); ?></h2>
<a href="formupload.php">➕ Tambah Produk Baru</a>
<a href="index.php">Home Page</a>

<table>
    <tr>
        <th>Gambar</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Rating</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = $produk_result->fetch_assoc()): ?>
    <tr>
        <td>
            <?php
                $img = base64_encode($row['gambar']);
                $tipe = htmlspecialchars($row['tipe_gambar']);
            ?>
            <img src="data:<?php echo $tipe; ?>;base64,<?php echo $img; ?>" width="80" height="80" style="object-fit:cover;">
        </td>
        <td><?php echo htmlspecialchars($row['nama']); ?></td>
        <td>$<?php echo number_format($row['harga'], 2); ?></td>
        <td><?php echo $row['stock']; ?></td>
        <td><?php echo $row['rating']; ?> ⭐</td>
        <td>
            <a href="editProduk.php?id=<?php echo $row['id']; ?>">✏️ Edit</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
