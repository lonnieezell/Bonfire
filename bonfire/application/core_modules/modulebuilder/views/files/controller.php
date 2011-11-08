<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Define the various parts of the class here as variables with 
	{placeholders} for variable data. Below, we'll replace the parts
	as needed. 
	
	This should make modifying the way the class is built much easier.
*/

//--------------------------------------------------------------------
// !CLASS PARTS
//--------------------------------------------------------------------

$mb_class_wrapper =<<<END
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class {$controller_name} extends {extend_class} {

	//--------------------------------------------------------------------

{class_content}

}
END;

//--------------------------------------------------------------------

$mb_constructor =<<<END
	public function __construct() 
	{
		parent::__construct();

		{restrict}
		\$this->load->model('{$module_name_lower}_model', null, true);
		\$this->lang->load('{$module_name_lower}');
		
		{constructor_extras}
	}
	
	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------

$mb_index =<<<END
	/*
		Method: index()
		
		Displays a list of form data.
	*/
	public function index() 
	{
		\$data = array();
		\$data['records'] = \$this->{$module_name_lower}_model->find_all();

		Assets::add_js(\$this->load->view('{$controller_name}/js', null, true), 'inline');
		
		Template::set('data', \$data);
		Template::set('toolbar_title', "Manage {$module_name}");
		Template::render();
	}
	
	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------

$mb_index_front =<<<END
	/*
		Method: index()
		
		Displays a list of form data.
	*/
	public function index() 
	{
		\$data = array();
		\$data['records'] = \$this->{$module_name_lower}_model->find_all();

		Template::set('data', \$data);
		Template::render();
	}
	
	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------

$mb_create =<<<END
	/*
		Method: create()
		
		Creates a {$module_name} object.
	*/
	public function create() 
	{
		\$this->auth->restrict('{create_permission}');

		if (\$this->input->post('submit'))
		{
			if (\$insert_id = \$this->save_{$module_name_lower}())
			{
				// Log the activity
				\$this->activity_model->log_activity(\$this->auth->user_id(), lang('{$module_name_lower}_act_create_record').': ' . \$insert_id . ' : ' . \$this->input->ip_address(), '{$module_name_lower}');
					
				Template::set_message(lang("{$module_name_lower}_create_success"), 'success');
				Template::redirect(SITE_AREA .'/{$controller_name}/{$module_name_lower}');
			}
			else 
			{
				Template::set_message(lang('{$module_name_lower}_create_failure') . \$this->{$module_name_lower}_model->error, 'error');
			}
		}
	
		Template::set('toolbar_title', lang('{$module_name_lower}_create_new_button'));
		Template::set('toolbar_title', lang('{$module_name_lower}_create') . ' {$module_name}');
		Template::render();
	}
	
	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------

$mb_edit =<<<END
	/*
		Method: edit()
		
		Allows editing of {$module_name} data.
	*/
	public function edit() 
	{
		\$this->auth->restrict('{edit_permission}');

		\$id = (int)\$this->uri->segment(5);
		
		if (empty(\$id))
		{
			Template::set_message(lang('{$module_name_lower}_invalid_id'), 'error');
			redirect(SITE_AREA .'/{$controller_name}/{$module_name_lower}');
		}
	
		if (\$this->input->post('submit'))
		{
			if (\$this->save_{$module_name_lower}('update', \$id))
			{
				// Log the activity
				\$this->activity_model->log_activity(\$this->auth->user_id(), lang('{$module_name_lower}_act_edit_record').': ' . \$id . ' : ' . \$this->input->ip_address(), '{$module_name_lower}');
					
				Template::set_message(lang('{$module_name_lower}_edit_success'), 'success');
			}
			else 
			{
				Template::set_message(lang('{$module_name_lower}_edit_failure') . \$this->{$module_name_lower}_model->error, 'error');
			}
		}
		
		Template::set('{$module_name_lower}', \$this->{$module_name_lower}_model->find(\$id));
	
		Template::set('toolbar_title', lang('{$module_name_lower}_edit_heading'));
		Template::set('toolbar_title', lang('{$module_name_lower}_edit') . ' {$module_name}');
		Template::render();		
	}
	
	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------

