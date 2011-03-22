<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App_hooks {

	private $ci;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		$this->ci =& get_instance();
	}
	
	//--------------------------------------------------------------------
	
	
	/**
	 * Stores the name of the current uri in the session as 'previous_page'.
	 * This allows redirects to take us back to the previous page without
	 * relying on inconsistent browser support or spoofing.
	 * 
	 * @access	public
	 * @return	void
	 */
	public function prep_redirect() 
	{
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
	
		$this->ci->session->set_userdata('previous_page', $this->ci->uri->uri_string()); 
	}
	
	//--------------------------------------------------------------------
	
	public function check_site_status() 
	{
		if ($this->ci->config->item('site.status') == 0 && $this->ci->auth->role_id() != 1 && $this->ci->auth->role_id() != 6)
		{
			include (APPPATH .'errors/offline'. EXT);
			die();
		}
	}
	
	//--------------------------------------------------------------------
	
}

// End App_hooks class