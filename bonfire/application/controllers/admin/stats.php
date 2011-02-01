<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Statistics.View');
		
		Template::set('toolbar_title', 'Statistics');
	}
	
	//--------------------------------------------------------------------	

	public function index() 
	{
		Template::set_view('admin/stats/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}