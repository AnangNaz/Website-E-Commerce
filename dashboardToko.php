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
    header('Location: bukatoko.php');
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <link rel="stylesheet" href="style.css">
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
            background-color: #4300FF;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .button-group a:hover {
            background-color: #0118D8;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            width: 200px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: 0.3s;
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-card h4 {
            margin: 10px 0 5px;
            font-size: 16px;
            color: #333;
        }

        .product-card .price {
            font-weight: bold;
            color: #444;
        }

        .product-card .stock {
            font-size: 14px;
            color: #666;
        }

        .product-card .rating {
            color: #f4c150;
            font-size: 14px;
            margin: 5px 0;
        }

        .product-card .edit-btn {
            margin-top: 10px;
            display: inline-block;
            padding: 6px 12px;
            background-color: #4300FF;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .product-card .edit-btn:hover {
            background-color: #0118D8;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Dashboard Toko: <?php echo htmlspecialchars($nama_toko); ?></h2>
        <div class="button-group">
            <a href="formupload.php">➕ Tambah Produk</a>
             <a href="editToko.php?id=<?php echo $store_id; ?>">✏️ Edit Toko</a>
            <a href="index.php">🏠 Home</a>
            <a href="laporanpenjualan.php">📊 Laporan Penjualan</a>
        </div>
    </div>

    <div class="product-container">
        <?php while ($row = $produk_result->fetch_assoc()): ?>
            <?php
            $img = base64_encode($row['gambar']);
            $tipe = htmlspecialchars($row['tipe_gambar']);
            ?>
            <div class="product-card">
                <img src="data:<?php echo $tipe; ?>;base64,<?php echo $img; ?>" alt="Produk">
                <h4><?php echo htmlspecialchars($row['nama']); ?></h4>
                <div class="rating">
                    <?php
                    $rating = intval($row['rating']);
                    for ($i = 0; $i < 5; $i++) {
                        echo $i < $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                    }
                    ?>
                </div>
                <div class="price">IDR <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                <div class="stock">Stok: <?php echo $row['stock']; ?></div>
                <a href="editProduk.php?id=<?php echo $row['id']; ?>" class="edit-btn">✏️ Edit</a>
            </div>
        <?php endwhile; ?>
    </div>

    <footer>
    <div class="footer-grid">

      <!-- Baris 1 -->
      <div class="footer-section">
        <h4>Home</h4>
        <ul>
          <li><a href="index.php">Beranda</a></li>
          <li><a href="#">Tentang Kami</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Karir</a></li>
          <li><a href="#">Kontak</a></li>
        </ul>
      </div>

      <!-- Baris 2 -->
      <div class="footer-section">
        <h4>TokoSaya</h4>
        <ul>
          <li><a href="dashboardToko.php">Produk</a></li>
          <li><a href="#">Kategori</a></li>
          <li><a href="#">Promo</a></li>
          <li><a href="#">Keranjang</a></li>
          <li><a href="#">Riwayat</a></li>
        </ul>
      </div>

      <!-- Baris 3 -->
      <div class="footer-section">
        <h4>Pasar</h4>
        <ul>
          <li><a href="#">Lapak Terbaru</a></li>
          <li><a href="#">Paling Dicari</a></li>
          <li><a href="#">Pasar Rakyat</a></li>
          <li><a href="#">Pasar Digital</a></li>
          <li><a href="#">Pasar Lokal</a></li>
        </ul>
      </div>

      <!-- Baris 4: Kolom Pencarian -->
     <div class="footer-section search-section">
        <h4>Cari Sesuatu</h4>
        <form action="pencarian.php" method="GET">
            <input type="text" name="q" placeholder="Cari di sini..." required>
            <button type="submit">Cari</button>
        </form>
    </div>

    </div>
  </footer>

</body>

</html>