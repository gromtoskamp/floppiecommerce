<?php

namespace Reflection\Model;

class Reflection extends \ReflectionClass
{
    /**
     * Gets only the methods which are set specifically on the class,
     * rather than all inherited methods.
     *
     * @return array
     */
    public function getClassMethods()
    {
        return array_filter(
            $this->getMethods(),
            function($method) {
                return ($this->name == $method->class);
            }
        );
    }

    /**
     * Gets the names of the methods which are set specifically
     * on the class, rather than all inherited methods.
     *
     * @return array
     */
    public function getClassMethodNames()
    {
        return array_map(
            function($method) {
                return $method->name;
            },
            $this->getClassMethods()
        );
    }
}