<?php
session_start();
include("koneksi.php");

// Cek login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_toko = $_POST['nama_toko'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_SESSION['user_id'];

    $logo = null;
    if (!empty($_FILES['logo']['tmp_name'])) {
        $logo = file_get_contents($_FILES['logo']['tmp_name']);
    }

    $stmt = $db->prepare("INSERT INTO stores (user_id, nama_toko, deskripsi, logo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $nama_toko, $deskripsi, $logo);
    $stmt->send_long_data(3, $logo);

    if ($stmt->execute()) {
        $pesan = "✅ Toko berhasil dibuat! <a href='index.php'>Lanjut ke Dashboard</a>";
    } else {
        $pesan = "❌ Gagal membuat toko: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buka Toko</title>
</head>
<body>
    <h2>Buka Toko Baru</h2>

    <?php if ($pesan): ?>
        <p><?= $pesan ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nama Toko:</label><br>
        <input type="text" name="nama_toko" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="4" cols="40"></textarea><br><br>

        <label>Logo (gambar):</label><br>
        <input type="file" name="logo" accept="image/*"><br><br>

        <input type="submit" value="Buka Toko">
    </form>
</body>
</html>
