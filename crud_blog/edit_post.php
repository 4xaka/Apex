<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
$conn = new mysqli("localhost", "root", "", "blog");
$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();
    header("Location: dashboard.php");
}
$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
?>
<form method="POST">
    <h2>Edit Post</h2>
    Title: <input name="title" value="<?= $post['title'] ?>" required><br>
    Content: <textarea name="content" required><?= $post['content'] ?></textarea><br>
    <button type="submit">Update</button>
</form>
