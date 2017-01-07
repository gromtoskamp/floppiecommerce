<?php

if (isset($_SERVER['REMOTE_ADDR'])) {
    //TODO: handle this nicely.
    die('only accessible through cli');
}

require_once './init.php';
require_once './autoload.php';

require_once 'vendor/Cli/Controller/Cli.php';

$cli = new \Cli\Controller\Cli();
$cli->run();