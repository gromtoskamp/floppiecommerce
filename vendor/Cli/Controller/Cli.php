<?php

namespace Cli\Controller;

use Object\Model\Object;
use Cli\Model\Cli as CliModel;

class Cli extends Object
{
    /**
     * @var \Cli\Model\Cli
     */
    protected $cli;

    /**
     * Cli constructor.
     */
    public function __construct(
    ) {
        parent::__construct();
        $this->cli = $this->getInstance(CliModel::class);
    }

    public function run()
    {
        
    }

}
