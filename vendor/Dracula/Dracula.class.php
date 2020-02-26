<?php

class Dracula
{
    private $id;
    private $connection;
    private $blueprint;
    private $directory;

    public function __construct($blueprint, $directory='models')
    {
        // create connection instance
        $orm = new ORM();
        $this->connection = $orm->getConnection();
        $this->blueprint = $blueprint;
        $this->directory = $directory;
    }

    public function getFields(){
        require_once $this->directory."/".$this->blueprint.".php";
        $class = new $this->blueprint;
        $vars = get_class_vars(get_class($class));
        $fields = [];
        // extract fields names from vars keys
        foreach ($vars as $key => $var) array_push($fields, $key);
        return $fields;
    }

    public function findAll(){
        // implode to single string
        $fields = implode(', ', $this->getFields());
        // build sql request
        $sql = "SELECT ".$fields." FROM ".strtolower($this->blueprint);
        // fetch result then return in json format
        return $this->query($sql, null);
    }
    
    public function find($condition){
        // implode to single string
        $fields = implode(', ', $this->getFields());
        // build sql request
        $sql = "SELECT ".$fields." FROM ".strtolower($this->blueprint)." WHERE ".$condition;
        // fetch result then return in json format
        $result = $this->query($sql, $condition);
        if(count($result) == 1)
            return $result[0];
        if(count($result) > 1) return $result;
        return array('err' => "No data found");
    }

    public function count($condition){
        // build sql request
        $sql = "SELECT count(*) as count FROM ".strtolower($this->blueprint)." WHERE ".$condition;
        // fetch result then return in json format
        return $this->query($sql, null)[0];
    }

    public function delete($condition){
        // build sql request
        $sql = "DELETE FROM ".strtolower($this->blueprint)." WHERE ".$condition;
        // fetch result then return in json format
        $result = $this->queryUpdate($sql, $condition);
        return array('msg' => "Row deleted successfully");
    }

    public function queryUpdate($sql, $params=null){
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function query($sql, $params=null){
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $rs = $stmt->get_result();
        return $rs->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mysqli|null
     */
    public function getConnection(): ?mysqli
    {
        return $this->connection;
    }

    /**
     * @param mysqli|null $connection
     */
    public function setConnection(?mysqli $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getBlueprint()
    {
        return $this->blueprint;
    }

    /**
     * @param mixed $blueprint
     */
    public function setBlueprint($blueprint): void
    {
        $this->blueprint = $blueprint;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

}