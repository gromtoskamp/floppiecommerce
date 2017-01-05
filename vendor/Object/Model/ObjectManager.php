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

    private static $rewrites = array();

    /**
     * Gets the class defined in $namespace, or the rewrite of this $namespace.
     *
     * @param $namespace
     * @return mixed
     * @throws \Exception
     */
    public function create($class)
    {
        /**
         * Parse the namespace for rewrite and validation purposes.
         */
        $class = $this->parseClassName($class);

        /**
         * Return a new instance of the Object.
         */
        return new $class;
    }

    /**
     * Gets an instance of an object,
     * identifiable by both classname and possibly provided id.
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
        $class = $this->parseClassName($class);

        /**
         * If the Singleton is not already set,
         * get a new instance of the singleton object, and save it in the $singletons array.
         */
        if (!$this->singletons[$class][$id]) {
            $this->singletons[$class][$id] = $this->create($class);
        }

        /**
         * Return the instance of the singleton defined in the $singletons array.
         */
        return $this->singletons[$class];
    }

    /**
     * Function that parses a namespace, checks for potential rewrites and return either the
     * (rewritten) namespace or an exception.
     *
     * @param $class
     * @return string
     * @throws \Exception
     */
    public function parseClassName($class)
    {
        /**
         * Namespaces should be defined with a starting '\'.
         * If this is not provided, add it to the namespace for convenience and singleton persistence.
         */
        if (substr($class, 0, 1) !== '\\') {
            $class = '\\' . $class;
        }

        /**
         * Check if a rewrite is defined and return the rewritten namespace if found.
         */
        $class = $this->getRewrite($class) ? $this->getRewrite($class) : $class;

        /**
         * If the parsed class does not exist, throw an exception to inform the user of this mishap.
         */
        if (!class_exists($class)) {
            throw new \Exception(
                sprintf(\Object\Declarations::ERROR_CLASS_NOT_FOUND, $class),
                \Object\Declarations::ERROR_CLASS_NOT_FOUND_CODE
            );
        }

        return $class;
    }

    /**
     * Sets a rewrite in the static $rewrites array.
     * This method is static, to make sure that rewrites can be added from init.php files.
     *
     * @param string $from (\Namespace\Path\To\Original)
     * @param string $to (\Namespace\Path\To\Rewrite)
     * @throws \Exception in case of a double rewrite.
     */
    public static function setRewrite($from, $to)
    {
        /**
         * If a conflicting rewrite is found, throw an exception informing the user.
         */
        if (isset(self::$rewrites[$from])) {
            throw new \Exception(
                sprintf(
                    \Object\Declarations::ERROR_DOUBLE_REWRITE,
                    $from,
                    $to,
                    self::$rewrites[$from]),
                \Object\Declarations::ERROR_DOUBLE_REWRITE_CODE
            );
        }

        /**
         * Return the namespace of the rewrite.
         */
        self::$rewrites[$from] = $to;
    }

    /**
     * Returns a rewrite if this is found,
     * otherwise returns false for comparison purposes.
     *
     * @param $from
     * @return string | bool
     */
    public function getRewrite($from)
    {
        if (isset(self::$rewrites[$from])) {
            return self::$rewrites[$from];
        }

        return false;
    }

}