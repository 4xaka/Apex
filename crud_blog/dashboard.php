<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "blog");

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_safe = $conn->real_escape_string($search);

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get posts with search and pagination
$sql = "SELECT * FROM posts 
        WHERE title LIKE '%$search_safe%' OR content LIKE '%$search_safe%' 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM posts 
              WHERE title LIKE '%$search_safe%' OR content LIKE '%$search_safe%'";
$total_result = $conn->query($count_sql);
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?></h2>
    <a href="create_post.php" class="btn btn-success mb-3">Add New Post</a>
    <a href="logout.php" class="btn btn-secondary mb-3">Logout</a>

    <!-- Search Form -->
    <form class="d-flex mb-4" method="GET" action="dashboard.php">
        <input class="form-control me-2" type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

    <!-- Posts Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['content']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="dashboard.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>
