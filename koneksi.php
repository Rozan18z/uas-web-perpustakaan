<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "db_perpustakaan"; // Pastikan nama database di phpMyAdmin sama persis seperti ini

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil atau gagal
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}
?>