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
	public $field_numbers = array(6,10,20,40);

	public function __construct()
	{
		parent::__construct();
		
		$this->auth->restrict('Bonfire.Database.Manage');
		$this->load->library('modulebuilder');
		$this->load->config('modulebuilder');
		
		$this->options = $this->config->item( 'modulebuilder' );

		
		Template::set('sidebar', 'admin/sidebar');
	}

	//---------------------------------------------------------------

	/**
	 * Displays a list of tables in the database.
	 */
	public function index()
	{
		$hide_form = false;
				

		// $this->load->helper('date_helper'); not required for v1
		$field_total = 6;
		$tot = $this->uri->total_segments();
		$last_seg = $this->uri->segment( $tot);

		if( is_numeric($last_seg) ) {
			$field_total = $last_seg;
		}

		// make this available to my callback function
		$this->field_total = $field_total;

		$fields_array = array('view_field_name1','view_field_name2','view_field_name3');

		$this->form_validation->set_rules("module_name",'Module Name',"trim|required|xss_clean");
		$this->form_validation->set_rules("main_context",'Contexts',"required|xss_clean|is_array");
		$this->form_validation->set_rules("contexts",'Contexts',"required|xss_clean|is_array");
		$this->form_validation->set_rules("form_action",'Controller Actions',"required|xss_clean|is_array");
		$this->form_validation->set_rules("db_required",'DB Required',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("ajax_processing",'Ajax Processing',"trim|xss_clean|is_numeric");
		$this->form_validation->set_rules("form_input_delimiters",'Form Input Delimiters',"required|trim|xss_clean");
		$this->form_validation->set_rules("form_error_delimiters",'Form Error Delimiters',"required|trim|xss_clean");
		
		for($counter=1; $field_total >= $counter; $counter++)
		{
			if ($counter != 1) // better to do it this way round as this statement will be fullfilled more than the one below
			{
				$this->form_validation->set_rules("view_field_label$counter",'Label','trim|xss_clean');       
			}
			else
			{
				// the first field always needs to be required i.e. we need to have at least one field in our form
				$this->form_validation->set_rules("view_field_label$counter",'Label','trim|required|xss_clean');
			}
			
			$this->form_validation->set_rules("view_field_name$counter",'Name',"trim|requiredif[view_field_label$counter]|callback_no_match[$counter]|xss_clean");
			$this->form_validation->set_rules("view_field_type$counter",'Field Type',"trim|requiredif[view_field_label$counter]|xss_clean");
			$this->form_validation->set_rules("db_field_type$counter",'DB Field Type',"trim|requiredif[view_field_label$counter]|xss_clean");
			$this->form_validation->set_rules("db_field_length_value$counter",'DB Field Length',"trim|requiredif[view_field_label$counter]|xss_clean");
			$this->form_validation->set_rules('validation_rules'.$counter.'[]','Validation Rules','trim|xss_clean');
		}
			
		$this->form_validation->set_error_delimiters('<div class="error">Error: ', '</div>');
		
		if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		{

			$data['field_total'] = $field_total;
			
			if (!empty($_POST))
			{
				$data['form_error'] = TRUE;
			}
			else
			{
				$data['form_error'] = FALSE;
			}
			
			$data['form_action_options'] = $this->options['form_action_options'];
			$data['field_numbers'] = $this->field_numbers;
			Template::set_view('form');
			
		}
		else // passed validation proceed to second page
		{
			$module_name = $this->input->post('module_name');
			$main_context = $this->input->post('main_context');
			$contexts = $this->input->post('contexts');
			$action_names = $this->input->post('form_action');
			$db_required = isset($_POST['db_required']) ? TRUE : FALSE;
			$ajax_processing = isset($_POST['ajax_processing']) ? TRUE : FALSE;
			$form_input_delimiters = explode(',', $this->input->post('form_input_delimiters'));
			if( !is_array($form_input_delimiters) OR count($form_input_delimiters) != 2) {
				$form_input_delimiters = $this->options['form_input_delimiters'];
			}

			$form_error_delimiters = explode(',', $this->input->post('form_error_delimiters'));
			if( !is_array($form_error_delimiters) OR count($form_error_delimiters) != 2) {
				$form_error_delimiters = $this->options['$form_error_delimiters'];
			}
			
			$file_data = $this->modulebuilder->build_files($field_total, $module_name, $main_context, $contexts, $action_names, $db_required, $ajax_processing, $form_input_delimiters, $form_error_delimiters);

			// make the variables available to the view file		
			$data['module_name'] = $module_name;
			$data['module_name_lower'] = strtolower($module_name);
			$data['controller_name'] = $module_name;
			$data = $data + $file_data;
			
			Template::set_view('output');
		}
				
		Template::set('error', array());
		Template::set('data', $data);
		if (!Template::get('toolbar_title'))
		{
			Template::set('toolbar_title', 'Module Builder');
		}
		Template::render();

	}

	
}

// END ___ class

/* End of file ___.php */
/* Location: ./application/controllers/___.php */