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
	
	public $field_numbers = array(6,10,20,40);

	//---------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Developer.View');
		$this->load->library('modulebuilder');
		$this->load->config('modulebuilder');
		
		$this->options = $this->config->item('modulebuilder');
	}

	//---------------------------------------------------------------

	/**
	 * Displays a list of tables in the database.
	 */
	public function index()
	{
		$hide_form = false;
		$this->field_total = 6;
		$last_seg = $this->uri->segment( $this->uri->total_segments() );

		if (is_numeric($last_seg)) {
			$this->field_total = $last_seg;
		}
		
		if ($this->validate_form($this->field_total) == FALSE) // validation hasn't been passed
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
			
			Template::set('form_action_options', $this->options['form_action_options']);
			Template::set('field_numbers', $this->field_numbers);
		}
		else // passed validation proceed to second page
		{
			$this->build_module($this->field_total);
			
			Template::set_view('developer/output');
		}
				
		Template::set('error', array());

		Template::set('toolbar_title', 'Module Builder');
		Template::render();
	}

	//--------------------------------------------------------------------
	
	private function validate_form($field_total=0) 
	{
		$this->form_validation->set_rules("module_name",'Module Name',"trim|required|xss_clean");
		$this->form_validation->set_rules("contexts",'Contexts',"required|xss_clean|is_array");
		$this->form_validation->set_rules("form_action",'Controller Actions',"required|xss_clean|is_array");
		$this->form_validation->set_rules("db_required",'DB Required',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("primary_key_field",'Primary Key Field',"required|trim|xss_clean");
		$this->form_validation->set_rules("form_input_delimiters",'Form Input Delimiters',"required|trim|xss_clean");
		$this->form_validation->set_rules("form_error_delimiters",'Form Error Delimiters',"required|trim|xss_clean");
		$this->form_validation->set_rules("textarea_editor",'Textarea Editor',"trim|xss_clean");
		
		for($counter=1; $field_total >= $counter; $counter++)
		{
			if ($counter != 1) // better to do it this way round as this statement will be fullfilled more than the one below
			{
				$this->form_validation->set_rules("view_field_label$counter","Label $counter",'trim|xss_clean');
			}
			else
			{
				// the first field always needs to be required i.e. we need to have at least one field in our form
				$this->form_validation->set_rules("view_field_label$counter","Label $counter",'trim|required|xss_clean');
			}
			
			$name_required = '';
			$label = $this->input->post("view_field_label$counter");
			if( !empty($label) )
			{
				$name_required = 'required|';
			}
			$this->form_validation->set_rules("view_field_name$counter","Name $counter","trim|".$name_required."callback_no_match[$counter]|xss_clean");
			$this->form_validation->set_rules("view_field_type$counter","Field Type $counter","trim|required|xss_clean");
			$this->form_validation->set_rules("db_field_type$counter","DB Field Type $counter","trim|xss_clean");
			
			// make sure that the length field is required if the DB Field type requires a length
			$db_len_required = '';
			$field_type = $this->input->post("db_field_type$counter");
			if( !empty($label) && !($field_type == 'TEXT' 
				OR $field_type == 'DATE' OR $field_type == 'TIME' OR $field_type == 'DATETIME'
				OR $field_type == 'TIMESTAMP' OR $field_type == 'YEAR'
				 OR $field_type == 'TINYBLOB' OR $field_type == 'BLOB' OR $field_type == 'MEDIUMBLOB' OR $field_type == 'LONGBLOB') )
			{
				$db_len_required = 'required|';
			}
			$this->form_validation->set_rules("db_field_length_value$counter","DB Field Length $counter","trim|".$db_len_required."xss_clean");
			$this->form_validation->set_rules('validation_rules'.$counter.'[]',"Validation Rules $counter",'trim|xss_clean');
		}
		
		return $this->form_validation->run();
	}
	
	//--------------------------------------------------------------------
	
	private function build_module($field_total=0) 
	{
		$module_name = $this->input->post('module_name');
		$contexts = $this->input->post('contexts');
		$action_names = $this->input->post('form_action');
		
		$db_required = isset($_POST['db_required']) ? TRUE : FALSE;
		$primary_key_field = $this->input->post('primary_key_field');
		if( $primary_key_field == '') {
			$primary_key_field = $this->options['primary_key_field'];
		}
		
		$form_input_delimiters = explode(',', $this->input->post('form_input_delimiters'));
		
		if( !is_array($form_input_delimiters) OR count($form_input_delimiters) != 2) {
			$form_input_delimiters = $this->options['form_input_delimiters'];
		}

		$form_error_delimiters = explode(',', $this->input->post('form_error_delimiters'));
		if( !is_array($form_error_delimiters) OR count($form_error_delimiters) != 2) {
			$form_error_delimiters = $this->options['$form_error_delimiters'];
		}
		
		$file_data = $this->modulebuilder->build_files($field_total, $module_name, $contexts, $action_names, $primary_key_field, $db_required, $form_input_delimiters, $form_error_delimiters);

		// make the variables available to the view file
		$data['module_name']		= $module_name;
		$data['module_name_lower']	= strtolower($module_name);
		$data['controller_name']	=  $module_name;
		$data = $data + $file_data;
		
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


}
