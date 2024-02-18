<?php

use Backend\ADatabase\Database;

/**
 * Execute query and format result as associative array with column names as keys
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_array()
{
    return call_user_func_array(array(Database::instance()->get(), 'getArray'), func_get_args());
}

/**
 * Execute query and format result as associative array with column names as keys and index as defined field
 *
 * @param string $query unparsed query
 * @param string $field field for array index
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_hash_array()
{
    return call_user_func_array(array(Database::instance()->get(), 'getHash'), func_get_args());
}

/**
 * Execute query and format result as associative array with column names as keys and then return first element of this array
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_row()
{
    return call_user_func_array(array(Database::instance()->get(), 'getRow'), func_get_args());
}

/**
 * Execute query and returns first field from the result
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return string structured data
 */
function db_get_field()
{
    $r = call_user_func_array(array(Database::instance()->get(), 'getField'), func_get_args());

    return $r;
}

/**
 * Execute query and format result as set of first column from all rows
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_fields()
{
    return call_user_func_array(array(Database::instance()->get(), 'getColumn'), func_get_args());
}

/**
 * Execute query and format result as one of: field => array(field_2 => value), field => array(field_2 => row_data), field => array([n] => row_data)
 *
 * @param string $query unparsed query
 * @param array $params array with 3 elements (field, field_2, value)
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_hash_multi_array()
{
    return call_user_func_array(array(Database::instance()->get(), 'getMultiHash'), func_get_args());
}

/**
 * Execute query and format result as key => value array
 *
 * @param string $query unparsed query
 * @param array $params array with 2 elements (key, value)
 * @param mixed ... unlimited number of variables for placeholders
 * @return array structured data
 */
function db_get_hash_single_array()
{
    return call_user_func_array(array(Database::instance()->get(), 'getSingleHash'), func_get_args());
}

/**
 *
 * Prepare data and execute REPLACE INTO query to DB
 * If one of $data element is null function unsets it before querry
 *
 * @param string $table Name of table that condition generated. Must be in SQL notation without placeholder.
 * @param array $data Array of key=>value data of fields need to insert/update
 * @return array
 */
function db_replace_into($table, $data)
{
    return call_user_func_array(array(Database::instance()->get(), 'replaceInto'), func_get_args());
}

/**
 * Execute query
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return mixed result set or the ID generated for an AUTO_INCREMENT field for insert statement
 */
function db_query()
{
    return call_user_func_array(array(Database::instance()->get(), 'query'), func_get_args());
}

/**
 * Parse query and replace placeholders with data
 *
 * @param string $query unparsed query
 * @param mixed ... unlimited number of variables for placeholders
 * @return string parsed query
 */
function db_quote()
{
    return call_user_func_array(array(Database::instance()->get(), 'quote'), func_get_args());
}

/**
 * Parse query and replace placeholders with data
 *
 * @param string $query unparsed query
 * @param array $data data for placeholders
 * @return string parsed query
 */
function db_process()
{
    return call_user_func_array(array(Database::instance()->get(), 'process'), func_get_args());
}

/**
 * Get column names from table
 *
 * @param string $table_name table name
 * @param array $exclude optional array with fields to exclude from result
 * @param bool $wrap_quote optional parameter, if true, the fields will be enclosed in quotation marks
 * @return array columns array
 */
function fn_get_table_fields()
{
    return call_user_func_array(array(Database::instance()->get(), 'getTableFields'), func_get_args());
}

/**
 * Check if passed data corresponds columns in table and remove unnecessary data
 *
 * @param array $data data for compare
 * @param array $table_name table name
 * @return mixed array with filtered data or false if fails
 */
function fn_check_table_fields()
{
    return call_user_func_array(array(Database::instance()->get(), 'checkTableFields'), func_get_args());
}

/**
 * Remove value from set (e.g. remove 2 from "1,2,3" results in "1,3")
 *
 * @param string $field table field with set
 * @param string $value value to remove
 * @return string database construction for removing value from set
 */
function fn_remove_from_set($field, $value)
{
    return Database::instance()->get()->quote("TRIM(BOTH ',' FROM REPLACE(CONCAT(',', $field, ','), CONCAT(',', ?s, ','), ','))", $value);
}

/**
 * Add value to set (e.g. add 2 from "1,3" results in "1,3,2")
 *
 * @param string $field table field with set
 * @param string $value value to add
 * @return string database construction for add value to set
 */
