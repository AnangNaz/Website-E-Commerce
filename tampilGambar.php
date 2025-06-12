<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = $id");

    if ($data = mysqli_fetch_assoc($query)) {
        header("Content-Type: image/jpeg");
        echo $data['gambar'];
        exit;
    } else {
        http_response_code(404);
        echo "Gambar tidak ditemukan.";
        exit;
    }
} else {
    http_response_code(400);
    echo "ID tidak valid.";
    exit;
}
?>
