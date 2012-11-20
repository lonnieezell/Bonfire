<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') === FALSE)
			show_error('This file is not directly accessible.');
			
		parent::__construct();
		
		$this->lang->load('install');
	}
	
	//--------------------------------------------------------------------
	
	/**
	 * Test our database connection settings
	 *
	 * Borrowed from PyroCMS and tweaked for multiple drivers.
	 *
	 * @access	public
	 * @return	json
	 */
	public function confirm_database() 
	{
		$driver		= $this->input->post('driver');
		$server		= $this->input->post('server');
		$username	= $this->input->post('username');
		$password	= $this->input->post('password');
		$port		= $this->input->post('port');

		$host		= $server .':'. $port;
		
		if ($driver == 'mysql')
		{
			$link = @mysql_connect($host, $username, $password, true);
		}
		else if ($driver == 'mysqli')
		{
			$mysqli = new mysqli($server, $username, $password, '', $port);

			if ($mysqli->connect_error)
			{
				$link = false;
			}
			else
			{
				$link = true;
			}

		}
		
		if (!$link)
		{
			$data['success'] = 'false';
			$data['message'] = lang('in_db_no_connect');
		}
		else
		{
			$data['success'] = 'true';
			$data['message'] = lang('in_db_connect');
		}
		
		// Set some headers for our JSON
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');

		echo json_encode($data);
	}
	
	//--------------------------------------------------------------------
}