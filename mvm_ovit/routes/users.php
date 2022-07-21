<?php
header("Access-Control-Allow-Origion: *"); // It allow all origions like localhost or any domain and sub domain
//header("Content-type: application/json; charset=UTF-8"); // data which we are getting inside request is json
header("Access-Control-Allow-Methods: GET"); // Method type like get post put etc.


include_once('../config/database.php');
include_once('../models/auth.php');

$db = new Database();
$connection = $db->connect();
$auth = new Auth($connection);

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $data = $auth->getAllUsers();
    if ($data->num_rows > 0) {
        $result["records"] = array();
        while($row = $data->fetch_assoc()) {
            array_push($result["records"], array(
                "id" => $row["id"],
                "fullname" => $row["fullname"],
                "email" => $row["email"],
                "password" => $row["password"],
                "timestamp" =>date(strtotime($row["timestamp"])),
            ));
        }

        http_response_code(200);
        echo json_encode(array(
            "status" => "success",
            "data" => $result["records"]
        ));
    }else {
        http_response_code(404);
        echo json_encode(array(
            "status" => "success",
            "message" => "No data found."
        ));
    }

} else {
    http_response_code(503);
    echo json_encode(array(
        "status" => "error",
        "message" => "Access Denied."
    ));
}
