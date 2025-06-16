<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_safe = "%{$search}%";

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$user = $_SESSION["user"];
$role = $_SESSION["role"];

// Get current user ID
$user_id = null;
$user_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_stmt->bind_param("s", $user);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows > 0) {
    $user_id = $user_result->fetch_assoc()['id'];
}

// Prepare main query
if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?");
    $stmt->bind_param("ssii", $search_safe, $search_safe, $limit, $offset);
    $count_stmt->bind_param("ss", $search_safe, $search_safe);
} else {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? AND (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM posts WHERE user_id = ? AND (title LIKE ? OR content LIKE ?)");
    $stmt->bind_param("issii", $user_id, $search_safe, $search_safe, $limit, $offset);
    $count_stmt->bind_param("iss", $user_id, $search_safe, $search_safe);
}

$stmt->execute();
$result = $stmt->get_result();

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_posts = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);
?>
<!-- HTML below remains unchanged (same as your current version) -->

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Welcome, <?= htmlspecialchars($user) ?> (<?= htmlspecialchars($role) ?>)</h2>
    <a href="create_post.php" class="btn btn-success mb-3">Add New Post</a>
    <a href="logout.php" class="btn btn-secondary mb-3">Logout</a>

    <!-- Search Form -->
    <form class="d-flex mb-4" method="GET" action="dashboard.php">
        <input class="form-control me-2" type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

    <!-- Posts Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Created At</th>
            <?php if ($role === 'admin' || $role === 'editor'): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['content']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <?php if ($role === 'admin' || $role === 'editor'): ?>
                    <td>
                        <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_post.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="dashboard.php?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>
