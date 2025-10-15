<?php
// includes/Book.php
class Book {
    private $conn;
    private $table_name = "books";

    public $id;
    public $title;
    public $author;
    public $price;
    public $original_price;
    public $category;
    public $book_condition;  // Changed from condition
    public $available;
    public $description;
    public $front_img;
    public $back_img;
    public $pdf_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all books
    public function read() {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single book
    public function readOne() {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->title = $row['title'];
            $this->author = $row['author'];
            $this->price = $row['price'];
            $this->original_price = $row['original_price'];
            $this->category = $row['category'];
            $this->book_condition = $row['book_condition'];  // Changed from condition
            $this->available = $row['available'];
            $this->description = $row['description'];
            $this->front_img = $row['front_img'];
            $this->back_img = $row['back_img'];
            $this->pdf_url = $row['pdf_url'];
            return true;
        }
        return false;
    }

    // Create book
    public function create() {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "INSERT INTO " . $this->table_name . "
                SET title=:title, author=:author, price=:price, original_price=:original_price, 
                category=:category, book_condition=:book_condition, available=:available, description=:description, 
                front_img=:front_img, back_img=:back_img, pdf_url=:pdf_url";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->original_price = htmlspecialchars(strip_tags($this->original_price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->book_condition = htmlspecialchars(strip_tags($this->book_condition));  // Changed from condition
        $this->available = htmlspecialchars(strip_tags($this->available));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->front_img = htmlspecialchars(strip_tags($this->front_img));
        $this->back_img = htmlspecialchars(strip_tags($this->back_img));
        $this->pdf_url = htmlspecialchars(strip_tags($this->pdf_url));
        
        // Bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":original_price", $this->original_price);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":book_condition", $this->book_condition);  // Changed from :condition
        $stmt->bindParam(":available", $this->available);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":front_img", $this->front_img);
        $stmt->bindParam(":back_img", $this->back_img);
        $stmt->bindParam(":pdf_url", $this->pdf_url);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update book
    public function update() {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "UPDATE " . $this->table_name . "
                SET title=:title, author=:author, price=:price, original_price=:original_price, 
                category=:category, book_condition=:book_condition, available=:available, description=:description, 
                front_img=:front_img, back_img=:back_img, pdf_url=:pdf_url
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->original_price = htmlspecialchars(strip_tags($this->original_price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->book_condition = htmlspecialchars(strip_tags($this->book_condition));
        $this->available = htmlspecialchars(strip_tags($this->available));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->front_img = htmlspecialchars(strip_tags($this->front_img));
        $this->back_img = htmlspecialchars(strip_tags($this->back_img));
        $this->pdf_url = htmlspecialchars(strip_tags($this->pdf_url));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":original_price", $this->original_price);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":book_condition", $this->book_condition);
        $stmt->bindParam(":available", $this->available);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":front_img", $this->front_img);
        $stmt->bindParam(":back_img", $this->back_img);
        $stmt->bindParam(":pdf_url", $this->pdf_url);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete book
    public function delete() {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search books
    public function search($keywords) {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE title LIKE ? OR author LIKE ? OR category LIKE ? OR description LIKE ?
                ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        
        $stmt->execute();
        return $stmt;
    }

    // Filter by category and condition - FIXED THIS METHOD
    public function filter($category, $condition) {
        if ($this->conn === null) {
            throw new Exception("Database connection is not established");
        }
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        
        if($category != 'all') {
            $query .= " AND category = :category";
        }
        
        if($condition != 'all') {
            $query .= " AND book_condition = :condition"; // FIXED: Changed from condition to book_condition
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if($category != 'all') {
            $category = htmlspecialchars(strip_tags($category));
            $stmt->bindParam(":category", $category);
        }
        
        if($condition != 'all') {
            $condition = htmlspecialchars(strip_tags($condition));
            $stmt->bindParam(":condition", $condition);
        }
        
        $stmt->execute();
        return $stmt;
    }
}
?>