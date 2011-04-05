<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('GitHub_lib');
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !HMVC METHODS
	//--------------------------------------------------------------------
	
	/*
		update_check()
		
		Checks with github for any Bonfire updates and notifies the develoepr.
	*/
	public function update_check() 
	{ 
		$message = '';
		
		if (!$this->config->item('updates.do_check'))
		{
			return;
		}
		
		/*
			If they're living on the bleeding edge, then we need to find
			the latest commit reference and compare to what this installed
			version is at.
		*/
		if ($this->config->item('updates.bleeding_edge'))
		{ 
			$commits = $this->github_lib->user_timeline('ci-bonfire', 'Bonfire');
		
			$last_commit = $commits[0]->id;
			
			if ($last_commit !== $this->config->item('updates.last_commit'))
			{
				$message .= 'A <b>bleeding edge</b> update to Bonfire is available.';
			}
			
			unset($commits, $last_commit);
		}
		
		/*
			Also check for major, tagged releases.
		*/
		$tags = $this->github_lib->repo_refs('ci-bonfire', 'Bonfire');

		foreach ($tags as $tag => $ref)
		{
			if ($tag > BONFIRE_VERSION)
			{
				$message .= ' Version <b>'. $tag .'</b> of Bonfire is available. You are currently running '. BONFIRE_VERSION;
				break;
			}
		}
		
		unset($tags);
		
		/*
			Show the message(s)
		*/
		if (!empty($message))
		{
			echo '<div class="notification attention">';
			echo $message .' <a href="'. site_url('admin/developer/update') .'">View Updates</a>.';
			echo '</div>';
		}
	}
	
	//--------------------------------------------------------------------
}

// End Update/Developer controller