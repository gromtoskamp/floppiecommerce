<?php

namespace Module\Model;

class RewrittenModel
{
    /**
     * This will never be called,
     * since RewrittenModel is overwritten in init.php
     *
     * RewrittenModel constructor.
     */
    public function __construct()
    {
        die('we won\'t get here anyway');
    }
}