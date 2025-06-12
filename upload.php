<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $rating = $_POST['rating'];
    $stock = $_POST['stock'];
    $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
    $tipe = $_FILES['gambar']['type'];

    $user_id = $_SESSION['user_id'];

    $stmt_store = $conn->prepare("SELECT id FROM stores WHERE user_id = ?");
    $stmt_store->bind_param("i", $user_id);
    $stmt_store->execute();
    $result = $stmt_store->get_result();

    if ($result->num_rows === 0) {
        echo "❌ Anda belum membuka toko. <a href='bukatoko.php'>Buka toko dulu</a>";
        exit();
    }

    $store = $result->fetch_assoc();
    $store_id = $store['id'];

    $stmt = $conn->prepare("INSERT INTO produk (nama, harga, rating, gambar, stock, tipe_gambar, store_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sdisssi", $nama, $harga, $rating, $gambar, $stock, $tipe, $store_id);

    $stmt->send_long_data(3, $gambar);

    if ($stmt->execute()) {
        echo "✅ Produk berhasil diupload! <a href='index.php'>Lihat Dashboard</a>";
    } else {
        echo "❌ Gagal upload: " . $stmt->error;
    }
}
