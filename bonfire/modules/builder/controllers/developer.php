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
        $this->lang->load('builder');
        $this->load->helper('file');
        $this->load->dbforge();

        $this->options = $this->config->item('modulebuilder');

        Template::set_block('sub_nav', 'developer/_sub_nav');
        Template::set_block('sidebar', 'developer/sidebar');

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
    		$this->form_validation->set_rules('context_name', 'Context Name', 'required|trim|alpha_numeric|xss_clean');

    		if ($this->form_validation->run() !== false)
    		{
    			/*
    				Validated!
    			*/
	    		$name		= $this->input->post('context_name');
		    	$for_roles	= $this->input->post('roles');
		    	$migrate	= $this->input->post('migrate') == 'on' ? true : false;

		    	// Try to save the context, using the UI/Context helper
		    	$this->load->library('ui/contexts');
		    	if (Contexts::create_context($name, $for_roles, $migrate))
		    	{
		    		Template::set_message('Context succesfully created.', 'success');
			    	redirect(SITE_AREA .'/developer/builder');
		    	}
		    	else
		    	{
			    	Template::set_message('Error creating Context: '. Contexts::errors(), 'error');
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
        Assets::add_module_js('builder', 'modulebuilder.js');

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
            $query = $this->db->select('role_id,role_name')->where('deleted', 0)->order_by('role_name')->get('roles');
            Template::set('roles', $query->result_array());
            Template::set('form_action_options', $this->options['form_action_options']);
            Template::set('validation_rules', $this->options['validation_rules']);
            Template::set('validation_limits', $this->options['validation_limits']);
            Template::set('field_numbers', range(0,20));
            Template::set('field_total', $this->field_total);
            Template::set_view('developer/modulebuilder_form');

        }
        elseif($this->input->post('module_db') == 'existing' && $this->field_total == 0)
        {
            // if the user has specified the table including the prefix then remove the prefix
            $_POST['table_name'] = preg_replace("/^".$this->db->dbprefix."/", "", $this->input->post('table_name'));


            // read the fields from the specified db table and pass them back into the form
            $table_fields = $this->table_info($this->input->post('table_name'));

            $num_fields = 0;

            if (is_array($table_fields)) {
                $num_fields = count($table_fields);
            }

            Template::set('field_total', $this->field_total);
            if ($num_fields != 0) {
                Template::set('field_total', $num_fields - 1); // discount the first field as it is the primary key
            }

            if (!empty($_POST) && $num_fields == 0)
            {
                Template::set('form_error', TRUE);
                log_message('error', "ModuleBuilder: The specified table name does not exist");
                Template::set('error_message', 'The specified table name does not exist');
            }
            else
            {
                Template::set('form_error', FALSE);
            }

            $query = $this->db->select('role_id,role_name')->order_by('role_name')->get('roles');
            Template::set('roles', $query->result_array());
            Template::set('existing_table_fields', $table_fields);
            Template::set('form_action_options', $this->options['form_action_options']);
            Template::set('validation_rules', $this->options['validation_rules']);
            Template::set('validation_limits', $this->options['validation_limits']);
            Template::set('field_numbers', range(0,20));
            Template::set_view('developer/modulebuilder_form');

        }
        else
        {
            // passed validation proceed to second page
            $this->build_module($this->field_total);

            // Log the activity
           $this->activity_model->log_activity((integer) $this->current_user->id, lang('mb_act_create').': ' . $this->input->post('module_name') . ' : ' . $this->input->ip_address(), 'modulebuilder');

            Template::set_view('developer/output');
        }//end if

        // check that the modules folder is writeable
        Template::set('writeable', $this->_check_writeable());

        Template::set('error', array());

        Template::set('toolbar_title', 'Module Builder');

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
			$this->db->select('permission_id')->like('name', "{$module_name}.", 'after');

			$query = $this->db->get('permissions');

            if ($query->num_rows() > 0)
            {
                foreach($query->result_array() as $row)
                {
                    // undo any permissions that exist
                    $this->db->where('permission_id',$row['permission_id']);
                    $this->db->delete('permissions');

                    // and fron the roles as well.
                    $this->db->where('permission_id',$row['permission_id']);
                    $this->db->delete('role_permissions');
                }
            }

            // drop the schema - old Migration schema method
            $module_name_lower = preg_replace("/[ -]/", "_", strtolower($module_name));
            if ($this->db->field_exists( $module_name_lower . '_version', 'schema_version'))
            {
                $this->dbforge->drop_column('schema_version', $module_name_lower . '_version');
            }
            // drop the Migration record - new Migration schema method
            $module_name_lower = preg_replace("/[ -]/", "_", strtolower($module_name));
            if ($this->db->field_exists('version', 'schema_version'))
            {
                $this->db->delete('schema_version', array('type' => $module_name_lower.'_'));
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                Template::set_message('We could not delete this module.', $this->db->error, 'error');
            }
            else
            {
                $this->db->trans_commit();

                // database was successful in deleting everything. Now try to get rid of the files.
                if (delete_files(module_path($module_name), true))
                {
                    @rmdir(module_path($module_name.'/'));

                    // Log the activity
                    $this->activity_model->log_activity((integer) $this->current_user->id, lang('mb_act_delete').': ' . $module_name . ' : ' . $this->input->ip_address(), 'builder');

                    Template::set_message('The module and associated database entries were successfully deleted.', 'success');
                }
                else
                {
                    Template::set_message('The module and associated database entries were successfully deleted, HOWEVER, the module folder and files were not removed. They must be removed manually.', 'info');
                }
            }//end if
        }//end if

        redirect(SITE_AREA .'/developer/builder');

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
        $this->form_validation->set_rules("contexts_content",'Contexts :: Content',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_developer",'Contexts :: Developer',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_public",'Contexts :: Public',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_reports",'Contexts :: Reports',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("contexts_settings",'Contexts :: Settings',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("module_db",'Create Module Table',"trim|xss_clean|alpha");
        $this->form_validation->set_rules("form_action_create",'Form Actions :: View',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_action_delete",'Form Actions :: View',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_action_edit",'Form Actions :: View',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_action_view",'Form Actions :: List',"trim|xss_clean|is_numeric");
        $this->form_validation->set_rules("form_error_delimiters",'Form Error Delimiters',"required|trim|xss_clean");
        $this->form_validation->set_rules("module_description",'Module Description',"trim|required|xss_clean");
        $this->form_validation->set_rules("module_name",'Module Name',"trim|required|xss_clean|callback__modulename_check");
        $this->form_validation->set_rules("role_id",'Give Role Full Access',"trim|xss_clean|is_numeric");

        // no point doing all this checking if we don't want a table
        if ($this->input->post('module_db'))
        {
            $this->form_validation->set_rules("table_name",'Table Name',"trim|required|xss_clean|alpha_dash");

            if ($this->input->post('module_db') == 'new')
            {
                $this->form_validation->set_rules("primary_key_field",'Primary Key Field',"required|trim|xss_clean|alpha_dash");
                $this->form_validation->set_rules("textarea_editor",'Textarea Editor',"trim|xss_clean|alpha_dash");
                $this->form_validation->set_rules("use_soft_deletes",'Soft Deletes',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("use_created",'Use Created Field',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("created_field",'Created Field Name',"trim|xss_clean|alpha_dash");
                $this->form_validation->set_rules("use_modified",'Use Modified Field',"trim|xss_clean|alpha");
                $this->form_validation->set_rules("modified_field",'Modified Field Name',"trim|xss_clean|alpha_dash");
            }
            elseif ($this->input->post('module_db') == 'existing' && $field_total > 0)
            {
                $this->form_validation->set_rules("primary_key_field",'Primary Key Field',"required|trim|xss_clean|alpha_dash");
            }

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
                $this->form_validation->set_rules("view_field_name$counter","Name $counter","trim|".$name_required."callback__no_match[$counter]|xss_clean");
                $this->form_validation->set_rules("view_field_type$counter","Field Type $counter","trim|required|xss_clean|alpha");
                $this->form_validation->set_rules("db_field_type$counter","DB Field Type $counter","trim|xss_clean|alpha");

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

                $db_len_required = '';
                $field_type = $this->input->post("db_field_type$counter");
                if( !empty($label) && !in_array($field_type, $no_length) && !in_array($field_type, $optional_length))
                {
                    $db_len_required = 'required|';
                }
                $this->form_validation->set_rules("db_field_length_value$counter","DB Field Length $counter","trim|".$db_len_required."xss_clean");
                $this->form_validation->set_rules('validation_rules'.$counter.'[]',"Validation Rules $counter",'trim|xss_clean');
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
        $fields = array();

        // check that the table exists in this database
        if ($this->db->table_exists($table_name))
        {

			// TODO: Replace SHOW COLUMNS FROM with field_data($table_name) ?
            $query_string = "SHOW COLUMNS FROM ".$this->db->dbprefix.$table_name;
            if($query = $this->db->query($query_string))
            {

                // We have a title - Edit it
                foreach($query->result_array() as $field)
                {
                    $field_array = array();

                    $field_array['name'] = $field['Field'];

                    $type = '';
                    if(strpos($field['Type'], "("))
                    {
                        list($type, $max_length) = explode("--", str_replace("(", "--", str_replace(")", "", $field['Type'])));
                    }
                    else
                    {
                        $type = $field['Type'];
                    }

                    $field_array['type'] = strtoupper($type);

                    $values = '';
                    if(is_numeric($max_length))
                    {
                        $max_length = $max_length;
                    }
                    else
                    {
                        $values = $max_length;
                        $max_length = 1;
                    }

                    $field_array['max_length'] = $max_length;
                    $field_array['values'] = $values;

                    $primary_key = 0;
                    if($field['Key'] == "PRI") {
                        $primary_key = 1;
                    }
                    $field_array['primary_key'] = $primary_key;

                    $field_array['default'] = $field['Default'];

                    $fields[] = $field_array;
                } // end foreach

                return $fields;

            }//end if
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
        $module_name        = $this->input->post('module_name');
        $table_name         = strtolower(preg_replace("/[ -]/", "_", $this->input->post('table_name')));
        $contexts           = $this->input->post('contexts');
        $action_names       = $this->input->post('form_action');
        $module_description = $this->input->post('module_description');
        $role_id            = $this->input->post('role_id');

        $db_required = $this->input->post('module_db');

		$table_as_field_prefix = (bool) $this->input->post('table_as_field_prefix');

        $primary_key_field = $this->input->post('primary_key_field');
        if( $primary_key_field == '')
        {
            $primary_key_field = $this->options['primary_key_field'];
        }

        $form_error_delimiters = explode(',', $this->input->post('form_error_delimiters'));
        if( !is_array($form_error_delimiters) OR count($form_error_delimiters) != 2)
        {
            $form_error_delimiters = $this->options['$form_error_delimiters'];
        }

		$file_data = $this->modulebuilder->build_files($field_total, $module_name, $contexts, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $module_description, $role_id, $table_name, $table_as_field_prefix);

        // make the variables available to the view file
        $data['module_name']        = $module_name;
        $data['module_name_lower']  = strtolower(preg_replace("/[ -]/", "_", $module_name));
        $data['controller_name']    = preg_replace("/[ -]/", "_", $module_name);
        $data['table_name']         = empty($table_name) ? strtolower(preg_replace("/[ -]/", "_", $module_name)) : $table_name;
        $data = $data + $file_data;

        // Allow for the Old method - update the schema first to prevent errors in duplicate column names due to Migrations.php caching db columns
        if (!$this->db->field_exists('version', 'schema_version'))
        {
            $this->load->dbforge();
            $this->dbforge->add_column('schema_version', array(
                    $data['module_name_lower'] . '_version' => array(
                    'type'          => 'INT',
                    'constraint'    => 4,
                    'null'          => true,
                    'default'       => 0
                )
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
                $this->form_validation->set_message('_no_match', "Field names ($fieldno & $counter) must be unique!");
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
        if (!preg_match("/^([A-Za-z \-]+)$/", $str))
        {
            $this->form_validation->set_message('_modulename_check', 'The %s field is not valid');
            return FALSE;
        }
        else
        {
            return TRUE;
        }

    }//end _modulename_check()

}//end Developer
