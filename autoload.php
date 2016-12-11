<?php

spl_autoload_register( 'autoload' );

/**
 * autoload
 *
 * @author Tom Groskamp <Tom.Groskamp@gmail.com>
 * @param  string $class
 * @param  string $dir
 * @return bool
 *
 * TODO: handle when ./vendor/ is not the basedir.
 *
 */
function autoload( $class, $dir = null )
{
    /**
     * Basedir to look through
     */
    $basedir = './vendor/';

    if ( is_null( $dir ) )
        $dir = $basedir;


    foreach (scandir($dir) as $file) {
        /**
         * If this is a directory, check recursively for files.
         */
        if (is_dir($dir . $file)
            && substr($file, 0, 1) !== '.') {
            autoload($class, $dir . $file . '/');
        }

        /**
         * If this is a php file, check if it is the class required and autoload it.
         */
        if (substr($file, 0, 2) !== '._' && preg_match("/.php$/i", $file)) {

            /**
             * Strip './vendor/' from the path and replace / with \ to get namespace-like dirnames.
             */
            $subdir = str_replace('/', '\\', str_replace($basedir, '', $dir));

            /**
             * Check if the name of the found class/path is the same as the requested namespace. If so, include it.
             */
            if (strcasecmp($subdir . str_replace('.php', '', $file), $class) == 0||
                strcasecmp($subdir . str_replace('.class.php', '', $file), $class) == 0) {

                include $dir . $file;
            }
        }
    }
}