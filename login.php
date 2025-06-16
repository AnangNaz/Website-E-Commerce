<?php
session_start();
include("koneksi.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi.";
    } else {
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nama'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Password salah.";
                }
            } else {
                $error = "Email tidak ditemukan.";
            }
            $stmt->close();
        } else {
            $error = "Terjadi kesalahan pada server.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 16px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">

            <label for="email">Email</label>
            <input type="text" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit">
        </form>
    </div>
</body>

</html>