<?php


class PreferredHandler extends ActionHandler {

    public function __construct($params = null){
        parent::__construct($params);
        if(! file_exists('preferred/'.ucfirst($this->getContext()).'.php')){
            (new Logger())->json(null, 404, 'Context not found');
            exit;
        }
    }

    public function handle(){
        /// TODO add actions to take here (Handle both GET and POST methods)
        /// Create Dracula instance and execute the query method
        /// Handle $this->getAction() router

        require_once 'preferred/'.ucfirst($this->getContext()).'.php';
        $class = ucfirst($this->getContext());
        $obj = new $class;
        $obj->setAction($this->getAction());
        $obj->setParams($this->getParams());
        $obj->setMethod($this->getMethod());
        $obj->setDracula($this->getDracula());
        $obj->setDirectory($this->getDirectory());
        $obj->handle();
    }

}