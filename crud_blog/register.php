<?php
$conn = new mysqli("localhost", "root", "", "blog");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    echo "Registration successful! <a href='login.php'>Login</a>";
}
?>
<form method="POST">
    <h2>Register</h2>
    Username: <input name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>
