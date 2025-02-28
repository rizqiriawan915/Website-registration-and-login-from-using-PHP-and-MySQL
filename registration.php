<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>Registration Form</title>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullName"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password !== $passwordRepeat) {
                array_push($errors, "Passwords do not match");
            }

            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $rowCount = mysqli_num_rows($result);

            if ($rowCount > 0) {
                array_push($errors, "Email already exists");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                mysqli_stmt_execute($stmt);

                // Redirect ke halaman login setelah registrasi sukses
                header("Location: login.php");
                exit();
            }
        }
        ?>
        
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullName" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div><p>Sudah punya akun? <a href="login.php">Login di sini</a></p></div>
    </div>
</body>
</html>
