<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST["email"], $_POST["password"])) {
        die("Error: Missing required fields.");
    }

    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    
    if (empty($email) || empty($password)) {
        die("Error: Email and password are required!");
    }

    
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $stored_password);
        $stmt->fetch();

        if ($password === $stored_password) { 
            $_SESSION["user_id"] = $id;
            $_SESSION["full_name"] = $full_name;
            echo "Login successful!";
        } else {
            die("Error: Invalid password.");
        }
    } else {
        die("Error: No account found with this email.");
    }

    $stmt->close();
}
$conn->close();
?>
