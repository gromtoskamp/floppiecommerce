<?php

namespace Router\Controller;

/**
 * Class Router
 *
 * TODO: add 404 route.
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

    /**
     * Routing function.
     *
     * Currently only works with GET.
     *
     * TODO: make this work with POST, PUT, and DELETE.
     * TODO: create a request object that will be passed to the actual controller.
     * TODO: return a response object.
     *  TODO: determine if the response object should be the View part of the MVC pattern.
     */
    public function route()
    {
        echo '<pre>';
        print_r($this->getNew('\Router\Model\Request'));
        exit;

        /**
         * Homepage routing.
         */
        if ($_SERVER['REQUEST_URI'] == '/') {
            $this->redirectHomePage();
        }

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
        } catch (\Exception $e) {
            /**
             * If we get a class_not_found exception, redirect to 404.
             */
            if($e->getCode() == \Object\Declarations::ERROR_CLASS_NOT_FOUND_CODE) {
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