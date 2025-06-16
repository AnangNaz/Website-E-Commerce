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
<<<<<<< HEAD
        <h2 style="text-align: center; margin-bottom: 20px;">Upload Produk</h2>
        <label for="">Nama Produk</label>
        <input type="text" name="nama" placeholder="Nama Produk" required>
         <label for="">Harga</label>
        <input type="number" name="harga" placeholder="Harga" required>
         <label for="">Rating</label>
        <input type="number" name="rating" min="0" max="5" value="4" required>
         <label for="">Stock</label>
        <input type="number" name="stock"  required>
         <label for="">Gambar Produk</label>
        <input type="file" name="gambar" required>
        <button type="submit">Upload Produk</button>
        <button><a href="dashboardToko.php">Dashboard Toko</a></button>
=======
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
>>>>>>> 93e13ad59ea988cfffd094a3f03f08c9bf9668a0
    </form>
</main>
</body>
</html>
