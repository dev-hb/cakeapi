<?php


class Storage
{
    /**
     * @var long $id
     */
    private $id;
    /**
     * @var string $token
     */
    private $token;
    /**
     * @var string $path
     */
    private $path;

    /**
     * Storage constructor.
     * @param long $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return long
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param long $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Create new folder inside storage folder
     * @param $token
     * @return bool
     */
    public static function makeFolder($token){
        return mkdir(Config::get('storage') . '/' . $token);
    }

    /**
     * delete empty storage
     */
    public static function clearEmpty(){
        // delete empty storage
    }
}