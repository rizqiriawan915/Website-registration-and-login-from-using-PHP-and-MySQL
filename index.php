<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION["user"]; // Ambil nama pengguna dari sesi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>User Dashboard</title>
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Selamat datang, <?= htmlspecialchars($username); ?>!</h1>
        <a href="./logout.php" class="btn btn-warning mt-3">Logout</a>
    </div>
</body>
</html>
