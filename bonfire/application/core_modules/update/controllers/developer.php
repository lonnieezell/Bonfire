<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		
		Template::set('toolbar_title', 'Update Manager');
	}
	
	//--------------------------------------------------------------------

	public function index() 
	{
		if ($this->config->item('updates.do_check'))
		{
			$this->load->library('GitHub_lib');
			$this->load->helper('date');
		
			// Latest commits
			Template::set('commits', $this->github_lib->user_timeline('ci-bonfire', 'Bonfire'));
			
			$tags = $this->github_lib->repo_refs('ci-bonfire', 'Bonfire');
	
			$version = 0.0;
	
			foreach ($tags as $tag => $ref)
			{
				if ($tag > $version)
				{
					$version = $tag;
				}
			}
			
			Template::set('update_message', 'You are running Bonfire version <b>'. BONFIRE_VERSION .'</b>. The latest available version is <b>'. $version .'</b>.');
		}
	
		Template::render();
	}
	
	//--------------------------------------------------------------------
	

}