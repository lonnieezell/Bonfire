<?php

$controller = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($controller_name).' extends ';
$controller .= $controller_name == $module_name_lower ? "Front_controller" : "Admin_Controller";
$controller .= ' {
               
	function __construct()
	{
 		parent::__construct();
		
		$this->auth->restrict(\''.ucfirst($module_name).'.'.ucfirst($controller_name).'.View\');
		
		$this->load->model(\''.$module_name_lower.'_model\');
		$this->lang->load(\''.$module_name_lower.'\');
		
		Assets::add_js($this->load->view(\''.$controller_name.'/js\', null, true), \'inline\');

		Template::set("toolbar_title", "Manage '.$module_name.'");
	}
	
	';

	if(in_array('index', $action_names) ) {

$controller .= '
	/** 
	 * function index
	 *
	 * list form data
	 */
	public function index()
	{
		$data = array();
		$data["records_array"] = $this->'.$module_name_lower.'_model->find_all();

		Template::set_view("'.$controller_name.'/index");
		Template::set("data", $data);
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	';
	}

	if(in_array('create', $action_names) ) {
		$controller .= '
	public function create() 
	{
		$this->auth->restrict(\''.ucfirst($module_name).'.'.ucfirst($controller_name).'.Create\');

		if ($this->input->post(\'submit\'))
		{
			if ($this->save_'.$module_name_lower.'())
			{
				Template::set_message(lang("'.$module_name_lower.'_create_success"), \'success\');
				Template::redirect(\'/admin/'.$controller_name.'/'.$module_name_lower.'\');
			}
			else 
			{
				Template::set_message(lang("'.$module_name_lower.'_create_failure") . $this->'.$module_name_lower.'_model->error, \'error\');
			}
		}
	
		Template::set(\'toolbar_title\', lang("'.$module_name_lower.'_create_new_button"));
		Template::set_view(\''.$controller_name.'/create\');
		Template::render();
	}
	//--------------------------------------------------------------------
	
	';
	}
	if( in_array('edit', $action_names) ) {
		$controller .= '
	public function edit() 
	{
		$this->auth->restrict(\''.ucfirst($module_name).'.'.ucfirst($controller_name).'.Edit\');

		$id = (int)$this->uri->segment(5);
		
		if (empty($id))
		{
			Template::set_message(lang("'.$module_name_lower.'_invalid_id"), \'error\');
			redirect(\'/admin/'.$controller_name.'/'.$module_name_lower.'\');
		}
	
		if ($this->input->post(\'submit\'))
		{
			if ($this->save_'.$module_name_lower.'(\'update\', $id))
			{
				Template::set_message(lang("'.$module_name_lower.'_edit_success"), \'success\');
			}
			else 
			{
				Template::set_message(lang("'.$module_name_lower.'_edit_failure") . $this->'.$module_name_lower.'_model->error, \'error\');
			}
		}
		
		Template::set(\''.$module_name_lower.'\', $this->'.$module_name_lower.'_model->find($id));
	
		Template::set(\'toolbar_title\', lang("'.$module_name_lower.'_edit_heading"));
		Template::set_view(\''.$controller_name.'/edit\');
		Template::render();		
	}
	
			';
	}
	
	if(in_array('delete', $action_names) ) {
		$controller .= '
	public function delete() 
	{	
		$this->auth->restrict(\''.ucfirst($module_name).'.'.ucfirst($controller_name).'.Delete\');

		$id = $this->uri->segment(5);
	
		if (!empty($id))
		{	
			if ($this->'.$module_name_lower.'_model->delete($id))
			{
				Template::set_message(lang("'.$module_name_lower.'_delete_success"), \'success\');
			} else
			{
				Template::set_message(lang("'.$module_name_lower.'_delete_failure") . $this->'.$module_name_lower.'_model->error, \'error\');
			}
		}
		
		redirect(\'/admin/'.$controller_name.'/'.$module_name_lower.'\');
	}
		';
	}
	
	$controller .= '
	public function save_'.$module_name_lower.'($type=\'insert\', $id=0) 
	{	
';
		$last_field = 0;
		for($counter=1; $field_total >= $counter; $counter++)
		{
			// only build on fields that have data entered. 
	
			//Due to the requiredif rule if the first field is set the the others must be
	
			if (set_value("view_field_label$counter") == NULL)
			{
				continue; 	// move onto next iteration of the loop
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
			
			if (set_value("db_field_type$counter") != 'ENUM' && set_value("db_field_length_value$counter") != NULL)
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
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		if ($type == \'insert\')
		{
			$id = $this->'.$module_name_lower.'_model->insert($_POST);
			
			if (is_numeric($id))
			{
				$return = true;
			} else
			{
				$return = false;
			}
		}
		else if ($type == \'update\')
		{
			$return = $this->'.$module_name_lower.'_model->update($id, $_POST);
		}
		
		return $return;
	}

}
';
	
	echo $controller;
?>