<?php
// api/users_api.php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../includes/db.php';
include_once '../includes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        // Register new user
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->name) && !empty($data->email) && !empty($data->password)) {
            $user->name = $data->name;
            $user->email = $data->email;
            $user->password = $data->password;
            $user->type = 'user'; // Default type
            
            if($user->create()) {
                http_response_code(201);
                echo json_encode(array(
                    "message" => "User was created.",
                    "user_id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email
                ));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user. Email may already exist."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}


?>