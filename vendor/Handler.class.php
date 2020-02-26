<?php

abstract class Handler {

    private $action;
    private $dracula;
    private $directory='models';

    public function __construct($dracula = null, $action = null){
        $this->action = $action;
        $this->dracula = $dracula;
        $this->directory = 'models';
    }

    /**
     * @return null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param null $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return null
     */
    public function getDracula()
    {
        return $this->dracula;
    }

    /**
     * @param null $dracula
     */
    public function setDracula($dracula): void
    {
        $this->dracula = $dracula;
    }

    public function getContext(){
        $context = "";
        if(count($_POST) > 0){
            if(! isset($_POST['context'])){
                (new Logger())->shout("Err : No context defined");
                exit;
            }elseif(! isset($_POST['action'])){
                (new Logger())->shout("Err : No action defined");
                exit;
            }
            $context = $_POST['context'];
            $this->setAction($_POST['action']);
        }else{
            if(! isset($_GET['context'])){
                (new Logger())->shout("Err : No context defined");
                exit;
            }elseif(! isset($_GET['action'])){
                (new Logger())->shout("Err : No action defined");
                exit;
            }
            $this->setAction($_GET['action']);
            $context = $_GET['context'];
        }
        return trim(htmlentities($context));
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
        $this->getDracula()->setDirectory($this->getDirectory());
    }

}