<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends Front_Controller {

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
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

	/*
		Method: index()
		
		Loads and displays the page.	
	*/
	public function index() 
	{		
		global $OUT;
		$output = null;
		$alias = $this->uri->uri_string();
		
		// Are we displaying the home page? 
		if (empty($alias))
		{
			$alias = config_item('pages.home_page_alias');
			
			if (empty($alias))
			{
				show_404();
			}
		}
	
		// Try to get it from the cache
		$page = $this->cache->get('pages_'. $alias);
		
		// No cache? Then get it from the db
		if ($page == false)
		{  
			$page = $this->get_page($alias);
			
			// Render the page.
			Template::set('body_content', $page->body);
			Template::render();
			
			$output = $OUT->get_output();
			
			// If set, cache this bad boy.
			if ($page->cacheable)
			{ 	
				$this->cache->save('pages_'. $alias, $output, 60*60*24*365);	// 365 days - is deleted on page update.
			}
		}
		
		// Render the output
		if (empty($output) && $page != false)
		{ 
			$output = $page;
		}
		
		$OUT->set_output($output);
		
		// Should we track page hits?
		if (config_item('pages.track_hits'))
		{
			$this->track_hit(null, $alias);
		}
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: get_page()
	
		Retrieves the page from the database, check permissions,
		process the document according to rte_type.
		
		Parameters:
			$alias	- the alias to find.
			
		Return:
			A stdObject with all of the page details.
	*/
	private function get_page($alias=null) 
	{
		if (empty($alias))
		{
			return false;
		}
		
		$this->load->model('Page_model', 'page_model', true);
		
		$message = '';
		
		// Find the page in the database that matches the alias
		$page = $this->page_model->find_by('alias', $alias);
		
		if (is_array($page))
		{
			$page = $page[0];
		}
		
		// If the user can manage pages, show the page, whether it's 
		// published or note.
		$today = date('Y-m-d H:i:s', time());
		if ($page->published == 0 || ($page->published == 1 && ($page->pub_date > $today || ($page->unpub_date != '0000-00-00 00:00:00' && $page->unpub_date < $today))))
		{ 
			if (has_permission('Bonfire.Pages.Manage'))
			{
				$message = Template::message('This page is <b>not published</b>.', 'attention');
			} else
			{
				show_404();
			}
		}
		
		
		switch ($page->rte_type)
		{
			case 'html':
				$this->load->helper('typography');
				$page->body = auto_typography($page->body);
				break;
			case 'markdown':
				$this->load->helper('markdown');
				$page->body = Markdown($page->body);
				break;
			case 'textile':
				$this->load->library('textile');
				$page->body = $this->textile->TextileThis($page->body);
		}
		
		// Add our admin message, if any
		$page->body = $message . $page->body;
		
		return $page;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: track_hits()
		
		Saves a view of the page to the database as a 'hit'.
		
		Parameters:
			$page_id	- The (int) of the page to record a hit for.
			$page_alias	- The alias of the page to record a hit for.
			
		Return:
			void 
	*/
	private function track_hit($page_id=0, $page_alias='') 
	{
		if (empty($page_id) && empty($page_alias))
		{
			return;
		}
		
		// Make sure the page model is loaded.
		if (!class_exists('Page_model'))
		{
			$this->load->model('Page_model', 'page_model', true);
		}
		$this->load->model('pages/Tracking_model', 'tracking_model', true);
		
		// We can either find the information via the id or alias.
		if ($page_id)
		{
			$data = array(
				'resource_id'	=> $page_id,
				'ip_address'	=> $this->input->ip_address(),
			);
		} 
		else if ($page_alias !== '')
		{
			$id = $this->page_model->select('page_id')->find_by('alias', $page_alias);
			
			if (is_array($id)) $id = $id[0];
		
			$data = array(
				'resource_id'	=> $id->page_id,
				'ip_address'	=> $this->input->ip_address(),
			);
		}
		
		// Save it
		$this->tracking_model->insert($data);
	}
	
	//--------------------------------------------------------------------
	
}

// End Pages Controller