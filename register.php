<?php
session_start();
include("koneksi.php");

$error = [];
$pesan = '';
$nama = '';
$email = '';
$no_telp = '';

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $no_telp = trim($_POST['no_telp'] ?? '');

    // Validasi
    if (empty($nama)) {
        $error[] = 'Nama harus diisi.';
    }
    if (empty($email)) {
        $error[] = 'Email harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Format email tidak valid.';
    }
    if (empty($password)) {
        $error[] = 'Password harus diisi.';
    }
    if (empty($no_telp)) {
        $error[] = 'Nomor telepon harus diisi.';
    } elseif (!preg_match('/^[0-9]+$/', $no_telp)) {
        $error[] = 'Nomor telepon hanya boleh terdiri dari angka.';
    }

    // Cek duplikat email & no_telp
    if (empty($error)) {
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error[] = 'Email sudah terdaftar.';
        }
        $stmt->close();
    }

    if (empty($error)) {
        $stmt = $conn->prepare("SELECT id FROM user WHERE no_telp = ?");
        $stmt->bind_param("s", $no_telp);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error[] = 'Nomor telepon sudah terdaftar.';
        }
        $stmt->close();
    }

    // Insert data jika validasi lolos
    if (empty($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (nama, email, password, no_telp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $hashed_password, $no_telp);
        if ($stmt->execute()) {
            $_SESSION['pesan'] = "Registrasi berhasil! Silakan login.";
            header("Location: login.php");
            exit();
        } else {
            $error[] = "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    }

    if (!empty($error)) {
        $_SESSION['error'] = $error;
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['no_telp'] = $no_telp;
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            background: white;
            margin: 80px auto;
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            background-color: #ffeaea;
            border-left: 4px solid red;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🔐 Registrasi Pengguna</h2>

        <?php
        if (!empty($_SESSION['error'])) {
            echo '<div class="error"><ul>';
            foreach ($_SESSION['error'] as $e) {
                echo '<li>' . htmlspecialchars($e) . '</li>';
            }
            echo '</ul></div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="register.php" method="POST">
            <label for="name">Nama</label>
            <input type="text" name="name" id="name" required value="<?= htmlspecialchars($_SESSION['nama'] ?? '') ?>" />

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" />

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />

            <label for="no_telp">Nomor Telepon</label>
            <input type="text" name="no_telp" id="no_telp" required value="<?= htmlspecialchars($_SESSION['no_telp'] ?? '') ?>" />

            <input type="submit" value="Daftar" />
        </form>

        <a class="back-link" href="login.php">Sudah punya akun? Login di sini</a>
    </div>
    <?php
    // Hapus data session input setelah form tampil
    unset($_SESSION['nama'], $_SESSION['email'], $_SESSION['no_telp']);
    ?>
</body>

</html>