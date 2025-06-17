<?php
include 'koneksi.php';

$search = $_GET['search'] ?? '';
$search_sql = mysqli_real_escape_string($conn, $search);

$query = "
  SELECT produk.*, stores.nama_toko 
  FROM produk 
  JOIN stores ON produk.store_id = stores.id
  WHERE 
    LOWER(produk.nama) LIKE LOWER('%$search_sql%') OR 
    LOWER(stores.nama_toko) LIKE LOWER('%$search_sql%')
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Hasil Pencarian</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        h2 {
            margin-top: 0;
        }

        .produk-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .produk-item {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 16px;
            display: flex;
            gap: 16px;
            width: calc(50% - 10px);
            box-sizing: border-box;
            align-items: center;
            transition: transform 0.2s;
        }

        .produk-item:hover {
            transform: scale(1.01);
        }

        .produk-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }

        .produk-info {
            flex: 1;
        }

        .produk-info h3 {
            margin: 0 0 6px;
            font-size: 18px;
            color: #222;
        }

        .produk-info p {
            margin: 4px 0;
            font-size: 14px;
        }

        .btn-beli {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-beli:hover {
            background-color: #43a047;
        }

        .info-pencarian {
            margin-top: 10px;
            color: #666;
        }

        .button-wrapper {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-kembali {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-kembali:hover {
            background-color: #45a049;
        }


        @media (max-width: 768px) {
            .produk-item {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .produk-item img {
                margin-bottom: 10px;
            }

            form {
                width: 100%;
                text-align: right;
            }
        }
    </style>
</head>

<body>

    <div class="button-wrapper">
        <a href="index.php" class="btn-kembali">🏠 Kembali ke Homepage</a>
    </div>



    <h2>Hasil pencarian untuk: <?= htmlspecialchars($search_sql) ?></h2>
    <p class="info-pencarian">Ditemukan <?= mysqli_num_rows($result) ?> produk</p>

    <div class="produk-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="produk-item">
                    <?php
                    $imgSrc = "data:" . $row['tipe_gambar'] . ";base64," . base64_encode($row['gambar']);
                    ?>
                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                    <div class="produk-info">
                        <h3><?= htmlspecialchars($row['nama']) ?></h3>
                        <p><strong>Toko:</strong> <?= htmlspecialchars($row['nama_toko']) ?></p>
                        <p><strong>Harga:</strong> IDR <?= number_format($row['harga']) ?></p>
                        <p><strong>Stok:</strong> <?= intval($row['stock']) ?></p>
                    </div>
                    <form action="pembayaran.php" method="post">
                        <input type="hidden" name="produk_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-beli">🛒 Beli</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Produk atau toko tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>

</html>