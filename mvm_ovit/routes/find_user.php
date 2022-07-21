<?php
ini_set('display_errors', 1);
header("Access-Control-Allow-Origion: *"); // It allow all origions like localhost or any domain and sub domain
header("Content-type: application/json; charset=UTF-8"); // data which we are getting inside request is json
header("Access-Control-Allow-Methods: POST"); // Method type like get post put etc.


include_once('../config/database.php');
include_once('../models/auth.php');

$db = new Database();
$connection = $db->connect();
$auth = new Auth($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $param = json_decode(file_get_contents("php://input"));

    $operation = $param->operation;


    if (!empty($param->email)) {
        $auth->email = $param->email;
        $data = $auth->findUser();
        if ($data != null) {
            
            if ($operation == "auth") {
                $password = $param->password;
                $verify = password_verify($password, $data["password"]);
                if ($verify) {
                    $data["password"] = "verified";
                    http_response_code(200);
                echo json_encode(array(
                    "status" => "success",
                    "data" => $data
                ));
                } else {
                    http_response_code(200);
                    echo json_encode(array(
                        "status" => "success",
                        "data" => $data
                    ));
                }
            } else {
                http_response_code(200);
                echo json_encode(array(
                    "status" => "success",
                    "data" => $data
                ));
            }
        } else {
            http_response_code(404);
            echo json_encode(array(
                "status" => "error",
                "message" => "User not found."
            ));
        }
    } else {
        http_response_code(500);
        echo json_encode(array(
            "status" => "error",
            "message" => "Username required."
        ));
    }
} else {
    http_response_code(503);
    echo json_encode(array(
        "status" => "error",
        "message" => "Access Denied."
    ));
}
