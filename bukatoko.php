<?php
session_start();
include("koneksi.php");

// Cek login


$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_toko = $_POST['nama_toko'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_SESSION['user_id'];

    $logo = null;
    if (!empty($_FILES['logo']['tmp_name'])) {
        $logo = file_get_contents($_FILES['logo']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO stores (user_id, nama_toko, deskripsi, logo) VALUES (?, ?, ?, ?)");
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
    <link rel="stylesheet" href="style.css">
</head>
<body class="register-page">
    <main role="main">
    <h2 class="title">Buka Toko Baru</h2>

    <?php if ($pesan): ?>
        <p><?= $pesan ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
         <div class="form-group">
                <label for="name">Nama Toko</label>
                <input type="text"  name="nama_toko" required>
         </div>
         <div class="form-group">
            <label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="4" cols="40"></textarea>
        </div>
        <div class="form_group">
        <label>Logo (gambar):</label><br>
        <input type="file" name="logo" accept="image/*">    
        </div>

        <input type="submit" class="button-submit" value="Buka Toko">
    </form>
    </main>
</body>
</html>
