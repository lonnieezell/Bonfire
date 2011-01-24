<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->model('roles/role_model');
	}
	
	//--------------------------------------------------------------------

	public function _remap($method) 
	{ 
		if (method_exists($this, $method))
		{
			$this->$method();
		}
	}
	
	//--------------------------------------------------------------------
	
	public function index() 
	{
		$offset = $this->uri->segment(4);
	
		$total_users = $this->user_model->count_all();
	
		$this->pager['base_url'] = site_url('admin/settings/users/index');
		$this->pager['total_rows'] = $total_users;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment']	= 4;
		
		$this->pagination->initialize($this->pager);
		
		// Was a filter set?
		if ($this->input->post('filter_submit') && $this->input->post('filter_by_role_id'))
		{
			$role_id = $this->input->post('filter_by_role_id');
			
			$this->db->where('role_id', $role_id);
			Template::set('filter', $role_id);
		}
	
		Template::set('users', $this->user_model->limit($this->limit, $offset)->find_all());
		Template::set('total_users', $total_users);
		Template::set('roles', $this->role_model->select('role_id, role_name, default')->find_all());
	
		Template::set('toolbar_title', 'User Management');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function create() 
	{
		$this->load->helper('address');
	
		if ($this->input->post('submit'))
		{
			if ($this->save_user())
			{
				Template::set_message('User successfully created.', 'success');
				redirect('admin/settings/users');
			}
			else 
			{
				Template::set_message('There was a problem creating the user: '. $this->user_model->error);
			}
		}
		
		Template::set('roles', $this->role_model->select('role_id, role_name, default')->find_all());
	
		Template::set('toolbar_title', 'Create New User');
		Template::set_view('settings/user_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit() 
	{
		$this->load->helper('address');
		
		$user_id = $this->uri->segment(5);
		
		if ($this->input->post('submit'))
		{
			if ($this->save_user('update', $user_id))
			{
				Template::set_message('User successfully created.', 'success');
				redirect('admin/settings/users');
			}
			else 
			{
				Template::set_message('There was a problem creating the user: '. $this->user_model->error);
			}
		}
		
		Template::set('user', $this->user_model->find($user_id));
		Template::set('roles', $this->role_model->select('role_id, role_name, default')->find_all());
		
		Template::set('toolbar_title', 'Edit User');
		Template::set_view('settings/user_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function do_action() 
	{
		$actionable = $this->input->post('actionable') ? $this->input->post('actionable') : false;
	
		if (!$this->input->post('action') || $actionable == false)
		{
			redirect('/admin/settings/users');
		}
		
		switch (strtolower($this->input->post('action')))
		{
			case 'ban':
				foreach ($actionable as $id)
				{
					$this->user_model->update($id, array('banned' => 1));
				}
				break;
			case 'delete':
				foreach ($actionable as $id)
				{
					$this->user_model->delete($id);
				}
				break;
		}
		
		redirect('/admin/settings/users');
	}
	
	//--------------------------------------------------------------------
		
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	public function unique_email($str) 
	{	
		if ($this->user_model->is_unique('email', $str))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('unique_email', 'The %s address is already in use. Please choose another.');
			return false;
		}
	}
	
	//--------------------------------------------------------------------
	
	private function save_user($type='insert', $id=0) 
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|strip_tags|alpha|max_length[20]|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|strip_tags|alpha|max_length[20]|xss_clean');
		if ($type == 'insert')
		{
			$this->form_validation->set_rules('email', 'Email', 'required|trim|callback_unique_email|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|trim|strip_tags|max_length[40]|xss_clean');
			$this->form_validation->set_rules('pass_confirm', 'Password (again)', 'required|trim|strip_tags|matches[password]|xss_clean');
		} else 
		{
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|max_length[40]|xss_clean');
			$this->form_validation->set_rules('pass_confirm', 'Password (again)', 'trim|strip_tags|matches[password]|xss_clean');
		}
		$this->form_validation->set_rules('street1', 'Street 1', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('street2', 'Street 2', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|strip_tags|numeric|max_length[7]|xss_clean');
		$this->form_validation->set_rules('zip_extra', 'Zipcode Extra', 'trim|strip_tags|numeric|max_length[5]|xss_clean');
		
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		if ($type == 'insert')
		{
			return $this->user_model->insert($_POST);
		}
		else	// Update
		{	
			return $this->user_model->update($id, $_POST);
		}
	}
	
	//--------------------------------------------------------------------
	
	
}

// End User Admin class