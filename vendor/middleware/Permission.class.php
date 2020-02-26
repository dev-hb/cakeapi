<?php


class Permission {

    private $host;

    public function __construct($host = "*"){
        $this->host = $host;
    }

    public function accordAll(){
        header("Access-Control-Allow-Origin: ". $this->host);
        header("Access-Control-Allow-Headers: ". $this->host);
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     * @return mixed
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

}