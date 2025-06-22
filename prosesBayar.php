<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['produk_id'], $_SESSION['nama'], $_SESSION['store_id'], $_SESSION['nama_toko'], $_SESSION['harga'], $_SESSION['jumlah_beli'])) {
    die("Data produk belum tersedia. Silakan pilih produk terlebih dahulu.");
}

$produk_id = $_SESSION['produk_id'];
$nama_produk = $_SESSION['nama'];
$toko_id = $_SESSION['store_id'];
$nama_toko = $_SESSION['nama_toko'];
$harga_satuan = $_SESSION['harga'];
$jumlah = $_SESSION['jumlah_beli'];

$harga = isset($_POST['harga']) ? (int)$_POST['harga'] : $harga_satuan;
$jumlah_post = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : $jumlah;
$metodeDipilih = $_POST['metode'] ?? '';

$total = $harga * $jumlah_post;
$biayaAdmin = 0;
$biayaAdmin = 0;
if ($metodeDipilih === 'bca') {
    $biayaAdmin = 1000;
} elseif ($metodeDipilih === 'bri') {
    $biayaAdmin = 1500;
} elseif ($metodeDipilih === 'cod') {
    $biayaAdmin = 0;
}

$status = 'sukses';// Ambil kecamatan toko
$queryToko = mysqli_query($conn, "SELECT kecamatan_toko FROM stores WHERE id = $toko_id");
$dataToko = mysqli_fetch_assoc($queryToko);
$kecamatanToko = $dataToko['kecamatan_toko'] ?? '';

// Ambil kecamatan pembeli
$queryUser = mysqli_query($conn, "SELECT kecamatan FROM user WHERE id = {$_SESSION['user_id']}");
$dataUser = mysqli_fetch_assoc($queryUser);
$kecamatanPembeli = $dataUser['kecamatan'] ?? '';

// Ambil biaya ongkir
$queryOngkir = mysqli_query($conn, "SELECT biaya FROM ongkir WHERE asal_kecamatan = '$kecamatanToko' AND tujuan_kecamatan = '$kecamatanPembeli'");
$dataOngkir = mysqli_fetch_assoc($queryOngkir);
$ongkir = $dataOngkir['biaya'] ?? 0;

// Total akhir
$totalFinal = $total + $biayaAdmin + $ongkir;

$nama_pembeli = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Anonim';

$kodePembayaran = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $metodeDipilih !== '') {
    $cekStok = mysqli_query($conn, "SELECT stock FROM produk WHERE id = $produk_id");
    $dataStok = mysqli_fetch_assoc($cekStok);

    if (!$dataStok) {
        die("Produk tidak ditemukan di database.");
    }

    $stok_tersedia = (int)$dataStok['stock'];

    if ($jumlah_post > $stok_tersedia) {
        die("Stok tidak mencukupi untuk jumlah pembelian.");
    }

 $query = "INSERT INTO penjualan 
(produk_id, toko_id, nama_toko, nama_produk, jumlah, harga_satuan, total_harga, nama_pembeli, status , metode_pembayaran, ongkir) 
VALUES 
('$produk_id', '$toko_id', '$nama_toko', '$nama_produk', '$jumlah_post', '$harga', '$totalFinal', '$nama_pembeli','$status', '$metodeDipilih', '$ongkir')";



    $simpan = mysqli_query($conn, $query);

    if ($simpan) {
        $stok_baru = $stok_tersedia - $jumlah_post;
        mysqli_query($conn, "UPDATE produk SET stock = $stok_baru WHERE id = $produk_id");

        if ($metodeDipilih !== 'cod') {
            $kodePembayaran = strtoupper(uniqid('PAY-'));
        }
        unset($_SESSION['produk_id'], $_SESSION['nama'], $_SESSION['store_id'], $_SESSION['nama_toko'], $_SESSION['harga'], $_SESSION['jumlah_beli']);
        header('location: pengiriman.php');
    } else {
        echo "Error saat menyimpan data penjualan: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Proses Pembayaran</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            padding: 30px;
            margin: 0;
        }

        .container {
            max-width: 420px;
            margin: 30px auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(246, 139, 30, 0.15);
            /* warna oranye semi transparan */
            border: 1px solid #fbe4c9;
            /* warna border oranye terang */
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4300FF;
            /* oranye */
            font-weight: 700;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            margin-top: 20px;
            color: #7a4e00;
            /* oranye gelap */
            font-size: 15px;
        }

        select,
        button {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1.8px solid #4300FF;
            /* oranye */
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        select:focus,
        button:focus {
            outline: none;
            border-color: #4300FF;
            /* oranye lebih gelap */
            box-shadow: 0 0 8px rgba(199, 108, 0, 0.5);
        }

        button {
            margin-top: 25px;
            background-color: #4300FF;
            /* oranye */
            color: white;
            font-weight: 700;
            border: none;
            cursor: pointer;
            user-select: none;
            box-shadow: 0 4px 8px rgba(246, 139, 30, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background-color: #0118D8;
            /* oranye gelap */
            box-shadow: 0 6px 15px rgba(199, 108, 0, 0.6);
        }

        .summary p {
            font-size: 16px;
            margin: 10px 0;
            color: #5a3900;
            /* oranye gelap */
        }

        .summary strong {
            font-weight: 700;
        }

        #kodePembayaran {
            background-color: #fff3e0;
            /* oranye sangat terang */
            border-left: 6px solid ;
            padding: 18px 20px;
            margin-top: 30px;
            font-weight: 700;
            color: #7a4e00;
            word-break: break-word;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(246, 139, 30, 0.15);
        }

        #jumlah_bayar {
            border: 1.8px solid #4300FF;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            margin-top: 8px;
            margin-bottom: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        #jumlah_bayar:focus {
            outline: none;
            border-color: #ffffff;
            box-shadow: 0 0 8px rgba(199, 108, 0, 0.5);
        }

        #notifBayar {
            font-weight: 700;
            margin-top: 10px;
        }
    </style>


</head>

<body>
    <div class="container">
        <h2>Proses Pembayaran</h2>

        <div class="summary">
            <p>Total Harga Produk: <strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></p>
            <p>Ongkos Kirim: <strong>Rp<?= number_format($ongkir, 0, ',', '.') ?></strong></p>
            <p>Biaya Admin: <strong id="biayaAdmin" data-total="<?= $total + $ongkir ?>">Rp<?= number_format($biayaAdmin, 0, ',', '.') ?></strong></p>
            <hr>
            <p>Total Bayar: <strong id="totalBayar">Rp<?= number_format($totalFinal, 0, ',', '.') ?></strong></p>

        </div>


        <?php if (!$kodePembayaran && $metodeDipilih === ''): ?>
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

                <button type="submit" id="btnProses">Proses</button>
            </form>
        <?php endif; ?>

        <?php if ($kodePembayaran): ?>
            <div id="kodePembayaran">
                Kode Pembayaran Anda:<br><strong><?= $kodePembayaran ?></strong>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $metodeDipilih === 'cod'): ?>
            <div id="kodePembayaran">
                Anda memilih <strong>COD</strong>. Tidak ada kode pembayaran.
            </div>
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