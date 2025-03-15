<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    die("Access Denied: Admins only");
}

// Fetch borrowing statistics
$totalBorrowed = $conn->query("SELECT COUNT(*) AS total FROM borrowings WHERE status = 'Borrowed'")->fetch_assoc()['total'];
$totalReturned = $conn->query("SELECT COUNT(*) AS total FROM borrowings WHERE status = 'Returned'")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role != 'Admin'")->fetch_assoc()['total'];
$totalResources = $conn->query("SELECT COUNT(*) AS total FROM resources")->fetch_assoc()['total'];

// Fetch borrowing trends
$trends = $conn->query("SELECT r.title, COUNT(b.id) AS borrow_count FROM borrowings b JOIN resources r ON b.resource_id = r.id GROUP BY r.title ORDER BY borrow_count DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Reports - Resource Center</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

header {
    background-color: #2c3e50;
    color: #fff;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header .logo h1 {
    margin: 0;
    font-size: 24px;
}

header .logo p {
    margin: 0;
    font-size: 14px;
    color: #bdc3c7;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

nav ul li {
    margin-left: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
}

nav ul li a.logout {
    color: #e74c3c;
}

main {
    padding: 20px;
}

.reports h2 {
    font-size: 22px;
    margin-bottom: 20px;
    color: #2c3e50;
}

.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #3498db;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

.report-section {
    margin-bottom: 30px;
}

.report-section h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #2c3e50;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.stat-card h4 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #2c3e50;
}

.stat-card p {
    font-size: 24px;
    font-weight: bold;
    color: #3498db;
}

.borrowing-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.borrowing-table th, .borrowing-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.borrowing-table th {
    background-color: #2c3e50;
    color: #fff;
}

.borrowing-table tr:hover {
    background-color: #f1f1f1;
}

footer {
    background-color: #2c3e50;
    color: #fff;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
}
    </style>
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
        <section class="reports">
            <h2>Borrowing Reports</h2>
            <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>

            <!-- Statistics Section -->
            <div class="report-section">
                <h3>Statistics</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h4>Total Borrowed</h4>
                        <p><?php echo $totalBorrowed; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Total Returned</h4>
                        <p><?php echo $totalReturned; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Total Users</h4>
                        <p><?php echo $totalUsers; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Total Resources</h4>
                        <p><?php echo $totalResources; ?></p>
                    </div>
                </div>
            </div>

            <!-- Top 5 Borrowed Resources Section -->
            <div class="report-section">
                <h3>Top 5 Borrowed Resources</h3>
                <table class="borrowing-table">
                    <thead>
                        <tr>
                            <th>Resource Title</th>
                            <th>Times Borrowed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $trends->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['borrow_count']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Resource Center Management System. All rights reserved.</p>
    </footer>
</body>
</html>