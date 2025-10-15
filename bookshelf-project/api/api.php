<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db.php';
include_once 'Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get all books or single book
        if(isset($_GET['id'])) {
            $book->id = $_GET['id'];
            if($book->readOne()) {
                echo json_encode(array(
                    "id" => $book->id,
                    "title" => $book->title,
                    "author" => $book->author,
                    "price" => $book->price,
                    "original_price" => $book->original_price,
                    "category" => $book->category,
                    "condition" => $book->condition,
                    "available" => $book->available,
                    "description" => $book->description,
                    "front_img" => $book->front_img,
                    "back_img" => $book->back_img,
                    "pdf_url" => $book->pdf_url
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Book not found."));
            }
        } 
        // Search books
        else if(isset($_GET['search'])) {
            $stmt = $book->search($_GET['search']);
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $books_arr = array();
                $books_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $book_item = array(
                        "id" => $id,
                        "title" => $title,
                        "author" => $author,
                        "price" => $price,
                        "original_price" => $original_price,
                        "category" => $category,
                        "condition" => $condition,
                        "available" => $available,
                        "description" => $description,
                        "front_img" => $front_img,
                        "back_img" => $back_img,
                        "pdf_url" => $pdf_url
                    );
                    array_push($books_arr["records"], $book_item);
                }
                
                echo json_encode($books_arr);
            } else {
                echo json_encode(array("message" => "No books found."));
            }
        }
        // Filter books
        else if(isset($_GET['category']) || isset($_GET['condition'])) {
            $category = isset($_GET['category']) ? $_GET['category'] : 'all';
            $condition = isset($_GET['condition']) ? $_GET['condition'] : 'all';
            
            $stmt = $book->filter($category, $condition);
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $books_arr = array();
                $books_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $book_item = array(
                        "id" => $id,
                        "title" => $title,
                        "author" => $author,
                        "price" => $price,
                        "original_price" => $original_price,
                        "category" => $category,
                        "condition" => $condition,
                        "available" => $available,
                        "description" => $description,
                        "front_img" => $front_img,
                        "back_img" => $back_img,
                        "pdf_url" => $pdf_url
                    );
                    array_push($books_arr["records"], $book_item);
                }
                
                echo json_encode($books_arr);
            } else {
                echo json_encode(array("message" => "No books found."));
            }
        }
        // Get all books
        else {
            $stmt = $book->read();
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $books_arr = array();
                $books_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $book_item = array(
                        "id" => $id,
                        "title" => $title,
                        "author" => $author,
                        "price" => $price,
                        "original_price" => $original_price,
                        "category" => $category,
                        "condition" => $condition,
                        "available" => $available,
                        "description" => $description,
                        "front_img" => $front_img,
                        "back_img" => $back_img,
                        "pdf_url" => $pdf_url
                    );
                    array_push($books_arr["records"], $book_item);
                }
                
                echo json_encode($books_arr);
            } else {
                echo json_encode(array("message" => "No books found."));
            }
        }
        break;
    
    case 'POST':
        // Create new book
        $data = json_decode(file_get_contents("php://input"));
        
        if(
            !empty($data->title) &&
            !empty($data->author) &&
            !empty($data->price) &&
            !empty($data->category) &&
            !empty($data->description) &&
            !empty($data->front_img)
        ) {
            $book->title = $data->title;
            $book->author = $data->author;
            $book->price = $data->price;
            $book->original_price = $data->original_price;
            $book->category = $data->category;
            $book->condition = $data->condition;
            $book->available = $data->available;
            $book->description = $data->description;
            $book->front_img = $data->front_img;
            $book->back_img = $data->back_img;
            $book->pdf_url = $data->pdf_url;
            
            if($book->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Book was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create book."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create book. Data is incomplete."));
        }
        break;
    
    case 'PUT':
        // Update book
        $data = json_decode(file_get_contents("php://input"));
        
        $book->id = $data->id;
        
        if(
            !empty($data->title) &&
            !empty($data->author) &&
            !empty($data->price) &&
            !empty($data->category) &&
            !empty($data->description) &&
            !empty($data->front_img)
        ) {
            $book->title = $data->title;
            $book->author = $data->author;
            $book->price = $data->price;
            $book->original_price = $data->original_price;
            $book->category = $data->category;
            $book->condition = $data->condition;
            $book->available = $data->available;
            $book->description = $data->description;
            $book->front_img = $data->front_img;
            $book->back_img = $data->back_img;
            $book->pdf_url = $data->pdf_url;
            
            if($book->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Book was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update book."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update book. Data is incomplete."));
        }
        break;
    
    case 'DELETE':
        // Delete book
        $data = json_decode(file_get_contents("php://input"));
        $book->id = $data->id;
        
        if($book->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Book was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete book."));
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>