<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if borrow ID is provided
if (!isset($_GET['borrow_id'])) {
    die("Error: Borrow ID is missing.");
}

$borrow_id = intval($_GET['borrow_id']);

// Check if the borrowing record exists and belongs to the user
$checkBorrow = $conn->prepare("SELECT id FROM borrowings WHERE id = ? AND user_id = ? AND status = 'Borrowed'");
$checkBorrow->bind_param("ii", $borrow_id, $user_id);
$checkBorrow->execute();
$checkBorrow->store_result();

if ($checkBorrow->num_rows == 0) {
    die("Error: Invalid borrowing record or already returned.");
}
$checkBorrow->close();

// Mark the resource as returned
$stmt = $conn->prepare("UPDATE borrowings SET status = 'Returned', return_date = NOW() WHERE id = ?");
$stmt->bind_param("i", $borrow_id);

if ($stmt->execute()) {
    echo "Success: Resource returned successfully!";
    header("Location: user_borrowing.php");
    exit();
} else {
    die("Error: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
