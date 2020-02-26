<?php

class ORM {
    private $connection = null;

    public function getConnection(){
        if($this->connection == null){
            // connect to database
            // getting credentials from .connection file
            $keywords = ['hostname', 'username', 'password', 'dbname'];
            $env = explode(PHP_EOL, file_get_contents('.connection'));
            $credentials = [];
            foreach($env as $param){
                if(preg_match("/(hostname|username|password|dbname)/", $param))
                    array_push($credentials,  explode('=', $param)[1]);
            }
            $this->connection = new mysqli($credentials[0], $credentials[1], $credentials[2], $credentials[3]);
            
        }
        
        return $this->connection;
    }

}