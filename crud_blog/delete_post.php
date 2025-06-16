<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) die("Invalid post ID.");

$user = $_SESSION["user"];
$role = $_SESSION["role"];

// Get current user ID
$stmt_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt_user->bind_param("s", $user);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_id = $user_result->fetch_assoc()['id'];

// Check ownership
if ($role === "admin") {
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
}
$stmt->execute();

$_SESSION["message"] = "Post deleted!";
header("Location: dashboard.php");
exit();
?>
