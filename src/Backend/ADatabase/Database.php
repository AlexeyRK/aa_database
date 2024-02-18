<?php

namespace Backend\ADatabase;

class Database
{
    protected static $instance = null;

    private $database;

    public function get()
    {
        return $this->database;
    }

    public function __construct($db_user, $db_password, $db_host, $db_name, $table_prefix)
    {

        $this->database = new Connection();
        $this->database->connect($db_user, $db_password, $db_host, $db_name, array('table_prefix' => $table_prefix));
    }

    public static function instance()
    {
        return self::$instance;
    }


    public static function init($db_user, $db_password, $db_host, $db_name, $table_prefix)
    {
        self::$instance = new Database($db_user, $db_password, $db_host, $db_name, $table_prefix);
    }
}

