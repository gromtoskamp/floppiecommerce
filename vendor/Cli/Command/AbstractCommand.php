<?php

namespace Cli\Command;
use Cli\Model\Cli;

abstract class AbstractCommand extends Cli
{
    public abstract function run();
}