<?php


class Binder {
    private $objector; // blueprints to be merged
    private $criteria; // fields criteria
    private $result; // returned result MySQL cursor
    private $condition = null; // root blueprint's condition

    /**
     * Binder constructor.
     * @param $blueprint root blue print
     */
    public function __construct($blueprint){
        $this->objector = [array('blueprint' => $blueprint)];
    }

    /**
     * @param $b
     * @return $this
     */
    public function bind($b){
        array_push($this->objector, array('blueprint' => $b));
        return $this;
    }

    /**
     * @param $b
     * @return $this
     */
    public function hasMany($b){
        $this->objector[count($this->objector)-1][$b] = $b;
        return $this;
    }

    /**
     * @param $c
     * @return $this
     */
    public function criteria($c){
        $this->criteria = $c;
        return $this;
    }

    /**
     * @param $fields
     * @param $values
     * @return $this
     */
    public function where($fields, $values){
        $this->condition = ['fields' => $fields, 'values' => $values];
        return $this;
    }

    /**
     * Handle the given blueprints
     * @return $this
     */
    public function handle(){
        $data = [];
        foreach ($this->objector as $bp){
            // retrieve each table then aggregate
            if($this->condition == null)
                $res = (new Dracula($bp['blueprint']))->findAll();
            else
                $res = (new Dracula($bp['blueprint']))->find(
                    $this->getCondition()['fields'],
                    $this->getCondition()['values']
                );

            // check if the result is an array or a single object
            if(! isset($res[0]))
                $res = array($res);

            // bind sub array for each recorded entry
            foreach ($res as $i=>$record){
                if(count($bp) > 1){
                    $keys = [];
                    foreach ($bp as $key=>$item)
                        array_push($keys, $key);
                    // bind has many
                    if(count($this->getCriteria()) == 0){
                        (new Logger())->json(['err', 'please provider criteria fields']);
                        exit;
                    } // check if the criteria presented is a single column, if its the case then duplicate te column
                    if(count($this->getCriteria()) <= 1) $this->criteria[1] = $this->criteria[0];
                    $res[$i][$keys[1]] = (new Dracula(ucfirst($keys[1])))->find(
                        [$this->getCriteria()[1]] , [$res[$i][$this->getCriteria()[0]]]
                    );
                }
            }
            $data = $res;
        }

        $this->setResult($data);
        return $this;
    }

    /**
     * @param int $code
     * @param string $status
     */
    public function json($code=200, $status="OK"){
        (new Logger())->json($this->getResult(), $code, $status);
        exit;
    }

    /**
     * @return array
     */
    public function getObjector()
    {
        return $this->objector;
    }

    /**
     * @param array $objector
     */
    public function setObjector($objector)
    {
        $this->objector = $objector;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return null
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param null $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }


}