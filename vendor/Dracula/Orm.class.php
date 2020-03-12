<?php

class ORM {
    private $connection = null;

    public function getConnection(){
        if($this->connection == null){
            // connect to database
            // getting credentials from .connection file
            $keywords = ['hostname', 'username', 'password', 'dbname'];
            $env = explode("\n", file_get_contents('.connection'));
            $credentials = [];
            foreach($env as $param){
                if(preg_match("/(hostname|username|password|dbname)/", $param))
                    $credentials[trim(explode('=', $param)[0])] = trim(explode('=', $param)[1]);
            }
            $this->connection = new mysqli($credentials['hostname'], $credentials['username'], $credentials['password'], $credentials['dbname']);
            
        }
        
        return $this->connection;
    }

}