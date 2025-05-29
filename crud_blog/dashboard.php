<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
$conn = new mysqli("localhost", "root", "", "blog");
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
echo "<h2>Welcome, " . $_SESSION["user"] . "</h2>";
echo "<a href='create_post.php'>Add New Post</a> | <a href='logout.php'>Logout</a><hr>";
while ($row = $result->fetch_assoc()) {
    echo "<h3>{$row['title']}</h3>";
    echo "<p>{$row['content']}</p>";
    echo "<small>Posted on: {$row['created_at']}</small><br>";
    echo "<a href='edit_post.php?id={$row['id']}'>Edit</a> | ";
    echo "<a href='delete_post.php?id={$row['id']}'>Delete</a><hr>";
}
?>
