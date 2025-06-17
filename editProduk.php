<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "Produk tidak ditemukan.";
    exit();
}

$produk_id = $_GET['id'];

$query = $conn->prepare("
    SELECT produk.* FROM produk 
    JOIN stores ON produk.store_id = stores.id 
    WHERE produk.id = ? AND stores.user_id = ?
");
$query->bind_param("ii", $produk_id, $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan atau Anda tidak memiliki akses.";
    exit();
}

$produk = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stock'];
    $rating = $_POST['rating'];

    if (!empty($_FILES['gambar']['tmp_name'])) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
        $tipe = $_FILES['gambar']['type'];

        $stmt = $conn->prepare("UPDATE produk SET nama=?, harga=?, stock=?, rating=?, gambar=?, tipe_gambar=? WHERE id=?");
        $stmt->bind_param("sdisssi", $nama, $harga, $stok, $rating, $gambar, $tipe, $produk_id);
        $stmt->send_long_data(4, $gambar);
    } else {
        $stmt = $conn->prepare("UPDATE produk SET nama=?, harga=?, stock=?, rating=? WHERE id=?");
        $stmt->bind_param("sdiii", $nama, $harga, $stok, $rating, $produk_id);
    }

    if ($stmt->execute()) {
        echo "✅ Produk berhasil diperbarui. <a href='dashboardToko.php'>Kembali ke Dashboard</a>";
        exit();
    } else {
        echo "❌ Gagal update: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 40px;
        }

        .button-wrapper {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-kembali {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-kembali:hover {
            background-color: #45a049;
        }

        .form-container {
            max-width: 500px;
            background: #fff;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .gambar-preview {
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .gambar-preview img {
            width: 120px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form input[type="submit"] {
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

    <div class="button-wrapper">
        <a href="dashboardToko.php" class="btn-kembali">🏠 Kembali ke Dashboard</a>
    </div>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <h2>Edit Produk</h2>

            <label>Nama Produk:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($produk['nama']) ?>" required>

            <label>Harga:</label>
            <input type="number" step="0.01" name="harga" value="<?= $produk['harga'] ?>" required>

            <label>Stok:</label>
            <input type="number" name="stock" value="<?= $produk['stock'] ?>" required>

            <label>Rating (0–5):</label>
            <input type="number" name="rating" min="0" max="5" value="<?= $produk['rating'] ?>" required>

            <label>Gambar Produk (biarkan kosong jika tidak ingin mengubah):</label>
            <input type="file" name="gambar" accept="image/*">

            <div class="gambar-preview">
                <small>Gambar saat ini:</small><br>
                <img src="data:<?= $produk['tipe_gambar'] ?>;base64,<?= base64_encode($produk['gambar']) ?>" alt="Gambar Produk">
            </div>

            <input type="submit" value="💾 Simpan Perubahan">
        </form>
    </div>

</body>

</html>