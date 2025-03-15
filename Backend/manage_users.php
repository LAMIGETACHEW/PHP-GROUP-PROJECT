<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    die("Access Denied: Admins only");
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: manage_users.php");
    exit();
}

// Handle role update
if (isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $conn->real_escape_string($_POST['role']);
    $conn->query("UPDATE users SET role = '$new_role' WHERE id = $user_id");
    header("Location: manage_users.php");
    exit();
}

// Pagination
$limit = 10; // Number of users per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of users (except admin)
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role != 'Admin'")->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit);

// Fetch users with pagination
$result = $conn->query("SELECT id, full_name, email, role FROM users WHERE role != 'Admin' LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Resource Center</title>
    <link rel="stylesheet" href="manage_users_styles.css">
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
        <section class="manage-users">
            <h2>Manage Users</h2>
            <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>

            <!-- User Table -->
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['full_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <form action="manage_users.php" method="POST" class="role-form">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="Student" <?php echo $user['role'] === 'Student' ? 'selected' : ''; ?>>Student</option>
                                    <option value="Librarian" <?php echo $user['role'] === 'Librarian' ? 'selected' : ''; ?>>Librarian</option>
                                </select>
                                <input type="submit" name="update_role" value="Update Role" style="display:none;">
                            </form>
                        </td>
                        <td>
                            <a href="manage_users.php?delete=<?php echo $user['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1) { ?>
                    <a href="manage_users.php?page=<?php echo $page - 1; ?>">Previous</a>
                <?php } ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <a href="manage_users.php?page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                <?php } ?>
                
                <?php if ($page < $total_pages) { ?>
                    <a href="manage_users.php?page=<?php echo $page + 1; ?>">Next</a>
                <?php } ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Resource Center Management System. All rights reserved.</p>
    </footer>
</body>
</html>