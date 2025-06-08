<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "blog");
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input class="form-control" type="text" name="username" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input class="form-control" type="password" name="password" required>
        </div>
        <button class="btn btn-success" type="submit">Register</button>
        <a href="login.php" class="btn btn-link">Back to Login</a>
    </form>
</div>
</body>
</html>
