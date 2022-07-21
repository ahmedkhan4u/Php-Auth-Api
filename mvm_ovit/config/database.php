<?php 

class Database {
    private $hostname = 'localhost';
    private $dbname = "mvm_ovit";
    private $username = "root";
    private $password = "";

    private $conn;

    public function connect() {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);

        if($this->conn->connect_errno){
            print_r($this->conn->connect_error);
            exit;
        }else {
            //print_r($this->conn);
            return $this->conn;
        }
    }
}

//$db = new Database();
//$db->connect();

?>