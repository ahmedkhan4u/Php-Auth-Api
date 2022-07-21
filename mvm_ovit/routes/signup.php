<?php
header("Access-Control-Allow-Origion: *"); // It allow all origions like localhost or any domain and sub domain
header("Content-type: application/json; charset=UTF-8"); // data which we are getting inside request is json
header("Access-Control-Allow-Methods: POST"); // Method type like get post put etc.


include_once('../config/database.php');
include_once('../models/auth.php');

$db = new Database();
$connection = $db->connect();
$auth = new Auth($connection);


if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $data = json_decode(file_get_contents("php://input"));

    // print_r($data);
    // die;

    if (!empty($data->fullname) && !empty($data->email) && !empty($data->password)) {
        $hash = password_hash($data->password, PASSWORD_DEFAULT);
        $auth->fullname = $data->fullname;
        $auth->email = $data->email;
        $auth->password = $hash;

        if ($auth->create_data()) {
            http_response_code(200); // Status success
            echo json_encode(array(
                "status" => "success",
                "message" => "User registered successfully."
            ));
            //echo "User registered successfully.";
        } else {
            http_response_code(500); // Internal server error
            echo json_encode(array(
                "status" => "error",
                "message" => "Failed to register a user."
            ));
        }
    }else {
        http_response_code(500); // Internal server error
            echo json_encode(array(
                "status" => "error",
                "message" => "All values are required."
            ));
    }
} else {
    http_response_code(503); // Internal server error
            echo json_encode(array(
                "status" => "error",
                "message" => "Access Denied."
            ));
}
