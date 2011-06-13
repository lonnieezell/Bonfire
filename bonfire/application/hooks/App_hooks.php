<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

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
		if ($this->ci->config->item('site.status') == 0)
		{
			if (!class_exists('Auth'))
			{
				$this->ci->load->library('users/auth');
			}

			if (!$this->ci->auth->has_permission('Site.Signin.Offline'))
			{
				include (APPPATH .'errors/offline'. EXT);
				die();
			}
		}
	}
	
	//--------------------------------------------------------------------
	
}

// End App_hooks class
