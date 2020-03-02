<?php


class WebGet extends CURL {

    private $prefixes = [
        "href=\"",
        "src=\"",
        "href='",
        "src='"
    ];

    public function __construct($url=null){
        // TOTO add construct code here
        parent::__construct($url);
    }

    /**
     * Replaces source files with relative paths to absolute paths
     */
    public function replace(){
        foreach($this->prefixes as $prefixe){
            $this->setResponse(str_replace("src=\"/", "src=\"", $this->getResponse()));
            $this->setResponse(str_replace($prefixe, $prefixe . $this->url . "/", $this->getResponse()));
        }

        return $this;
    }

}