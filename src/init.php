<?php

require_once 'Backend/ADatabase/Connection.php';
require_once 'Backend/ADatabase/Database.php';
require_once 'Backend/ADatabase/Mysqli.php';

/** @var array $config */
\Backend\ADatabase\Database::init($config['db_user'], $config['db_password'], $config['db_host'], $config['db_name'], $config['table_prefix']);

require_once 'functions.php';

