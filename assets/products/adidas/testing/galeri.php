<!DOCTYPE html>
<html>
<head>
    <title>Galeri Gambar</title>
</head>
<body>
    <h2>Galeri Gambar</h2>
    <?php
    $conn = new mysqli("localhost", "root", "", "testing");
    $result = $conn->query("SELECT * FROM gambar");

    while($row = $result->fetch_assoc()) {
        echo "<img src='".$row['path']."' width='200' style='margin:10px;'><br>";
    }
    ?>
</body>
</html>
