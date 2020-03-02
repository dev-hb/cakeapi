<?php


abstract class CURL {
    protected $id;
    protected $url;
    protected $response;

    public function __construct($url = null){
        $this->url = $url;
        $this->response = null;
    }

    /**
     * Fetch the website's source code and return result
     */
    public function read(){
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //remove on upload
        curl_setopt($ch, CURLOPT_URL, $this->url); // url to be fetched
        curl_setopt($ch, CURLOPT_VERBOSE, 1); // allow verbose mode
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_REFERER, parse_url($this->url, PHP_URL_HOST)); // hostname
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // HTTP version to be used
        curl_setopt($ch, CURLOPT_HEADER, 0); // allow headers

        $this->response = curl_exec($ch);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }

}