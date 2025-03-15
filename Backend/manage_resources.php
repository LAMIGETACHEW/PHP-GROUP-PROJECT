<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    die("Access Denied: Admins only");
}

// Handle adding a new resource
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_resource'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $category = $conn->real_escape_string($_POST['category']);
    $file_path = "";

    if (!empty($_FILES['file']['name'])) {
        $upload_dir = "uploads/";
        $file_path = $upload_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    }

    $conn->query("INSERT INTO resources (title, author, category, file_path) VALUES ('$title', '$author', '$category', '$file_path')");
    header("Location: manage_resources.php");
    exit();
}

// Handle deleting a resource
if (isset($_GET['delete'])) {
    $resource_id = intval($_GET['delete']);
    $conn->query("DELETE FROM resources WHERE id = $resource_id");
    header("Location: manage_resources.php");
    exit();
}

// Fetch all resources
$result = $conn->query("SELECT * FROM resources");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resources - Resource Center</title>
    <link rel="stylesheet" href="manage-resource-styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Resource Center</h1>
            <p>Admin Dashboard</p>
        </div>
        <nav>
            <ul>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_resources.php">Manage Resources</a></li>
                <li><a href="borrowing_overview.php">Borrowing Overview</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="manage-resources">
            <h2>Manage Resources</h2>
            <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>

            <!-- Add Resource Form -->
            <form method="POST" enctype="multipart/form-data" class="resource-form">
                <input type="text" name="title" placeholder="Resource Title" required>
                <input type="text" name="author" placeholder="Author" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="file" name="file">
                <button type="submit" name="add_resource">Add Resource</button>
            </form>

            <!-- Resource Table -->
            <table class="resource-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($resource = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $resource['id']; ?></td>
                        <td><?php echo $resource['title']; ?></td>
                        <td><?php echo $resource['author']; ?></td>
                        <td><?php echo $resource['category']; ?></td>
                        <td><?php echo !empty($resource['file_path']) ? "<a href='" . $resource['file_path'] . "' download>Download</a>" : "No File"; ?></td>
                        <td>
                            <a href="manage_resources.php?delete=<?php echo $resource['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this resource?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Resource Center Management System. All rights reserved.</p>
    </footer>
</body>
</html>