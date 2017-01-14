<?php

namespace Cli\Controller;

use Object\Model\Object;
use Cli\Model\Cli as CliModel;
use Cli\Model\Hub;

class Cli extends Object
{
    const ERROR_INVALID_COMMAND_CODE = '401';
    const ERROR_INVALID_COMMAND = 'Command %s does not exist';

    /**
     * @var \Cli\Model\Cli
     */
    protected $cli;

    /**
     * @var \Cli\Model\Hub
     */
    protected $hub;

    /**
     * Cli constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->cli = $this->getInstance(CliModel::class);
        $this->hub = $this->getInstance(Hub::class);
    }

    /**
     * Main entry point for CLI commands.
     * If no command is provided, this will print a welcome message
     * and print a list of available commands and their descriptions.
     *
     * @throws \Exception
     */
    public function run()
    {
        global $argv;

        $commands = $this->hub->getCommands();

        /**
         * If no command is provided,
         * just give the list  of commands.
         */
        if (!isset($argv[1])) {
            $this->cli->writeLine('Welcome!');
            foreach ($commands as $name => $command) {
                $this->cli->writeLine($name . ' - ' . $command['description']);
            }
            return;
        }

        /**
         * If a non-existent command is provided, throw an exception.
         */
        if (!isset($commands[$argv[1]])) {
            throw new \Exception(
                sprintf(
                    self::ERROR_INVALID_COMMAND,
                    $argv[1]
                ),
                self::ERROR_INVALID_COMMAND_CODE
            );
        }

        /**
         * If the command seems to exist, try to call it.
         */
        $class = $this->create($commands[$argv[1]]['class']);
        $class->run();
    }

}
