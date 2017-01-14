<?php

namespace Object\Model;

class Object
{

    /**
     * 103
     * Magic method undefined.
     */
    const ERROR_MAGIC_METHOD_UNDEFINED_CODE = '103';
    const ERROR_MAGIC_METHOD_UNDEFINED = 'Magic method %s undefined!';

    /**
     * 105
     * Add value not an array.
     */
    const ERROR_ADD_VALUE_NOT_ARRAY_CODE = '105';
    const ERROR_ADD_VALUE_NOT_ARRAY = 'Value of %s is not an array!';

    const REALLY_EMPTY = '"\(シ)/"';

    /**
     * @var \Object\Model\ObjectManager ObjectManager
     */
    private $objectManager;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var array
     */
    private $magicMethods = array(
        'get',
        'uns',
        'has',
        'set',
        'add',
    );

    /**
     * Object constructor.
     */
    public function __construct()
    {
        $this->objectManager = new ObjectManager;
    }

    /**
     * Call magic function
     * Used for getting, setting, unsetting, hassing.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        /**
         * Extract the magic method call and the index used for the magic method.
         */
        $functionName = substr($name, 0, 3);
        $index = substr($name, 3);

        /**
         * Put the index at the beginning of the argument list,
         * and call the requested magic method for the given arguments.
         */
        array_unshift($arguments, lcfirst($index));
        if (!in_array($functionName, $this->magicMethods)) {
            throw new \Exception(
                sprintf(self::ERROR_MAGIC_METHOD_UNDEFINED, $name),
                self::ERROR_MAGIC_METHOD_UNDEFINED_CODE
            );
        }

        /**
         * Return the result of the magic function.
         */
        return call_user_func_array(array($this, $functionName), $arguments);
    }

    /**
     * Supermagic method alert!
     *
     * This magicey method will try to get the value from the data array.
     * If this value is not present, it will try to call the provided index as a function.
     *
     * This function can be placed in the class of the object, where it will only
     * need to return the value of what should be set, if nothing is set yet.
     *
     * @param null $index
     * @return array|bool
     */
    public function get($index = null, $refresh = false)
    {
        /**
         * If no index is provided, return the entire data array.
         */
        if ($index == null) {
            return $this->data;
        }

        /**
         * If we don't already have index, check if we can generate it.
         * If so, set it on the object, otherwise return false.
         */
        if (!$this->has($index) || $refresh) {
            if (method_exists($this, $index)) {
                $this->set($index, $this->$index());
            } else {
                return false;
            }
        }

        /**
         * Return the index from the data array.
         */
        return $this->data[$index];
    }

    /**
     * Sets the value of an index in $data.
     * TODO: add description of this weird-ass construction.
     *
     * @param $index
     * @param string $value
     * @return $this
     */
    public function set($index, $value = self::REALLY_EMPTY)
    {
        if ($value == self::REALLY_EMPTY) {
            $value = method_exists($this, $index) ? $this->$index() : null;
        }

        $this->data[$index] = $value;
        return $this;
    }

    /**
     * Adds a value to an array in $data.
     *
     * @param $index
     * @param null $value
     * @return $this
     * @throws \Exception
     */
    public function add($index, $value = null)
    {
        /**
         * If the value is null, create an array under $index with initial value $value.
         * If the value is not an array and not null, throw an Exception.
         */
        $indexValue = $this->has($index) ? $this->get($index) : array();
        if (!is_array($value)) {
            throw new \Exception(
                sprintf(self::ERROR_ADD_VALUE_NOT_ARRAY, $index),
                self::ERROR_ADD_VALUE_NOT_ARRAY_CODE
            );
        }

        /**
         * Merge the new value to the already set value, and set that on the object.
         */
        $indexValue = array_merge_recursive($indexValue, $value);
        $this->set($index, $indexValue);

        return $this;
    }

    /**
     * Resets the value of an index in $data to null.
     *
     * @param $index
     * @return $this
     * @throws \Exception
     */
    public function uns($index)
    {
        $this->data[$index] = null;
        return $this;
    }

    /**
     * True if set, false if not.
     *
     * @param $index
     * @return bool
     */
    public function has($index)
    {
        /**
         * If provided with an array,
         * check recursively if we can find the next index
         * as a child of the current index.
         */
        if (is_array($index)) {
            return $this->hasRecursive($index);
        }

        /**
         * Otherwise just check if is set.
         */
        return isset($this->data[$index]);
    }

    /**
     * Checks recursively if a value is set.
     * Walks through all items in $indexArray,
     * and checks if the item is present as an index in the previous index' value.
     *
     * @param array $indexArray
     * @return bool
     */
    public function hasRecursive(array $indexArray)
    {
        $dataObject = $this->get();
        foreach ($indexArray as $index) {
            if (!isset($dataObject[$index])) {
                return false;
            }
            $dataObject = $dataObject[$index];
        }

        return true;
    }

    /**
     * Passes the handling of creating a new object to the objectmanager.
     *
     * @param $class
     * @return mixed
     * @throws \Exception
     */
    public function create($class)
    {
        return $this->objectManager->create($class);
    }

    /**
     * Passes the handling of creating a singleton
     *
     * @param $class
     * @param int $id
     * @return \Object\Model\Object
     */
    public function getInstance($class, $id = 0)
    {
        return $this->objectManager->getInstance($class, $id);
    }

    public function debug($object)
    {
        //TODO: REMOVE THIS
        echo '<pre>';
        print_r($object);
        exit;
    }

    /**
     * Uses ReflectionClass to loop through class methods.
     * Every class method with the annotation '@builder' will be executed,
     * and the result will be set.
     */
    public function build()
    {
        /**
         * Get a reflectionClass of the current object.
         */
        $reflectionClass = new \ReflectionClass($this);

        /**
         * Loop through all functions.
         * Every function with a @builder tag is executed as a set method.
         */
        foreach ($reflectionClass->getMethods()  as $method) {
            if (strpos($method->getDocComment(), '@builder') === false) {
                continue;
            }

            $methodName = $method->getName();
            $this->set($methodName, $this->$methodName());
        }
    }


}