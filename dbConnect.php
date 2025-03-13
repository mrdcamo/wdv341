<?php
$server = "localhost";  // Change if using an online server
$username = "root";     // Default for XAMPP/MAMP (change if needed)
$password = "";         // Empty by default for XAMPP/MAMP
$database = "wdv341";   // The database we created

try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
