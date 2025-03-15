<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    die("Access Denied: Admins only");
}

// Handle returning a book
if (isset($_GET['return'])) {
    $borrow_id = intval($_GET['return']);
    $conn->query("UPDATE borrowings SET status = 'Returned', return_date = NOW() WHERE id = $borrow_id");
    header("Location: borrowing_overview.php");
    exit();
}

// Fetch all borrowing records
$result = $conn->query("SELECT b.id, u.full_name, r.title, b.borrow_date, b.return_date, b.status 
                        FROM borrowings b
                        JOIN users u ON b.user_id = u.id
                        JOIN resources r ON b.resource_id = r.id
                        ORDER BY b.borrow_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Overview - Resource Center</title>
    <link rel="stylesheet" href="borrowing-overview-styles.css">
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
        <section class="borrowing-overview">
            <h2>Borrowing Overview</h2>
            <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>

            <!-- Borrowing Table -->
            <table class="borrowing-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Resource</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['borrow_date']; ?></td>
                        <td><?php echo $row['return_date'] ? $row['return_date'] : "Not returned"; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] === 'Borrowed') { ?>
                                <a href="borrowing_overview.php?return=<?php echo $row['id']; ?>" class="return-link" onclick="return confirm('Mark as returned?')">Mark as Returned</a>
                            <?php } else { ?>
                                <span class="returned">Returned</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Resource Center Management System. All rights reserved.</p>
    </footer>
</body>
</html>