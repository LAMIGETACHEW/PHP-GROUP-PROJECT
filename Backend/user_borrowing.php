<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';

// Build query with search and filter options
$query = "SELECT b.id, r.title, b.borrow_date, b.return_date, b.status FROM borrowings b 
          JOIN resources r ON b.resource_id = r.id WHERE b.user_id = $user_id";

if (!empty($search)) {
    $query .= " AND r.title LIKE '%$search%'";
}
if (!empty($filter)) {
    $query .= " AND b.status = '$filter'";
}

$query .= " ORDER BY b.borrow_date DESC";
$borrowed = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowings - Resource Center</title>
    <link rel="stylesheet" href="user-borrowing-styles.css">
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
        <section class="user-borrowing">
            <h2>My Borrowings</h2>
            <a href="user_dashboard.php" class="back-link">Back to Home</a>

            <!-- Search & Filter Section -->
            <div class="search-filter">
                <h3>Search & Filter</h3>
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search by title" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="status">
                        <option value="">All</option>
                        <option value="Borrowed" <?php if ($filter == 'Borrowed') echo 'selected'; ?>>Borrowed</option>
                        <option value="Returned" <?php if ($filter == 'Returned') echo 'selected'; ?>>Returned</option>
                    </select>
                    <button type="submit">Apply</button>
                </form>
            </div>

            <!-- Borrowed Resources Table -->
            <div class="borrowed-resources">
                <h3>Borrowed Resources</h3>
                <table class="borrowing-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $borrowed->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['borrow_date']; ?></td>
                            <td><?php echo $row['return_date'] ? $row['return_date'] : "Not returned"; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <?php if ($row['status'] === 'Borrowed') { ?>
                                    <a href="return.php?borrow_id=<?php echo $row['id']; ?>" class="return-link" onclick="return confirm('Return this resource?')">Return</a>
                                <?php } else { ?>
                                    <span class="returned">Returned</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Resource Center Management System. All rights reserved.</p>
    </footer>
</body>
</html>