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
		$this->ci->session->set_userdata('previous_page', $this->ci->uri->uri_string()); 
	}
	
	//--------------------------------------------------------------------
	

}

// End App_hooks class