<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permission_upgrade extends Base_Controller {

	//--------------------------------------------------------------------
	public function __construct() 
	{
		parent::__construct();
		$this->lang->load('application');
		$this->load->helper('application');		
//		$this->config->load('migrations');
		$this->load->library('migrations/migrations');	
		$this->lang->load('migrations/migrations');
	}	

	public function index() 
	{	
		

		$result = $this->migrations->version(3);
			
		if ($result)
		{
			Template::set_message('Successfully migrated database to version '. $result, 'success');
		} else 
		{
			Template::set_message('There was an error migrating the database.', 'error');
		}		

		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}