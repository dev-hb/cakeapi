<?php


class Logger {
    private $data;

    /**
     * Logger constructor.
     * @param $data
     */
    public function __construct($data="")
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    public function shout($data){
        if($data == null){
            echo $this->getData();
        }else{
            echo $data;
        }
    }

    public function json($data, $code=200, $status="OK"){
        echo json_encode(["status" => $status, "code" => $code, "body" => $data]);
    }

}