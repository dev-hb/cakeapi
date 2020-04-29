<?php


class AccessToken extends Token {

    private $blueprint;
    private $dracula;
    private $context;
    private $valid_contexts;

    /**
     * AccessToken constructor.
     * @param $context string
     * @param $token string
     * @param $blueprint string
     * @param $dracula object
     */
    public function __construct($context, $token){
        /// TODO add access token credentials and rules
        $this->token = $token;
        $this->blueprint = (new Helpers())->getTokenerConfig()['table'];
        $this->dracula = new Dracula($this->blueprint);
        $this->valid_contexts = ['authentication'];
        $this->context = $context;

        if($token == null && ! in_array($context, $this->valid_contexts)){
            (new Logger())->json(['err' => "token not provided"]);
            exit;
        }
    }

    /**
     * @return void
     */
    public function handle(){

        if(in_array($this->context, $this->valid_contexts)) return;

        $token = $this->getToken();
        if($token == null){
            (new Logger())->json(['err' => 'no token provided']);
            exit;
        }
        $blueprint = $this->getBlueprint();
        $is_valid = $this->dracula->query("SELECT * FROM $blueprint WHERE token=? && token_expires>CURRENT_TIMESTAMP", [$this->token]);
        if(! $is_valid){
            (new Logger())->json(['err' => 'invalid token']);
            exit;
        }
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
    public function setBlueprint($blueprint)
    {
        $this->blueprint = $blueprint;
    }

    /**
     * @return mixed
     */
    public function getValidContexts()
    {
        return $this->valid_contexts;
    }

    /**
     * @param mixed $valid_contexts
     */
    public function setValidContexts($valid_contexts)
    {
        $this->valid_contexts = $valid_contexts;
    }

}