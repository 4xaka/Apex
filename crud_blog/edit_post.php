<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "blog");
$id = $_GET["id"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $conn->query("UPDATE posts SET title='$title', content='$content' WHERE id=$id");
    $_SESSION["message"] = "Post updated!";
    header("Location: dashboard.php");
    exit();
}
$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Post</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Title</label>
            <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea class="form-control" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button class="btn btn-warning" type="submit">Update</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
