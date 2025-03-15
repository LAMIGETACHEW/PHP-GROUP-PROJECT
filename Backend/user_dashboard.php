<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$user = $conn->query("SELECT full_name FROM users WHERE id = $user_id")->fetch_assoc();

// Fetch available resources
$resources = $conn->query("SELECT * FROM resources");

// Fetch user borrowing history
$borrowed = $conn->query("SELECT r.title, b.borrow_date, b.return_date, b.status 
                          FROM borrowings b 
                          JOIN resources r ON b.resource_id = r.id 
                          WHERE b.user_id = $user_id 
                          ORDER BY b.borrow_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Resource Center</title>
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

.user-dashboard h2 {
    font-size: 22px;
    margin-bottom: 20px;
    color: #2c3e50;
}

.resource-section, .borrowing-section {
    margin-bottom: 30px;
}

.resource-section h3, .borrowing-section h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #2c3e50;
}

.resource-table, .borrowing-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.resource-table th, .resource-table td,
.borrowing-table th, .borrowing-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.resource-table th, .borrowing-table th {
    background-color: #2c3e50;
    color: #fff;
}

.resource-table tr:hover, .borrowing-table tr:hover {
    background-color: #f1f1f1;
}

.borrow-link {
    color: #3498db;
    text-decoration: none;
}

.borrow-link:hover {
    text-decoration: underline;
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
            <p>User Dashboard</p>
        </div>
        <nav>
            <ul>
                <li><a href="user_dashboard.php">Home</a></li>
                <li><a href="user_borrowing.php">My Borrowings</a></li>
                <li><a href="logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="user-dashboard">
            <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></h2>

            <!-- Available Resources Section -->
            <div class="resource-section">
                <h3>Available Resources</h3>
                <table class="resource-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($resource = $resources->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $resource['title']; ?></td>
                            <td><?php echo $resource['author']; ?></td>
                            <td><?php echo $resource['category']; ?></td>
                            <td>
                                <a href="borrow.php?resource_id=<?php echo $resource['id']; ?>" class="borrow-link">Borrow</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Borrowing History Section -->
            <div class="borrowing-section">
                <h3>My Borrowing History</h3>
                <table class="borrowing-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $borrowed->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['borrow_date']; ?></td>
                            <td><?php echo $row['return_date'] ? $row['return_date'] : "Not returned"; ?></td>
                            <td><?php echo $row['status']; ?></td>
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