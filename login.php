<?php
session_start();
include("koneksi.php");

$error = '';
$pesan = '';

// Ambil pesan error jika ada di session
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['pesan'])) {
    $pesan = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}

// Proses login ketika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan password harus diisi.";
        header("Location: login.php");
        exit();
    }

    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Set session jika berhasil login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nama'];

                // Redirect ke index.php
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Password salah.";
            }
        } else {
            $_SESSION['error'] = "Email tidak ditemukan.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Terjadi kesalahan pada server.";
    }

    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Login</title>
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

        .success {
            color: green;
            background-color: #e6ffe6;
            border-left: 4px solid green;
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
        <h2>🔐 Login Pengguna</h2>

        <?php if (!empty($pesan)): ?>
            <div class="success"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error">
                <?php
                if (is_array($error)) {
                    echo '<ul>';
                    foreach ($error as $e) {
                        echo '<li>' . htmlspecialchars($e) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo htmlspecialchars($error);
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required />

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <a href="resetPassword.php">Lupa Password?</a>
            </p>

            <input type="submit" value="Masuk" />
        </form>

        <a class="back-link" href="register.php">Belum punya akun? Daftar di sini</a>
    </div>
</body>

</html>
