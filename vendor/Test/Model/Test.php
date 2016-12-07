<?php

namespace Test\Model;

class Test extends \Object\Model\Test
{

    public $test = 'hallo ik ben een test';

    public function __construct()
    {
        parent::__construct();
        $this->getNew(\Test\Model\Test2);
    }

}