<?php


class LocalStorage extends Storage
{
    /**
     * @var Uploader $uploader
     */
    private $uploader;
    /**
     * @var $size
     */
    private $size;
    /**
     * @var $max_size
     */
    private $max_size;
    /**
     * @var $files
     */
    private $files;
    /**
     * @var $max_files
     */
    private $max_files;

    /**
     * LocalStorage constructor.
     * @param long $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Init storage if not exists
     */
    function initStorage(){
        if(file_exists(Config::get('storage') . "/" . $this->getToken()));

    }

    /**
     * Handle the storage limiter middleware
     */
    public function handleMiddleware(){
        // check requirements
        if(! Params::get("filename")) Logger::json("Please provide a filename via GET or POST");
        if(! file_exists(Config::get("storage"))) Logger::json("Storage not found, please contact administrator");
        if(! Params::get('token'))  Logger::json(Functions::generateToken() ?? 'Storage not created, please try again');
        if(Params::get('token')) $this->setToken(Params::get('token'));
    }

    /**
     * @return Uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @param Uploader $uploader
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getMaxSize()
    {
        return $this->max_size;
    }

    /**
     * @param mixed $max_size
     */
    public function setMaxSize($max_size)
    {
        $this->max_size = $max_size;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getMaxFiles()
    {
        return $this->max_files;
    }

    /**
     * @param mixed $max_files
     */
    public function setMaxFiles($max_files)
    {
        $this->max_files = $max_files;
    }

}