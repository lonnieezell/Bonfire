<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Front_Controller {

	//--------------------------------------------------------------------
	
	public function index() 
	{	
		
		$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
		
		Template::set('logged_in', isset ($cookie['logged_in']));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}