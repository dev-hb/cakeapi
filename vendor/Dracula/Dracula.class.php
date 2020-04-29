<?php

class Dracula
{
    private $id;
    private $connection;
    private $blueprint;
    private $directory;

    public function __construct($blueprint=null, $directory='models')
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
    
    public function find($keys, $values){
        // implode to single string
        $fields = implode(', ', $this->getFields());
        /// getting parameters count
        $qm = [];
        foreach ($keys as $k) array_push($qm, "$k=?");
        $qm = implode(' AND ', $qm);
        // build sql request
        $sql = "SELECT ".$fields." FROM ".strtolower($this->blueprint)." WHERE $qm";
        // fetch result then return in json format
        $result = $this->query($sql, $values);
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
        $result = $this->queryUpdate($sql, null);
        return $result ? array('msg' => "Row deleted successfully") : array('err' => "Error deleting row");
    }

    public function insert($params){
        // build sql request
        $fields = implode(',', $params[0]);
        $values = $params[1];
        /// getting parameters count
        $qm = [];
        foreach ($values as $v) array_push($qm, "?");
        $qm = implode(', ', $qm);
        /// sql to be execute
        $sql = "INSERT INTO ".strtolower($this->blueprint)." ($fields) VALUES ($qm)";
        // fetch result then return in json format
        $result = $this->queryUpdate($sql, $values);
        return $result ? array('msg' => "Row inserted successfully") : array('err' => "Error inserting row");
    }

    public function update($params, $condition){
        $keys = $params[0];
        $values = $params[1];
        /// getting parameters count
        $qm = [];
        foreach ($keys as $k) array_push($qm, "$k = ?");
        $qm = implode(', ', $qm);
        /// sql to be execute
        $sql = "UPDATE ".strtolower($this->blueprint)." SET $qm WHERE $condition[0] = ?";
        // fetch result then return in json format
        array_push($values, $condition[1]);
        $result = $this->queryUpdate($sql, $values);
        return $result ? array('msg' => "Row updated successfully") : array('err' => "Error updating row");
    }

    public function queryUpdate($sql, $params=null){
        $stmt = $this->connection->prepare($sql);
        // bind params if exists
        if($params != null){
            $types = ""; // bind all params as string or integers
            foreach ($params as $v) $types .= ( is_numeric($v) ? "i" : "s");
            $stmt->bind_param($types,...$params);
        }
        return $stmt->execute();
    }

    public function query($sql, $params=null){
        $stmt = $this->connection->prepare($sql);
        // bind params if exists
        if($params != null){
            $types = ""; // bind all params as string or integers
            foreach ($params as $v) $types .= ( is_numeric($v) ? "i" : "s");
            $stmt->bind_param($types,...$params);
        }
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