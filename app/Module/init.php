<?php

use Cli\Model\Hub;
use Object\Model\Di;

/**
 * In the Hub, we can add a command, this is done in the syntax:
 * Hub::addCommand($name, $classReference, $description)
 */
Hub::addCommand('example', Module\Command\ExampleCommand::class, 'An example command');

/**
 * In the Di, we can rewrite a class to a different class. This is done in the syntax:
 * Di::rewrite($fromClass, $toClass);
 */
Di::rewrite(Module\Model\RewrittenModel::class, Module\Model\Model::class);

/**
 * In the Di, we can add addon classes to any class we want.
 * This should be used with caution, but can be used to create separate
 * modules with each their own additions to a specific model.
 */
Di::setAddon(Module\Model\Model::class, Module\Addon\Addon::class);
