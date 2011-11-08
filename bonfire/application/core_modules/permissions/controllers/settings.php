<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {
               
	function __construct()
	{
 		parent::__construct();

		$this->auth->restrict('Permissions.Settings.View');
		$this->auth->restrict('Permissions.Settings.Manage');

		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('permission_model');
		$this->lang->load('permissions');
		$this->load->helper('inflector');
			
	}
	
	
	/** 
	 * function index
	 *
	 * list form data
	 */
	function index()
	{
		Assets::add_js($this->load->view('settings/js', null, true), 'inline');
		Template::set('records', $this->permission_model->order_by('name')->find_all());
		Template::set('permission_header', '');
		if (!Template::get("toolbar_title"))
		{
			Template::set("toolbar_title", lang("permissions_manage"));
		}
		Template::render();
	}
	
	
	public function create() 
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_permissions())
			{
				Template::set_message(lang("permissions_create_success"), 'success');
				Template::redirect(SITE_AREA .'/settings/permissions');
			}
			else 
			{
				Template::set_message(lang("permissions_create_failure") . $this->permission_model->error, 'error');
			}
		}
	
		Template::set('toolbar_title', lang("permissions_create_new_button"));
		Template::set_view('settings/permission_form');
		Template::render();
	}
			
	public function edit() 
	{
		$id = (int)$this->uri->segment(5);
		
		if (empty($id))
		{
			Template::set_message(lang("permissions_invalid_id"), 'error');
			redirect(SITE_AREA .'/settings/permissions');
		}
	
		if ($this->input->post('submit'))
		{
			if ($this->save_permissions('update', $id))
			{
				Template::set_message(lang("permissions_edit_success"), 'success');
			}
			else 
			{
				Template::set_message(lang("permissions_edit_failure") . $this->permission_model->error, 'error');
			}
		}
		
		Template::set('permissions', $this->permission_model->find($id));
	
		Template::set('toolbar_title', lang("permissions_edit_heading"));
		Template::set_view('settings/permission_form');
		Template::render();		
	}
	
			
	public function delete() 
	{	
		$id = $this->uri->segment(5);
	
		if (!empty($id))
		{	
			if ($this->permission_model->delete($id))
			{
				Template::set_message(lang("permissions_delete_success"), 'success');
			} else
			{
				Template::set_message(lang("permissions_delete_failure") . $this->permission_model->error, 'error');
			}
		}
		
		redirect(SITE_AREA .'/settings/permissions');
	}
		
	public function save_permissions($type='insert', $id=0) 
	{	
			
		$this->form_validation->set_rules('name','Name','required|trim|xss_clean|max_length[30]');			
		$this->form_validation->set_rules('description','Description','trim|xss_clean|max_length[100]');			
		$this->form_validation->set_rules('status','Status','required|trim|xss_clean');
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		if ($type == 'insert')
		{
			$id = $this->permission_model->insert($_POST);
			
			if (is_numeric($id))
			{
				$return = true;
			} else
			{
				$return = false;
			}
		}
		else if ($type == 'update')
		{
			$return = $this->permission_model->update($id, $_POST);
		}
		
		return $return;
	}

}
