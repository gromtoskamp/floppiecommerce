<?php

/**
 * Browse through all ./vendor/ directories and load in the init.php files.
 * TODO: handle non-vendor folders.
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

/**
 * Scan through all module folders
 */
foreach (scandir($vendorDir) as $module) {
    $moduleDir = $vendorDir . $module . '/';

    /**
     * If the folder is not . or .. , check if it has an init file.
     * file_exists also executes the file, therefore no require_once is needed.
     * This is because of the autoloader.
     */
    if ((strpos($module, '.') !== 0) && is_dir($moduleDir)) {
        $initFilePath = $moduleDir . 'init.php';
        file_exists($initFilePath);
    }
}