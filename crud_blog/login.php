<?php
session_start();
$conn = new mysqli("localhost", "root", "", "blog");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = $user["username"];
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password.";
    }
}
?>
<form method="POST">
    <h2>Login</h2>
    Username: <input name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
