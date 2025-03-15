<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST["full_name"], $_POST["email"], $_POST["password"])) {
        die("Error: Missing required fields.");
    }

    $full_name = $conn->real_escape_string($_POST["full_name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    
    if (empty($full_name) || empty($email) || empty($password)) {
        die("Error: All fields are required!");
    }


     // Server-side validation
     if (empty($full_name) || empty($email) || empty($password)) {
        die("Error: All fields are required."); 
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    if (strlen($password) < 6) {
        die("Error: Password must be at least 6 characters long.");
    }

    
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$checkEmail) {
        die("Error: " . $conn->error);
    }
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        die("Error: Email already registered.");
    }
    $checkEmail->close();

    
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("sss", $full_name, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        die("Error: " . $stmt->error);
    }

    $stmt->close();
}
$conn->close();
?>
