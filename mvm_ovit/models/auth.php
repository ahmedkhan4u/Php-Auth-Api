<?php

class Auth
{

    public $fullname;
    public $email;
    public $password;

    private $conn;
    private $table_name;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->table_name = "auth";
    }

    public function create_data()
    {
        $query = "INSERT INTO " . $this->table_name . "
        SET fullname = ?, email = ?, password = ?";

        $obj = $this->conn->prepare($query);
        // Sanitize input means remove special and extra characters symbols some tags etc from data.
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        // binding params with prepare obj
        $obj->bind_param("sss", $this->fullname, $this->email, $this->password);

        if ($obj->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllUsers()
    {
        $sql_query = "SELECT * from " . $this->table_name;
        $result_obj = $this->conn->prepare($sql_query);
        $result_obj->execute();
        return $result_obj->get_result();
    }

    public function findUser()
    {
        $sql_query = "SELECT * from ".$this->table_name." WHERE email = ?";
        $result_obj = $this->conn->prepare($sql_query);
        $result_obj->bind_param("s", $this->email);
        $result_obj->execute();
        $data = $result_obj->get_result();
        return $data->fetch_assoc();
    }
}
