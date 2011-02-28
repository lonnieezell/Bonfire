<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends Front_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->load->model('Page_model', 'page_model', true);
	}

	//--------------------------------------------------------------------
	
	/*
		Remaps everything to flow into the index method. 
	*/
	public function _remap() 
	{
		$this->index();
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{		
		$page = $this->get_page($this->uri->uri_string());
		
		Template::set('body_content', $page->body);
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	private function get_page($alias=null) 
	{
		if (empty($alias))
		{
			return false;
		}
		
		// Find the page in the database that matches the alias
		$page = $this->page_model->find_by('alias', $alias);
		
		if (is_array($page))
		{
			$page = $page[0];
		}
		
		// If the user can manage pages, show the page, whether it's 
		// published or note.
		$today = date('Y-m-d H:i:s', time());
		if ($page->published == 0 || $page->pub_date > $today || $page->unpub_date < $today)
		{ 
			if (has_permission('Bonfire.Pages.Manage'))
			{
				$page->body = Template::message('This page is <b>not published</b>.') . $page->body;
			} else
			{
				show_404();
			}
		}
		
		return $page;
	}
	
	//--------------------------------------------------------------------
	
}

// End Pages Controller