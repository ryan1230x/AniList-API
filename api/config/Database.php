<?php

class Database {

    public function connect() {
        $this->conn = null;
        $host = "localhost";
        $username = "";
        $password = "";
        $db_name = "";
        $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";

        try {
            $this->conn = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
           echo "connection failed:" .$e->getMessage();
        }
        return $this->conn;
    }
    
}
?>