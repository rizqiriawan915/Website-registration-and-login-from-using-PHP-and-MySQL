<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_register";

// Mengaktifkan mode error MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
