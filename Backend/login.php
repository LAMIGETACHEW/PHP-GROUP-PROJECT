<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set
    if (!isset($_POST["email"], $_POST["password"])) {
        die("Error: Missing required fields.");
    }

    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    // Ensure fields are not empty
    if (empty($email) || empty($password)) {
        die("Error: Email and password are required!");
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $stored_password, $role);
        $stmt->fetch();

        if ($password === $stored_password) { // Direct comparison (not secure, should hash passwords)
            $_SESSION["user_id"] = $id;
            $_SESSION["full_name"] = $full_name;
            $_SESSION["role"] = $role; // Store role in session

            if ($role === "Admin") {
                header("Location: admin_dashboard.php"); // Redirect admin
            } else {
                header("Location: user_dashboard.php"); // Redirect normal users
            }
            exit();
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