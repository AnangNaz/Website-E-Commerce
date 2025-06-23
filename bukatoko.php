<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_toko = trim($_POST['nama_toko'] ?? '');
    $kecamatan = trim($_POST['kecamatan'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $user_id = $_SESSION['user_id'];

    $logo = null;
    if (!empty($_FILES['logo']['tmp_name']) && is_uploaded_file($_FILES['logo']['tmp_name'])) {
        $logo = file_get_contents($_FILES['logo']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO stores (user_id, nama_toko, kecamatan, deskripsi, logo) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        $pesan = "❌ Gagal mempersiapkan statement: " . $conn->error;
    } else {
        $stmt->bind_param("isssb", $user_id, $nama_toko, $kecamatan, $deskripsi, $null);

        if ($logo !== null) {
            $stmt->send_long_data(4, $logo);
        }

        if ($stmt->execute()) {
            $pesan = "✅ Toko berhasil dibuat! <a href='index.php'>Lanjut ke Dashboard</a>";
        } else {
            $pesan = "❌ Gagal membuat toko: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Buka Toko</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        /* (CSS sama seperti versi sebelumnya - dipertahankan) */
    </style>
</head>
<body class="register-page">
    <div class="header" style="padding: 15px 30px;">
        <div style="flex: 1;"></div>
        <div class="button-group">
            <a href="index.php">🏠 Home</a>
        </div>
    </div>

    <main role="main" class="container">
        <h2 class="title">Buka Toko Baru</h2>

        <?php if ($pesan): ?>
            <p><?= $pesan ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>
            <div class="form-group">
                <label for="nama_toko">Nama Toko</label>
                <input type="text" name="nama_toko" id="nama_toko" required>
            </div>

            <div class="form-group">
                <label for="kecamatan">Kecamatan Toko</label>
                <select name="kecamatan" id="kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    <option value="Taktakan">Taktakan</option>
                    <option value="Cipocok Jaya">Cipocok Jaya</option>
                    <option value="Kasemen">Kasemen</option>
                    <option value="Curug">Curug</option>
                    <option value="Serang">Serang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" cols="40"></textarea>
            </div>

            <div class="form-group">
                <label for="logo">Logo (gambar):</label>
                <input type="file" name="logo" id="logo" accept="image/*">
            </div>

            <input type="submit" class="button-submit" value="Buka Toko">
        </form>
    </main>
</body>
</html>
