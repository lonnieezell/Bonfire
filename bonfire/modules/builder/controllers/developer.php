<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Module Builder Developer Context Controller
 *
 * This controller displays the list of current modules in the bonfire/modules folder
 * and also allows the users to create new modules.
 *
 * This code is originally based on Ollie Rattue's http://formigniter.org/ project
 *
 * @package    Bonfire
 * @subpackage Modules_ModuleBuilder
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/core_modules/modulebuilder.html
 *
 */
class Developer extends Admin_Controller {

    //---------------------------------------------------------------

    /**
     * Setup restrictions and load configs, libraries and language files
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth->restrict('Site.Developer.View');

        $this->load->library('modulebuilder');
        $this->load->config('modulebuilder');
        $this->load->helper('file');

        $this->lang->load('builder');

        $this->options = $this->config->item('modulebuilder');

        if (isset($this->options['form_error_delimiters'])
            && is_array($this->options['form_error_delimiters'])
            && count($this->options['form_error_delimiters']) == 2) {
            $this->form_validation->set_error_delimiters($this->options['form_error_delimiters'][0], $this->options['form_error_delimiters'][1]);
        }

        Template::set_block('sub_nav', 'developer/_sub_nav');
        Template::set_block('sidebar', 'developer/sidebar');

        Assets::add_module_js('builder', 'modulebuilder.js');

    }//end __construct

    //---------------------------------------------------------------

    /**
     * Displays a list of installed modules with the option to create
     * a new one.
     *
     * @access public
     *
     * @return void
     */
    public function index()
    {
        $modules = module_list(true);
        $configs = array();

        foreach ($modules as $module)
        {
            $configs[$module] = module_config($module);

            if ( ! isset($configs[$module]['name']))
            {
                $configs[$module]['name'] = ucwords($module);
            }
        }

        // check that the modules folder is writeable
        Template::set('writeable', $this->_check_writeable());

        ksort($configs);
        Template::set('modules', $configs);
        Template::set('toolbar_title', lang('mb_toolbar_title_index'));

        Template::render('two_left');

    }//end index()

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !Context Builder
    //--------------------------------------------------------------------

