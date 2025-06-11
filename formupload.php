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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            width: 130%;
            max-width: 400px;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        form button {
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <h2 style="text-align: center; margin-bottom: 20px;">Upload Produk</h2>
        <input type="text" name="nama" placeholder="Nama Produk" required>
        <input type="number" name="harga" placeholder="Harga" required>
        <input type="number" name="rating" min="0" max="5" value="4" required>
        <input type="file" name="gambar" required>
        <button type="submit">Upload Produk</button>
    </form>

</body>
</html>
