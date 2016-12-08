<?php

namespace Object;

/**
 * Class Declarations
 *
 * Declaration classes are used as constant dictionaries throughout the entire module.
 * This ensures that alterations of paths, error codes, messages, etc can all be done from a single
 * file, and the entire module will listen to this change.
 *
 * @package Object
 */
class Declarations
{

    /************************************************************************************************************
     * Error codes
     ************************************************************************************************************/

    /**
     * 100
     * Class not found.
     */
    const ERROR_CLASS_NOT_FOUND_CODE = '100';
    const ERROR_CLASS_NOT_FOUND = 'Class %s does not exist!';

    /**
     * 101
     * Double rewrite.
     */
    const ERROR_DOUBLE_REWRITE_CODE = '101';
    const ERROR_DOUBLE_REWRITE = 'Double rewrite found for object %s! Rerwrite requested: %s, Rewrite found: %s.';

    /**
     * 102
     * Function not found.
     */
    const ERROR_UNDEFINED_FUNCTION_CODE = '102';
    const ERROR_UNDEFINED_FUNCTION = 'Function %s does not exist!';

    /**
     * 103
     * Magic method undefined.
     */
    const ERROR_MAGIC_METHOD_UNDEFINED_CODE = '103';
    const ERROR_MAGIC_METHOD_UNDEFINED = 'Magic method %s undefined!';

    /**
     * 104
     * Strict magic method failure.
     */
    const ERROR_STRICT_MAGIC_FUNCTION_CALL_CODE = '104';
    const ERROR_STRICT_MAGIC_FUNCTION = 'Strict magic function validation failed. Data index %s called, but not found! Did you mean one of the following indices?';

    /**
     * 105
     * Add value not an array.
     */
    const ERROR_ADD_VALUE_NOT_ARRAY_CODE = '105';
    const ERROR_ADD_VALUE_NOT_ARRAY = 'Value of %s is not an array!';

}
