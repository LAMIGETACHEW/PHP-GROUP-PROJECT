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




$email = "admin@resourcecenter.com";
$password = "admin"; 
$role = "Admin";

$insertQuery = "INSERT IGNORE INTO users (full_name, email, password, role)
                VALUES ('Admin User', '$email', '$password', '$role')";

$conn->query($insertQuery);

// Create resources table if it does not exist
$resourcesTableQuery = "CREATE TABLE IF NOT EXISTS resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($resourcesTableQuery) === FALSE) {
    die("Error creating resources table: " . $conn->error);
}



$borrowingOverviewTableQuery = "CREATE TABLE IF NOT EXISTS borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    resource_id INT NOT NULL,
    borrow_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    return_date TIMESTAMP NULL,
    status ENUM('Borrowed', 'Returned') DEFAULT 'Borrowed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
)";


if ($conn->query($borrowingOverviewTableQuery) === FALSE) {
    die("Error creating Borrowing Overview table: " . $conn->error);
}





?>
