<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends Base_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		Template::set_theme('installer');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('site_title', 'Site Title', 'required|trim|strip_tags|min_length[1]|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'required|trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|strip_tags|alpha_dash|min_length[8]|xss_clean');
			$this->form_validation->set_rules('pass_confirm', 'Password (again)', 'required|trim|matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|strip_tags|valid_email|xss_clean');
			
			if ($this->form_validation->run() !== false)
			{
			
			}
		}
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}