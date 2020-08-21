<?php


class Uploader
{
    /**
     * @var File $file
     */
    private $file;

    /**
     * Uploader constructor.
     * @param File $file
     */
    public function __construct($file = null)
    {
        $this->file = $file;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    function upload(){
        $this->file->setName(Params::get('filename'));
    }

}