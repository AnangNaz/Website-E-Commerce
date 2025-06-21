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
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $user_id = $_SESSION['user_id'];

    $logo = null;
    if (!empty($_FILES['logo']['tmp_name']) && is_uploaded_file($_FILES['logo']['tmp_name'])) {
        $logo = file_get_contents($_FILES['logo']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO stores (user_id, nama_toko, deskripsi, logo) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        $pesan = "❌ Gagal mempersiapkan statement: " . $conn->error;
    } else {
        $stmt->bind_param("issb", $user_id, $nama_toko, $deskripsi, $null);

        if ($logo !== null) {
            $stmt->send_long_data(3, $logo);
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
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            background: white;
            margin: 80px auto;
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2.title {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"].button-submit {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"].button-submit:hover {
            background-color: #0056b3;
        }

        p {
            font-size: 15px;
            color: #333;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .header {
            display: flex;
            align-items: center;
            padding: 15px 30px;
        }

        .button-group a {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            font-weight: 600;
        }

        .button-group a:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body class="register-page">

    <div class="header" style="padding: 15px 30px;">
        <div style="flex: 1;"></div> <!-- Spacer kiri supaya tombol pindah ke kanan -->
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
                <label for="deskripsi">Deskripsi:</label><br>
                <textarea name="deskripsi" id="deskripsi" rows="4" cols="40"></textarea>
            </div>

            <div class="form-group">
                <label for="logo">Logo (gambar):</label><br>
                <input type="file" name="logo" id="logo" accept="image/*">
            </div>

            <input type="submit" class="button-submit" value="Buka Toko">
        </form>
    </main>
</body>

</html>