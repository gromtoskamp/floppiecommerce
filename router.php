<?php

/**
 * Class Router
 *
 * TODO: move this to router module.
 * TODO: add 404 route.
 */
class Router extends \Object\Model\Object
{

    /**
     * Routing function.
     *
     * TODO: add exception handling.
     */
    public function route()
    {
        /**
         * Get the URI and explode it from the base url, leaving only usable parts.
         */
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $module = ucfirst($uri[1]);
        $file = ucfirst($uri[2]);
        $action = ucfirst(isset($uri[3]) ? $uri[3] : 'index');

        /**
         * Load the controller class and call the action on this class.
         */
        try {
            $controller = $this->getSingleton("$module\\Controller\\$file");
            $controller->$action();
        } catch (Exception $e) {
            /**
             * If we get a class_not_found exception, redirect to 404.
             */
            if($e->getCode() == Object\Model\ObjectManager::ERRORCODE_CLASS_NOT_FOUND) {
                $this->redirect404();
            }
            throw new Exception($e);
        }
    }

    /**
     * Give a 404 header and die, hard.
     */
    public function redirect404()
    {
        header("HTTP/1.0 404 Not Found");
        exit;
    }

}