    /**
     * Displays the create a context form.
     *
     * @access	public
     *
     * @return	void
     */
    public function create_context()
    {
    	// Load our roles for display in the form.
    	$this->load->model('roles/role_model');
    	$roles = $this->role_model->select('role_id, role_name')
    							  ->where('deleted', 0)
    							  ->find_all();
    	Template::set('roles', $roles);

    	// Form submittal?
    	if (isset($_POST['build']))
    	{
    		$this->form_validation->set_rules('context_name', 'lang:mb_context_name', 'required|trim|alpha_numeric|xss_clean');

    		if ($this->form_validation->run() !== false)
    		{
    			// Validated!
	    		$name		= $this->input->post('context_name');
		    	$for_roles	= $this->input->post('roles');
		    	$migrate	= $this->input->post('migrate') == 'on' ? true : false;

		    	// Try to save the context, using the UI/Context helper
		    	$this->load->library('ui/contexts');
		    	if (Contexts::create_context($name, $for_roles, $migrate))
		    	{
		    		Template::set_message(lang('mb_context_create_success'), 'success');
			    	redirect(SITE_AREA . '/developer/builder');
		    	}
		    	else
		    	{
			    	Template::set_message(lang('mb_context_create_error') . Contexts::errors(), 'error');
		    	}
		    }
    	}

    	Template::set('toolbar_title', lang('mb_create_a_context'));

    	Template::render();
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !Module Builder
    //--------------------------------------------------------------------

    /**
     * Displays the create a module form.
     *
     * @access public
     *
     * @return void
     */
    public function create_module($fields = 0)
    {
        $this->auth->restrict('Bonfire.Modules.Add');

        $hide_form = false;
        $this->field_total = $fields;

        // validation hasn't been passed
        if ($this->validate_form($this->field_total) == FALSE)
        {
            Template::set('field_total', $this->field_total);

            if (isset($_POST['build']))
            {
                Template::set('form_error', TRUE);
            }
            else
            {
                Template::set('form_error', FALSE);
            }

            $query = $this->db->select('role_id,role_name')
                              ->where('deleted', 0)
                              ->order_by('role_name')
                              ->get('roles');
            Template::set('roles', $query->result_array());
            Template::set('form_action_options', $this->options['form_action_options']);
            Template::set('validation_rules', $this->options['validation_rules']);
            Template::set('validation_limits', $this->options['validation_limits']);
            Template::set('field_numbers', range(0,20));
            Template::set('field_total', $this->field_total);

            Template::set_view('developer/modulebuilder_form');

        }
        elseif ($this->input->post('module_db') == 'existing' && $this->field_total == 0)
        {
            // if the user has specified the table including the prefix then remove the prefix
            $_POST['table_name'] = preg_replace("/^".$this->db->dbprefix."/", "", $this->input->post('table_name'));

            // read the fields from the specified db table and pass them back into the form
            $table_fields = $this->table_info($this->input->post('table_name'));

            $num_fields = 0;

            if (is_array($table_fields))
			{
                $num_fields = count($table_fields);
            }
            Template::set('field_total', $this->field_total);

            if ($num_fields != 0)
			{
                Template::set('field_total', $num_fields - 1); // discount the first field as it is the primary key
            }

            if ( ! empty($_POST) && $num_fields == 0)
            {
                Template::set('form_error', TRUE);

                $error_message = lang('mb_module_table_not_exist');
                log_message('error', 'ModuleBuilder: ' . $error_message);
                Template::set('error_message', $error_message);
                unset($error_message);
            }
            else
            {
                Template::set('form_error', FALSE);
            }

            $query = $this->db->select('role_id,role_name')
                              ->order_by('role_name')
                              ->get('roles');

            Template::set('roles', $query->result_array());
            Template::set('existing_table_fields', $table_fields);
            Template::set('form_action_options', $this->options['form_action_options']);
            Template::set('validation_rules', $this->options['validation_rules']);
            Template::set('validation_limits', $this->options['validation_limits']);
            Template::set('field_numbers', range(0, 20));

            Template::set_view('developer/modulebuilder_form');

        }
        else
        {
            // passed validation proceed to second page
            $this->build_module($this->field_total);

            // Log the activity
           log_activity((integer) $this->current_user->id, lang('mb_act_create').': ' . $this->input->post('module_name') . ' : ' . $this->input->ip_address(), 'modulebuilder');

            Template::set_view('developer/output');

        }//end if

        // check that the modules folder is writeable
        Template::set('writeable', $this->_check_writeable());
        Template::set('error', array());
        Template::set('toolbar_title', lang('mb_toolbar_title_create'));

        Template::render();

    }//end create

    //--------------------------------------------------------------------

    /**
     * Deletes a module and all of its files.
     *
     * @access public
     *
     * @return void
     */
    public function delete()
    {
        $module_name = $this->input->post('module');

        if ( ! empty($module_name))
        {
            $this->auth->restrict('Bonfire.Modules.Delete');

            $this->db->trans_begin();

            // check if there is a model to drop (non-table modules will have no model)
            $model_name = $module_name . '_model';
            if (module_file_path($module_name, 'models', $model_name . '.php'))
            {
                // drop the table
                $this->load->model($module_name . '/' . $model_name, 'mt');
                $this->load->dbforge();
                $this->dbforge->drop_table($this->mt->get_table());
            }

            // get any permission ids
			$query = $this->db->select('permission_id')
				->like('name', $module_name . '.', 'after')
				->get('permissions');

            if ($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    // undo any permissions that exist
                    $this->db->where('permission_id', $row['permission_id'])
						->delete('permissions');

                    // and from the roles as well.
                    $this->db->where('permission_id', $row['permission_id'])
							->delete('role_permissions');
                }
            }

            // drop the schema - old Migration schema method
            $module_name_lower = preg_replace("/[ -]/", "_", strtolower($module_name));
            if ($this->db->field_exists($module_name_lower . '_version', 'schema_version'))
            {
                $this->dbforge->drop_column('schema_version', $module_name_lower . '_version');
            }

            // drop the Migration record - new Migration schema method
            if ($this->db->field_exists('version', 'schema_version'))
            {
                $this->db->delete('schema_version', array('type' => $module_name_lower . '_'));
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                Template::set_message(lang('mb_delete_trans_false'), $this->db->error, 'error');
            }
            else
            {
                $this->db->trans_commit();

                // database was successful in deleting everything. Now try to get rid of the files.
                if (delete_files(module_path($module_name), true))
                {
                    @rmdir(module_path($module_name.'/'));

                    // Log the activity
                    log_activity((integer) $this->current_user->id, lang('mb_act_delete').': ' . $module_name . ' : ' . $this->input->ip_address(), 'builder');

                    Template::set_message(lang('mb_delete_success'), 'success');
                }
                else
                {
                    Template::set_message(lang('mb_delete_success') . lang('mb_delete_success_db_only'), 'info');
                }
            }//end if
        }//end if

        redirect(SITE_AREA . '/developer/builder');

    }//end delete()

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     * Handles the validation of the modulebuilder form.
     *
     * @access private
     *
     * @param int $field_total The number of fields to add to the table
     *
     * @return bool Whether the form data was valid or not
     */
    private function validate_form($field_total=0)
    {
        $this->form_validation->set_rules("contexts_content",'lang:mb_contexts_content',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_developer",'lang:mb_contexts_developer',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_public",'lang:mb_contexts_public',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_reports",'lang:mb_contexts_reports',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_settings",'lang:mb_contexts_settings',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("module_db",'lang:mb_module_db',"trim|xss_clean|alpha");
        $this->form_validation->set_rules("form_action_create",'lang:mb_form_action_create',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_action_delete",'lang:mb_form_action_delete',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_action_edit",'lang:mb_form_action_edit',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_action_view",'lang:mb_form_action_view',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_error_delimiters",'lang:mb_form_err_delims',"required|trim|xss_clean");
        $this->form_validation->set_rules("module_description",'lang:mb_form_mod_desc',"trim|required|xss_clean");
        $this->form_validation->set_rules("module_name",'lang:mb_form_mod_name',"trim|required|xss_clean|callback__modulename_check");
        $this->form_validation->set_rules("role_id",'lang:mb_form_role_id',"trim|xss_clean|is_numeric");

        // no point doing all this checking if we don't want a table
        if ($this->input->post('module_db'))
        {
            $this->form_validation->set_rules("table_name",'lang:mb_form_table_name',"trim|required|xss_clean|alpha_dash");

            if ($this->input->post('module_db') == 'new')
            {
                $this->form_validation->set_rules("primary_key_field",'lang:mb_form_primarykey',"required|trim|xss_clean|alpha_dash");
                // textarea_editor seems to be gone...
                $this->form_validation->set_rules("textarea_editor",'lang:mb_form_text_ed',"trim|xss_clean|alpha_dash");
                $this->form_validation->set_rules("use_soft_deletes",'lang:mb_form_soft_deletes',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("soft_delete_field",'lang:mb_soft_delete_field',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("use_created",'lang:mb_form_use_created',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("created_field",'lang:mb_form_created_field',"trim|xss_clean|alpha_dash");
                $this->form_validation->set_rules("use_modified",'lang:mb_form_use_modified',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("modified_field",'lang:mb_form_modified_field',"trim|xss_clean|alpha_dash");
            }
            elseif ($this->input->post('module_db') == 'existing' && $field_total > 0)
            {
                $this->form_validation->set_rules("primary_key_field",'lang:mb_form_primarykey',"required|trim|xss_clean|alpha_dash");
            }

            // No need to retrieve this on every iteration of the loop
            $lang_field_details = lang('mb_form_field_details') . ' ';

            for ($counter = 1; $field_total >= $counter; $counter++)
            {
                $field_details_label = $lang_field_details . $counter . ' :: ';

                // We don't define the validation labels with 'lang:' in this
                // loop because we don't want to create language entries for
                // every possible $counter value

                if ($counter != 1) // better to do it this way round as this statement will be fullfilled more than the one below
                {
                    $this->form_validation->set_rules("view_field_label$counter", $field_details_label . lang('mb_form_label'), 'trim|xss_clean|alpha_extra');
                }
                else
                {
                    // the first field always needs to be required i.e. we need to have at least one field in our form
                    $this->form_validation->set_rules("view_field_label$counter", $field_details_label . lang('mb_form_label'),'trim|required|xss_clean|alpha_extra');
                }

                $name_required = '';
                $label = $this->input->post("view_field_label$counter");
                if ( ! empty($label))
                {
                    $name_required = 'required|';
                }

                $this->form_validation->set_rules("view_field_name$counter", $field_details_label . lang('mb_form_fieldname'), "trim|".$name_required."callback__no_match[$counter]|xss_clean");
                $this->form_validation->set_rules("view_field_type$counter", $field_details_label . lang('mb_form_type'), "trim|required|xss_clean|alpha");
                $this->form_validation->set_rules("db_field_type$counter", $field_details_label . lang('mb_form_dbtype'), "trim|xss_clean|alpha");

                // make sure that the length field is required if the DB Field type requires a length
				$no_length = array(
					'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT',
					'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB',
					'BOOL',
					'DATE', 'DATETIME', 'TIME', 'TIMESTAMP',
				);
				$optional_length = array(
					'INT', 'TINYINT', 'MEDIUMINT', 'BIGINT',
					'YEAR',
				);

                $field_type = $this->input->post("db_field_type$counter");
                $db_len_required = '';
                if ( ! empty($label) && ! in_array($field_type, $no_length) && ! in_array($field_type, $optional_length))
                {
                    $db_len_required = 'required|';
                }

                $this->form_validation->set_rules("db_field_length_value$counter", $field_details_label . lang('mb_form_length'), "trim|".$db_len_required."xss_clean");
                $this->form_validation->set_rules('validation_rules'.$counter.'[]', $field_details_label . lang('mb_form_rules'), 'trim|xss_clean');
            }
        }//end if

        return $this->form_validation->run();

    }//end validate_form()

    //--------------------------------------------------------------------

    /**
     * Returns an array with the structure and details for the fields in the specified
     * DB table.
     *
     * @access private
     *
     * @param string $table_name Name of the table to check
     *
     * @return mixed An array of fields or FALSE if the table does not exist
     */
    private function table_info($table_name)
    {
        $newfields = array();

        // check that the table exists in this database
        if ($this->db->table_exists($table_name))
        {
			$fields = $this->db->field_data($table_name);

			// We have a title - Edit it
			foreach ($fields as $field)
			{
				$field_array = array();

				$field_array['name'] = $field->name;

				$type = '';
                $max_length = null;
				if (strpos($field->type, "("))
				{
					list($type, $max_length) = explode("--", str_replace("(", "--", str_replace(")", "", $field->type)));
				}
				else
				{
					$type = $field->type;
				}

				$field_array['type'] = strtoupper($type);

				$values = '';
				if (is_numeric($field->max_length))
				{
					$max_length = $field->max_length;
				}
				else
				{
					$values = $field->max_length;
				}
                $max_length = $max_length == null ? 1 : $max_length;

				$primary_key = $field->primary_key == 1 ? 1 : 0;

				$field_array['max_length']  = $max_length;
				$field_array['values']      = $values;
				$field_array['primary_key'] = $primary_key;
				$field_array['default']     = $field->default;

				$newfields[] = $field_array;
			} // end foreach

			return $newfields;

        }//end if

        return FALSE;

    }//end table_info()


	//--------------------------------------------------------------------

	/**
	 * Handles the heavy-lifting of building a module from ther user's specs.
	 *
	 * @access private
	 *
	 * @param int $field_total The number of fields to add to the table
	 *
	 * @return void
	 */
	private function build_module($field_total=0)
	{
		$module_name			= $this->input->post('module_name');
		$table_name				= strtolower(preg_replace("/[ -]/", "_", $this->input->post('table_name')));
		$contexts				= $this->input->post('contexts');
		$action_names			= $this->input->post('form_action');
		$module_description		= $this->input->post('module_description');
		$role_id				= $this->input->post('role_id');
		$db_required			= $this->input->post('module_db');
		$table_as_field_prefix	= (bool) $this->input->post('table_as_field_prefix');
		$primary_key_field		= $this->input->post('primary_key_field');
		$form_error_delimiters	= explode(',', $this->input->post('form_error_delimiters'));

		if ($primary_key_field == '')
		{
			$primary_key_field = $this->options['primary_key_field'];
		}

		if ( ! is_array($form_error_delimiters) OR count($form_error_delimiters) != 2)
		{
			$form_error_delimiters = $this->options['$form_error_delimiters'];
		}

		$file_data = $this->modulebuilder->build_files($field_total, $module_name, $contexts, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $module_description, $role_id, $table_name, $table_as_field_prefix);

		// make the variables available to the view file
		$data['module_name']		= $module_name;
		$data['module_name_lower']	= strtolower(preg_replace("/[ -]/", "_", $module_name));
		$data['controller_name']	= preg_replace("/[ -]/", "_", $module_name);
		$data['table_name']			= empty($table_name) ? strtolower(preg_replace("/[ -]/", "_", $module_name)) : $table_name;

		$data = $data + $file_data;

		// Allow for the Old method - update the schema first to prevent errors in duplicate column names due to Migrations.php caching db columns
		if ( ! $this->db->field_exists('version', 'schema_version'))
		{
			$this->dbforge->add_column('schema_version', array(
				$data['module_name_lower'] . '_version' => array(
					'type'			=> 'INT',
					'constraint'	=> 4,
					'null'			=> true,
					'default'		=> 0,
				),
			));
		}

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

	}//end build_module()

    //--------------------------------------------------------------------


    /**
     * Custom Form Validation Callback Rule
     *
     * Checks that one field doesn't match all the others.
     * This code is not really portable. Would of been nice to create a rule that accepted an array
     *
     * @access  public
     *
     * @param string $str    String to check against the other fields
     * @param array $fieldno The field number of this field
     *
     * @return bool
     */
    public function _no_match($str, $fieldno)
    {
        for ($counter = 1; $this->field_total >= $counter; $counter++)
        {
            // nothing has been entered into this field so we don't need to check
            // or the field being checked is the same as the field we are checking from
            if ($_POST["view_field_name$counter"] == '' || $fieldno == $counter)
            {
                continue;
            }

            if ($str == $_POST["view_field_name{$counter}"])
            {
                $this->form_validation->set_message('_no_match', sprintf(lang('mb_validation_no_match'), lang('mb_form_field_details'), lang('mb_form_fieldname'), $fieldno, $counter));
                return FALSE;
            }
        }

        return TRUE;
    }

    //--------------------------------------------------------------------

    /**
     * Check that the Modules folder is writeable
     *
     * @access  private
     *
     * @return  bool
     */
    public function _check_writeable()
    {
        return is_writeable($this->options['output_path']);

    }//end _check_writeable()


    /**
     * Check the module name is valid
     *
     * @access  public
     *
     * @param string $str String to check
     *
     * @return  bool
     */
    public function _modulename_check($str)
    {
        if ( ! preg_match("/^([A-Za-z \-]+)$/", $str))
        {
            $this->form_validation->set_message('_modulename_check', lang('mb_modulename_check'));
            return FALSE;
        }
        else
        {
            return TRUE;
        }

    }//end _modulename_check()

}//end Developer