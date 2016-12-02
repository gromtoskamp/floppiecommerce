<?php

namespace Object\Model;

class ObjectManager
{

    const ERRORCODE_CLASS_NOT_FOUND = 1;

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
     * Gets the class defined in $namespace.
     *
     * @param $namespace
     * @return mixed
     * @throws \Exception
     */
    public function getNew($namespace)
    {
        $namespace = $this->validateNamespace($namespace);

        return new $namespace;
    }

    /**
     * @param $namespace
     * @return mixed
     */
    public function getSingleton($namespace)
    {
        $namespace = $this->validateNamespace($namespace);
        if (!isset($this->singletons[$namespace]))
        {
            $this->singletons[$namespace] = $this->getNew($namespace);
        }

        return $this->singletons[$namespace];
    }

    /**
     * Function to validate namespace to make this less error-prone.
     * Also checks if the namespace is findable. If this is not the case, throw an \Exception.
     *
     * @param $namespace
     * @return string
     * @throws \Exception
     */
    public function validateNamespace($namespace)
    {
        /**
         * Namespaces should be defined with a starting '\'.
         * If this is not provided, add it to the namespace for convenience and singleton persistence.
         */
        if (substr($namespace, 0, 1) !== '\\') {
            $namespace = '\\' . $namespace;
        }

        /**
         * If the parsed class does not exist, throw an exception to inform the user of this mishap.
         */
        if (!class_exists($namespace)) {
            throw new \Exception('Class ' . $namespace . ' does not exist!', self::ERRORCODE_CLASS_NOT_FOUND);
        }

        return $namespace;
    }

}