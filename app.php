<?php

/**
 * Welcome to Floppiecommerce!
 *
 * Damn Magento, I'll make my own E-commerce platform, with blackjack and API's!
 *
 * TODO: make a module control system.
 * TODO: make CLI tester.
 * TODO: create installation.
 * TODO: create database connection.
 *  - shitty version: serialized arrays in files.
 * TODO: create basic object.
 * TODO: create caching - filebased initially.
 * TODO: create overwrite functionality.
 * TODO: create bender easter egg.
 *
 * DONE:
 *  - create a bootup. App::__construct starts the application.
 *  - create an Autoloader.
 *      - autoloader.php.
 *  - create dependency injection.
 *      - Object\Model\ObjectManager handles getting of new object and singleton objects.
 *  - create a router
 *      - now router.php. TODO: move this to a separate module.
 */

/**
 * Bootup:
 *  - Find registered modules.
 *      - in vendor folder.
 *      - find and require all init.php files.
 *  - Check versions
 */

require_once './autoload.php';
require_once './router.php';

$app = new App();

/**
 * Class App
 */
class App
{

    /**
     * App constructor.
     */
    public function __construct()
    {
        $router = new Router;
        $router->route();
        echo '<pre>';
        print_r(new \Object\Model\Object);
    }
}

