<?php

spl_autoload_register( 'autoload' );

/**
 * autoload
 *
 * @author Tom Groskamp <Tom.Groskamp@gmail.com>
 * @param $class
 * @param null $dir
 * @throws Exception
 */
function autoload( $class, $dir = null )
{
    $roots = [VENDOR, APP, BASEDIR];
    foreach ($roots as $root) {
        $path = $root . DS . str_replace('\\', '/', $class) . '.php';
        if(file_exists($path)) {
            include $path;
            return;
        }

    }

    /**
     * If no file has been found,
     * throw an Exception.
     */
    $roots = implode (', ', $roots);
    throw new \Exception(
        "Autoloader could not find class '$class' in roots '$roots'"
    );
}