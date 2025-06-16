<?php
session_start();
if (!isset($_SESSION["user"]) || ($_SESSION["role"] !== "admin" && $_SESSION["role"] !== "editor")) {
    die("Access denied.");
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (empty($title) || empty($content)) {
        $error = "Both title and content are required.";
    } else {
        // Get current user ID
        $username = $_SESSION["user"];
        $stmt_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_user->bind_param("s", $username);
        $stmt_user->execute();
        $user_result = $stmt_user->get_result();
        $user_id = $user_result->fetch_assoc()['id'];

        // Insert post with user_id
        $stmt = $conn->prepare("INSERT INTO posts (title, content, created_at, user_id) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("ssi", $title, $content, $user_id);
        $stmt->execute();

        $_SESSION["message"] = "Post created successfully!";
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!-- HTML remains same as your original -->

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            const title = document.forms["createForm"]["title"].value.trim();
            const content = document.forms["createForm"]["content"].value.trim();
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
    <h2>Create New Post</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form name="createForm" method="POST" onsubmit="return validateForm();">
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
