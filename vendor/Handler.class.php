<?php

abstract class Handler {

    protected $action;
    protected $dracula;
    protected $directory='models';
    protected $params;

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
        if(! array_key_exists('context', $this->getParams())){
            (new Logger())->json(null, 404, "No context defined");
            exit;
        }elseif(! array_key_exists('action', $this->getParams())){
            (new Logger())->json(null, 404, "No action defined");
            exit;
        }
        $context = $this->getParams()['context'];
        $this->setAction($this->getParams()['action']);

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

    /**
     * @return null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param null $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
    }

}