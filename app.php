<?php

/**
 * Class App
 */
class App
{

    private static $registry = array();

    public static $debug = true;

     /**
     * App constructor.
     */
    public function __construct()
    {
        $this->bootup();
    }

    public function run()
    {
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
     * Register a value (possibly used for rewrites), to be later called back.
     * These values persist throughout the entire app, but are lost once a call is completed.
     *
     * TODO: create a module for this.
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

    /**
     * Gather init files and load these up, allowing modules to register themselves.
     */
    public function getInitFiles()
    {
        require_once './init.php';
    }
}

