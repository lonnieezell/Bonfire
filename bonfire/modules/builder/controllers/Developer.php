<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Builder Developer Context Controller
 *
 * This controller displays the list of current modules in the
 * application/modules folder and also allows the user to create new modules and
 * contexts
 *
 * This code is originally based on Ollie Rattue's http://formigniter.org/ project
 *
 * @package Bonfire\Modules\Builder\Controllers\Developer
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/builder
 */
class Developer extends Admin_Controller
{
    /**
     * @var Array The options from the /config/modulebuilder.php file
     */
    private $options;

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

        $this->lang->load('builder');
        $this->load->config('modulebuilder');

        $this->options = $this->config->item('modulebuilder');
        if (isset($this->options['form_error_delimiters'])
            && is_array($this->options['form_error_delimiters'])
            && count($this->options['form_error_delimiters']) == 2
           ) {
            $this->form_validation->set_error_delimiters($this->options['form_error_delimiters'][0], $this->options['form_error_delimiters'][1]);
        }

        Assets::add_module_css('builder', 'builder.css');
        Assets::add_module_js('builder', 'modulebuilder.js');

        Template::set_block('sub_nav', 'developer/_sub_nav');
        Template::set_block('sidebar', 'developer/sidebar');
    }

    /**
     * Display a list of installed modules
     *
     * Includes the options to create a new module or context and delete
     * existing modules.
     *
     * @return void
     */
    public function index()
    {
        $modules = Modules::list_modules(true);
        $configs = array();

        foreach ($modules as $module) {
            $configs[$module] = Modules::config($module);

            if (! isset($configs[$module]['name'])) {
                $configs[$module]['name'] = ucwords($module);
            } elseif (strpos($configs[$module]['name'], 'lang:') === 0) {
                // If the name is configured, check to see if it is a lang entry
                // and, if it is, pull it from the application_lang file.
                $configs[$module]['name'] = lang(str_replace('lang:', '', $configs[$module]['name']));
            }
        }
        // Sort the module list (by the name of each module's folder)
        ksort($configs);

        // Check that the modules folder is writable
        Template::set('writable', $this->checkWritable());
        Template::set('modules', $configs);
        Template::set('toolbar_title', lang('mb_toolbar_title_index'));

        Template::render('two_left');
    }

    //--------------------------------------------------------------------
    // !Context Builder
    //--------------------------------------------------------------------

    /**
     * Display the form which allows the user to create a context.
     *
     * @return  void
     */
    public function create_context()
    {
        // Form submittal?
        if (isset($_POST['build'])) {
            $this->form_validation->set_rules('context_name', 'lang:mb_context_name', 'required|trim|alpha_numeric');

            if ($this->form_validation->run() !== false) {
                // Validated!
                $name       = $this->input->post('context_name');
                $for_roles  = $this->input->post('roles');
                $migrate    = $this->input->post('migrate') == 'on';

                // Try to save the context, using the UI/Context helper
                $this->load->library('ui/contexts');
                if (Contexts::create_context($name, $for_roles, $migrate)) {
                    Template::set_message(lang('mb_context_create_success'), 'success');
                    redirect(SITE_AREA . '/developer/builder');
                }

                // Creating the context failed
                Template::set_message(lang('mb_context_create_error') . Contexts::errors(), 'error');
            }
        }

        // Load roles for display in the form.
        $this->load->model('roles/role_model');
        $this->role_model->select(
            array(
                'role_id',
                'role_name',
            )
        )
                         ->where('deleted', 0);

        Template::set('roles', $this->role_model->find_all());
        Template::set('toolbar_title', lang('mb_create_a_context'));

        Template::render();
    }

    //--------------------------------------------------------------------
    // !Module Builder
    //--------------------------------------------------------------------

    /**
     * Display the form which allows the user to create a module.
     *
     * @return void
     */
    public function create_module($fields = 0)
    {
        $this->auth->restrict('Bonfire.Modules.Add');

        $hide_form = false;
        $this->field_total = $fields;

        // Validation failed
        if ($this->validate_form($this->field_total) == false) {
            // Display the modulebuilder_form
            $this->prepareModuleForm($this->field_total, isset($_POST['build']));
        } elseif ($this->input->post('module_db') == 'existing'
            && $this->field_total == 0
        ) {
            // Validation Passed, Use existing DB, need to detect the fields.
            //
            // If the table name includes the prefix, remove the prefix
            $_POST['table_name'] = preg_replace("/^".$this->db->dbprefix."/", "", $this->input->post('table_name'));

            // Read the fields from the db table and pass them back to the form
            $table_fields = $this->table_info($this->input->post('table_name'));
            $num_fields   = is_array($table_fields) ? count($table_fields) : 0;

            // $num_fields includes the primary key, field_total doesn't
            $fieldTotal = $num_fields > 0 ? $num_fields - 1 : $this->field_total;
            $formError  = false;

            // If the table wasn't found, log/set an error message
            if (! empty($_POST) && $num_fields == 0) {
                $formError = true;
                $error_message = lang('mb_module_table_not_exist');
                log_message('error', "ModuleBuilder: {$error_message}");
                Template::set('error_message', $error_message);
            }

            Template::set('existing_table_fields', $table_fields);

            // Display the modulebuilder_form
            $this->prepareModuleForm($fieldTotal, $formError);
        } else {
            // Validation passed and ready to proceed
            $this->build_module($this->field_total);
            log_activity((int) $this->current_user->id, lang('mb_act_create') . ': ' . $this->input->post('module_name') . ' : ' . $this->input->ip_address(), 'modulebuilder');

            Template::set_view('developer/output');
        }

        Template::set('error', array());
        Template::set('toolbar_title', lang('mb_toolbar_title_create'));
        Template::set('writable', $this->checkWritable());

        Template::render();
    }

    /**
     * Delete a module and all of its files.
     *
     * @return void
     */
    public function delete()
    {
        // If there's no module to delete, redirect
        $module_name = $this->input->post('module');
        if (empty($module_name)) {
            redirect(SITE_AREA . '/developer/builder');
        }

        $this->auth->restrict('Bonfire.Modules.Delete');

        // Remove the module's data and permissions from the database
        if ($this->deleteModuleData($module_name) === false) {
            // Something went wrong while trying to delete the data
            Template::set_message(lang('mb_delete_trans_false'), $this->db->error, 'error');

            redirect(SITE_AREA . '/developer/builder');
        }

        // Data deleted successfully, now try to remove the files.
        $this->load->helper('file');
        if (delete_files(Modules::path($module_name), true)) {
            @rmdir(Modules::path("{$module_name}/"));

            log_activity((int) $this->auth->user_id(), lang('mb_act_delete') . ": {$module_name} : " . $this->input->ip_address(), 'builder');
            Template::set_message(lang('mb_delete_success'), 'success');
        } else {
            // Database removal succeeded, but the files may still be present
            Template::set_message(lang('mb_delete_success') . lang('mb_delete_success_db_only'), 'info');
        }

        redirect(SITE_AREA . '/developer/builder');
    }

    //--------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     * Delete the data for a module
     *
     * Removes migration information, permissions based on the name of the
     * module, and any tables returned by the module's model's get_table()
     * method.
     *
     * @todo Use the migration library instead of doing everything directly.
     *
     * @param string $moduleName The name of the module
     *
     * @return bool    true if the data is removed, false on error
     */
    private function deleteModuleData($moduleName)
    {
        $this->load->dbforge();
        $this->db->trans_begin();

        // Drop the migration record - old Migration schema method
        $moduleNameLower = preg_replace("/[ -]/", "_", strtolower($moduleName));
        if ($this->db->field_exists("{$moduleNameLower}_version", 'schema_version')) {
            $this->dbforge->drop_column('schema_version', "{$moduleNameLower}_version");
        }

        // Drop the Migration record - new Migration schema method
        if ($this->db->field_exists('version', 'schema_version')) {
            $this->db->delete('schema_version', array('type' => $moduleNameLower . '_'));
        }

        // Get any permission ids
        $this->load->model('permissions/permission_model');
        $permissionKey = $this->permission_model->get_key();
        $permissionIds = $this->permission_model->select($permissionKey)
                                                ->like('name', $moduleName . '.', 'after')
                                                ->find_all();

        // Undo any permissions that exist, from the roles as well
        if (! empty($permissionIds)) {
            foreach ($permissionIds as $row) {
                $this->permission_model->delete($row->permission_id);
            }
        }

        // Check whether there is a model to drop (a model should have a table
        // which may require dropping)
        $modelName = "{$moduleName}_model";
        if (Modules::file_path($moduleName, 'models', "{$modelName}.php")) {
            // Drop the table
            $this->load->model("{$moduleName}/{$modelName}", 'mt');
            $mtTableName = $this->mt->get_table();

            // If the model has a table and it exists in the database, drop it
            if (! empty($mtTableName) && $this->db->table_exists($mtTableName)) {
                $this->dbforge->drop_table($mtTableName);
            }
        }

        // Complete the database transaction or roll it back
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /**
     * Prepare the variables used for the modulebuilder_form and set the view
     *
     * @todo Allow configuration of defaultRoleWithFullAccess
     *
     * @todo Ideally the field type variables would be set at a higher level for
     * consistent use throughout the builder
     *
     * @param int $fieldTotal The number of fields to add to the table
     * @param bool $formError Set to true if an error has occurred on the form
     *
     * @return void
     */
    private function prepareModuleForm($fieldTotal, $formError)
    {
        $this->load->model('roles/role_model');
        $this->role_model->select(array('role_id', 'role_name'))
                         ->where('deleted', 0)
                         ->order_by('role_name');

        $this->load->library('modulebuilder');
        $boolFieldTypes = array_merge($this->modulebuilder->getBooleanTypes(), array('BIT', 'BOOL', 'TINYINT'));
        $listFieldTypes = $this->modulebuilder->getListTypes();
        $textFieldTypes = $this->modulebuilder->getTextTypes();
        $dbFieldTypes = array();
        foreach (array_keys($this->modulebuilder->getDatabaseTypes()) as $key) {
            $dbFieldTypes[$key] = $key;
        }

        Template::set('availableContexts', config_item('contexts'));
        Template::set('boolFieldTypes', $boolFieldTypes);
        Template::set('db_field_types', $dbFieldTypes);
        Template::set('defaultRoleWithFullAccess', $this->auth->role_id());
        Template::set('field_numbers', range(0, 20));
        Template::set('field_total', $fieldTotal);
        Template::set('form_action_options', $this->options['form_action_options']);
        Template::set('form_error', $formError);
        Template::set('listFieldTypes', $listFieldTypes);
        Template::set('roles', $this->role_model->as_array()->find_all());
        Template::set(
            'textarea_editors',
            array(
                ''          => 'None',
                'ckeditor'  => 'CKEditor',
                'elrte'     => 'ElRte with ElFinder',
                'markitup'  => 'MarkitUp!',
                'tinymce'   => 'TinyMCE',
                'xinha'     => 'Xinha',
            )
        );
        Template::set('textFieldTypes', $textFieldTypes);
        Template::set(
            'truefalse',
            array(
                'false' => 'False',
                'true' => 'True',
            )
        );
        Template::set('validation_limits', $this->options['validation_limits']);
        Template::set('validation_rules', $this->options['validation_rules']);
        Template::set(
            'view_field_types',
            array(
                'input'    => 'INPUT',
                'checkbox' => 'CHECKBOX',
                'password' => 'PASSWORD',
                'radio'    => 'RADIO',
                'select'   => 'SELECT',
                'textarea' => 'TEXTAREA',
            )
        );

        Template::set_view('developer/modulebuilder_form');
    }

    /**
     * Validate the modulebuilder form.
     *
     * @param int $field_total The number of fields to add to the table
     *
     * @return bool Whether the form data was valid or not
     */
    private function validate_form($field_total = 0)
    {
        $this->form_validation->set_rules("contexts_content", 'lang:mb_contexts_content', "trim|is_numeric");
        $this->form_validation->set_rules("contexts_developer", 'lang:mb_contexts_developer', "trim|is_numeric");
        $this->form_validation->set_rules("contexts_public", 'lang:mb_contexts_public', "trim|is_numeric");
        $this->form_validation->set_rules("contexts_reports", 'lang:mb_contexts_reports', "trim|is_numeric");
        $this->form_validation->set_rules("contexts_settings", 'lang:mb_contexts_settings', "trim|is_numeric");
        $this->form_validation->set_rules("module_db", 'lang:mb_module_db', "trim|alpha");
        $this->form_validation->set_rules("form_action_create", 'lang:mb_form_action_create', "trim|is_numeric");
        $this->form_validation->set_rules("form_action_delete", 'lang:mb_form_action_delete', "trim|is_numeric");
        $this->form_validation->set_rules("form_action_edit", 'lang:mb_form_action_edit', "trim|is_numeric");
        $this->form_validation->set_rules("form_action_view", 'lang:mb_form_action_view', "trim|is_numeric");
        $this->form_validation->set_rules("form_error_delimiters", 'lang:mb_form_err_delims', "required|trim");
        $this->form_validation->set_rules("module_description", 'lang:mb_form_mod_desc', "trim|required");
        $this->form_validation->set_rules("module_name", 'lang:mb_form_mod_name', "trim|required|callback__modulename_check");
        $this->form_validation->set_rules("role_id", 'lang:mb_form_role_id', "trim|is_numeric");

        // If there's no database table, don't use the table validation
        if ($this->input->post('module_db')) {
            $this->form_validation->set_rules("table_name", 'lang:mb_form_table_name', "trim|required|alpha_dash");

            // If it's a new table, extra validation is required
            if ($this->input->post('module_db') == 'new') {
                $this->form_validation->set_rules("primary_key_field", 'lang:mb_form_primarykey', "required|trim|alpha_dash");
                $this->form_validation->set_rules("soft_delete_field", 'lang:mb_soft_delete_field', "trim|alpha_dash");
                $this->form_validation->set_rules("created_field", 'lang:mb_form_created_field', "trim|alpha_dash");
                $this->form_validation->set_rules("modified_field", 'lang:mb_form_modified_field', "trim|alpha_dash");
                $this->form_validation->set_rules('deleted_by_field', 'lang:mb_deleted_by_field', 'trim|alpha_dash');
                $this->form_validation->set_rules('created_by_field', 'lang:mb_form_created_by_field', 'trim|alpha_dash');
                $this->form_validation->set_rules('modified_by_field', 'lang:mb_form_modified_by_field', 'trim|alpha_dash');
                // textarea_editor seems to be gone...
                //$this->form_validation->set_rules("textarea_editor", 'lang:mb_form_text_ed', "trim|alpha_dash");
            } elseif ($this->input->post('module_db') == 'existing'
                && $field_total > 0
            ) {
                // If it's an existing table, the primary key validation is required
                $this->form_validation->set_rules("primary_key_field", 'lang:mb_form_primarykey', "required|trim|alpha_dash");
            }

            // No need to do any of the below on every iteration of the loop
            $lang_field_details = lang('mb_form_field_details') . ' ';

            $this->load->library('modulebuilder');

            // Make sure the length field is required if the DB Field type
            // requires a length
            $no_length = array_merge(
                $this->modulebuilder->getObjectTypes(),
                $this->modulebuilder->getBooleanTypes(),
                $this->modulebuilder->getDateTypes(),
                $this->modulebuilder->getTimeTypes()
            );

            $optional_length = array_diff(
                $this->modulebuilder->getIntegerTypes(),
                $this->modulebuilder->getBooleanTypes()
            );

            for ($counter = 1; $field_total >= $counter; $counter++) {
                $field_details_label = $lang_field_details . $counter . ' :: ';

                // We don't define the validation labels with 'lang:' in this
                // loop because we don't want to create language entries for
                // every possible $counter value

                // Better to do it this way round as this statement will be
                // fullfilled more than the one below
                if ($counter != 1) {
                    $this->form_validation->set_rules("view_field_label$counter", $field_details_label . lang('mb_form_label'), 'trim|alpha_extra');
                } else {
                    // At least one field is required in the form
                    $this->form_validation->set_rules("view_field_label$counter", $field_details_label . lang('mb_form_label'), 'trim|required|alpha_extra');
                }

                $label = $this->input->post("view_field_label$counter");
                $name_required = empty($label) ? '' : 'required|';

                $this->form_validation->set_rules("view_field_name$counter", $field_details_label . lang('mb_form_fieldname'), "trim|{$name_required}callback__no_match[$counter]");
                $this->form_validation->set_rules("view_field_type$counter", $field_details_label . lang('mb_form_type'), "trim|required|alpha");
                $this->form_validation->set_rules("db_field_type$counter", $field_details_label . lang('mb_form_dbtype'), "trim|alpha");

                $field_type = $this->input->post("db_field_type$counter");
                $db_len_required = '';
                if (! empty($label)
                    && ! in_array($field_type, $no_length)
                    && ! in_array($field_type, $optional_length)
                ) {
                    $db_len_required = '|required';
                }

                $this->form_validation->set_rules("db_field_length_value$counter", $field_details_label . lang('mb_form_length'), "trim{$db_len_required}");
                $this->form_validation->set_rules('validation_rules'.$counter.'[]', $field_details_label . lang('mb_form_rules'), 'trim');
            }
        }

        return $this->form_validation->run();
    }

    /**
     * Get the structure and details for the fields in the specified DB table
     *
     * @param string $table_name Name of the table to check
     *
     * @return mixed An array of fields or false if the table does not exist
     */
    private function table_info($table_name)
    {
        $newfields = array();

        // Check whether the table exists in this database
        if (! $this->db->table_exists($table_name)) {
            return false;
        }

        $fields = $this->db->field_data($table_name);

        // There may be something wrong or the database driver may not return
        // field data
        if (empty($fields)) {
            return false;
        }

        foreach ($fields as $field) {
            $max_length = null;
            $type = '';
            if (isset($field->type)) {
                if (strpos($field->type, "(")) {
                    list($type, $max_length) = explode("--", str_replace("(", "--", str_replace(")", "", $field->type)));
                } else {
                    $type = $field->type;
                }
            }

            $values = '';
            if (isset($field->max_length)) {
                if (is_numeric($field->max_length)) {
                    $max_length = $field->max_length;
                } else {
                    $values = $field->max_length;
                }
            }

            $newfields[] = array(
                'name'          => isset($field->name) ? $field->name : '',
                'type'          => strtoupper($type),
                'max_length'    => $max_length == null ? '' : $max_length,
                'values'        => $values,
                'primary_key'   => isset($field->primary_key) && $field->primary_key == 1 ? 1 : 0,
                'default'       => isset($field->default) ? $field->default : null,
            );
        }

        return $newfields;
    }

    /**
     * Handles the heavy-lifting of building a module from ther user's specs.
     *
     * @param int $field_total The number of fields to add to the table
     *
     * @return void
     */
    private function build_module($field_total = 0)
    {
        $action_names           = $this->input->post('form_action');
        $contexts               = $this->input->post('contexts');
        $db_required            = $this->input->post('module_db');
        $form_error_delimiters  = explode(',', $this->input->post('form_error_delimiters'));
        $module_description     = $this->input->post('module_description');
        $module_name            = $this->input->post('module_name');
        $primary_key_field      = $this->input->post('primary_key_field');
        $role_id                = $this->input->post('role_id');
        $table_name             = strtolower(preg_replace("/[ -]/", "_", $this->input->post('table_name')));
        $table_as_field_prefix  = (bool) $this->input->post('table_as_field_prefix');

        $logUser        = $this->input->post('log_user') == 1;
        $useCreated     = $this->input->post('use_created') == 1;
        $useModified    = $this->input->post('use_modified') == 1;
        $usePagination  = $this->input->post('use_pagination') == 1;
        $useSoftDeletes = $this->input->post('use_soft_deletes') == 1;

        $created_field      = $this->input->post('created_field') ?: 'created_on';
        $created_by_field   = $this->input->post('created_by_field') ?: 'created_by';
        $soft_delete_field  = $this->input->post('soft_delete_field') ?: 'deleted';
        $deleted_by_field   = $this->input->post('deleted_by_field') ?: 'deleted_by';
        $modified_field     = $this->input->post('modified_field') ?: 'modified_on';
        $modified_by_field  = $this->input->post('modified_by_field') ?: 'modified_by';

        $textarea_editor = $this->input->post('textarea_editor');

        if ($primary_key_field == '') {
            $primary_key_field = $this->options['primary_key_field'];
        }

        if (! is_array($form_error_delimiters)
            || count($form_error_delimiters) != 2
        ) {
            $form_error_delimiters = $this->options['$form_error_delimiters'];
        }

        $controller_name = preg_replace("/[ -]/", "_", $module_name);
        $module_name_lower = strtolower($controller_name);

        $this->load->library('modulebuilder');
        $file_data = $this->modulebuilder->buildFiles(
            array(
                'action_names'          => $action_names,
                'contexts'              => $contexts,
                'controller_name'       => $controller_name,
                'created_by_field'      => $created_by_field,
                'created_field'         => $created_field,
                'db_required'           => $db_required,
                'deleted_by_field'      => $deleted_by_field,
                'field_total'           => $field_total,
                'form_error_delimiters' => $form_error_delimiters,
                'logUser'               => $logUser,
                'modified_by_field'     => $modified_by_field,
                'modified_field'        => $modified_field,
                'module_description'    => $module_description,
                'module_name'           => $module_name,
                'module_name_lower'     => $module_name_lower,
                'primary_key_field'     => $primary_key_field,
                'role_id'               => $role_id,
                'soft_delete_field'     => $soft_delete_field,
                'table_as_field_prefix' => $table_as_field_prefix,
                'table_name'            => $table_name,
                'textarea_editor'       => $textarea_editor,
                'useCreated'            => $useCreated,
                'useModified'           => $useModified,
                'usePagination'         => $usePagination,
                'useSoftDeletes'        => $useSoftDeletes,
            )
        );

        // Make the variables available to the view file
        $data['module_name']       = $module_name;
        $data['controller_name']   = $controller_name;
        $data['module_name_lower'] = $module_name_lower;
        $data['table_name']        = empty($table_name) ? $module_name_lower : $table_name;

        $data = $data + $file_data;

        // @todo Need to test whether this section can be removed... Only the
        // migrations library should know anything about the structure of the
        // migration versions in the database.
        //
        // Allow for the Old method - update the schema first to prevent errors
        // in duplicate column names due to Migrations.php caching db columns
        if (! $this->db->field_exists('version', 'schema_version')) {
            $this->dbforge->add_column(
                'schema_version',
                array(
                    $data['module_name_lower'] . '_version' => array(
                        'type'          => 'INT',
                        'constraint'    => 4,
                        'null'          => true,
                        'default'       => 0,
                    ),
                )
            );
        }

        // Load the migrations library
        $this->load->library('migrations/migrations');

        // Run the migration install routine
        if ($this->migrations->install("{$data['module_name_lower']}_")) {
            $data['mb_migration_result'] = 'mb_out_tables_success';
        } else {
            $data['mb_migration_result'] = 'mb_out_tables_error';
        }

        Template::set($data);
    }

    /**
     * Check that the Modules folder is writable
     *
     * @deprecated since 0.7.1 use checkWritable() instead
     *
     * @todo This method was marked private in the DocBlock but is public, since
     * it is now deprecated, just need to ensure it is not used elsewhere before
     * it is deleted.
     *
     * @return  bool
     */
    public function _check_writeable()
    {
        return $this->checkWritable();
    }

    /**
     * Verify that the Modules folder is writable
     *
     * @return bool    true if the folder is writable, else false
     */
    private function checkWritable()
    {
        return is_writable($this->options['output_path']);
    }

    //--------------------------------------------------------------------------
    // Form validation callbacks
    //--------------------------------------------------------------------------

    /**
     * Form validation callback for the module name
     *
     * @param string $str String to check
     *
     * @return  bool
     */
    public function _modulename_check($str)
    {
        if (! preg_match("/^([A-Za-z \-]+)$/", $str)) {
            $this->form_validation->set_message('_modulename_check', lang('mb_modulename_check'));
            return false;
        }

        if (class_exists($str)) {
            $this->form_validation->set_message('_modulename_check', lang('mb_modulename_check_class_exists'));
            return false;
        }

        return true;
    }

    /**
     * Custom Form Validation Callback Rule
     *
     * Checks that one field doesn't match all the others.
     * This code is not really portable. Would have been nice to create a rule
     * that accepted an array
     *
     * @param string $str    String to check against the other fields
     * @param array $fieldno The field number of this field
     *
     * @return bool
     */
    public function _no_match($str, $fieldno)
    {
        for ($counter = 1; $this->field_total >= $counter; $counter++) {
            // Nothing has been entered into the current field or the current
            // field is the same as the field to validate
            if ($_POST["view_field_name$counter"] == '' || $fieldno == $counter) {
                continue;
            }

            if ($str == $_POST["view_field_name{$counter}"]) {
                $this->form_validation->set_message('_no_match', sprintf(lang('mb_validation_no_match'), lang('mb_form_field_details'), lang('mb_form_fieldname'), $fieldno, $counter));
                return false;
            }
        }
        return true;
    }
}
