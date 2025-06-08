<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "blog");
    $title = $_POST["title"];
    $content = $_POST["content"];
    $conn->query("INSERT INTO posts (title, content, created_at) VALUES ('$title', '$content', NOW())");
    $_SESSION["message"] = "Post created successfully!";
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create New Post</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Title</label>
            <input class="form-control" type="text" name="title" required>
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea class="form-control" name="content" required></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Submit</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
