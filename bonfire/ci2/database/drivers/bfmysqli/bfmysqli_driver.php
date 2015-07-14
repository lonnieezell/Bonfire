<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 */

/**
 * BfMySQLi Database Adapter Class
 *
 * Note: _DB is an extender class that the app controller
 * creates dynamically based on whether the active record
 * class is being used or not.
 *
 * This is a modified version of the MySQLi Database Adapter class.
 *
 * While all of the functions have been made public (because they previously did
 * not have their visibility set), the PHPDoc @access tags have been left in place
 * for those which were not public to indicate that their visibility may change
 * in future versions.
 *
 * @package     CodeIgniter
 * @subpackage  Drivers
 * @category    Database
 * @author      ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/database/
 */
class CI_DB_bfmysqli_driver extends CI_DB
{
    public $dbdriver = 'bfmysqli';

    // The character used for escaping
    public $_escape_char = '`';

    // clause and character used for LIKE escape sequences - not used in MySQL
    public $_like_escape_str = '';
    public $_like_escape_chr = '';

    /**
     * Whether to use the MySQL "delete hack" which allows the number
     * of affected rows to be shown. Uses a preg_replace when enabled,
     * adding a bit more processing to all queries.
     */
    public $delete_hack = true;

    /**
     * The syntax to count rows is slightly different across different
     * database engines, so this string appears in each driver and is
     * used for the count_all() and count_all_results() functions.
     */
    public $_count_string = 'SELECT COUNT(*) AS ';
    public $_random_keyword = ' RAND()'; // database specific random keyword

    // whether SET NAMES must be used to set the character set
    public $use_set_names;

    public $use_transaction_api;

    // -------------------------------------------------------------------------

    /**
     * Non-persistent database connection
     *
     * @access  private called by the base class
     *
     * @param boolean $persistent If true, create a persistent connection.
     *
     * @return resource
     */
    public function db_connect($persistent = false)
    {
        $hostName = null;
        $port     = null;
        $socket   = null;

        // Is this a socket path?
        if ($this->hostname[0] === '/') {
            $socket = $this->hostname;
        } else {
            $hostName = $persistent === true ? "p:{$this->hostname}" : $this->hostname;
            if (! empty($this->port)) {
                $port = (int) $this->port;
            }
        }

        return @mysqli_connect($hostName, $this->username, $this->password, $this->database, $port, $socket);
    }

    /**
     * Persistent database connection
     *
     * @access  private called by the base class
     * @return  resource
     */
    public function db_pconnect()
    {
        return $this->db_connect(true);
    }

    /**
     * Reconnect
     *
     * Keep / reestablish the db connection if no queries have been
     * sent for a length of time exceeding the server's idle timeout
     *
     * @return  void
     */
    public function reconnect()
    {
        if (mysqli_ping($this->conn_id) === false) {
            $this->conn_id = false;
        }
    }

    /**
     * Select the database
     *
     * @access  private called by the base class
     * @return  resource
     */
    public function db_select()
    {
        return @mysqli_select_db($this->conn_id, $this->database);
    }

    /**
     * Set client character set
     *
     * @access  private
     * @param   string
     * @param   string
     * @return  resource
     */
    public function _db_set_charset($charset, $collation)
    {
        if ($this->useSetNames()) {
            return @mysqli_query(
                $this->conn_id,
                "SET NAMES '" . $this->escape_str($charset) . "' COLLATE '" . $this->escape_str($collation) . "'"
            );
        }

        return @mysqli_set_charset($this->conn_id, $charset);
    }

    /**
     * Version number query string
     *
     * @return  string
     */
    public function _version()
    {
        return "SELECT version() AS ver";
    }

    /**
     * Execute the query
     *
     * @access  private called by the base class
     * @param   string  an SQL query
     * @return  resource
     */
    public function _execute($sql)
    {
        return @mysqli_query($this->conn_id, $this->_prep_query($sql));
    }

