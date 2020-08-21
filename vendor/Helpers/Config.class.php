<?php

class Config {
    private static $variables = null;

    /**
     * @param string $key
     * @return mixed|string|null
     */
    public static function get($key=null){
        if($key == null) return null;
        // fetch all configs if not already fetched
        if(Config::$variables == null){
            // read .config and explode to lines
            $cg = []; // temp holds all variables
            $config = explode("\n", file_get_contents(".config"));
            foreach ($config as $c){
                if(trim($c) != ""){
                    $line = explode("=", $c);
                    if(count($line) == 2) {
                        $cg[trim($line[0])] = trim($line[1]);
                    }
                }
            }
            Config::$variables = $cg;
        }else $cg = Config::$variables;
        return array_key_exists($key, $cg) ? $cg[$key] : null;
    }
}