<?php
// Database connection details
define('DB_HOST', 'localhost'); // Change this if your database is hosted elsewhere
define('DB_NAME', 'mordecai_dbconnect'); // Replace with your actual database name
define('DB_USER', 'mordecai_dbconnect'); // Replace with your MySQL username
define('DB_PASS', 'Ma476351!'); // Replace with your MySQL password

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Connection successful message
    echo "✅ Successfully connected to the database!";
} catch (PDOException $e) {
    // Connection failed message
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
