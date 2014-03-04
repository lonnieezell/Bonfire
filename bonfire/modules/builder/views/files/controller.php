<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
 * Define the various parts of the class here as variables with {placeholders}
 * for variable data. Below, replace the parts as needed.
 *
 * This should make modifying the way the class is built much easier.
 */

$controller_name_lower = strtolower($controller_name);
$primary_key_field = set_value("primary_key_field");

//--------------------------------------------------------------------
// !CLASS PARTS
//--------------------------------------------------------------------

$mb_class_wrapper =<<<END
<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * {$controller_name} controller
 */
class {$controller_name} extends {extend_class}
{
{class_content}
}
END;

//--------------------------------------------------------------------

$mb_constructor = "
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		{restrict}";

if ($db_required != '') {
	$mb_constructor .= "
		\$this->load->model('{$module_name_lower}_model', null, true);";
}

$mb_constructor .= "
		\$this->lang->load('{$module_name_lower}');
		{constructor_extras}";

// Check that this is an admin area controller before adding the sub_nav
if ($controller_name_lower != $module_name_lower) {
	$mb_constructor .= "
		Template::set_block('sub_nav', '{$controller_name_lower}/_sub_nav');";
}

$mb_constructor .= "

		Assets::add_module_js('{$module_name_lower}', '{$module_name_lower}.js');
	}";

//--------------------------------------------------------------------

