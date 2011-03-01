<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tracking_model extends MY_Model {

	protected $table			= 'tracking';
	protected $key				= 'tracking_id';
	protected $soft_deletes		= false;
	protected $date_format		= 'datetime';
	protected $set_created		= true;
	protected $set_modified 	= false;

}