<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ModuleBuilder
 *
 * An easy module generator for the Bonfire project on the CodeIgniter framework
 * 
 * @package   ModuleBuilder
 * @version   0.5.0
 * @author    Sean Downey, <sean[at]considerweb.com>
 * @copyright Copyright (c) 2011, Sean Downey
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @link      http://github.com/seandowney/bonfire_modulebuilder
 * 
 * This code is originally based on Ollie Rattue's http://formigniter.org/ project
 */

class Developer extends Admin_Controller {

	//---------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		$this->load->library('modulebuilder');
		$this->load->config('modulebuilder');
		$this->lang->load('modulebuilder');
		$this->load->helper('file');
		$this->load->dbforge();
		
		$this->options = $this->config->item('modulebuilder');
		
	}

	//---------------------------------------------------------------

	/*
		Method: index()
		
		Displays a list of installed modules with the option to create
		a new one.
	*/
	public function index()
	{
		
		Assets::add_js($this->load->view('developer/modulebuilder_js', null, true), 'inline');

		$modules = module_list(true);
		$configs = array();
	
		foreach ($modules as $module)
		{
			$configs[$module] = module_config($module);
			
			if (!isset($configs[$module]['name']))
			{
				$configs[$module]['name'] = ucwords($module);
			}
		}
		
		// check that the modules folder is writeable
		Template::set('writeable', $this->_check_writeable());
		
		ksort($configs);
		Template::set('modules', $configs);
		Template::set('toolbar_title', 'Manage Modules');
		Template::render();
		
	}
    
	//--------------------------------------------------------------------
	
	/*
		Method: create()
		
		Displays the create a module form.
	*/
	public function create()
	{
		Assets::add_js($this->load->view('developer/modulebuilder_js', null, true), 'inline');
		
		$this->auth->restrict('Bonfire.Modules.Add');
		
		$hide_form = false;
		
		$this->field_total = $this->input->post('field_total');
		$this->field_total = 0;
		
		$last_seg = $this->uri->segment( $this->uri->total_segments() );
		
		if (is_numeric($last_seg)) 
		{
			$this->field_total = $last_seg;
		}
		
		// validation hasn't been passed
		if ($this->validate_form($this->field_total) == FALSE)
		{
			Template::set('field_total', $this->field_total);
			
			if (!empty($_POST))
			{
				Template::set('form_error', TRUE);
			}
			else
			{
				Template::set('form_error', FALSE);
			}
			$query = $this->db->select('role_id,role_name')->order_by('role_name')->get('roles');
			Template::set('roles', $query->result_array());
			Template::set('form_action_options', $this->options['form_action_options']);
			Template::set('validation_rules', $this->options['validation_rules']);
			Template::set('validation_limits', $this->options['validation_limits']);
			Template::set('field_numbers', range(0,20));
			Template::set_view('developer/modulebuilder_form');
						
		} else 
		{
			// passed validation proceed to second page
			$this->build_module($this->field_total);
			
			// Log the activity
			$this->activity_model->log_activity($this->auth->user_id(), lang('mb_act_create').': ' . $this->input->post('module_name') . ' : ' . $this->input->ip_address(), 'modulebuilder');
			
			Template::set_view('developer/output');
		}
				
		// check that the modules folder is writeable
		Template::set('writeable', $this->_check_writeable());

		Template::set('error', array());

		Template::set('toolbar_title', 'Module Builder');
		
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: delete()
		
		Deletes a module and all of it's files.
	*/
	public function delete() 
	{	
		$module_name = $this->uri->segment(5);

		if (!empty($module_name)) 
		{	
			$this->auth->restrict('Bonfire.Modules.Delete');
			
			$prefix = $this->db->dbprefix;
			
			$this->db->trans_begin();
			
			// check if there is a model to drop (non-table modules will have no model)
			$model_name = $module_name."_model";
			if (module_file_path($module_name,'models',$model_name.'.php'))
			{
				// drop the table
				$this->load->model($module_name.'/'.$model_name,'mt');
				$this->dbforge->drop_table($this->mt->get_table());
			}			
			
			// get any permission ids
			$query = $this->db->query('SELECT permission_id FROM '.$prefix.'permissions WHERE name LIKE "'.$module_name.'.%.%"');

			if ($query->num_rows() > 0) {
            	foreach($query->result_array() as $row) {
            		// undo any permissions that exist
					$this->db->where('permission_id',$row['permission_id']);
            		$this->db->delete($prefix.'permissions');
					
					// and fron the roles as well.
					$this->db->where('permission_id',$row['permission_id']);
					$this->db->delete($prefix.'role_permissions');            		
            	}		
	        }
	        
	        // drop the schema #
	        $module_name_lower = strtolower($module_name);
	        if ($this->db->field_exists( $module_name_lower . '_version', 'schema_version'))
	        {
	        	$this->dbforge->drop_column('schema_version', $module_name_lower . '_version');
	        }
	        
	        if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				Template::set_message('We could not delete this module.', $this->db->error, 'error');
			} else {
				$this->db->trans_commit();
				
				// database was successful in deleting everything. Now try to get rid of the files.
				if (delete_files(module_path($module_name), true)) {
					@rmdir(module_path($module_name.'/'));

					// Log the activity
					$this->activity_model->log_activity($this->auth->user_id(), lang('mb_act_delete').': ' . $module_name . ' : ' . $this->input->ip_address(), 'modulebuilder');

					Template::set_message('The module and associated database entries were successfully deleted.', 'success');
					Template::redirect(SITE_AREA .'/developer/modulebuilder');
				} else {
					Template::set_message('The module and associated database entries were successfully deleted, HOWEVER, the module folder and files were not removed. They must be removed manually.', 'info');
				}
			}
		}
		
		Template::render();
	}

	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: validate_form()
		
		Handles the validation of the modulebuilder form.
	*/
	private function validate_form($field_total=0) 
	{
		$this->form_validation->set_rules("contexts_content",'Contexts :: Content',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("contexts_developer",'Contexts :: Developer',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("contexts_public",'Contexts :: Public',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("contexts_reports",'Contexts :: Reports',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("contexts_settings",'Contexts :: Settings',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("db_required",'Generate Migration',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("form_action_create",'Form Actions :: View',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("form_action_delete",'Form Actions :: View',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("form_action_edit",'Form Actions :: View',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("form_action_view",'Form Actions :: List',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("form_error_delimiters",'Form Error Delimiters',"required|trim|xss_clean");
		$this->form_validation->set_rules("form_input_delimiters",'Form Input Delimiters',"required|trim|xss_clean");
		$this->form_validation->set_rules("module_description",'Module Description',"trim|required|xss_clean");
		$this->form_validation->set_rules("module_name",'Module Name',"trim|required|xss_clean|alpha_dash");
		$this->form_validation->set_rules("role_id",'Give Role Full Access',"trim|xss_clean|is_numeric");
		
		// no point doing all this checking if we don't want a table
		if ($this->input->post('db_required')) {
			$this->form_validation->set_rules("primary_key_field",'Primary Key Field',"required|trim|xss_clean|alpha_dash");
			$this->form_validation->set_rules("table_name",'Table Name',"trim|required|xss_clean|alpha_dash");
			$this->form_validation->set_rules("textarea_editor",'Textarea Editor',"trim|xss_clean|alpha_dash");
			$this->form_validation->set_rules("use_soft_deletes",'Soft Deletes',"trim|xss_clean|alpha");
			$this->form_validation->set_rules("use_created",'Use Created Field',"trim|xss_clean|alpha");
			$this->form_validation->set_rules("created_field",'Created Field Name',"trim|xss_clean|alpha_dash");
			$this->form_validation->set_rules("use_modified",'Use Modified Field',"trim|xss_clean|alpha");
			$this->form_validation->set_rules("modified_field",'Modified Field Name',"trim|xss_clean|alpha_dash");
		
			for($counter=1; $field_total >= $counter; $counter++)
			{
				if ($counter != 1) // better to do it this way round as this statement will be fullfilled more than the one below
				{
					$this->form_validation->set_rules("view_field_label$counter","Label $counter",'trim|xss_clean|alpha_extra');
				}
				else
				{
					// the first field always needs to be required i.e. we need to have at least one field in our form
					$this->form_validation->set_rules("view_field_label$counter","Label $counter",'trim|required|xss_clean|alpha_extra');
				}
				
				$name_required = '';
				$label = $this->input->post("view_field_label$counter");
				if( !empty($label) )
				{
					$name_required = 'required|';
				}
				$this->form_validation->set_rules("view_field_name$counter","Name $counter","trim|".$name_required."callback_no_match[$counter]|xss_clean");
				$this->form_validation->set_rules("view_field_type$counter","Field Type $counter","trim|required|xss_clean|alpha");
				$this->form_validation->set_rules("db_field_type$counter","DB Field Type $counter","trim|xss_clean|alpha");
				
				// make sure that the length field is required if the DB Field type requires a length
				$db_len_required = '';
				$field_type = $this->input->post("db_field_type$counter");
				if( !empty($label) && !($field_type == 'TEXT' OR $field_type == 'BOOL'
					OR $field_type == 'DATE' OR $field_type == 'TIME' OR $field_type == 'DATETIME'
					OR $field_type == 'TIMESTAMP' OR $field_type == 'YEAR'
					 OR $field_type == 'TINYBLOB' OR $field_type == 'BLOB' OR $field_type == 'MEDIUMBLOB' OR $field_type == 'LONGBLOB') )
				{
					$db_len_required = 'required|';
				}
				$this->form_validation->set_rules("db_field_length_value$counter","DB Field Length $counter","trim|".$db_len_required."xss_clean");
				$this->form_validation->set_rules('validation_rules'.$counter.'[]',"Validation Rules $counter",'trim|xss_clean');
			}
		}
		return $this->form_validation->run();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: build_module()
		
		Handles the heavy-lifting of building a module from ther user's specs.
	*/
	private function build_module($field_total=0) 
	{
		$module_name 		= $this->input->post('module_name');
		$table_name 		= str_replace(' ','_',strtolower($this->input->post('table_name')));
		$contexts 			= $this->input->post('contexts');
		$action_names 		= $this->input->post('form_action');
		$module_description = $this->input->post('module_description');
		$role_id			= $this->input->post('role_id');
		
		$db_required = isset($_POST['db_required']) ? TRUE : FALSE;
		
		$primary_key_field = $this->input->post('primary_key_field');
		if( $primary_key_field == '') {
			$primary_key_field = $this->options['primary_key_field'];
		}
		$primary_key_field = strtolower($primary_key_field);
		
		$form_input_delimiters = explode(',', $this->input->post('form_input_delimiters'));
		
		if( !is_array($form_input_delimiters) OR count($form_input_delimiters) != 2) {
			$form_input_delimiters = $this->options['form_input_delimiters'];
		}

		$form_error_delimiters = explode(',', $this->input->post('form_error_delimiters'));
		if( !is_array($form_error_delimiters) OR count($form_error_delimiters) != 2) {
			$form_error_delimiters = $this->options['$form_error_delimiters'];
		}
		
		$file_data = $this->modulebuilder->build_files($field_total, $module_name, $contexts, $action_names, $primary_key_field, $db_required, $form_input_delimiters, $form_error_delimiters, $module_description, $role_id, $table_name);

		// make the variables available to the view file
		$data['module_name']		= $module_name;
		$data['module_name_lower']	= strtolower($module_name);
		$data['controller_name']	= $module_name;
		$data['table_name']			= empty($table_name) ? $module_name : $table_name;
		$data = $data + $file_data;
		
		// update the schema first to prevent errors in duplicate column names due to Migrations.php caching db columns
		$this->load->dbforge();
		$this->dbforge->add_column('schema_version', array(
				$data['module_name_lower'] . '_version'	=> array(
				'type'			=> 'INT',
				'constraint'	=> 4,
				'null'			=> true, 
				'default'		=> 0
			)
		));	
		
		// load the migrations library
		$this->load->library('migrations/Migrations');
		// run the migration install routine
		if ($this->migrations->install($data['module_name_lower'] . '_'))
		{
			$data['mb_migration_result'] = 'mb_out_tables_success';
		}
		else 
		{
			$data['mb_migration_result'] = 'mb_out_tables_error';
		}
		
		Template::set($data);
	}
	
	//--------------------------------------------------------------------
	
	
	/** Custom Form Validation Callback Rule
	 *
	 * Checks that one field doesn't match all the others.
	 * This code is not really portable. Would of been nice to create a rule that accepted an array
	 *
	 * @access	public
	 * @param	string
	 * @param	fields array
	 * @return	bool
	 */

	function no_match($str, $fieldno)
	{		
		for($counter=1; $this->field_total >= $counter; $counter++)
		{
			// nothing has been entered into this field so we don't need to check
			// or the field being checked is the same as the field we are checking from
			if ($_POST["view_field_name$counter"] == '' || $fieldno == $counter) 			
			{
				continue;				
			}

			if ($str == $_POST["view_field_name{$counter}"])
			{
				$this->form_validation->set_message('no_match', "Field names ($fieldno & $counter) must be unique!");
				return FALSE;
			}
		}

		return TRUE;
	}

	//--------------------------------------------------------------------
	
	
	/** Check that the Modules folder is writeable
	 *
	 * @access	private
	 * @return	bool
	 */
	function _check_writeable()
	{
		return is_writeable($this->options['output_path']);
		
	}//end _check_writeable()
}
