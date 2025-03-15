<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if resource ID is provided
if (!isset($_GET['resource_id'])) {
    die("Error: Resource ID is missing.");
}

$resource_id = intval($_GET['resource_id']);

// Check if the resource is already borrowed
$checkBorrow = $conn->prepare("SELECT id FROM borrowings WHERE resource_id = ? AND user_id = ? AND status = 'Borrowed'");
$checkBorrow->bind_param("ii", $resource_id, $user_id);
$checkBorrow->execute();
$checkBorrow->store_result();

if ($checkBorrow->num_rows > 0) {
    die("Error: You have already borrowed this resource.");
}
$checkBorrow->close();

// Borrow the resource
$stmt = $conn->prepare("INSERT INTO borrowings (user_id, resource_id, borrow_date, status) VALUES (?, ?, NOW(), 'Borrowed')");
$stmt->bind_param("ii", $user_id, $resource_id);

if ($stmt->execute()) {
    echo "Success: Resource borrowed successfully!";
    header("Location: user_borrowing.php");
    exit();
} else {
    die("Error: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
