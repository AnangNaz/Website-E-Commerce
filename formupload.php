<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Upload Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 40px;
        }

        .button-wrapper {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-kembali {
            background-color: #4300ff;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-kembali:hover {
            background-color: #0118D8;
        }

        .form-container {
            max-width: 450px;
            background: #fff;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        form button {
            width: 100%;
            background-color: #4300ff;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        form button:hover {
            background-color: #0118D8;
        }

        .secondary-button {
            background-color: #95a5a6;
        }

        .secondary-button:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>

<body>

    <div class="button-wrapper">
        <a href="dashboardToko.php" class="btn-kembali">🏠 Kembali ke Dashboard Toko</a>
    </div>

    <div class="form-container">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <h2>Upload Produk</h2>

            <label for="nama">Nama Produk</label>
            <input type="text" name="nama" placeholder="Nama Produk" required>

            <label for="harga">Harga</label>
            <input type="number" name="harga" placeholder="Harga" required>

            <label for="rating">Rating</label>
            <input type="number" name="rating" min="0" max="5" value="4" required>

            <label for="stock">Stok</label>
            <input type="number" name="stock" required>

            <label for="gambar">Gambar Produk</label>
            <input type="file" name="gambar" required>

            <button type="submit">Upload Produk</button>
        </form>
    </div>

</body>

</html>