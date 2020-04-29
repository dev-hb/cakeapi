<?php


abstract class Token {
    protected $token;

    /**
     * Token constructor.
     * @param $token
     */
    public function __construct($token=null)
    {
        $this->token = $token;
    }

    /**
     * @return null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param null $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

}