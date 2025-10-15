<?php
// admin/admin.php
session_start();
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Fix the include paths - use relative paths from the admin directory
include_once '../includes/db.php';
include_once '../includes/Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['add_book'])) {
        $book->title = $_POST['title'];
        $book->author = $_POST['author'];
        $book->price = $_POST['price'];
        $book->original_price = $_POST['original_price'];
        $book->category = $_POST['category'];
        $book->condition = $_POST['condition'];
        $book->available = isset($_POST['available']) ? 1 : 0;
        $book->description = $_POST['description'];
        $book->front_img = $_POST['front_img'];
        $book->back_img = $_POST['back_img'];
        $book->pdf_url = $_POST['pdf_url'];
        
        if($book->create()) {
            $success = "Book added successfully!";
        } else {
            $error = "Unable to add book.";
        }
    }
    
    if(isset($_POST['update_book'])) {
        $book->id = $_POST['book_id'];
        $book->title = $_POST['title'];
        $book->author = $_POST['author'];
        $book->price = $_POST['price'];
        $book->original_price = $_POST['original_price'];
        $book->category = $_POST['category'];
        $book->condition = $_POST['condition'];
        $book->available = isset($_POST['available']) ? 1 : 0;
        $book->description = $_POST['description'];
        $book->front_img = $_POST['front_img'];
        $book->back_img = $_POST['back_img'];
        $book->pdf_url = $_POST['pdf_url'];
        
        if($book->update()) {
            $success = "Book updated successfully!";
        } else {
            $error = "Unable to update book.";
        }
    }
    
    if(isset($_POST['delete_book'])) {
        $book->id = $_POST['book_id'];
        if($book->delete()) {
            $success = "Book deleted successfully!";
        } else {
            $error = "Unable to delete book.";
        }
    }
}

// Get all books for display
$stmt = $book->read();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShelf Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .success { color: green; }
        .error { color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; }
        input, textarea, select { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 15px; background: #4a6fa5; color: white; border: none; cursor: pointer; }
        button:hover { background: #166088; }
    </style>
</head>
<body>
    <div class="container">
        <h1>BookShelf Admin Panel</h1>
        <a href="admin_logout.php" style="float: right;">Logout</a>
        
        <?php if(isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <h2>Add New Book</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" step="0.01" name="original_price" placeholder="Original Price">
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Science">Science</option>
                <option value="Technology">Technology</option>
                <option value="Biography">Biography</option>
            </select>
            <select name="condition" required>
                <option value="New">New</option>
                <option value="Old">Old</option>
            </select>
            <label>
                <input type="checkbox" name="available" checked> Available
            </label>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="text" name="front_img" placeholder="Front Image URL" required>
            <input type="text" name="back_img" placeholder="Back Image URL">
            <input type="text" name="pdf_url" placeholder="PDF URL">
            <button type="submit" name="add_book">Add Book</button>
        </form>
        
        <h2>Manage Books</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Condition</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($books as $book): ?>
                <tr>
                    <td><?php echo $book['id']; ?></td>
                    <td><?php echo $book['title']; ?></td>
                    <td><?php echo $book['author']; ?></td>
                    <td>$<?php echo $book['price']; ?></td>
                    <td><?php echo $book['category']; ?></td>
                    <td><?php echo $book['condition']; ?></td>
                    <td><?php echo $book['available'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" name="delete_book">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>