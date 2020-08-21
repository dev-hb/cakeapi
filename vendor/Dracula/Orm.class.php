<?php

class ORM {
    private $connection = null;

    public function getConnection(){
        if($this->connection == null){
            $this->connection = new mysqli(
                Config::get("hostname"),
                Config::get("username"),
                Config::get("password"),
                Config::get("dbname")
            );
            
        }

        if($this->connection->connect_errno != 0) {
            var_dump(
                [
                    "body" => "Database not connected : " . $this->connection->connect_error,
                    "code" => 401,
                    "status" => "Connection Error"
                ]
            );
            exit;
        }
        $this->connection->set_charset('utf8');
        return $this->connection;
    }

}