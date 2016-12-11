<?php

namespace Db\Model;

use Object\Model\Object;

class Connection extends Object
{
    /**
     * @var \SQLite3
     */
    protected $db;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $db = new \SQLite3('sqlite3.db');

        $result = $db->query('SELECT bar FROM foo');
        var_dump($result->fetchArray());
    }
}