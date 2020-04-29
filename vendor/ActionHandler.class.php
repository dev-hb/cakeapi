<?php


class ActionHandler extends Handler {
    private $params;
    private $method = 'GET';
    private $dracula;
    private $privileges;
    private $input;

    const POST = 'POST';
    const GET = 'GET';

    /**
     * ActionHandler constructor.
     * @param $params
     */
    public function __construct($params=null)
    {
        $this->params = $params;
        $this->parseParams();
        if(count($_POST) > 0){
            if(! isset($_POST['context'])){
                (new Logger())->shout("Err : No context defined");
                exit;
            }
        }else{
            if(! isset($_GET['context'])){
                (new Logger())->shout("Err : No context defined");
                exit;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getInput(){
        return file_get_contents("php://input");
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

    /**
     * @return mixed
     */
    public function getDracula()
    {
        return $this->dracula;
    }

    /**
     * @param mixed $dracula
     */
    public function setDracula($dracula): void
    {
        $this->dracula = $dracula;
    }

    /**
     * Parsing params to array
     */
    public function parseParams(){
        $this->params = $this->getMethod() == 'GET' ? $_GET : $_POST;
    }

    /**
     * Handle incoming actions
     */
    public function handle(){
        // switch habitual action
        $params = $this->getParams();
        $data = "";
        switch (strtolower($this->getAction())){
            case 'findall':
                // find all data from table
                if(! $this->getPrivileges()->isGranted('select')) $data = $this->getPermissionMessage('select from');
                else $data = $this->getDracula()->findAll();
                break;
            case 'find':
                // find data with given column
                if(! $this->getPrivileges()->isGranted('select')) $data = $this->getPermissionMessage('select from');
                else{
                    if(count($params) <= 2 ) $data = array('err' => 'Specify a condition');
                    else{
                        $fields = [];
                        $values = [];
                        foreach ($params as $key=>$param)
                            if($key != 'context' && $key != 'action'){
                                array_push($fields, $key);
                                array_push($values, $param);
                            }
                        $data = $this->getDracula()->find($fields, $values);
                    }
                }
                break;
            case 'count':
                // get rows count
                if(! $this->getPrivileges()->isGranted('select')) $data = $this->getPermissionMessage('select from');
                else{
                    $condition = [];
                    foreach ($params as $key=>$param)
                        if($key != 'context' && $key != 'action')
                            array_push($condition, "$key='$param'");
                    $condition = count($condition) > 0 ? implode(' AND ', $condition) : "1=1";
                    $data = $this->getDracula()->count($condition);
                }
                break;
            case 'delete':
                // delete with given column
                if(! $this->getPrivileges()->isGranted('delete')) $data = $this->getPermissionMessage('delete from');
                else if($this->getMethod() == 'GET') $data = array('err' => 'Use POST request instead');
                else{
                    $condition = null;
                    foreach ($params as $key=>$param) if($param != 'context' && $param != 'action') $condition = "$key='$param'";
                    $data = $this->getDracula()->delete($condition);
                }
                break;
            case 'insert':
                // update given columns
                if(! $this->getPrivileges()->isGranted('insert')) $data = $this->getPermissionMessage('insert into');
                else if($this->getMethod() == 'GET') $data = array('err' => 'Use POST request instead');
                else{
                    $fields = [];
                    $values = [];
                    foreach ($params as $key=>$param)
                        if($key != 'context' && $key != 'action'){
                            array_push($fields, $key);
                            array_push($values, $param);
                        }
                    $data = $this->getDracula()->insert(array($fields, $values));
                }
                break;
            case 'update':
                // update given columns
                if(! $this->getPrivileges()->isGranted('update')) $data = $this->getPermissionMessage('update');
                else if($this->getMethod() == 'GET') $data = array('err' => 'Use POST request instead');
                else{
                    $fields = [];
                    $values = [];
                    $i = 0;
                    $condition = array("1", "0");
                    foreach ($params as $key=>$param){
                        if($i == 2) $condition = array($key, $param);
                        if($key != 'context' && $key != 'action' && $i > 2){ // exclude where clause from link's params
                            array_push($fields, $key);
                            array_push($values, $param);
                        } $i++;
                    }
                    $data = $this->getDracula()->update(array($fields, $values), $condition);
                }
                break;
            default:
                (new Logger())->json(['err'=>"Invalid action"]);
                die;
        }
        (new Logger())->json($data);
        exit;
    }

    public function getPermissionMessage($msg){
        return array('err' => 'You don\'t have permission to '.$msg.' this resource');
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
        $this->setAction($this->getMethod() == 'GET' ? $_GET['action'] : $_POST['action']);
    }

    /**
     * @return mixed
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @param mixed $privileges
     */
    public function setPrivileges($privileges): void
    {
        $this->privileges = $privileges;
    }

}