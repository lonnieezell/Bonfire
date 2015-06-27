<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
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
 * BfMySQLi Utility Class
 *
 * This is a modified version of the MySQLi Utility class.
 *
 * While all of the functions have been made public (because they previously did
 * not have their visibility set), the PHPDoc @access tags have been left in place
 * for those which were not public to indicate that their visibility may change
 * in future versions.
 *
 * @category    Database
 * @author      ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/database/
 */
class CI_DB_bfmysqli_utility extends CI_DB_utility
{
    /**
     * List databases
     *
     * @access  private
     * @return  bool
     */
    public function _list_databases()
    {
        return "SHOW DATABASES";
    }

    /**
     * Optimize table query
     *
     * Generates a platform-specific query so that a table can be optimized
     *
     * @access  private
     * @param   string  the table name
     * @return  object
     */
    public function _optimize_table($table)
    {
        return "OPTIMIZE TABLE " . $this->db->_escape_identifiers($table);
    }

    /**
     * Repair table query
     *
     * Generates a platform-specific query so that a table can be repaired
     *
     * @access  private
     * @param   string  the table name
     * @return  object
     */
    public function _repair_table($table)
    {
        return "REPAIR TABLE " . $this->db->_escape_identifiers($table);
    }

    /**
     * MySQLi Export
     *
     * @access  private
     * @param   array   Preferences
     *     'add_drop'   => true,    // True includes DROP TABLE statements, false excludes them.
     *     'add_insert' => true,    // True includes INSERT statements, false excludes them.
     *     'filename'   => '',      // Not used here; the name of the export file, only needed for zip (defaults to current date/time).
     *     'format'     => 'gzip',  // Not used here; format of the export file: gzip, zip, txt.
     *     'ignore'     => array(), // Tables to ignore.
     *     'newline'    => "\n",    // The newline character used in the export file: "\n", "\r", "\r\n".
     *     'tables'     => array(), // Tables to back up.
     *
     * @return  mixed
     */
    public function _backup($params = array())
    {
        if (empty($params) || ! is_array($params)) {
            return false;
        }

        // Rather than returning false as in the above case, if an empty 'tables'
        // array is passed or the 'tables' param is not set, the MySQL driver will
        // return an empty string (if it doesn't error out). This aims to behave
        // the same way, but be a little more direct about it.
        if (empty($params['tables'])) {
            return '';
        }

        // Be explicit about the expected params and their default values. This
        // also permits performing the type casts up front.
        $tables     = (array) $params['tables'];
        $ignore     = isset($params['ignore']) ? (array) $params['ignore'] : array();
        $add_drop   = isset($params['add_drop']) ? (bool) $params['add_drop'] : true;
        $add_insert = isset($params['add_insert']) ? (bool) $params['add_insert'] : true;
        $newline    = isset($params['newline']) ? $params['newline'] : "\n";
        // $filename   = isset($params['filename']) ? $params['filename'] : '';
        // $format     = isset($params['format']) ? $params['format'] : 'gzip';

        // Build the output.
        $output = '';
        foreach ($tables as $table) {
            // If the table is in the "ignore" list, skip it.
            if (in_array($table, $ignore, true)) {
                continue;
            }

            // Get the table schema.
            $query = $this->db->query(
                'SHOW CREATE TABLE ' . $this->db->_escape_identifiers("{$this->db->database}.{$table}")
            );

            // If no table schema is returned, move on to the next table.
            if ($query === false) {
                continue;
            }

            // Write out the table schema.
            $output .= "#{$newline}# TABLE STRUCTURE FOR: {$table}{$newline}#{$newline}{$newline}";
            if ($add_drop == true) {
                $output .= 'DROP TABLE IF EXISTS ' . $this->db->_protect_identifiers($table) . ";{$newline}{$newline}";
            }

            $i = 0;
            $result = $query->result_array();
            foreach ($result[0] as $val) {
                if ($i++ % 2) {
                    $output .= "{$val};{$newline}{$newline}";
                }
            }

            // If inserts are not needed move on to the next table.
            if ($add_insert == false) {
                continue;
            }

            $query->free_result();

            // Switch to mysqli_query to retrieve the mysqli_result for use in the
            // rest of the loop.

            // Grab all the data from the current table.
            $mysqliResult = mysqli_query(
                $this->db->conn_id,
                'SELECT * FROM ' . $this->db->_protect_identifiers($table)
            );
            if ($mysqliResult->num_rows == 0) {
                continue;
            }

            // Fetch the field names and determine whether the field is an integer
            // type. Use this info to decide whether to surround the data with quotes.

            $i = 0;
            $fieldArr = array();
            $is_int = array();
            while ($field = $mysqliResult->fetch_field()) {

                // Is this field an integer?
                // Integer types: tinyint, smallint, mediumint, int, bigint.
                // Most versions of MySQL store timestamp as a string

                $is_int[$i] = in_array(
                    $field->type,
                    array(
                        MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_INT24,
                        MYSQLI_TYPE_LONG, MYSQLI_TYPE_LONGLONG
                    ), //, MYSQLI_TIMESTAMP_FLAG),
                    true
                );

                // Create a list of field names.
                $fieldArr[] = $this->db->_escape_identifiers($field->name);
                ++$i;
            }

            // Build a string from the list of field names.
            $field_str = implode(', ', $fieldArr);

            // Build the insert string for each row.
            while ($row = $mysqliResult->fetch_row()) {
                $valArr = array();
                $i = 0;
                foreach ($row as $v) {
                    // Is the value null? Escape the data if it's not an integer.
                    $valArr[] = $v === null ? 'NULL'
                        : ($is_int[$i] == false ? $this->db->escape($v) : $v);
                    ++$i;
                }

                $val_str = implode(', ', $valArr);

                // Build the INSERT string
                $output .= 'INSERT INTO '
                    . $this->db->_protect_identifiers($table)
                    . " ({$field_str}) VALUES ({$val_str});{$newline}";
            }

            $output .= "{$newline}{$newline}";
        }

        return $output;
    }
}
/* End of file bfmysqli_utility.php */
/* Location: ./system/database/drivers/bfmysqli/bfmysqli_utility.php */
