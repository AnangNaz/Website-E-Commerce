<?php
session_start();
include("koneksi.php");

$error = [];
$pesan = '';
$nama = '';
$email = '';
$no_telp = '';

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['no_telp'])) {
    $nama = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $no_telp = $_POST['no_telp']; 

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

    if (empty($error)) {
        $query_check_email = "SELECT * FROM user WHERE email = ?";
        $stmt_check_email = $db->prepare($query_check_email);
        if ($stmt_check_email === false) {
            $error[] = 'Gagal mempersiapkan statement: ' . $db->error;
        } else {
            $stmt_check_email->bind_param("s", $email);
            $stmt_check_email->execute();
            $result_check_email = $stmt_check_email->get_result();

            if ($result_check_email->num_rows > 0) {
                $error[] = 'Email sudah terdaftar.';
            } else {
                $query_check_no_telp = "SELECT * FROM user WHERE no_telp = ?";
                $stmt_check_no_telp = $db->prepare($query_check_no_telp);
                if ($stmt_check_no_telp === false) {
                    $error[] = 'Gagal mempersiapkan statement: ' . $db->error;
                } else {
                    $stmt_check_no_telp->bind_param("s", $no_telp);
                    $stmt_check_no_telp->execute();
                    $result_check_no_telp = $stmt_check_no_telp->get_result();

                    if ($result_check_no_telp->num_rows > 0) {
                        $error[] = 'Nomor telepon sudah terdaftar.';
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $query = "INSERT INTO user (nama, email, password, no_telp) VALUES (?, ?, ?, ?)";
                        $stmt = $db->prepare($query);
                        if ($stmt === false) {
                            $error[] = 'Gagal mempersiapkan statement: ' . $db->error;
                        } else {
                            $stmt->bind_param("ssss", $nama, $email, $hashed_password, $no_telp);

                            if ($stmt->execute()) {
                                $pesan = "Registrasi berhasil! Silakan login.";
                            } else {
                                $error[] = 'Terjadi kesalahan saat menyimpan data: ' . $stmt->error;
                            }
                            $stmt->close();
                        }
                    }
                    $stmt_check_no_telp->close();
                }
            }
            $stmt_check_email->close();
        }
    }
    $_SESSION['error'] = $error;
    $_SESSION['pesan'] = $pesan;
    $_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;
    $_SESSION['no_telp'] = $no_telp; 
}

header("Location: login.php");
exit();
?>
