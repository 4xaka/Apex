<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
$conn = new mysqli("localhost", "root", "", "blog");
$id = $_GET['id'];
$conn->query("DELETE FROM posts WHERE id=$id");
header("Location: dashboard.php");
?>
