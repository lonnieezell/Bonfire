<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Fake Query Builder
 */
class MY_DB {
	/**
     * CI_DB
     */
    public function select() { }
    public function select_max() { }
    public function select_min() { }
    public function select_avg() { }
    public function select_sum() { }
    public function distinct() { }
    public function join() { }
    public function get_where() { }
    public function where() { }
    public function or_where() { }
    public function where_in() { }
    public function or_where_in() { }
    public function where_not_in() { }
    public function or_where_not_in() { }
    public function like() { }
    public function not_like() { }
    public function or_like() { }
    public function or_not_like() { }
    public function group_by() { }
    public function having() { }
    public function or_having() { }
    public function get() { }
    public function from() { }
    public function insert() { }
    public function insert_batch() { }
    public function insert_id() { }
    public function set() { }
    public function update() { }
    public function update_batch() { }
    public function delete() { }
    public function order_by() { }
    public function limit() { }
    public function offset() { }
    public function count_all_results() { }
    public function count_all() { }
    public function truncate() { }

    public function platform() { }
    public function _error_message() { }
    public function affected_rows() { }

    /**
     * CI_DB_Result
     */
    public function row() { }
    public function result() { }
    public function row_array() { }
    public function result_array() { }
    public function num_rows() { }
}

/*
	Fake CI_Model for testing our models with...
 */
if (!class_exists('CI_Model'))
{
    class CI_Model {

    	public $db;

    	public function __construct() {	}

    }
}