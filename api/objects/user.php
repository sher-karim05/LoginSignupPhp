<?php
class User {

    // databae connection and table name
    private $conn;
    private $table_name = "users";

    //Object properties
    public $id;
    public $username;
    public $password;
    public $created_at;

    //Constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //Signup user
    function signup(){

        if($this->isAlreadyExist()){
            return false;
        }
        //query to insert record
        $query = "INSERT INTO " . $this->table_name . "
        SET
            username=:username,
            password=:password,
            created_at=:created_at";

            //prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->username=htmlspecialchars(strip_tags($this->username));
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));

            // bind values
            $stmt->bindParam(":username",
            $this->username);
            $stmt->bindParam(":password",
            $this->password);
            $stmt->bindParam(":created_at", 
            $this->created_at);

            //execute query
            if($stmt->execute()){
                $this->id = $this->conn->lastInsertId();
                return true;
            }

            return false;
        }

        // login user
        function login() {
            // select all query
            $query = "SELECT
            `id`, `username`, `password`,
            `created_at`
            FROM 
                " . $this->table_name . "WHERE
                username='".$this->username."' AND password='"$this->password."'";

            //prepare query statement

            $stmt = $this->conn->prepare($query);
            //execute query
            $stmt->execute();
            return $stmt;
        }
        function isAlreadyExist(){
            $query = "SELECT * FROM 
            " . $this->table_name . "WHERE 
            username='".$this->username."'";

            //prepare query statment
            $stmt = $this->conn->prepare($query);
            //execute query
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return true;
            }
            else {
                return false;
            }
        }
    }
?>