<?php
// create_database.php - This will create the database for you
include_once 'config.php';

echo "<h2>Creating Database</h2>";

try {
    // Connect to MySQL without specifying a database
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    $conn->exec($sql);
    echo "✅ Database '" . DB_NAME . "' created successfully!<br>";
    
    // Now use the database
    $conn->exec("USE " . DB_NAME);
    
    // Create table
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        original_price DECIMAL(10, 2) NULL,
        category VARCHAR(100) NOT NULL,
        book_condition ENUM('New', 'Old') NOT NULL DEFAULT 'New',
        available BOOLEAN NOT NULL DEFAULT TRUE,
        description TEXT,
        front_img VARCHAR(500) NOT NULL,
        back_img VARCHAR(500) NULL,
        pdf_url VARCHAR(500) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "✅ Table 'books' created successfully!<br>";
    
    // Add sample data
    $sql = "INSERT IGNORE INTO books (title, author, price, original_price, category, book_condition, available, description, front_img, back_img, pdf_url) VALUES
    ('The Great Gatsby', 'F. Scott Fitzgerald', 12.99, 15.99, 'Fiction', 'Old', 1, 'A classic novel of the Jazz Age, depicting the decadence and excess of the 1920s.', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80', 'https://drive.google.com/file/d/1wJZ2Wb0V7L3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3/preview'),
    ('Atomic Habits', 'James Clear', 14.99, 19.99, 'Non-Fiction', 'New', 1, 'Tiny Changes, Remarkable Results: An Easy & Proven Way to Build Good Habits & Break Bad Ones.', 'https://images.unsplash.com/photo-1558901357-ca41e027e43a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80', 'https://drive.google.com/file/d/1wJZ2Wb0V7L3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3/preview')";
    
    $conn->exec($sql);
    echo "✅ Sample data inserted successfully!<br>";
    
    echo "<h3>✅ Database setup complete! You can now <a href='admin/admin_login.php'>login to the admin panel</a>.</h3>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
    echo "<br><br>Please check your MySQL configuration in config.php";
}
?>