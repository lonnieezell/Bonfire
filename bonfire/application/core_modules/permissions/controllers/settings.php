<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

		Template::set_block('sub_nav', 'settings/_sub_nav');
	}

	//--------------------------------------------------------------------

	/**
	 * function index
	 *
	 * list form data
	 */
	function index()
	{
		// Deleting anything?
		if ($action = $this->input->post('submit'))
		{
			if ($action == 'Delete')
			{
				$checked = $this->input->post('checked');

				foreach ($checked as $pid)
				{
					$result = $this->permission_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('permissions_deleted') .'.', 'success');
				}
				else
				{
					Template::set_message(lang('permissions_del_error') . $this->permission_model->error, 'success');
				}
			}
		}

		$this->load->library('ui/dataset');
		$this->dataset->set_source('permission_model', 'find_all');

		$columns = array(
			array(
				'field'		=> 'id',
				'title'		=> 'ID',
				'width'		=> '3em'
			),
			array(
				'field'		=> 'name',
			),
			array(
				'field'		=> 'description',
			),
			array(
				'field'		=> 'active',
			)
		);

		$this->dataset->columns($columns);

		$this->dataset->actions(array('delete'));

		$this->dataset->initialize();

		Template::set("toolbar_title", lang("permissions_manage"));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function create()
	{
		if ($this->input->post('submit'))
		{
			if ($this->save_permissions())
			{
				Template::set_message(lang("permissions_create_success"), 'success');
				Template::redirect(SITE_AREA .'/settings/permissions');
			}
		}

		Template::set('toolbar_title', lang("permissions_create_new_button"));
		Template::set_view('settings/permission_form');
		Template::render();
	}

	//--------------------------------------------------------------------

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
		}

		Template::set('permissions', $this->permission_model->find($id));

		Template::set('toolbar_title', lang("permissions_edit_heading"));
		Template::set_view('settings/permission_form');
		Template::render();
	}

	//--------------------------------------------------------------------

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

	//--------------------------------------------------------------------

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

	//--------------------------------------------------------------------
}
