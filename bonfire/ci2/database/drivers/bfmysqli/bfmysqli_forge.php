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
 * BfMySQLi Forge Class
 *
 * This is a modified version of the MySQLi Forge class.
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
class CI_DB_bfmysqli_forge extends CI_DB_forge
{
    /**
     * Create database
     *
     * @access  private
     * @param   string  the database name
     * @return  bool
     */
    public function _create_database($name)
    {
        return "CREATE DATABASE {$name}";
    }

    /**
     * Drop database
     *
     * @access  private
     * @param   string  the database name
     * @return  bool
     */
    public function _drop_database($name)
    {
        return "DROP DATABASE {$name}";
    }

    /**
     * Process Fields
     *
     * @access  private
     * @param   mixed   the fields
     * @return  string
     */
    public function _process_fields($fields)
    {
        $current_field_count = 0;
        $sql = '';

        foreach ($fields as $field => $attributes) {
            // Numeric field names aren't allowed in databases, so if the key is
            // numeric, we know it was assigned by PHP and the developer manually
            // entered the field information, so we'll simply add it to the list
            if (is_numeric($field)) {
                $sql .= "\n\t{$attributes}";
            } else {
                $attributes = array_change_key_case($attributes, CASE_UPPER);
                $sql .= "\n\t" . $this->db->_protect_identifiers($field);

                if (array_key_exists('NAME', $attributes)) {
                    $sql .= ' ' . $this->db->_protect_identifiers($attributes['NAME']) . ' ';
                }

                if (array_key_exists('TYPE', $attributes)) {
                    $sql .=  " {$attributes['TYPE']}";
                }

                if (array_key_exists('CONSTRAINT', $attributes)) {
                    $sql .= "({$attributes['CONSTRAINT']})";
                }

                if (array_key_exists('UNSIGNED', $attributes) && $attributes['UNSIGNED'] === true) {
                    $sql .= ' UNSIGNED';
                }

                if (array_key_exists('DEFAULT', $attributes)) {
                    $sql .= " DEFAULT '{$attributes['DEFAULT']}'";
                }

                if (array_key_exists('NULL', $attributes) && $attributes['NULL'] === true) {
                    $sql .= ' NULL';
                } else {
                    $sql .= ' NOT NULL';
                }

                if (array_key_exists('AUTO_INCREMENT', $attributes) && $attributes['AUTO_INCREMENT'] === true) {
                    $sql .= ' AUTO_INCREMENT';
                }
            }

            // don't add a comma on the end of the last field
            if (++$current_field_count < count($fields)) {
                $sql .= ',';
            }
        }

        return $sql;
    }

    /**
     * Create Table
     *
     * @access  private
     * @param   string  the table name
     * @param   mixed   the fields
     * @param   mixed   primary key(s)
     * @param   mixed   key(s)
     * @param   boolean should 'IF NOT EXISTS' be added to the SQL
     * @return  bool
     */
    public function _create_table($table, $fields, $primary_keys, $keys, $if_not_exists)
    {
        $sql = 'CREATE TABLE ';

        if ($if_not_exists === true) {
            $sql .= 'IF NOT EXISTS ';
        }

        $sql .= $this->db->_escape_identifiers($table) . " (";
        $sql .= $this->_process_fields($fields);

        if (count($primary_keys) > 0) {
            $key_name = $this->db->_protect_identifiers(implode('_', $primary_keys));
            $primary_keys = $this->db->_protect_identifiers($primary_keys);
            $sql .= ",\n\tPRIMARY KEY {$key_name} (" . implode(', ', $primary_keys) . ")";
        }

        if (! empty($keys) && is_array($keys)) {
            foreach ($keys as $key) {
                if (is_array($key)) {
                    $key_name = $this->db->_protect_identifiers(implode('_', $key));
                    $key = $this->db->_protect_identifiers($key);
                } else {
                    $key_name = $this->db->_protect_identifiers($key);
                    $key = array($key_name);
                }

                $sql .= ",\n\tKEY {$key_name} (" . implode(', ', $key) . ")";
            }
        }

        $sql .= "\n) DEFAULT CHARACTER SET {$this->db->char_set} COLLATE {$this->db->dbcollat};";

        return $sql;
    }

    /**
     * Drop Table
     *
     * @access  private
     * @return  string
     */
    public function _drop_table($table)
    {
        return "DROP TABLE IF EXISTS " . $this->db->_escape_identifiers($table);
    }

    /**
     * Alter table query
     *
     * Generates a platform-specific query so that a table can be altered
     * Called by add_column(), drop_column(), and column_alter(),
     *
     * @access  private
     * @param   string  the ALTER type (ADD, DROP, CHANGE)
     * @param   string  the column name
     * @param   array   fields
     * @param   string  the field after which we should add the new field
     * @return  object
     */
    public function _alter_table($alter_type, $table, $fields, $after_field = '')
    {
        $sql = 'ALTER TABLE ' . $this->db->_protect_identifiers($table) . " {$alter_type} ";

        // DROP has everything it needs now.
        if ($alter_type == 'DROP') {
            return $sql . $this->db->_protect_identifiers($fields);
        }

        $sql .= $this->_process_fields($fields);
        if ($after_field != '') {
            $sql .= ' AFTER ' . $this->db->_protect_identifiers($after_field);
        }

        return $sql;
    }

    /**
     * Rename a table
     *
     * Generates a platform-specific query so that a table can be renamed
     *
     * @access  private
     * @param   string  the old table name
     * @param   string  the new table name
     * @return  string
     */
    public function _rename_table($table_name, $new_table_name)
    {
        return 'ALTER TABLE ' . $this->db->_protect_identifiers($table_name)
            . ' RENAME TO ' . $this->db->_protect_identifiers($new_table_name);
    }
}
/* End of file bfmysqli_forge.php */
/* Location: ./system/database/drivers/bfmysqli/bfmysqli_forge.php */