    /**
     * Prep the query
     *
     * If needed, each database adapter can prep the query string
     *
     * @access  private called by execute()
     * @param   string  an SQL query
     * @return  string
     */
    public function _prep_query($sql)
    {
        // "DELETE FROM TABLE" returns 0 affected rows This hack modifies
        // the query so that it returns the number of affected rows
        if ($this->delete_hack === true) {
            if (preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $sql)) {
                $sql = preg_replace("/^\s*DELETE\s+FROM\s+(\S+)\s*$/", "DELETE FROM \\1 WHERE 1=1", $sql);
            }
        }

        return $sql;
    }

    /**
     * Begin Transaction
     *
     * @return  bool
     */
    public function trans_begin($test_mode = false)
    {
        if (! $this->trans_enabled) {
            return true;
        }

        // When transactions are nested we only begin/commit/rollback the outermost ones
        if ($this->_trans_depth > 0) {
            return true;
        }

        // Reset the transaction failure flag.
        // If the $test_mode flag is set to true transactions will be rolled back
        // even if the queries produce a successful result.
        $this->_trans_failure = $test_mode === true;

        @mysqli_autocommit($this->conn_id, false);

        if (! isset($this->use_transaction_api)) {
            $this->use_transaction_api = version_compare(PHP_VERSION, '5.5', '>=');
        }

        if ($this->use_transaction_api) {
            @mysqli_begin_transaction($this->conn_id);
        } else {
            $this->simple_query('START TRANSACTION'); // can also be BEGIN or BEGIN WORK
        }

        return true;
    }

    /**
     * Commit Transaction
     *
     * @return  bool
     */
    public function trans_commit()
    {
        if (! $this->trans_enabled) {
            return true;
        }

        // When transactions are nested we only begin/commit/rollback the outermost ones
        if ($this->_trans_depth > 0) {
            return true;
        }

        @mysqli_commit($this->conn_id);
        @mysqli_autocommit($this->conn_id, true);

        return true;
    }

    /**
     * Rollback Transaction
     *
     * @return  bool
     */
    public function trans_rollback()
    {
        if (! $this->trans_enabled) {
            return true;
        }

        // When transactions are nested we only begin/commit/rollback the outermost ones
        if ($this->_trans_depth > 0) {
            return true;
        }

        @mysqli_rollback($this->conn_id);
        @mysqli_autocommit($this->conn_id, true);

        return true;
    }

    /**
     * Escape String
     *
     * @param   string
     * @param   bool    whether or not the string will be used in a LIKE condition
     * @return  string
     */
    public function escape_str($str, $like = false)
    {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escape_str($val, $like);
            }

            return $str;
        }

        if (is_object($this->conn_id) && $this->useSetNames()) {
            $str = mysqli_real_escape_string($this->conn_id, $str);
        } else {
            $str = addslashes($str);
        }

        // escape LIKE condition wildcards
        if ($like === true) {
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
        }

        return $str;
    }

    /**
     * Affected Rows
     *
     * @return  integer
     */
    public function affected_rows()
    {
        return @mysqli_affected_rows($this->conn_id);
    }

    /**
     * Insert ID
     *
     * @return  integer
     */
    public function insert_id()
    {
        return @mysqli_insert_id($this->conn_id);
    }

    /**
     * "Count All" query
     *
     * Generates a platform-specific query string that counts all records in
     * the specified database
     *
     * @param   string
     * @return  string
     */
    public function count_all($table = '')
    {
        if ($table == '') {
            return 0;
        }

        $query = $this->query(
            $this->_count_string
            . $this->_protect_identifiers('numrows')
            . " FROM "
            . $this->_protect_identifiers($table, true, null, false)
        );

        if ($query->num_rows() == 0) {
            return 0;
        }

        $row = $query->row();
        $this->_reset_select();
        return (int) $row->numrows;
    }

    /**
     * List table query
     *
     * Generates a platform-specific query string so that the table names can be fetched
     *
     * @access  private
     * @param   boolean
     * @return  string
     */
    public function _list_tables($prefix_limit = false)
    {
        $sql = "SHOW TABLES FROM {$this->_escape_char}{$this->database}{$this->_escape_char}";

        if ($prefix_limit !== false && $this->dbprefix != '') {
            $sql .= " LIKE '" . $this->escape_like_str($this->dbprefix) . "%'";
        }

        return $sql;
    }

    /**
     * Show column query
     *
     * Generates a platform-specific query string so that the column names can be fetched
     *
     * @param   string  the table name
     * @return  string
     */
    public function _list_columns($table = '')
    {
        return "SHOW COLUMNS FROM " . $this->_protect_identifiers($table, true, null, false);
    }

    /**
     * Field data query
     *
     * Generates a platform-specific query so that the column data can be retrieved
     *
     * @param   string  the table name
     * @return  object
     */
    public function _field_data($table)
    {
        return "DESCRIBE {$table}";
    }

    /**
     * The error message string
     *
     * @access  private
     * @return  string
     */
    public function _error_message()
    {
        return mysqli_error($this->conn_id);
    }

    /**
     * The error message number
     *
     * @access  private
     * @return  integer
     */
    public function _error_number()
    {
        return mysqli_errno($this->conn_id);
    }

    /**
     * Escape the SQL Identifiers
     *
     * This function escapes column and table names
     *
     * @access  private
     * @param   string
     * @return  string
     */
    public function _escape_identifiers($item)
    {
        if ($this->_escape_char == '') {
            return $item;
        }

        foreach ($this->_reserved_identifiers as $id) {
            if (strpos($item, ".{$id}") !== false) {
                $str = $this->_escape_char . str_replace('.', "{$this->_escape_char}.", $item);

                // remove duplicates if the user already included the escape
                return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
            }
        }

        if (strpos($item, '.') !== false) {
            $str = $this->_escape_char
                . str_replace('.', "{$this->_escape_char}.{$this->_escape_char}", $item)
                . $this->_escape_char;
        } else {
            $str = "{$this->_escape_char}{$item}{$this->_escape_char}";
        }

        // remove duplicates if the user already included the escape
        return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
    }

    /**
     * From Tables
     *
     * This function implicitly groups FROM tables so there is no confusion
     * about operator precedence in harmony with SQL standards
     *
     * @param   type
     * @return  type
     */
    public function _from_tables($tables)
    {
        if (! is_array($tables)) {
            $tables = array($tables);
        }

        return '(' . implode(', ', $tables) . ')';
    }

    /**
     * Insert statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @param   string  the table name
     * @param   array   the insert keys
     * @param   array   the insert values
     * @return  string
     */
    public function _insert($table, $keys, $values)
    {
        return "INSERT INTO {$table} (" . implode(', ', $keys)
            . ") VALUES (" . implode(', ', $values) . ")";
    }

    /**
     * Insert_batch statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @param   string  the table name
     * @param   array   the insert keys
     * @param   array   the insert values
     * @return  string
     */
    public function _insert_batch($table, $keys, $values)
    {
        return "INSERT INTO {$table} (" . implode(', ', $keys)
            . ") VALUES " . implode(', ', $values);
    }

    /**
     * Replace statement
     *
     * Generates a platform-specific replace string from the supplied data
     *
     * @param   string  the table name
     * @param   array   the insert keys
     * @param   array   the insert values
     * @return  string
     */
    public function _replace($table, $keys, $values)
    {
        return "REPLACE INTO {$table} (" . implode(', ', $keys)
            . ") VALUES (" . implode(', ', $values) . ")";
    }

    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @param   string  the table name
     * @param   array   the update data
     * @param   array   the where clause
     * @param   array   the orderby clause
     * @param   mixed   the limit clause
     * @return  string
     */
    public function _update($table, $values, $where, $orderby = array(), $limit = false)
    {
        foreach ($values as $key => $val) {
            $valstr[] = "{$key} = {$val}";
        }

        $sql  = "UPDATE {$table} SET " . implode(', ', $valstr);

        if (! empty($where) && is_array($where)) {
            $sql .= ' WHERE ' . implode(' ', $where);
        }
        if (! empty($orderby) && is_array($orderby)) {
            $sql .= ' ORDER BY ' . implode(', ', $orderby);
        }
        if (! empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }

        return $sql;
    }

    /**
     * Update_Batch statement
     *
     * Generates a platform-specific batch update string from the supplied data
     *
     * @param   string  the table name
     * @param   array   the update data
     * @param   array   the where clause
     * @return  string
     */
    public function _update_batch($table, $values, $index, $where = null)
    {
        $ids = array();
        foreach ($values as $key => $val) {
            $ids[] = $val[$index];

            foreach (array_keys($val) as $field) {
                if ($field != $index) {
                    $final[$field][] = "WHEN {$index} = {$val[$index]} THEN {$val[$field]}";
                }
            }
        }

        $sql = "UPDATE {$table} SET ";

        $cases = array();
        foreach ($final as $k => $v) {
            $currCase = "{$k} = CASE \n";
            foreach ($v as $row) {
                $currCase .= "{$row}\n";
            }
            $currCase .= "ELSE {$k} END";

            $cases[] = $currCase;
        }

        $sql .= implode(', ', $cases) . ' WHERE ';

        if (! empty($where) && is_array($where)) {
            $sql .= implode(' ', $where) . ' && ';
        }

        $sql .= "{$index} IN (" . implode(',', $ids) . ')';

        return $sql;
    }

    /**
     * Truncate statement
     *
     * Generates a platform-specific truncate string from the supplied data
     * If the database does not support the truncate() command
     * This function maps to "DELETE FROM table"
     *
     * @param   string  the table name
     * @return  string
     */
    public function _truncate($table)
    {
        return "TRUNCATE {$table}";
    }

    /**
     * Delete statement
     *
     * Generates a platform-specific delete string from the supplied data
     *
     * @param   string  the table name
     * @param   array   the where clause
     * @param   string  the limit clause
     * @return  string
     */
    public function _delete($table, $where = array(), $like = array(), $limit = false)
    {
        $conditions = '';
        if (count($where) > 0 || count($like) > 0) {
            $conditions = "\nWHERE " . implode("\n", $this->ar_where);

            if (count($where) > 0 && count($like) > 0) {
                $conditions .= " && ";
            }
            $conditions .= implode("\n", $like);
        }

        $limit = ! $limit ? '' : " LIMIT {$limit}";

        return "DELETE FROM {$table}{$conditions}{$limit}";
    }

    /**
     * Limit string
     *
     * Generates a platform-specific LIMIT clause
     *
     * @param   string  the sql query string
     * @param   integer the number of rows to limit the query to
     * @param   integer the offset value
     * @return  string
     */
    public function _limit($sql, $limit, $offset)
    {
        $sql .= "LIMIT {$limit}";

        if ($offset <= 0) {
            return $sql;
        }

        return "{$sql} OFFSET {$offset}";
    }

    /**
     * Close DB Connection
     *
     * @param   resource
     * @return  void
     */
    public function _close($conn_id)
    {
        @mysqli_close($conn_id);
    }

    /**
     * Get the value of $this->use_set_names. If the value is not set, determine
     * what it should be and set it.
     *
     * mysqli_set_charset() requires MySQL >= 5.0.7. If this is not available, use
     * SET NAMES as a fallback. If SET NAMES is used, mysqli_real_escape_string()
     * may not work properly...
     *
     * @return boolean False if mysqli_set_charset() is available, else true.
     */
    protected function useSetNames()
    {
        if (! isset($this->use_set_names)) {
            $this->use_set_names = version_compare(mysqli_get_server_info($this->conn_id), '5.0.7', '<');
        }

        return $this->use_set_names;
    }
}
/* End of file bfmysqli_driver.php */
/* Location: ./system/database/drivers/bfmysqli/bfmysqli_driver.php */
