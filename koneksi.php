<?php
$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "ecomm";

$db = new mysqli($hostname, $username, $password, $db_name);

if ($db->connect_error) {
    echo "koneksi gagal";
} else {

}
