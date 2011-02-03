<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Page_model extends MY_Model {

	protected $table		= 'pages';
	protected $key			= 'page_id';
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';
	protected $set_created	= true;
	protected $set_modified = true;

}