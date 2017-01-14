<?php

namespace Object\Model;
use Reflection\Model\Reflection;

class Di
{
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
     * Double addon function name.
     */
    const ERROR_DOUBLE_ADDON_CODE = '102';
    const ERROR_DOUBLE_ADDON = 'Double addon function name found for function %s in class %s';

    private static $addons = [];

    private static $rewrites = array();

    //TODO: centralize debug.
    private static $debug = true;

    /**
     * @param $base
     * @param $addon
     * @throws \Exception
     */
    public static function setAddon($base, $addon)
    {
        $base = self::parseClassname($base);
        if (self::$debug) {
            self::validateAddon($base, $addon);
        }
        self::$addons[$base][] = $addon;
    }

    /**
     * I know its ugly alright?
     *
     * Checks for the base class, the addon class,
     * and the previously set addons for the base class
     * if any of their functions have the same name,
     * which is something you really dont want.
     *
     * @param $base
     * @param $addon
     * @throws \Exception
     */
    public static function validateAddon($base, $addon)
    {
        $classes = array_merge([$base, $addon], self::getAddons($base));
        foreach ($classes as $class) {
            $class = new Reflection($class);
            $methodNames = $class->getClassMethodNames();

            foreach ($methodNames as $methodName) {
                if(isset($allMethods[$methodName])) {
                    throw new \Exception(
                        sprintf(self::ERROR_DOUBLE_ADDON, $methodName, $class->name),
                        self::ERROR_DOUBLE_ADDON_CODE
                    );
                }
                $allMethods[$methodName] = true;
            }
        }
    }

    /**
     * Returns all addons for a given class,
     * or an empty array if none are found.
     *
     * @param $className
     * @return array
     */
    public static function getAddons($className)
    {
        return isset(self::$addons[$className])
            ? self::$addons[$className]
            : [];
    }

    /**
     * Sets a rewrite in the static $rewrites array.
     * This method is static, to make sure that rewrites can be added from init.php files.
     *
     * @param string $from (\Namespace\Path\To\Original)
     * @param string $to (\Namespace\Path\To\Rewrite)
     * @throws \Exception in case of a double rewrite.
     */
    public static function rewrite($from, $to)
    {
        $from = self::parseClassname($from);

        /**
         * If a conflicting rewrite is found, throw an exception informing the user.
         */
        if (isset(self::$rewrites[$from])) {
            throw new \Exception(
                sprintf(
                    self::ERROR_DOUBLE_REWRITE,
                    $from,
                    $to,
                    self::$rewrites[$from]),
                self::ERROR_DOUBLE_REWRITE_CODE
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
     * @return string | null
     */
    public static function getRewrite($from)
    {
        if (isset(self::$rewrites[$from])) {
            return self::$rewrites[$from];
        }

        return null;
    }

    /**
     * Parses a class name and sorts the rewrites out.
     *
     * @param $class
     * @return null|string
     * @throws \Exception
     */
    public static function parseClassname($class)
    {
        /**
         * Namespaces should be defined with a starting '\'.
         *
         * If this is not provided,
         * add it to the namespace for convenience and singleton persistence.
         */
        if (substr($class, 0, 1) == '\\') {
            $class = substr($class, 1);
        }

        /**
         * Check if a rewrite is defined and return the rewritten namespace if found.
         */
        $class = self::getRewrite($class) ? self::getRewrite($class) : $class;

        /**
         * If the parsed class does not exist, throw an exception to inform the user of this mishap.
         */
        if (!class_exists($class)) {
            throw new \Exception(
                sprintf(self::ERROR_CLASS_NOT_FOUND, $class),
                self::ERROR_CLASS_NOT_FOUND_CODE
            );
        }

        return $class;
    }

}