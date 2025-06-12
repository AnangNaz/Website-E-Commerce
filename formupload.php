<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Upload Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="register-page">
    <main role="main">

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <h2 class="title">Upload Produk</h2>
        <div class="form-group">
            <input type="text" name="nama" placeholder="Nama Produk" required>
        </div>
        <div class="form-group">
            <input type="number" name="harga" placeholder="Harga" required>
        </div>
        <div class="form-group">
            <input type="number" name="rating" min="0" max="5" value="4" required>
        </div>
        <div class="form-group">
            <input type="file" name="gambar" required>
        </div>
        
        <button type="submit" class="button-submit">Upload Produk</button>
    </form>
</main>
</body>
</html>