$mb_delete =<<<END
	/*
		Method: delete()
		
		Allows deleting of {$module_name} data.
	*/
	public function delete() 
	{	
		\$this->auth->restrict('{delete_permission}');

		\$id = \$this->uri->segment(5);
	
		if (!empty(\$id))
		{	
			if (\$this->{$module_name_lower}_model->delete(\$id))
			{
				// Log the activity
				\$this->activity_model->log_activity(\$this->auth->user_id(), lang('{$module_name_lower}_act_delete_record').': ' . \$id . ' : ' . \$this->input->ip_address(), '{$module_name_lower}');
					
				Template::set_message(lang('{$module_name_lower}_delete_success'), 'success');
			} else
			{
				Template::set_message(lang('{$module_name_lower}_delete_failure') . \$this->{$module_name_lower}_model->error, 'error');
			}
		}
		
		redirect(SITE_AREA .'/{$controller_name}/{$module_name_lower}');
	}
	
	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------

$mb_save =<<<END
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	/*
		Method: save_{$module_name_lower}()
		
		Does the actual validation and saving of form data.
		
		Parameters:
			\$type	- Either "insert" or "update"
			\$id		- The ID of the record to update. Not needed for inserts.
		
		Returns:
			An INT id for successful inserts. If updating, returns TRUE on success.
			Otherwise, returns FALSE.
	*/
	private function save_{$module_name_lower}(\$type='insert', \$id=0) 
	{	
		{validation_rules}

		if (\$this->form_validation->run() === FALSE)
		{
			return FALSE;
		}
		
		// make sure we only pass in the fields we want
		{save_data_array}
		
		if (\$type == 'insert')
		{
			\$id = \$this->{$module_name_lower}_model->insert(\$data);
			
			if (is_numeric(\$id))
			{
				\$return = \$id;
			} else
			{
				\$return = FALSE;
			}
		}
		else if (\$type == 'update')
		{
			\$return = \$this->{$module_name_lower}_model->update(\$id, \$data);
		}
		
		return \$return;
	}

	//--------------------------------------------------------------------


END;

//--------------------------------------------------------------------
// !BUILD THE CLASS
//--------------------------------------------------------------------

// Constructor
$body = $mb_constructor;

