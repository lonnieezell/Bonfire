<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Emailer_model extends MY_Model {

	protected $table		= 'email_queue';
	protected $key			= 'id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_created	= false;
	protected $set_modified = false;

}