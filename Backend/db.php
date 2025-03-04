<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "user_management";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}


$conn->select_db($dbname);


$tableQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($tableQuery) === FALSE) {
    die("Error creating table: " . $conn->error);
}




$email = "fufawakjira@gmail.com";
$password = "admin"; 
$role = "Admin";

$insertQuery = "INSERT IGNORE INTO users (full_name, email, password, role)
                VALUES ('Admin User', '$email', '$password', '$role')";

$conn->query($insertQuery);

?>
