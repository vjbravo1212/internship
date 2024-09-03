<?php
$host = 'localhost';
$db = 'company';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
    $pdo->exec("USE $db");

    // Create the employees table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            gender ENUM('Male', 'Female', 'Other') NOT NULL,
            dob DATE NOT NULL,
            phone VARCHAR(15) NOT NULL,
            email VARCHAR(255) NOT NULL,
            address TEXT NOT NULL,
            pin VARCHAR(6) NOT NULL,
            state VARCHAR(100) NOT NULL,
            remark TEXT
        )
    ");

    // Create the dependents table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS dependents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT,
            name VARCHAR(255) NOT NULL,
            relation VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            gender ENUM('Male', 'Female', 'Other') NOT NULL,
            phone VARCHAR(15),
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        )
    ");

    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
