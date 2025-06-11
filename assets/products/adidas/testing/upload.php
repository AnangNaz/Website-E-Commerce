<?php
if (isset($_FILES["gambar"])) {
    $nama_file = $_FILES["gambar"]["name"];
    $tipe_file = $_FILES["gambar"]["type"];
    $data_file = file_get_contents($_FILES["gambar"]["tmp_name"]);

    $conn = new mysqli("localhost", "root", "", "testing");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO gambar_blob (nama_file, tipe_file, data) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama_file, $tipe_file, $data_file);
    $stmt->send_long_data(2, $data_file); // index 2 = kolom "data"

    if ($stmt->execute()) {
        echo "Gambar berhasil diupload! <br><a href='index.php'>Kembali</a>";
    } else {
        echo "Upload gagal: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Tidak ada file yang diupload.";
}
?>

