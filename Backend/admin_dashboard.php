<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch admin user role
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT role FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if ($user['role'] !== 'Admin') {
    die("Access Denied: Admins only");
}

// Fetch counts for dashboard
$totalResources = $conn->query("SELECT COUNT(*) as count FROM resources")->fetch_assoc()['count'];
$activeBorrowings = $conn->query("SELECT COUNT(*) as count FROM borrowings WHERE status = 'active'")->fetch_assoc()['count'];
$overdueResources = $conn->query("SELECT COUNT(*) as count FROM borrowings WHERE return_date < NOW() AND status = 'active'")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Resource Center Management</title>
    <link rel="stylesheet" href="AdminStyles.css">
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
        <section class="dashboard-overview">
            <h2>Dashboard Overview</h2>
            <div class="cards">
                <div class="card">
                    <h3>Total Resources</h3>
                    <p id="total-resources"><?php echo $totalResources; ?></p>
                </div>
                <div class="card">
                    <h3>Active Borrowings</h3>
                    <p id="active-borrowings"><?php echo $activeBorrowings; ?></p>
                </div>
                <div class="card">
                    <h3>Overdue Resources</h3>
                    <p id="overdue-resources"><?php echo $overdueResources; ?></p>
                </div>
                <div class="card">
                    <h3>Total Users</h3>
                    <p id="total-users"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </section>

        <section class="charts">
            <div class="chart-container">
                <canvas id="borrowingTrendsChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="resourceUsageChart"></canvas>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Resource Center Management System. All rights reserved.</p>
    </footer>

    <!-- <script src="scripts.js"></script> -->
</body>
</html>
