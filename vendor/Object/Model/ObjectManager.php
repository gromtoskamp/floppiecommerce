<?php

namespace Object\Model;

class ObjectManager
{
    /**
     * Array of singletons in the from of:
     *  array(
     *      \example\namespace\class => Object()
     *  )
     *
     * @var array
     */
    private $singletons = array();

    /**
     * Gets the class defined in $class, or the rewrite of this $class.
     *
     * @param $class
     * @return mixed
     * @throws \Exception
     */
    public function create($class)
    {
        /**
         * Parse the namespace for rewrite and validation purposes.
         */
        $class = $this->parseClassname($class);

        /**
         * Return a new instance of the Object.
         */
        return new $class;
    }

    /**
     * Gets the singleton class defined in $class,
     * or the rewrite of this $class.
     *
     * @param $class
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function getInstance($class, $id = 0)
    {
        /**
         * Parse the namespace for rewrite and validation purposes.
         */
        $class = $this->parseClassname($class);

        /**
         * If the Singleton is not already set,
         * get a new instance of the singleton object, and save it in the $singletons array.
         */
        if (!isset($this->singletons[$class][$id])) {
            $this->singletons[$class][$id] = $this->create($class);
        }

        /**
         * Return the instance of the singleton defined in the $singletons array.
         */
        return $this->singletons[$class][$id];
    }

    /**
     * Function that parses a namespace, checks for potential rewrites and return either the
     * (rewritten) namespace or an exception.
     *
     * @param $class
     * @return string
     * @throws \Exception
     */
    public function parseClassname($class)
    {
        return Di::parseClassname($class);
    }
}