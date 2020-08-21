<?php


class Params
{
    /**
     * @var $params
     */
    private static $params;

    /**
     * Params constructor.
     */
    public function __construct()
    {
        Params::$params = null;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function get($key){
        if(Params::$params == null){
            $prm = array_merge($_GET, $_POST);
            Params::$params = $prm;
        }else $prm = Params::$params;
        return array_key_exists($key, $prm) ? $prm[$key] : null;
    }

    /**
     * @return mixed
     */
    public static function getParams(){
        return Params::$params;
    }

}