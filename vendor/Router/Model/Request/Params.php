<?php

namespace Router\Model\Request;

class Params extends \Router\Model\Request
{
    public function __construct()
    {
        //TODO: REMOVE THIS
        echo '<pre>';
        print_r(array(
            func_get_args()
        ));
        echo 'test';
        exit;
    }
}