$mb_index = "

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index(";

$usePagination = false;
if ($this->input->post('use_pagination') == 'true') {
    $usePagination = true;
    $mb_index .= "\$offset = 0";
}

$mb_index .= ")
	{
";

if ($db_required != '') {
	$mb_index .= "
		// Deleting anything?
		if (isset(\$_POST['delete'])) {
			\$checked = \$this->input->post('checked');
			if (is_array(\$checked) && count(\$checked)) {
				\$result = false;
				foreach (\$checked as \$pid) {
					\$result = \$this->{$module_name_lower}_model->delete(\$pid);
				}

				if (\$result) {
					Template::set_message(count(\$checked) . ' ' . lang('{$module_name_lower}_delete_success'), 'success');
				} else {
					Template::set_message(lang('{$module_name_lower}_delete_failure') . \$this->{$module_name_lower}_model->error, 'error');
				}
			}
		}";

    if ($usePagination) {
        $mb_index .= "
        \$limit  = \$this->settings_lib->item('site.list_limit') ?: 15;
        \$pagerBaseUrl = site_url(SITE_AREA . '/{$controller_name_lower}/{$module_name_lower}/index') . '/';
        \$pagerUriSegment = 5;

        \$this->load->library('pagination');
        \$pager['base_url']    = \$pagerBaseUrl;
        \$pager['total_rows']  = \$this->{$module_name_lower}_model->count_all();
        \$pager['per_page']    = \$limit;
        \$pager['uri_segment'] = \$pagerUriSegment;

        \$this->pagination->initialize(\$pager);
        \$this->{$module_name_lower}_model->limit(\$limit, \$offset);";
    }

	$mb_index .= "

		\$records = \$this->{$module_name_lower}_model->find_all();

		Template::set('records', \$records);";
}

$mb_index .= "
		Template::set('toolbar_title', 'Manage {$module_name}');
		Template::render();
	}";

//--------------------------------------------------------------------

$mb_index_front = "

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index(";

    if ($usePagination) {
        $mb_index_front .= "\$offset = 0";
    }

$mb_index_front .= ")
	{
";

if ($db_required != '') {
    if ($usePagination) {
        $mb_index_front .= "
        \$limit  = \$this->settings_lib->item('site.list_limit') ?: 15;
        \$pagerBaseUrl = site_url('{$module_name_lower}/index') . '/';
        \$pagerUriSegment = 3;

        \$this->load->library('pagination');
        \$pager['base_url']    = \$pagerBaseUrl;
        \$pager['total_rows']  = \$this->{$module_name_lower}_model->count_all();
        \$pager['per_page']    = \$limit;
        \$pager['uri_segment'] = \$pagerUriSegment;

        \$this->pagination->initialize(\$pager);
        \$this->{$module_name_lower}_model->limit(\$limit, \$offset);";
    }

	$mb_index_front .= "
		\$records = \$this->{$module_name_lower}_model->find_all();

		Template::set('records', \$records);";
}

$mb_index_front .= "
		Template::render();
	}";

//--------------------------------------------------------------------

$mb_create = "

	/**
	 * Creates a {$module_name} object.
	 *
	 * @return void
	 */
	public function create()
	{
		\$this->auth->restrict('{create_permission}');
";

if ($db_required != '') {
	$mb_create .= "
		if (isset(\$_POST['save'])) {
			if (\$insert_id = \$this->save_{$module_name_lower}()) {
				log_activity(\$this->current_user->id, lang('{$module_name_lower}_act_create_record') . ': ' . \$insert_id . ' : ' . \$this->input->ip_address(), '{$module_name_lower}');
				Template::set_message(lang('{$module_name_lower}_create_success'), 'success');

				redirect(SITE_AREA . '/{$controller_name}/{$module_name_lower}');
			}

			Template::set_message(lang('{$module_name_lower}_create_failure') . \$this->{$module_name_lower}_model->error, 'error');
		}";
}

$mb_create .= "
		Assets::add_module_js('{$module_name_lower}', '{$module_name_lower}.js');

		Template::set('toolbar_title', lang('{$module_name_lower}_create') . ' {$module_name}');
		Template::render();
	}";

//--------------------------------------------------------------------

$mb_edit = "

	/**
	 * Allows editing of {$module_name} data.
	 *
	 * @return void
	 */
	public function edit()
	{
		\$id = \$this->uri->segment(5);
		if (empty(\$id)) {
			Template::set_message(lang('{$module_name_lower}_invalid_id'), 'error');

			redirect(SITE_AREA . '/{$controller_name}/{$module_name_lower}');
		}
";

if ($db_required != '') {
	$mb_edit .= "
		if (isset(\$_POST['save'])) {
			\$this->auth->restrict('{edit_permission}');

			if (\$this->save_{$module_name_lower}('update', \$id)) {
				log_activity(\$this->current_user->id, lang('{$module_name_lower}_act_edit_record') . ': ' . \$id . ' : ' . \$this->input->ip_address(), '{$module_name_lower}');
				Template::set_message(lang('{$module_name_lower}_edit_success'), 'success');
			} else {
				Template::set_message(lang('{$module_name_lower}_edit_failure') . \$this->{$module_name_lower}_model->error, 'error');
			}
		}";

	if (in_array('delete', $action_names)) {
		$mb_edit .= "
		elseif (isset(\$_POST['delete'])) {
			\$this->auth->restrict('{delete_permission}');

			if (\$this->{$module_name_lower}_model->delete(\$id)) {
				log_activity(\$this->current_user->id, lang('{$module_name_lower}_act_delete_record') . ': ' . \$id . ' : ' . \$this->input->ip_address(), '{$module_name_lower}');
				Template::set_message(lang('{$module_name_lower}_delete_success'), 'success');

				redirect(SITE_AREA . '/{$controller_name}/{$module_name_lower}');
			}
            Template::set_message(lang('{$module_name_lower}_delete_failure') . \$this->{$module_name_lower}_model->error, 'error');
		}";
	}

	$mb_edit .= "
		Template::set('{$module_name_lower}', \$this->{$module_name_lower}_model->as_array()->find(\$id));";
}

$mb_edit .= "
		Template::set('toolbar_title', lang('{$module_name_lower}_edit') .' {$module_name}');
		Template::render();
	}";

//--------------------------------------------------------------------

$mb_save =<<<END

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String \$type Either "insert" or "update"
	 * @param Int	 \$id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_{$module_name_lower}(\$type = 'insert', \$id = 0)
	{
		if (\$type == 'update') {
			\$_POST['{$primary_key_field}'] = \$id;
		}

		// Make sure we only pass in the fields we want
		{save_data_array}

        \$return = false;
		if (\$type == 'insert') {
			\$id = \$this->{$module_name_lower}_model->insert(\$data);

			if (is_numeric(\$id)) {
				\$return = \$id;
			}
		} elseif (\$type == 'update') {
			\$return = \$this->{$module_name_lower}_model->update(\$id, \$data);
		}

		return \$return;
	}
END;

//--------------------------------------------------------------------
// !BUILD THE CLASS
//--------------------------------------------------------------------

// Constructor
$body = $mb_constructor;

if ($controller_name == $module_name_lower) {
	$body = str_replace('{restrict}', '$this->load->library(\'form_validation\');', $body);
} else {
	$body = str_replace('{restrict}', '$this->auth->restrict(\'' . preg_replace("/[ -]/", "_", ucfirst($module_name)) . '.' . ucfirst($controller_name) . '.View\');', $body);
}

$extras = '';
$date_included     = false;
$datetime_included = false;
$textarea_included = false;

for ($counter = 1; $field_total >= $counter; $counter++) {
	$db_field_type = set_value("db_field_type$counter");
	$view_datepicker = '';

	if ($db_field_type != null) {
		if ($db_field_type == 'DATE' && $date_included === false) {
			$extras .= "
			Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
			Assets::add_js('jquery-ui-1.8.13.min.js');";
			$date_included = true;
		} elseif ($db_field_type == 'DATETIME' && $datetime_included === false) {
			// If a date field hasn't been included already then add in the jquery ui files
			if ($date_included === false) {
				$extras .= "
			Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
			Assets::add_js('jquery-ui-1.8.13.min.js');";
			}

			$extras .= "
			Assets::add_css('jquery-ui-timepicker.css');
			Assets::add_js('jquery-ui-timepicker-addon.js');";

			$date_included     = true;
			$datetime_included = true;
		} elseif (($db_field_type == 'TEXT' || $db_field_type == 'MEDIUMTEXT' || $db_field_type == 'LONGTEXT' || $db_field_type == 'TINYTEXT')
			&& $textarea_included === false
			&& ! empty($textarea_editor)
		) {
			if ($textarea_editor == 'ckeditor') {
				$extras .= "
			Assets::add_js(Template::theme_url('js/editors/ckeditor/ckeditor.js'));";
			} elseif ($textarea_editor == 'xinha') {
				$extras .= "
			Assets::add_js(Template::theme_url('js/editors/xinha_conf.js'));
			Assets::add_js(Template::theme_url('js/editors/xinha/XinhaCore.js'));";
			} elseif ($textarea_editor == 'markitup') {
				$extras .= "
			Assets::add_css(Template::theme_url('js/editors/markitup/skins/markitup/style.css'));
			Assets::add_css(Template::theme_url('js/editors/markitup/sets/default/style.css'));

			Assets::add_js(Template::theme_url('js/editors/markitup/jquery.markitup.js'));
			Assets::add_js(Template::theme_url('js/editors/markitup/sets/default/set.js'));";
			} elseif ($textarea_editor == 'tinymce') {
				$extras .= "
			Assets::add_js(Template::theme_url('js/editors/tiny_mce/tiny_mce.js'));
			Assets::add_js(Template::theme_url('js/editors/tiny_mce/tiny_mce_init.js'));";
			}

			$textarea_included = true;
		}
	}
}

$body = str_replace('{constructor_extras}', $extras, $body);
unset($extras);

//--------------------------------------------------------------------

// Index Method
if ( is_array($action_names) && in_array('index', $action_names)) {
	// Check whether this is the front controller
	if ($controller_name_lower == $module_name_lower) {
		$body .= $mb_index_front;
	} else {
		$body .= $mb_index;
	}
}

//--------------------------------------------------------------------
// Check whether this is the front controller
if ($controller_name_lower != $module_name_lower) {
	// Create
	if (in_array('create', $action_names)) {
		$body .= $mb_create;
		$body = str_replace('{create_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)) . '.' . ucfirst($controller_name) . '.Create', $body);
	}

	//--------------------------------------------------------------------
	// Edit
	if (in_array('edit', $action_names)) {
		$body .= $mb_edit;
		$body = str_replace('{edit_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)) . '.' . ucfirst($controller_name) . '.Edit', $body);
		$body = str_replace('{delete_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)) . '.' . ucfirst($controller_name) . '.Delete', $body);
	}

	//--------------------------------------------------------------------
	// Save
	if ($db_required != '') {
		$body .= $mb_save;
	}

	$save_data_array = '
		$data = array();';

	for ($counter = 1; $field_total >= $counter; $counter++) {
		// Only build on fields that have data entered.
		if (set_value("view_field_label$counter") == null) {
			continue; 	// move onto next iteration of the loop
		}

		// Set this variable as it will be used to place the comma after the last item to build the insert db array
		$last_field = $counter;
		if ($db_required == 'new' && $table_as_field_prefix === true) {
			$field_name = $module_name_lower . '_' . set_value("view_field_name$counter");
		} else {
			$field_name = set_value("view_field_name$counter");
		}

		$form_name = $module_name_lower . '_' . set_value("view_field_name$counter");

		// Setup the data array for saving to the db
		// Set defaults for certain field types
		switch (set_value("db_field_type$counter")) {
			case 'DATE':
				$save_data_array .= "\n\t\t\$data['{$field_name}']\t= \$this->input->post('{$form_name}') ? \$this->input->post('{$form_name}') : '0000-00-00';";
				break;

			case 'DATETIME':
				$save_data_array .= "\n\t\t\$data['{$field_name}']\t= \$this->input->post('{$form_name}') ? \$this->input->post('{$form_name}') : '0000-00-00 00:00:00';";
				break;

			default:
				$save_data_array .= "\n\t\t\$data['{$field_name}']\t= \$this->input->post('{$form_name}');";
				break;
		}
	}

	$body = str_replace('{save_data_array}', $save_data_array, $body);
}

//--------------------------------------------------------------------

// Wrap the class content into the actual class
$controller = str_replace('{class_content}', $body, $mb_class_wrapper);
if ($controller_name_lower == $module_name_lower) {
	$controller = str_replace('{extend_class}', 'Front_Controller', $controller);
} else {
	$controller = str_replace('{extend_class}', 'Admin_Controller', $controller);
}

// Echo out the final controller
echo $controller;
