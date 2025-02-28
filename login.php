<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
require_once "database.php"; // Pastikan file ini ada

if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $errors = array();

    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        // Menggunakan prepared statement untuk mencegah SQL Injection
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["full_name"];
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "Email does not exist";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>Login Form</title>
</head>
<body>
    <div class="container">
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter your email" name="email" class="form-control" 
                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter your password" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
        <div><p>Belum mempunyai akun? <a href="registration.php">Daftar disini</a></p></div>
    </div>
</body>
</html>
