<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
$conn = new mysqli("localhost", "root", "", "blog");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>
<form method="POST">
    <h2>Create New Post</h2>
    Title: <input name="title" required><br>
    Content: <textarea name="content" required></textarea><br>
    <button type="submit">Add Post</button>
</form>
