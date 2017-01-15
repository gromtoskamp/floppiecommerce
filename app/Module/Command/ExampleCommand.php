<?php

namespace Module\Command;

use Cli\Command\AbstractCommand;
use Module\Model\RewrittenModel;

class ExampleCommand extends AbstractCommand
{
    public function run()
    {
        /**
         * Use writeLine to write to CLI output
         * and end with a newline.
         */
        $this->writeLine('This is an example command');

        /**
         * We call RewrittenModel,
         * but we get Model.
         *
         * This is because, in init.php, we rewrote
         * RewrittenModel to Model.
         */
        $class = $this->create(RewrittenModel::class);
        $this->writeLine(
            get_class($class)
        );

        $this->writeLine(
            $class->addonFunction()
        );
    }
}
