<?php

namespace Cli\Controller;

use Object\Model\Object;
use Cli\Model\Cli as CliModel;
use Cli\Command\Hub;

class Cli extends Object
{
    /**
     * @var \Cli\Model\Cli
     */
    protected $cli;

    /**
     * @var \Cli\Command\Hub
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

    public function run()
    {
        $this->cli->writeLine('Welcome!');

        //TODO: make this work.
        $this->hub->scan();
    }

}
