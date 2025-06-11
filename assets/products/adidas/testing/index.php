<!DOCTYPE html>
<html>
<head>
    <title>Galeri Gambar dari BLOB</title>
</head>
<body>
    <h2>Upload Gambar</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="gambar" required>
        <button type="submit">Upload</button>
    </form>

    <hr>

    <h2>Galeri Gambar</h2>
    <?php
    $conn = new mysqli("localhost", "root", "", "testing");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM gambar_blob");

    while ($row = $result->fetch_assoc()) {
        $tipe = $row['tipe_file'];
        $data = base64_encode($row['data']);
        echo "<img src='data:$tipe;base64,$data' width='200' style='margin:10px;'><br>";
    }
    ?>
</body>
</html>
