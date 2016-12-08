<?php

namespace Object\Model;

class Object
{

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
                \Object\Declarations::ERROR_MAGIC_METHOD_UNDEFINED,
                \Object\Declarations::ERROR_MAGIC_METHOD_UNDEFINED_CODE
            );
        }

        /**
         * Return the result of the magic function.
         */
        return call_user_func_array(array($this, $functionName), $arguments);
    }

    /**
     * Returns a value magically, or false if the requested value is not set.
     * When strict is set to true, will throw an Exception if the value is not set.
     *
     * @param $index
     * @param bool $strict
     * @return mixed
     * @throws \Exception
     */
    public function get($index = null, $strict = false)
    {
        if ($index == null) {
            return $this->data;
        }

        /**
         * If strict parameter is provided, validate if the index is present in $data.
         */
        if ($strict == true && !$this->has($index)) {
            $this->validate($index);
        }

        /**
         * Return the set value, or false if not set.
         */
        return $this->has($index) ? $this->data[$index] : false;
    }

    /**
     * Sets the value of an index in $data.
     * When strict is set to true, will throw an Exception if the value is not yet set already.
     *
     * @param $index
     * @param null $value
     * @param bool $strict
     * @return $this
     * @throws \Exception
     */
    public function set($index, $value = null, $strict = false)
    {
        /**
         * If strict parameter is provided, validate if the index is present in $data.
         */
        if ($strict == true && !$this->has($index)) {
            $this->validate($index);
        }

        $this->data[$index] = $value;
        return $this;
    }

    /**
     * Adds a value to an array in $data.
     * When strict is set to true, will throw an Exception if the value is not yet set already.
     *
     * @param $index
     * @param null $value
     * @param bool $strict
     * @return $this
     * @throws \Exception
     */
    public function add($index, $value = null, $strict = false)
    {
        /**
         * If strict parameter is provided, validate if the index is present in $data.
         */
        if ($strict == true && !$this->has($index)) {
            $this->validate($index);
        }

        /**
         * If the value is null, create an array under $index with initial value $value.
         * If the value is not an array and not null, throw an Exception.
         */
        $indexValue = $this->has($index) ? $this->get($index) : array();
        if (!is_array($value)) {
            throw new \Exception(
                sprintf(\Object\Declarations::ERROR_ADD_VALUE_NOT_ARRAY, $index),
                \Object\Declarations::ERROR_ADD_VALUE_NOT_ARRAY_CODE
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
     * When strict is set to true, will throw an Exception if the value is not set.
     *
     * @param $index
     * @param bool $strict
     * @return $this
     * @throws \Exception
     */
    public function uns($index, $strict = false)
    {
        /**
         * If strict parameter is provided, validate if the index is present in $data.
         */
        if ($strict == true && !$this->has($index)) {
            $this->validate($index);
        }

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
        if (is_array($index)) {
            return $this->hasRecursive($index);
        }

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
     * Throw an Exception if index is not set in $data.
     *
     * @param $index
     * @throws \Exception
     */
    public function validate($index)
    {
        $message = sprintf(\Object\Declarations::ERROR_STRICT_MAGIC_FUNCTION, $index) . PHP_EOL .
            implode(' -=- ', array_keys($this->data));

        throw new \Exception(
            $message,
            \Object\Declarations::ERROR_STRICT_MAGIC_FUNCTION_CALL_CODE
        );
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