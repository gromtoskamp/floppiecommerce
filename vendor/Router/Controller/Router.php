<?php

namespace Router\Controller;

use Object\Model\ObjectManager;
use Router\Model\Request;

/**
 * Class Router
 *
 * TODO: add 404 route.
 * TODO: add SOAP.
 * TODO: refactor routes to take params directly.
 *
 */
class Router extends \Object\Model\Object
{

    /**
     * TODO: decide what to do with this.
     *  This could be used for debugging.
     *  This could be used for a general developer/production mode switch.
     *  This could be removed altogether.
     *
     * @var bool
     */
    protected $debugRoute = true;

    public $request;

    /**
     * Routing function.
     *
     * Currently only works with GET, POST, PUT, DELETE.
     *
     * TODO: Pass request object to the controller.
     * TODO: return a response object.
     */
    public function route()
    {
        $request = $this->getInstance(Request::class);

        /**
         * Homepage routing.
         */
        if ($_SERVER['REQUEST_URI'] == '/') {
            $this->redirectHomePage();
        }

        /**
         * Get the URI and explode it from the base url, leaving only usable parts.
         */
        $uri        = explode('/', $_SERVER['REQUEST_URI']);
        $module     = ucfirst($uri[1]);
        $controller = ucfirst(isset($uri[2]) ? $uri[2] : 'index');
        $action     = ucfirst(isset($uri[3]) ? $uri[3] : 'index');

        /**
         * Load the controller class and call the action on this class.
         */
        try {
            $controller = $this->getController($module, $controller);
            $controller->$action();
        } catch (\Exception $e) {
            /**
             * If we get a class_not_found exception, redirect to 404.
             */
            if($e->getCode() == ObjectManager::ERROR_CLASS_NOT_FOUND_CODE) {
                print_r('TODO: CREATE A SPECIFIC EXCEPTION');
                if (!$this->debugRoute) {
                    $this->redirect404();
                }
            }

            /**
             * If the exception is something other than class_not_found, just pass it along.
             */
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = $this->getSingleton('\Router\Model\Request');
        }

        return $this->request;
    }

    /**
     *
     */
    public function getController($module, $controller)
    {
        return $this->getSingleton("$module\\Controller\\$controller");
    }

    /**
     * Simple redirect to homepage function.
     */
    public function redirectHomePage()
    {
        //TODO: actually make this.
        exit;
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