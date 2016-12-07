<?php

/**
 * Browse through all ./vendor/ directories and load in the init.php files.
 * TODO: handle non-vendor folders.
 */
$vendorDir = './vendor/';

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