if ($controller_name == $module_name_lower)
{
	$body = str_replace('{restrict}', '$this->load->library(\'form_validation\');', $body);
} else
{
	$body = str_replace('{restrict}', '$this->auth->restrict(\''.str_replace(" ", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.View\');', $body);
}
$extras = '';

$date_included = FALSE;
$datetime_included = FALSE;
$textarea_included = FALSE;
for($counter=1; $field_total >= $counter; $counter++)
{
	$db_field_type = set_value("db_field_type$counter");
	$field_name = $module_name_lower.'_'.set_value("view_field_name$counter");
	$view_datepicker = '';
	if ($db_field_type != NULL)
	{
		if ($db_field_type == 'DATE' AND $date_included === FALSE)
		{
			$extras .= '
			Assets::add_css(\'flick/jquery-ui-1.8.13.custom.css\');
			Assets::add_js(\'jquery-ui-1.8.8.min.js\');';
			$date_included = TRUE;
		}
		elseif ($db_field_type == 'DATETIME' && $datetime_included === FALSE)
		{
			// if a date field hasn't been included already then add in the jquery ui files
			if ($date_included === FALSE)
			{
				$extras .= '
				Assets::add_css(\'flick/jquery-ui-1.8.13.custom.css\');
				Assets::add_js(\'jquery-ui-1.8.8.min.js\');';
			}
			$extras .= '
			Assets::add_css(\'jquery-ui-timepicker.css\');
			Assets::add_js(\'jquery-ui-timepicker-addon.js\');';
			$date_included = TRUE;
			$datetime_included = TRUE;
		}
		elseif ($db_field_type == 'TEXT' AND $textarea_included === FALSE AND !empty($textarea_editor) )
		{
			// if a date field hasn't been included already then add in the jquery ui files
			if ($textarea_editor == 'ckeditor') {
				$extras .= '
				Assets::add_js(Template::theme_url(\'js/editors/ckeditor/ckeditor.js\'));';
			}
			elseif ($textarea_editor == 'xinha') {
				$extras .= '
				Assets::add_js(Template::theme_url(\'js/editors/xinha_conf.js\'));
				Assets::add_js(Template::theme_url(\'js/editors/xinha/XinhaCore.js\'));';
			}
			$textarea_included = TRUE;
		}
	}
}

$body = str_replace('{constructor_extras}', $extras, $body);
unset($extras);

//--------------------------------------------------------------------

// Index Method

if (in_array('index', $action_names))
{
	// check if this is the front controller
	if ($controller_name == $module_name_lower)
	{
		$body .= $mb_index_front;
	}
	else {
		$body .= $mb_index;
	}
}

//--------------------------------------------------------------------
// check if this is the front controller
if ($controller_name != $module_name_lower)
{

	// Create

	if (in_array('create', $action_names))
	{
		$body .= $mb_create;

		$body = str_replace('{create_permission}', str_replace(" ", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Create', $body);
	}

	//--------------------------------------------------------------------

	// Edit

	if (in_array('edit', $action_names))
	{
		$body .= $mb_edit;

		$body = str_replace('{edit_permission}', ucfirst($module_name).'.'.ucfirst($controller_name).'.Edit', $body);
	}

	//--------------------------------------------------------------------

	// Delete

	if (in_array('delete', $action_names))
	{
		$body .= $mb_delete;

		$body = str_replace('{delete_permission}', str_replace(" ", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Delete', $body);
	}

	//--------------------------------------------------------------------

	// Save

	$body .= $mb_save;

	$rules = '';
	$save_data_array = '
		$data = array();';

	$last_field = 0;
	for($counter=1; $field_total >= $counter; $counter++)
	{
		// only build on fields that have data entered. 

		// Due to the required if rule if the first field is set the the others must be

		if (set_value("view_field_label$counter") == NULL)
		{
			continue; 	// move onto next iteration of the loop
		}

		// we set this variable as it will be used to place the comma after the last item to build the insert db array
		$last_field = $counter;

		$rules .= '			
		$this->form_validation->set_rules(\''.$module_name_lower.'_'.set_value("view_field_name$counter").'\',\''.set_value("view_field_label$counter").'\',\'';
		
		$save_data_array .= '
		$data[\''.$module_name_lower.'_'.set_value("view_field_name$counter").'\']        = $this->input->post(\''.$module_name_lower.'_'.set_value("view_field_name$counter").'\');';

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
					$rules .= '|';
				}

				if ($value == 'unique')	{		
					$prefix = $this->db->dbprefix;
					$rules .= $value.'['.$prefix.$table_name.'.'.$module_name_lower.'_'.set_value("view_field_name$counter").','.$prefix.$table_name.'.'.set_value("primary_key_field").']';
				} else {
					$rules .= $value;	
				}
				$rule_counter++;
			}
		}

		if (set_value("db_field_type$counter") != 'ENUM' && set_value("db_field_length_value$counter") != NULL)
		{
			if ($rule_counter > 0)
			{
				$rules .= '|';
			}

			$rules .= 'max_length['.set_value("db_field_length_value$counter").']';
		}

		$rules .= "');";
	}

	$body = str_replace('{validation_rules}', $rules, $body);
	$body = str_replace('{save_data_array}', $save_data_array, $body);
	
	unset($rules);
}

//--------------------------------------------------------------------

// Wrap the class content into the actual class

$controller = str_replace('{class_content}', $body, $mb_class_wrapper);

if ($controller_name == $module_name_lower)
{
	$controller = str_replace('{extend_class}', 'Front_Controller', $controller);
} else 
{
	$controller = str_replace('{extend_class}', 'Admin_Controller', $controller);
}


// Echo out the final controller

echo $controller;

// Clean up memory

unset($body, $mb_class_wrapper, $mb_constructor, $mb_index, $mb_create, $mb_edit, $mb_delete, $mb_save, $controller);

?>