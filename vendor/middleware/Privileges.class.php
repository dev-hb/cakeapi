<?php


class Privileges {
    private $id;
    private $privilege;
    private $context;

    /**
     * Privileges constructor.
     * @param $context
     */
    public function __construct($context=null){
        $this->privilege = [];
        $this->context = $context;
        $rights = file_get_contents('privileges/'.$this->context.'.priv');
        $rights = explode(',', $rights);
        $valid_privs = ['select', 'insert', 'update', 'delete', 'all', 'post'];
        foreach ($rights as $right){
            if(in_array($right, $valid_privs)){
                array_push($this->privilege, $right);
            }
        }
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
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @param null $privilege
     */
    public function setPrivilege($privilege): void
    {
        $this->privilege = $privilege;
    }

    /**
     * Returns true if the specified privilege is granted to a model
     */
    public function isGranted($priv){
        return in_array($priv, $this->getPrivilege());
    }

}