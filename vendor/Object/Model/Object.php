<?php

namespace Object\Model;

class Object
{

    /**
     * @var \Object\Model\ObjectManager ObjectManager
     */
    private $objectManager;

    /**
     * Object constructor.
     */
    public function __construct()
    {
        $this->objectManager = new ObjectManager;
    }

    public function __get($get)
    {
        echo 'we get here, gettit?';
    }

    /**
     * Passes the handling of creating a new object to the objectmanager.
     *
     * @param $namespace
     * @return mixed
     * @throws \Exception
     */
    public function getNew($namespace)
    {
        return $this->objectManager->getNew($namespace);
    }

    /**
     * Passes the handling of creating a singleton
     *
     * @param $namespace
     * @return mixed
     */
    public function getSingleton($namespace)
    {
        return $this->objectManager->getSingleton($namespace);
    }

    public function debug($object)
    {
        //TODO: REMOVE THIS
        echo '<pre>';
        print_r($object);
        exit;
    }


}