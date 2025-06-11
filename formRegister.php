<?php
session_start();

$error = $_SESSION['error'] ?? [];
$pesan = $_SESSION['pesan'] ?? '';
$nama = $_SESSION['nama'] ?? '';
$email = $_SESSION['email'] ?? '';
$no_telp = $_SESSION['no_telp'] ?? ''; 

unset($_SESSION['error']);
unset($_SESSION['pesan']);
unset($_SESSION['nama']);
unset($_SESSION['email']);
unset($_SESSION['no_telp']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        main.container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .title {
            font-size: 28px;
            margin-bottom: 8px;
            text-align: center;
            color: #333;
        }

        .subtitle {
            font-size: 16px;
            text-align: center;
            color: #666;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: border 0.2s ease;
        }

        input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
        }

        .message.error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .message.success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .message ul {
            margin: 0;
            padding-left: 20px;
        }

        @media (max-width: 600px) {
            main.container {
                padding: 20px;
            }

            .title {
                font-size: 24px;
            }

            .subtitle {
                font-size: 14px;
            }
        }
    </style>
</head>
<body class="register-page">
    <main class="container" role="main">
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
                       aria-required="true" aria-invalid="<?= !empty($error) && empty($password) ? 'true' : 'false' ?>"
                       placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label for="no_telp">Nomor Telepon</label>
                <input type="text" id="no_telp" name="no_telp" required
                       value="<?= htmlspecialchars($no_telp ?? '') ?>"
                       aria-required="true" aria-invalid="<?= !empty($error) && empty($no_telp) ? 'true' : 'false' ?>"
                       placeholder="Masukkan nomor telepon Anda">
            </div>

            <button type="submit" class="btn-submit" aria-label="Daftar akun baru">Daftar</button>
        </form>
    </main>
</body>
</html>

