<?php

/**
 * Browse through all ./vendor/ directories and load in the init.php files.
 */
$vendorDir = './vendor/';

/**
 * Set error reporting
 */
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('display_errors', '1');

/**
 * Define global values
 */
define('DS', DIRECTORY_SEPARATOR);
define('BASEDIR', realpath(__DIR__));
define('VENDOR', realpath(__DIR__ . DS . 'vendor'));
define('APP', realpath(__DIR__ . DS . 'app'));

/**
 * Include all init.php files
 */
foreach (glob('*' . DS . '*' . DS . 'init.php') as $initFile) {
    include $initFile;
}