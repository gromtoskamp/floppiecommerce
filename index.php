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
$app->run();