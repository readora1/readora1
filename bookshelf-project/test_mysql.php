<?php
// test_mysql.php - Place this in C:\xampp\htdocs\test_mysql.php
echo "<h2>Testing MySQL Connection</h2>";

try {
    // Try to connect to MySQL without specifying a database
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ MySQL connection successful!<br><br>";
    
    // Check what databases exist
    echo "<h3>Existing Databases:</h3>";
    $stmt = $conn->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($databases) > 0) {
        echo "<ul>";
        foreach ($databases as $db) {
            echo "<li>" . htmlspecialchars($db) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No databases found!";
    }
    
    // Check if our database exists
    if (in_array('bookshelf_db', $databases)) {
        echo "✅ bookshelf_db database exists!";
    } else {
        echo "❌ bookshelf_db database does NOT exist!";
        echo "<br><br>To create it, run this SQL in phpMyAdmin:";
        echo "<pre>CREATE DATABASE bookshelf_db;</pre>";
    }
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
    echo "<br><br>Check your MySQL configuration in XAMPP.";
}
?>