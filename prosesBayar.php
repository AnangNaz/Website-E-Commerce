<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['produk_id'], $_SESSION['nama'], $_SESSION['store_id'], $_SESSION['nama_toko'], $_SESSION['harga'], $_SESSION['jumlah_beli'])) {
    die("Data produk belum tersedia. Silakan pilih produk terlebih dahulu.");
}

$produk_id = $_SESSION['produk_id'];
$nama_produk = $_SESSION['nama'];
$store_id = $_SESSION['store_id'];
$nama_toko = $_SESSION['nama_toko'];
$harga_satuan = $_SESSION['harga'];
$jumlah = $_SESSION['jumlah_beli'];

$harga = isset($_POST['harga']) ? (int)$_POST['harga'] : $harga_satuan;
$jumlah_post = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : $jumlah;
$metodeDipilih = $_POST['metode'] ?? '';

$total = $harga * $jumlah_post;

$biayaAdmin = 0;
$kodePembayaran = "";

function generateKode($prefix)
{
    return $prefix . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $metodeDipilih !== '') {
    if ($metodeDipilih === 'bri') {
        $biayaAdmin = 1500;
        $kodePembayaran = generateKode('128');
    } elseif ($metodeDipilih === 'bca') {
        $biayaAdmin = 1000;
        $kodePembayaran = generateKode('129');
    } elseif ($metodeDipilih === 'cod') {
        $biayaAdmin = 0;
        $kodePembayaran = "";
    } else {
        die("Metode pembayaran tidak valid.");
    }

    $totalFinal = $total + $biayaAdmin;
    $nama_pembeli = $_SESSION['user_name'] ?? 'Anonim'; 

    $status = 'sukses';

    $sql = "INSERT INTO penjualan 
            (produk_id, nama_produk, toko_id, nama_toko, harga_satuan, jumlah, total_harga, nama_pembeli, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare statement error: " . $conn->error);
    }

    $stmt->bind_param(
        "isissdiss",
        $produk_id,
        $nama_produk,
        $store_id,
        $nama_toko,
        $harga_satuan,
        $jumlah_post,
        $totalFinal,
        $nama_pembeli,
        $status
    );

    if ($stmt->execute()) {
        $_SESSION['kode_pembayaran'] = $kodePembayaran;
        $_SESSION['total_bayar'] = $totalFinal;
        $_SESSION['metode_pembayaran'] = $metodeDipilih;

        header("Location: pengiriman.php");
        exit;
    } else {
        die("Error saat menyimpan data penjualan: " . $stmt->error);
    }
} else {
    $totalFinal = $total; 
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Proses Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 420px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #007bff;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            margin-top: 20px;
        }

        select,
        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            margin-top: 25px;
            background-color: #007bff;
            color: white;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .summary p {
            font-size: 16px;
            margin: 10px 0;
        }

        .summary strong {
            color: #333;
        }

        #kodePembayaran {
            background-color: #e1f0ff;
            border-left: 5px solid #007bff;
            padding: 15px;
            margin-top: 30px;
            font-weight: 600;
            word-break: break-word;
        }

        #jumlah_bayar {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            margin-top: 8px;
        }

        #jumlah_bayar:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        #jumlah_bayar::placeholder {
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Proses Pembayaran</h2>

        <div class="summary">
            <p>Total Harga Produk: <strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></p>
            <p>Biaya Admin: <strong id="biayaAdmin" data-total="<?= $total ?>"><?= 'Rp' . number_format($biayaAdmin, 0, ',', '.') ?></strong></p>
            <hr>
            <p>Total Bayar: <strong id="totalBayar">Rp<?= number_format($totalFinal, 0, ',', '.') ?></strong></p>
        </div>

        <form method="post" action="">
            <input type="hidden" name="harga" value="<?= htmlspecialchars($harga) ?>">
            <input type="hidden" name="jumlah" value="<?= htmlspecialchars($jumlah_post) ?>">

            <label for="metode">Pilih Metode Pembayaran:</label>
            <select name="metode" id="metode" required>
                <option value="">-- Pilih Metode --</option>
                <option value="bri" <?= $metodeDipilih === 'bri' ? 'selected' : '' ?>>BRI</option>
                <option value="bca" <?= $metodeDipilih === 'bca' ? 'selected' : '' ?>>BCA</option>
                <option value="cod" <?= $metodeDipilih === 'cod' ? 'selected' : '' ?>>COD</option>
            </select>

            <button type="submit" id="btnProses" disabled>Proses</button>
        </form>

        <?php if ($kodePembayaran): ?>
            <div id="kodePembayaran">
                Kode Pembayaran Anda:<br><strong><?= $kodePembayaran ?></strong>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $metodeDipilih === 'cod'): ?>
            <div id="kodePembayaran">
                Anda memilih <strong>COD</strong>. Tidak ada kode pembayaran.
            </div>
        <?php else: ?>
            <div id="kodePembayaran" style="display:none;"></div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(function() {
            const metodeSelect = $('#metode');
            const biayaAdminEl = $('#biayaAdmin');
            const totalBayarEl = $('#totalBayar');
            const btnProses = $('#btnProses');

            if ($('#jumlah_bayar').length === 0) {
                $('<label for="jumlah_bayar">Masukkan Jumlah Bayar (Rp):</label>').insertBefore(btnProses);
                $('<input type="number" id="jumlah_bayar" name="jumlah_bayar" min="0" required placeholder="Masukkan jumlah bayar" />').insertBefore(btnProses);
                $('<div id="notifBayar" style="font-weight:bold; margin-top:10px; display:none;"></div>').insertBefore(btnProses);
            }

            const jumlahBayarInput = $('#jumlah_bayar');
            const notifBayar = $('#notifBayar');

            const totalProduk = Number(biayaAdminEl.data('total')) || 0;

            const adminFees = {
                bri: 1500,
                bca: 1000,
                cod: 0
            };

            function formatRupiah(angka) {
                return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function updatePembayaran() {
                const metode = metodeSelect.val();

                if (!metode) {
                    biayaAdminEl.text('Rp0');
                    totalBayarEl.text(formatRupiah(totalProduk));
                    jumlahBayarInput.attr('min', totalProduk);
                    checkBayar();
                    return;
                }

                const biayaAdmin = adminFees[metode] ?? 0;
                biayaAdminEl.text(formatRupiah(biayaAdmin));
                const totalFinal = totalProduk + biayaAdmin;
                totalBayarEl.text(formatRupiah(totalFinal));
                jumlahBayarInput.attr('min', totalFinal);
                checkBayar();
            }

            function checkBayar() {
                const metode = metodeSelect.val();
                const biayaAdmin = adminFees[metode] ?? 0;
                const totalFinal = totalProduk + biayaAdmin;

                const bayar = Number(jumlahBayarInput.val());

                if (jumlahBayarInput.val() === '') {
                    notifBayar.hide();
                    btnProses.prop('disabled', true);
                    return;
                }

                if (bayar < totalFinal) {
                    notifBayar.text('Uang kurang!');
                    notifBayar.css('color', 'red');
                    notifBayar.show();
                    btnProses.prop('disabled', true);
                } else {
                    let kembalian = bayar - totalFinal;
                    if (kembalian > 0) {
                        notifBayar.text('Kembalian: ' + formatRupiah(kembalian));
                        notifBayar.css('color', 'green');
                        notifBayar.show();
                    } else {
                        notifBayar.hide();
                    }
                    btnProses.prop('disabled', false);
                }
            }

            metodeSelect.on('change', updatePembayaran);
            jumlahBayarInput.on('input', checkBayar);

            updatePembayaran();
        });
    </script>

</body>

</html>
