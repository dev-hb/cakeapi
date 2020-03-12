<?php


class Errors extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function displayErros()
    {
        ini_set("display_errors", 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

}