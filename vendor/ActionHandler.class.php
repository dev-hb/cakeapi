<?php


class ActionHandler extends Handler
{
    private $method = 'GET';
    private $privileges;
    private $input;
    private $use_post = false;

    const POST = 'POST';
    const GET = 'GET';

    /**
     * ActionHandler constructor.
     * @param $params
     */
    public function __construct($params = null)
    {
        $this->params = $params;
        $this->parseParams();
        if (count($_POST) > 0) {
            if (!isset($_POST['context'])) {
                (new Logger())->json(null, 404, "No context defined");
                exit;
            }
        } else {
            if (!isset($_GET['context'])) {
                (new Logger())->json(null, 404, "No context defined");
                exit;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return file_get_contents("php://input");
    }

    /**
     * @return false|string
     */
    public function getJsonInput()
    {
        return json_decode($this->getInput());
    }

    /**
     * Parsing params to array
     */
    public function parseParams()
    {
        $input = $this->getJsonInput();
        if ($input != null)
            $this->params = array_merge($_GET, $_POST, $input);
        else
            $this->params = array_merge($_GET, $_POST);
    }

    /**
     * Handle incoming actions
     */
    public function handle()
    {
        // switch habitual action
        $params = $this->getParams();
        $data = null;
        $status = "OK";
        switch (strtolower($this->getAction())) {
            case 'findall':
                // find all data from table
                if (!$this->getPrivileges()->isGranted('select')) $data = $this->getPermissionMessage('select from');
                else $data = $this->getDracula()->findAll();
                break;
            case 'find':
                // find data with given column
                if (!$this->getPrivileges()->isGranted('select')) $data = $this->getPermissionMessage('select from');
                else {
                    if (count($params) <= 2) $data = $this->jsonForm(null, 404, 'Specify a condition');
                    else {
                        $fields = [];
                        $values = [];
                        foreach ($params as $key => $param)
                            if ($key != 'context' && $key != 'action') {
                                array_push($fields, $key);
                                array_push($values, $param);
                            }
                        $data = $this->getDracula()->find($fields, $values);

                        if($data == null){
                            $status = "No row found";
                            $data = null;
                        }
                    }
                }
                break;
            case 'count':
                // get rows count
                if (!$this->getPrivileges()->isGranted('select')) $data = $this->getPermissionMessage('select from');
                else {
                    $condition = [];
                    foreach ($params as $key => $param)
                        if ($key != 'context' && $key != 'action')
                            array_push($condition, "$key='$param'");
                    $condition = count($condition) > 0 ? implode(' AND ', $condition) : "1=1";
                    $data = $this->getDracula()->count($condition);
                }
                break;
            case 'delete':
                // delete with given column
                if (!$this->getPrivileges()->isGranted('delete')) $data = $this->getPermissionMessage('delete from');
                else if ($this->use_post && $this->getMethod() == ActionHandler::GET) $data = $this->jsonForm(null, 403, 'Use POST request instead');
                else {
                    $condition = null;
                    foreach ($params as $key => $param) if ($param != 'context' && $param != 'action') $condition = "$key='$param'";
                    $data = $this->getDracula()->delete($condition);
                }

                if($data == null){
                    $status = "No row found";
                    $data = null;
                }
                break;
            case 'insert':
                // update given columns
                if (!$this->getPrivileges()->isGranted('insert')) $data = $this->getPermissionMessage('insert into');
                else if ($this->use_post && $this->getMethod() == ActionHandler::GET) $data = $this->jsonForm(null, 403, 'Use POST request instead');
                else {
                    $fields = [];
                    $values = [];
                    foreach ($params as $key => $param)
                        if ($key != 'context' && $key != 'action') {
                            array_push($fields, $key);
                            array_push($values, $param);
                        }
                    $data = $this->getDracula()->insert(array($fields, $values));
                    if($data == null){
                        $status = "No row found";
                        $data = null;
                    }
                }
                break;
            case 'update':
                // update given columns
                if (!$this->getPrivileges()->isGranted('update')) $data = $this->getPermissionMessage('update');
                else if ($this->use_post && $this->getMethod() == ActionHandler::GET) $data = $this->jsonForm(null, 403, 'Use POST request instead');
                else {
                    $fields = [];
                    $values = [];
                    $i = 0;
                    $condition = array("1", "0");
                    foreach ($params as $key => $param) {
                        if ($i == 2) $condition = array($key, $param);
                        if ($key != 'context' && $key != 'action' && $i > 2) { // exclude where clause from link's params
                            array_push($fields, $key);
                            array_push($values, $param);
                        }
                        $i++;
                    }
                    $data = $this->getDracula()->update(array($fields, $values), $condition);
                    if($data == null){
                        $status = "Error";
                        $data = null;
                    }else $data = $this->getDracula()->find([$condition[0]], [$condition[1]]);

                }
                break;
            default:
                (new Logger())->json(null, 403, "Invalid action");
                die;
        }
        $data = $data == 200 ? null : $data;
        (new Logger())->json($data, 200, $status);
        exit;
    }

    public function jsonForm($data, $code = 200, $status = "OK")
    {
        return array("status" => $status, "code" => $code, "body" => $data);
    }

    public function getPermissionMessage($msg)
    {
        echo json_encode(["status" => 'You don\'t have permission to ' . $msg . ' this resource', "code" => 403, "body" => null]);
        exit;
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
        $this->setAction($this->getParams()['action']);
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

    /**
     * @return bool
     */
    public function isUsePost()
    {
        return $this->use_post;
    }

    /**
     * @param bool $use_post
     */
    public function setUsePost($use_post)
    {
        $this->use_post = $use_post;
    }

}