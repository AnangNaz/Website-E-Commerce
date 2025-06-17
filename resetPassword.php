<?php
session_start();
include 'koneksi.php';

$pesan = '';

// Step 1: Cek email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $cek = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['reset_email'] = $email;
    } else {
        $pesan = "<p class='message error'>Email tidak ditemukan.</p>";
    }
}

// Step 2: Update password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passwordBaru'])) {
    if (isset($_SESSION['reset_email'])) {
        $email = $_SESSION['reset_email'];
        $passwordBaru = password_hash($_POST['passwordBaru'], PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE user SET password='$passwordBaru' WHERE email='$email'");
        unset($_SESSION['reset_email']);

        if ($update) {
            $pesan = "<p class='message success'>Password berhasil diubah. <a href='login.php'>Login di sini</a></p>";
        } else {
            $pesan = "<p class='message error'>Gagal mengubah password.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="container">
        <h2>Reset Password</h2>
        <?= $pesan ?>

        <?php if (!isset($_SESSION['reset_email'])): ?>
            <!-- Form Cek Email -->
            <form method="post">
                <label for="email">Masukkan Email Anda:</label>
                <input type="text" name="email" id="email" required>
                <input type="submit" value="Lanjut">
            </form>
        <?php else: ?>
            <!-- Form Ganti Password -->
            <form method="post">
                <label for="passwordBaru">Password Baru:</label>
                <input type="password" name="passwordBaru" id="passwordBaru" required>
                <input type="submit" value="Ubah Password">
            </form>
        <?php endif; ?>
            <a href="login.php">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>
