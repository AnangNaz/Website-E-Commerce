<?php
session_start();
include("koneksi.php");

$toko_id = $_GET['id'] ?? null;

if (!$toko_id) {
    echo "ID toko tidak ditemukan.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_toko = $_POST['nama_toko'];
    $deskripsi = $_POST['deskripsi'];

    if ($_FILES['logo']['size'] > 0) {
        $logo = addslashes(file_get_contents($_FILES['logo']['tmp_name']));
        $sql = "UPDATE stores SET nama_toko='$nama_toko', deskripsi='$deskripsi', logo='$logo' WHERE id=$toko_id";
    } else {
        $sql = "UPDATE stores SET nama_toko='$nama_toko', deskripsi='$deskripsi' WHERE id=$toko_id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Toko berhasil diperbarui'); window.location.href='dashboardToko.php';</script>";
    } else {
        echo "Gagal mengupdate: " . $conn->error;
    }
}

$query = $conn->query("SELECT * FROM stores WHERE id=$toko_id");
$data = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Toko</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            color: #444;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        button {
            margin-top: 20px;
            background-color: #4300FF;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0118D8;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #4300FF;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>✏️ Edit Toko</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Toko</label>
        <input type="text" name="nama_toko" value="<?= htmlspecialchars($data['nama_toko']) ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="5"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

        <label>Logo Baru (opsional)</label>
        <input type="file" name="logo" accept="image/*">

        <button type="submit">💾 Simpan Perubahan</button>
    </form>

    <a href="dashboardToko.php" class="back-link">← Kembali ke Dashboard</a>
</div>

</body>
</html>
