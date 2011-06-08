<?php

$controller = '<?php 

class '.ucfirst($controller_name).' extends Admin_Controller {
               
	function __construct()
	{
 		parent::__construct();
		$this->load->library(\'form_validation\');
		$this->load->helper(\'form\');
		$this->load->helper(\'url\');';
		if( $db_required ) {
		$controller .= '
		$this->load->model(\''.$module_name_lower.'_model\');';
		}
$controller .= '

		$this->form_validation->set_error_delimiters("'.$form_error_delimiters[0].'", "'.$form_error_delimiters[1].'");
	}
	
	';

	if(in_array('index', $action_names) ) {

$controller .= '
	/** 
	 * function index
	 *
	 * list form data
	 */
	function index()
	{
		$data = array();
		$data["records_array"] = $this->'.$module_name_lower.'_model->get_all();

		Template::set_view("'.$controller_name.'/index");
		Template::set("data", $data);
		if (!Template::get("toolbar_title"))
		{
			Template::set("toolbar_title", "Manage '.$module_name.'");
		}
		Template::render();
	}
	
	';
	}

	foreach($action_names as $key => $action_name) {

		if ($action_name == 'index')
		{
			continue; 	// move onto next iteration of the loop
		}

		$id_val = '';
		if($action_name != 'insert' && $action_name != 'add') {
			$id_val = '$id';
		}
$controller .= '
	
	/** 
	 * function '.$action_name.'
	 *
	 * '.$action_name.' form data
	 */
	function '.$action_name.'('.$id_val.')
	{';


		// loop to set form validation rules
		$last_field = 0;
		for($counter=1; $field_total >= $counter; $counter++)
		{
			// only build on fields that have data entered. 
	
			//Due to the requiredif rule if the first field is set the the others must be
	
			if (set_value("view_field_label$counter") == NULL)
			{
				continue; 	// move onto next iteration of the loop
			}
			
			if($action_name == 'delete' AND set_value("view_field_name$counter") != 'id') {
				continue;
			}
			
			// we set this variable as it will be used to place the comma after the last item to build the insert db array
			$last_field = $counter;
			
			$controller .= '			
		$this->form_validation->set_rules(\''.set_value("view_field_name$counter").'\',\''.set_value("view_field_label$counter").'\',\'';
			
			// set a friendly variable name
            $validation_rules = $this->input->post('validation_rules'.$counter);

			// rules have been selected for this fieldset
            $rule_counter = 0;

            if (is_array($validation_rules))
            {       
				// add rules such as trim|required|xss_clean
				foreach($validation_rules as $key => $value)
				{
					if ($rule_counter > 0)
					{
						$controller .= '|';
					}
				
					$controller .= $value;
					$rule_counter++;
				}
            }
			
			if (set_value("db_field_length_value$counter") != NULL)
			{
				if ($rule_counter > 0)
				{
					$controller .= '|';
				}

				$controller .= 'max_length['.set_value("db_field_length_value$counter").']';
			}
			
			$controller .= "');";
		}
	
		$controller .= '
			
		if ($this->form_validation->run() == FALSE) // validation hasn\'t been passed
		{
			$data = array();
			';
			if($action_name != 'insert' && $action_name != 'add') {
				$controller .= '
			$data = $this->'.$module_name_lower.'_model->get($id);
				';
			}
			else {
				$controller .= '
			$data = $this->_get_form_data();
				';
			}
			
			$controller .= '
			Template::set_view("'.$controller_name.'/'.$action_name.'");
			Template::set("data", $data);
			if (!Template::get("toolbar_title"))
			{
				Template::set("toolbar_title", "Manage '.$module_name.'");
			}
			Template::render();
		}
		else // passed validation proceed to post success logic
		{
		 	// build data array
			$form_data = $this->_get_form_data();';
		if($action_name != 'insert' && $action_name != 'add') {
			$controller .= '
			$form_data["id"] = $id;';
		}

		if( $db_required ) {
		$controller .= '
					
			// run insert model to write data to db
		
			if ($this->'.$module_name_lower.'_model->'.$action_name.'($form_data) == TRUE)
			{
				// the data was processed successfully
				Template::set_message("'.$action_name.' Successful", "success");
			}
			else
			{
				// the data was NOT processed successfully
				Template::set_message("'.$action_name.' Failed","error");
			}
			redirect("admin/'.$controller_name.'/'.$module_name_lower.'");';
		}
		else {
		$controller .= '
			// Process the form here
			Template::set_message("'.$action_name.' Validation passed", "success");';
			
		}
		$controller .= '
		}

	}';
	} // end foreach
	
	$controller .= '
		
	function _get_form_data()
	{
		$form_data = array(';
		// loop to build form data array
		for($counter=1; $field_total >= $counter; $counter++)
		{
			//Due to the requiredif rule if the first field is set the the others must be
			if (set_value("view_field_label$counter") == NULL)
			{
				continue; 	// move onto next iteration of the loop
			}
			
			$controller .= '
						"'.set_value("view_field_name$counter").'" => set_value("'.set_value("view_field_name$counter").'")';
			
			if ($counter != $last_field)
			{
				// add the comma in
				$controller .= ',';
			}
		}
		$controller .= '
					);
		$id = set_value("id");
		if( $id != "") {
			$form_data["id"] = $id;
		}
		return $form_data;
	}
}
';
	
	echo $controller;
?>