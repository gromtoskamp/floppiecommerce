<?php

namespace Db\Model;

use Object\Model\Object;

class Connection extends Object
{
    /**
     * @var \SQLite3
     */
    public $db;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * Instantiate Database on the object.
         */
        $this->db = new \SQLite3(__DIR__ . '/../sqlite3.db');
    }

    /**
     * Call a query.
     * Will take a raw string or a Query object.
     *
     * @param $query
     * @return \SQLite3Result
     */
    public function query($query)
    {
        return $this->db->query($query);
    }

    /**
     * @param $query
     * @return $this
     */
    public function exec($query)
    {
        $this->db->exec((string) $query);
        return $this;
    }

    /**
     * @param array $options
     * @param array $default
     */
    public function createTable($options = array(), $default = array(
        'temporary' => false,
        'if_not_exists' => true,
        'schema_name' => null,
        'table_name' => null,
        'columns' => array(
            'name' => array(
                'type' => null,
                'index' => array(
                    'primary_key' => false,
                    'autoincrement' => false,
                    'not_null' => false,
                    'default' => null
                )
            )
        )
    ))
    {

    }

}