function fn_add_to_set($field, $value)
{
    return Database::instance()->get()->quote("TRIM(BOTH ',' FROM CONCAT_WS(',', ?p, ?s))", fn_remove_from_set($field, $value), $value);
}

/**
 * Create set from php array
 *
 * @param array $set_data values array
 * @return string database construction for creating set
 */
function fn_create_set($set_data = array())
{
    return empty($set_data) ? '' : implode(',', $set_data);
}

function fn_find_array_in_set($arr, $set, $find_empty = false)
{
    $conditions = array();
    if ($find_empty) {
        $conditions[] = "$set = ''";
    }
    if (!empty($arr)) {
        foreach ($arr as $val) {
            $conditions[] = Database::instance()->get()->quote("FIND_IN_SET(?i, $set)", $val);
        }
    }

    return empty($conditions) ? '' : implode(' OR ', $conditions);
}

/**
 * Connect to database server and select database
 *
 * @param string $host database host
 * @param string $user database user
 * @param string $password database password
 * @param string $name database name
 * @param array $params additional connection parameters (name, table prefix)
 * @return resource database connection identifier, false if error occurred
 */
function db_initiate($host, $user, $password, $name, $params = array())
{
    $is_connected = Database::instance()->get()->connect($user, $password, $host, $name, $params);

    if ($is_connected) {
        return true;
    }

    return false;
}

/**
 * Change default connect to $dbc_name
 *
 * @param array $params Params for database connection
 * @param string $name Database name
 * @return bool True on success false otherwise
 */
function db_connect_to($params, $name)
{
    return Database::instance()->get()->changeDb($name, $params);
}

/**
 * Get the number of found rows from the last query
 *
 */
function db_get_found_rows()
{
    return Database::instance()->get()->getField("SELECT FOUND_ROWS()");
}


/**
 * Sort query results
 *
 * @param array $params sort params
 * @param array $sortings available sortings
 * @param string $default_by default sort field
 * @param string $default_by default order
 * @return string SQL substring
 */
function db_sort(&$params, $sortings, $default_by = '', $default_order = '')
{
    $directions = array(
        'asc' => 'desc',
        'desc' => 'asc',
        'descasc' => 'ascdesc', // when sorting by 2 fields
        'ascdesc' => 'descasc' // when sorting by 2 fields
    );

    if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {
        $params['sort_order'] = $default_order;
    }

    if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {
        $params['sort_by'] = $default_by;
    }

    if (!empty($directions[$params['sort_order']])) {
        $params['sort_order_rev'] = $directions[$params['sort_order']];
    }

    if (is_array($sortings[$params['sort_by']])) {
        if ($params['sort_order'] == 'descasc') {
            $order = implode(' desc, ', $sortings[$params['sort_by']]) . ' asc';
        } elseif ($params['sort_order'] == 'ascdesc') {
            $order = implode(' asc, ', $sortings[$params['sort_by']]) . ' desc';
        } else {
            $order = implode(' ' . $params['sort_order'] . ', ', $sortings[$params['sort_by']]) . ' ' . $params['sort_order'];
        }
    } else {
        $order = $sortings[$params['sort_by']] . ' ' . $params['sort_order'];
    }

    return ' ORDER BY ' . $order;
}

/**
 * Paginate query results
 *
 * @param int $page page number
 * @param int $items_per_page items per page
 * @return string SQL substring
 */
function db_paginate(&$page, &$items_per_page, $total_items = 0)
{
    $page = (int)$page;
    $items_per_page = (int)$items_per_page;

    if ($page <= 0) {
        $page = 1;
    }

    if ($items_per_page <= 0) {
        $items_per_page = 100;
    }

    // Check if page in valid limits
    if ($total_items > 0) {
        $page = db_get_valid_page($page, $items_per_page, $total_items);
    }

    return ' LIMIT ' . (($page - 1) * $items_per_page) . ', ' . $items_per_page;
}

function db_get_valid_page($page, $items_per_page, $total_items)
{
    if (($page - 1) * $items_per_page >= $total_items) {
        $page = ceil($total_items / $items_per_page);
    }

    return empty($page) ? 1 : $page;
}

/**
 * Check if the table exists in the database
 *
 * @param string $table_name Table name
 * @param bool $set_prefix Set prefix before check
 * @return bool
 */
function db_has_table($table_name, $set_prefix = true)
{
    return Database::instance()->get()->hasTable($table_name, $set_prefix);
}