<?php

namespace Router\Model;

use Object\Model\Object;

class Request extends Object
{
    public function __construct()
    {
        print_r($this->getId());
        $this->setId('testId2');
        print_r($this->getId());
        $this->unsId();
        var_dump($this->getId());
        print_r($this->unsId(true));

        exit;
    }
}