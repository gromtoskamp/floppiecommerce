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
     * Class not found.
     */
    const ERROR_OBJECT_CLASS_NOT_FOUND_CODE = '100';
    const ERROR_OBJECT_CLASS_NOT_FOUND_MESSAGE = 'Class %s does not exist!';

    /**
     * Double rewrite.
     */
    const ERROR_OBJECT_DOUBLE_REWRITE_CODE = '101';
    const ERROR_OBJECT_DOUBLE_REWRITE_MESSAGE = 'Double rewrite found for object %s! Rerwrite requested: %s, Rewrite found: %s.';

}