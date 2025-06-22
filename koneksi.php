<?php
$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "ecomm";

$conn = new mysqli($hostname, $username, $password, $db_name);

if ($conn->connect_error) {
    echo "koneksi gagal";
} else {

}
