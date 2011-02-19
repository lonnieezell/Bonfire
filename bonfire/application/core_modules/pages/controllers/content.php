<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Content extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Content.View');
		
		$this->load->model('Page_model', 'page_model', true);
		$this->load->helper('text');
		
		Assets::add_js($this->load->view('content/page_js', null, true), 'inline');
		Assets::add_js('jquery-ui-1.8.8.min');
		Assets::add_js('markitup/jquery.markitup');
		Assets::add_js('markitup/sets/textile/set');
		Assets::add_css('markitup/skins/simple/style');
		Assets::add_css('markitup/sets/textile/style');
		
		$this->setup_rte();
	}
	
	//--------------------------------------------------------------------
	

	public function index() 
	{
		Template::set('pages', $this->page_model->find_all());
	
		Template::set('toolbar_title', 'Manage Pages');
		Template::render('for_ui');
	}
	
	//--------------------------------------------------------------------
	
	public function create() 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_page())
			{
				Template::set_message('Successfully saved Page.', 'success');
				redirect('admin/content/pages');
			}
		}
			
		Template::set('padding_style', '');
		Template::set_view('content/page_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function view($page_id=0) 
	{
		Template::set('page', $this->page_model->find($page_id));
		
		Template::set('padding_style', '');
		Template::set_view('content/page_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	
	public function edit($id=0) 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_page($id, 'update'))
			{
				Template::set_message('Successfully saved Page.', 'success');
				redirect('admin/content/pages');
			}
		}
	
		Template::set('page', $this->page_model->find($id));
	
		Template::set('padding_style', '');
		Template::set_view('content/page_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	private function save_page($id=0, $type='insert') 
	{
		$this->form_validation->set_rules('page_title', 'Title', 'required|trim|max_length[255]|xss_clean');
		//$this->form_validation->set_rules('page_alias', 'Alias', 'required|trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('body', 'Page Body', 'trim|xss_clean');
		
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		print_r($_POST);
		// Build our page content
		$data = array(
			'page_title'	=> $_POST['page_title'],
			'long_title'	=> isset($_POST['long_title']) ? $_POST['long_title'] : '',
			'alias'			=> isset($_POST['page_alias']) ? $_POST['alias'] : '',
			'description'	=> isset($_POST['description']) ? $_POST['description'] : '',
			'published'		=> isset($_POST['published']) ? 1 : 0,
			'pub_date'		=> isset($_POST['pub_date']) ? $_POST['pub_date'] : '0000-00-00 00:00:00',
			'unpub_date'	=> isset($_POST['unpub_date']) ? $_POST['unpub_date'] : '0000-00-00 00:00:00',
			'body'			=> !empty($_POST['body']) ? htmlentities($_POST['body']) : '',
			'summary'		=> isset($_POST['summary']) ? $_POST['summary'] : '',
			'rich_text'		=> isset($_POST['rich_text']) ? 1 : 0,
			'searchable'	=> isset($_POST['searchable']) ? 1 : 0,
			'cacheable'		=> isset($_POST['cacheable']) ? 1 : 0,
			'is_folder'		=> isset($_POST['is_folder']) ? 1 : 0,
			'deleted'		=> isset($_POST['deleted']) ? 1 : 0,
		);
		
		if ($type=='insert')
		{
			$data['created_by'] = $this->auth->user_id();
		
			$id = $this->page_model->insert($data);
			
			if ($id) { $result = true; }
		}
		else if ($type == 'update')
		{
			$data['modified_by'] = $this->auth->user_id();
			
			$result = $this->page_model->update($id, $data);
		}
		
		return $result;
	}
	
	//--------------------------------------------------------------------
	
	private function setup_rte() 
	{
		//Assets::add_js('tiny_mce/tiny_mce');
	}
	
	//--------------------------------------------------------------------
	
}