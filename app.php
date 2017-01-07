<?php

/**
 * Welcome to Floppiecommerce!
 *
 * Damn Magento, I'll make my own E-commerce platform, with blackjack and API's!
 *
 * TODO: parse request params from URL.
 * TODO: make a module control system.
 *  - config.json files to declare, gets smashed together and validated
 *  - add dependencies in modulecontrol module, to make sure all required modules are active.
 * TODO: make CLI.
 * TODO: create installation.
 * TODO: create caching - filebased initially.
 * TODO: create overwrite/rewrite functionality.
 *  - rewrite done. Needs to be expanded with before and after functions.
 * TODO: create bender easter egg.
 * TODO: A/B tester!
 * TODO: Search bar that can search through categories/subcategories.
 * TODO: create logger.
 * TODO: Health module - benchmark/testing van essentiele onderdelen.
 * TODO: Integrated monitoring.
 *
 * DONE:
 *  - Create a simple database.
 *  - create a bootup. App::__construct starts the application.
 *  - create an Autoloader.
 *      - autoloader.php.
 *  - create dependency injection.
 *      - Object\Model\ObjectManager handles getting of new object and singleton objects.
 *  - create a router
 *      - now router.php. TODO: possibly move this to a separate module.
 */

/**
 * Bootup:
 *  - Find registered modules.
 *      - in vendor folder.
 *      - find and require all init.php files.
 *  - Check versions
 */

require_once './autoload.php';
require_once './init.php';

$app = new App();

/**
 * Class App
 */
class App
{

    private static $registry = array();

    private static $debugger = true;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->bootup();

        /**
         * Send the request to the router.
         */
        $router = new \Router\Controller\Router;
        try {
            $router->route();
        } catch (\Exception $e) {
            //TODO: Create an actual general exception handler.
            echo '<pre>';
            print_r($e);
            exit;
        }
    }

    private function bootup()
    {
        /**
         * Gather module init.php files.
         */
        $this->getInitFiles();
    }

    /**
     * Get debug mode.
     *
     * @return bool
     */
    public static function debugger()
    {
        return self::$debugger;
    }

    /**
     * Set debug mode on the fly.
     *
     * @param $mode
     */
    public static function setDebugger($mode)
    {
        self::$debugger = (bool) $mode;
    }

    /**
     * Register a value (possibly used for rewrites), to be later called back.
     * These values persist throughout the entire app, but are lost once a call is completed.
     *
     * TODO: create a module for this.
     * TODO: decide what to do with overwrites vs registry.
     *
     * @param $key
     * @param $arg
     */
    public static function register($key, $arg)
    {
        self::$registry[$key] = $arg;
    }

    /**
     * Returns the registry value, or false if not set.
     *
     * TODO: registry module?
     *
     * @param $key
     * @return bool
     */
    public static function registry($key)
    {
        return isset(self::$registry[$key]) ? self::$registry[$key] : false;
    }

    public static function rewrite($from, $to)
    {
        \Object\Model\ObjectManager::setRewrite($from, $to);
    }

    /**
     * Gather init files and load these up, allowing modules to register themselves.
     */
    public function getInitFiles()
    {
        require_once './init.php';
    }
}

