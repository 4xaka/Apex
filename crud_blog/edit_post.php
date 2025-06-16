<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

$user_id = $_SESSION["user_id"] ?? null;  // Safely access user_id
$role = $_SESSION["role"];
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0 || $user_id === null) {
    die("Invalid access.");
}

// Fetch the post depending on role
if ($role === 'admin') {
    // Admin can access any post
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    // Editor can access only their own posts
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found or access denied.");
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (empty($title) || empty($content)) {
        $error = "Both fields are required.";
    } else {
        if ($role === "admin") {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
        } else {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssii", $title, $content, $id, $user_id);
        }
        $stmt->execute();
        $_SESSION["message"] = "Post updated!";
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            const title = document.forms["editForm"]["title"].value.trim();
            const content = document.forms["editForm"]["content"].value.trim();
            if (title === "" || content === "") {
                alert("Both Title and Content are required!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Post</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form name="editForm" method="POST" onsubmit="return validateForm();">
        <div class="mb-3">
            <label>Title</label>
            <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea class="form-control" name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button class="btn btn-warning" type="submit">Update</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
