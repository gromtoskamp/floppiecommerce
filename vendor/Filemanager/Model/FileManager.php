<?php

namespace Filemanager\Model;

use Object\Model\Object;

class FileManager extends Object
{
    /**
     * 300
     * File not found.
     */
    const ERROR_NO_FILE_FOUND_CODE = '300';
    const ERROR_NO_FILE_FOUND = 'No file found while searching with path %s';

    /**
     * Tries to get files according to a provided path.
     * If a file extension (or wildcard file extension) is provided,
     * this will throw an exception if no results can be found.
     *
     * Syntax:
     *  'folder/subfolder' for direct path - returns all files in the folder
     *  'folder/file.extension' for direct path - returns the exact file if possible
     *  ' * /subfolder' (without space) - * is wildcard, matching any folder.
     *
     * @param $path
     * @param string $root
     * @return array
     * @throws \Exception
     */
    public function getFilesFromPath($path, $root = VENDOR)
    {
        /**
         * Check if there is a file in the path,
         * for different behaviour.
         */
        $pathArray = explode('/', $path);
        $fileInPath = strpos(array_pop($pathArray), '.') !== false;

        /**
         * If there is no file in the path. Imply wildcard '*.*';
         */
        if(!$fileInPath) {
            $l = strlen($path);
            /** add connecting '/' if necessary */
            $path .= substr($path, $l-1, $l) != '/'
                ? '/*.*'
                : '*.*';
        }

        /**
         * Collect result.
         * If a specific file (or wildcard) was requested,
         * throw an exception.
         */
        $result = glob($root . DS . $path);
        if (!$result && !$fileInPath) {
            throw new \Exception(
                sprintf(
                    self::ERROR_NO_FILE_FOUND,
                    $path
                ),
                self::ERROR_NO_FILE_FOUND_CODE
            );
        }

        return $result;
    }

    /**
     * Wrapper function of getFilesFromPath to always return
     * the first result in the list of results.
     *
     * @param $path
     * @param string $root
     */
    public function getFileFromPath($path, $root = VENDOR)
    {
        return $this->getFilesFromPath($path, $root)[0];
    }
}
