<?php
session_start();

$error = $_SESSION['error'] ?? [];
$pesan = $_SESSION['pesan'] ?? '';
$nama = $_SESSION['nama'] ?? '';
$email = $_SESSION['email'] ?? '';
$no_telp = $_SESSION['no_telp'] ?? '';
$kecamatan = $_SESSION['kecamatan'] ?? '';

unset($_SESSION['error'], $_SESSION['pesan'], $_SESSION['nama'], $_SESSION['email'], $_SESSION['no_telp'], $_SESSION['kecamatan']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="register-page">
    <main role="main">
        <h1 class="title">Daftar Akun Baru</h1>
        <p class="subtitle">Isi formulir berikut untuk membuat akun baru Anda.</p>

        <?php if (!empty($error)): ?>
            <div class="message error" role="alert" aria-live="assertive">
                <ul>
                    <?php foreach ($error as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($pesan): ?>
            <div class="message success" role="alert" aria-live="polite">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" novalidate autocomplete="off" aria-describedby="form-desc">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                       value="<?= htmlspecialchars($nama ?? '') ?>"
                       aria-required="true" aria-invalid="<?= !empty($error) && empty($nama) ? 'true' : 'false' ?>"
                       placeholder="Masukkan nama lengkap Anda">
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" required
                       value="<?= htmlspecialchars($email ?? '') ?>"
                       aria-required="true" aria-invalid="<?= !empty($error) && (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) ? 'true' : 'false' ?>"
                       placeholder="contoh@mail.com">
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                       aria-required="true"
                       placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label for="no_telp">Nomor Telepon</label>
                <input type="text" id="no_telp" name="no_telp" required
                       value="<?= htmlspecialchars($no_telp ?? '') ?>"
                       aria-required="true" aria-invalid="<?= !empty($error) && empty($no_telp) ? 'true' : 'false' ?>"
                       placeholder="Masukkan nomor telepon Anda">
            </div>

            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    <?php
                    $daftar_kecamatan = ['Taktakan', 'Cipocok Jaya', 'Kasemen', 'Serang', 'Walantaka'];
                    foreach ($daftar_kecamatan as $kcmt) {
                        $selected = $kcmt === $kecamatan ? 'selected' : '';
                        echo "<option value=\"$kcmt\" $selected>$kcmt</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="button-submit" aria-label="Daftar akun baru">Daftar</button>
        </form>
    </main>
</body>
</html>
