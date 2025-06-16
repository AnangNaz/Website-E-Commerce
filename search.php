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
        .produk-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }

        .produk-item {
            flex: 0 0 calc(50% - 10px);
            box-sizing: border-box;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
            min-height: 130px;
        }



        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }



        .produk-item img {
            max-width: 100px;
            border-radius: 5px;
        }

        .produk-info {
            flex: 1;
        }

        .produk-info h3 {
            margin: 0 0 8px 0;
        }

        .produk-info p {
            margin: 4px 0;
        }
    </style>
</head>

<body>

    <a href="index.php">← Kembali ke Homepage</a>

    <h2>Hasil pencarian untuk: <?= htmlspecialchars($search_sql) ?></h2>
    <p>Ditemukan <?= mysqli_num_rows($result) ?> produk</p>

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
                    <form action="pembayaran.php" method="post" style="margin-left: auto;">
                        <input type="hidden" name="produk_id" value="<?= $row['id'] ?>">
                        <button type="submit">Beli</button>
                    </form>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <p>Produk atau toko tidak ditemukan.</p>
        <?php endif; ?>
    </div>


</body>

</html>