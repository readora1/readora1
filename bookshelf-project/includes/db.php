<?php
// includes/db.php
include_once __DIR__ . '/../config.php';

class Database {
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // More descriptive error message
            $error_message = "Connection error: " . $exception->getMessage();
            
            // Check if it's a "database doesn't exist" error
            if ($exception->getCode() == 1049) {
                $error_message .= "<br><br>The database '" . DB_NAME . "' doesn't exist. ";
                $error_message .= "Please visit <a href='../create_database.php'>this link</a> to create it automatically.";
            } else if ($exception->getCode() == 1045) {
                $error_message .= "<br><br>Access denied. Please check your database username and password in config.php";
            } else if ($exception->getCode() == 2002) {
                $error_message .= "<br><br>Cannot connect to MySQL server. Please make sure MySQL is running in XAMPP.";
            }
            
            // Display error message
            die($error_message);
        }
        return $this->conn;
    }
}
?>