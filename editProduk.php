<?php
session_start();
include("koneksi.php");

// Cek login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "Produk tidak ditemukan.";
    exit();
}

$produk_id = $_GET['id'];

// Ambil produk berdasarkan ID dan user yang sedang login
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

    // Jika gambar baru diupload
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
        $tipe = $_FILES['gambar']['type'];

        $stmt = $conn->prepare("UPDATE produk SET nama=?, harga=?, stock=?, rating=?, gambar=?, tipe_gambar=? WHERE id=?");
        $stmt->bind_param("sdisssi", $nama, $harga, $stok, $rating, $gambar, $tipe, $produk_id);
        $stmt->send_long_data(4, $gambar);
    } else {
        // Jika tidak ubah gambar
        $stmt = $conn->prepare("UPDATE produk SET nama=?, harga=?, stock=?, rating=? WHERE id=?");
        $stmt->bind_param("sdiii", $nama, $harga, $stok, $rating, $produk_id);
    }

    if ($stmt->execute()) {
        echo "✅ Produk berhasil diperbarui. <a href='dashboard_toko.php'>Kembali ke Dashboard</a>";
        exit();
    } else {
        echo "❌ Gagal update: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
</head>
<body>
<h2>Edit Produk</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama" value="<?php echo htmlspecialchars($produk['nama']); ?>" required><br><br>

    <label>Harga:</label><br>
    <input type="number" step="0.01" name="harga" value="<?php echo $produk['harga']; ?>" required><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stock" value="<?php echo $produk['stock']; ?>" required><br><br>

    <label>Rating (0–5):</label><br>
    <input type="number" name="rating" min="0" max="5" value="<?php echo $produk['rating']; ?>" required><br><br>

    <label>Gambar Produk (biarkan kosong jika tidak ingin mengubah):</label><br>
    <input type="file" name="gambar" accept="image/*"><br>
    <small>Gambar sekarang:</small><br>
    <img src="data:<?php echo $produk['tipe_gambar']; ?>;base64,<?php echo base64_encode($produk['gambar']); ?>" width="120"><br><br>

    <input type="submit" value="💾 Simpan Perubahan">
</form>

<br>
<a href="dashboard_toko.php">⬅️ Kembali ke Dashboard</a>
</body>
</html>
