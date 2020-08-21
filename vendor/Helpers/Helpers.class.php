<?php


class Helpers {

    const Tokener_Config_Path = 'tokener.config';
    private $tokener_config;

    public function __construct(){
        if(file_exists(Helpers::Tokener_Config_Path)){
            $data = explode("\n", file_get_contents(Helpers::Tokener_Config_Path));
            $params = [];
            foreach ($data as $param){
                if($param != ""){
                    $c = explode("=", $param);
                    $params[trim($c[0])] = trim($c[1]);
                }
            }
            $this->tokener_config = $params;
        }else $this->tokener_config = null;
    }

    /**
     * @param $A
     * @param $b
     * @param $n
     * @return string
     */
    public function generate_password_hash($A, $b, $n){
        $hash = [];

        for($i=0; $i<$A; $i++) array_push($hash, chr( rand(ord('A'), ord('Z'))));
        for($i=0; $i<$A; $i++) array_push($hash,  chr( rand(ord('a'), ord('z'))));
        for($i=0; $i<$A; $i++) array_push($hash,  chr( rand(ord('0'), ord('9'))));

        shuffle($hash);

        return implode('', $hash);
    }

    public function checkTokener(){
        $config = Helpers::Tokener_Config_Path;
        if(! file_exists($config)) return false;
        $configuration = $this->getTokenerConfig();
        if($configuration['active'] != "true") return false;
        return true;
    }

    /**
     * @return null
     */
    public function getTokenerConfig()
    {
        return $this->tokener_config;
    }

    /**
     * @param null $tokener_config
     */
    public function setTokenerConfig($tokener_config): void
    {
        $this->tokener_config = $tokener_config;
    }

}