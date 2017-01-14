<?php

namespace Cli\Model;

use Object\Model\Object;
use Filemanager\Model\FileManager;

class Hub extends Object
{
    const ERROR_DOUBLE_COMMAND_CODE = '400';
    const ERROR_DOUBLE_COMMAND = 'Command with name \'%s\' already registered. class: \'%s\', description: \'%s\'';

    /**
     * @var \FileManager\Model\FileManager
     */
    protected $fileManager;

    /**
     * @var array
     */
    private static $commands = [];

    /**
     * Hub constructor.
     */
    public function __construct(
    ) {
        parent::__construct();
        $this->fileManager = $this->getInstance(FileManager::class);
    }

    /**
     * Returns an array of registered commands.
     *
     * Commands are formatted as
     *  [
     *      'name'        => $name,
     *      'description' => $description
     *  ]
     */
    public function getCommands()
    {
        return self::$commands;
    }

    /**
     * Adds a command to the static list of commands.
     *
     * @param $name
     * @param $class
     * @param $description
     * @throws \Exception
     */
    public static function addCommand($name, $class, $description)
    {
        /**
         * If there is already a command set under that name,
         * throw an exception.
         */
        if (isset(self::$commands[$name])) {
            throw new \Exception(
                sprintf(
                    self::ERROR_DOUBLE_COMMAND,
                    $name,
                    self::$commands[$name]['class'],
                    self::$commands[$name]['description']
                ),
                self::ERROR_DOUBLE_COMMAND_CODE
            );
        }

        /**
         * If the command is not double, set it in the list.
         */
        self::$commands[$name] = [
            'class'       => $class,
            'description' => $description
        ];
    }
}
