<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

/**
 * Module Builder library
 *
 * This library performs the heavy-lifting while creating new modules for Bonfire.
 *
 * This code is originally based on Ollie Rattue's http://formigniter.org/ project
 *
 * @package    Bonfire
 * @subpackage Modules_ModuleBuilder
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/guides/core_modules/modulebuilder.html
 *
 */
class Modulebuilder
{

    /**
     * A pointer to the CodeIgniter instance.
     *
     * @access public
     *
     * @var object
     */
    public $CI;

    /**
     * Contains various settings from the modulebuilder config file
     *
     * @access public
     *
     * @var array
     */
    public $options = array();

    /**
     * @todo Not used?
     */
    public $field_numbers = array(6,10,20,40);

    /**
     * Total number of fields being used in this module
     *
     * @access private
     *
     * @var int
     */
    private $field_total = 0;

    /**
     * Array of the files being output for the current module
     *
     * @access private
     *
     * @var array
     */
    private $files = array();


    //--------------------------------------------------------------------

    /**
     * Setup the options
     *
     * @return void
     */
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('modulebuilder');
        $this->options = $this->CI->config->item('modulebuilder');
        // filenames
        $this->files = array(
                            'model' => 'myform_model',
                            'view' => 'myform_view',
                            'controller' => 'myform',
                            'migration'  => 'migration'
                            );

    }//end __construct

    //--------------------------------------------------------------------

    /**
     * Generare the files required for the module
     *
     * @access public
     *
     * @param int    $field_total           The number of fields to add to the table
     * @param string $module_name           The name given to the module
     * @param array  $contexts              An array of contexts selected
     * @param array  $action_names          An array of the controller actions (methods) required
     * @param string $primary_key_field     The name of the primary key
     * @param string $db_required           The database requirement setting (new, existing or none)
     * @param array  $form_error_delimiters An array with the html delimiters for error messages
     * @param string $module_description    A description for the module which appears in the config file
     * @param int    $role_id               The id of the role which receives full access to the module
     * @param string $table_name            The name of the table in the database
     * @param int    $table_as_field_prefix Use table name as field prefix
     *
     * @return array An array with the content for the generated files
     */
    public function build_files($field_total, $module_name, $contexts, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $module_description, $role_id, $table_name, $table_as_field_prefix)
    {
        $this->CI->load->helper('inflector');

        // filenames
        $this->files = array(
                            'model' => singular($module_name).'_model',
                            'migration'  => 'migration',
                            );

        $content = array();
        $content['acl_migration'] = FALSE;
        $content['config'] = FALSE;
        $content['controllers'] = FALSE;
        $content['db_migration'] = FALSE;
        $content['lang'] = FALSE;
        $content['model'] = FALSE;
        $content['views'] = FALSE;

        // if the db is required then there is at least one field, the primary ID, so make $field_total at least 1
        $field_total = (empty($field_total) && $db_required != '') ? 1 : $field_total;

        // build the files
        $module_file_name = strtolower(preg_replace("/[ -]/", "_", $module_name));
        foreach( $contexts as $key => $context_name) {
            // controller
            $public_context = FALSE;
            if($context_name == 'public') {
                $context_name = $module_file_name;
                $public_context = TRUE;
            }
            $content['controllers'][$context_name] = $this->build_controller($field_total, $module_name, $context_name, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $table_name, $table_as_field_prefix);

            // view files
            if ($public_context === TRUE)
            {
                // only build this view in the Public context
                $content['views'][$context_name]['index'] = $this->build_view($field_total, $module_name, $context_name, 'index_front', 'Index', $primary_key_field, $table_as_field_prefix);
            }
            else {
                // only build these views for the Admin contexts
                foreach($action_names as $key => $action_name) {
                    if ($action_name != 'delete' ) {
                        $content['views'][$context_name][$action_name] = $this->build_view($field_total, $module_name, $context_name, $action_name, $this->options['form_action_options'][$action_name], $primary_key_field);
                    }
                }
                $content['views'][$context_name]['js'] = $this->build_view($field_total, $module_name, $context_name, 'js', $this->options['form_action_options'][$action_name], $primary_key_field);
                $content['views'][$context_name]['_sub_nav'] = $this->build_view($field_total, $module_name, $context_name, 'sub_nav', $this->options['form_action_options'][$action_name], $primary_key_field);
            }
        }

        // build the config file
        $content['config'] = $this->build_config($module_name, $module_description);

        // build the lang file
        $content['lang'] = $this->build_lang($module_name, $module_file_name);

        // build the permissions migration file
        $content['acl_migration'] = $this->build_acl_sql($field_total, $module_name, $contexts, $action_names, $role_id, $table_name);

        if ($field_total && $db_required != '') {
           // build the model file
            $content['model'] = $this->build_model($field_total, $module_file_name, $action_names, $primary_key_field, $table_name);

            // db based files - migrations
            if( $db_required == 'new') {
                $content['db_migration'] = $this->build_db_sql($field_total, $module_name, $primary_key_field, $table_name, $table_as_field_prefix);
            }
        }

        if ($content['acl_migration'] == FALSE || $content['config'] == FALSE || $content['controllers'] == FALSE || $content['views'] == FALSE || ($db_required != '' && (($content['model'] == FALSE && $content['db_migration'] == FALSE) ) ) )
        {
            // something went wrong when trying to build the form
            log_message('error', "The form was not built. There was an error with one of the build_() functions. Probably caused by total fields variable not being set");
            $this->CI->session->set_flashdata('error', 'Wow! There was a problem igniting your form. It would be great if you could let me know what happened. Thanks.');
            redirect();
        }

        // we need something unique to build the file directory. unix timestamp seemed like a good choice
        $id = '';
        // write to files to disk
        $write_status = $this->_write_files($module_file_name, $content, $table_name, $db_required);

        $data['error'] = FALSE;
        if( $write_status['status'] ) {

        }
        else {
            // write failed
            $data['error'] = TRUE;
            $data['error_msg'] = $write_status['error'];
        }

        // make the variables available to the view file
        $data['acl_migration'] 	= $content['acl_migration'];
        $data['build_config'] 	= $content['config'];
        $data['controllers'] 	= $content['controllers'];
        $data['db_migration'] 	= $content['db_migration'];
        $data['lang'] 			= $content['lang'];
        $data['model'] 			= $content['model'];
        $data['views'] 			= $content['views'];
        $data['db_table'] 		= $table_name;

        return $data;

    }//end build_files()

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     * Write the files for the module to the server
     *
     * @access private
     *
     * @param string $module_name The name of the module
     * @param array  $content     An array containing the content for the files
     * @param string $table_name  The name of the db table
     * @param string $db_required The database requirement setting (new, existing or none)
     *
     * @return array An array containing the status and error message
     */
    private function _write_files($module_name, $content, $table_name, $db_required)
    {

        $ret_val = array('status' => TRUE);
        $error_msg = 'Module Builder:';

        if (!is_dir($this->options['output_path']."{$module_name}/") && !@mkdir($this->options['output_path']."{$module_name}/",0777))
        {
            log_message('error', "failed to make directory ./forms/{$module_name}/");
            $ret_val['status'] = FALSE;
            $ret_val['error'] = $error_msg. " " .$this->options['output_path']."{$module_name}/";
        }
        else
        {
            // loop to save all the files to disk - considered using a db but this makes things more portable
            // and easier for a user to install
            @mkdir($this->options['output_path']."{$module_name}/assets/",0777);
            @mkdir($this->options['output_path']."{$module_name}/assets/css/",0777);
            @mkdir($this->options['output_path']."{$module_name}/assets/js/",0777);
            @mkdir($this->options['output_path']."{$module_name}/config/",0777);
            @mkdir($this->options['output_path']."{$module_name}/controllers/",0777);
            @mkdir($this->options['output_path']."{$module_name}/views/",0777);
            @mkdir($this->options['output_path']."{$module_name}/language/",0777);
            @mkdir($this->options['output_path']."{$module_name}/language/english/",0777);
            @mkdir($this->options['output_path']."{$module_name}/migrations/",0777);

            // create the models folder if the db is required
            if ($db_required != '')
            {
                @mkdir($this->options['output_path']."{$module_name}/models/",0777);
            }

            foreach($content as $type => $value)
            {
                if($type == 'controllers') {
                    foreach($content[$type] as $name => $value)
                    {
                        if($value != '') {
                            if ( ! write_file($this->options['output_path']."{$module_name}/{$type}/{$name}.php", $value))
                            {
                                log_message('error', "failed to write file ./forms/{$module_name}/{$type}/{$name}/");
                                $ret_val['status'] = FALSE;
                                $ret_val['error'] = $error_msg. " " .$this->options['output_path']."{$module_name}/{$type}/{$name}/";
                                break;
                            }
                        }
                    }
                }
                elseif($type == 'views') {
                    $this->CI->load->helper('file');

                    $view_files = $content['views'];
                    foreach($view_files as $view_context => $context_views)
                    {
                        foreach($context_views as $action => $value)
                        {
                            if($action == 'display') {
                                $action = 'index';
                            }

                            $file_name = $action.".php";
                            $path = $module_name."/".$type."/".$view_context;
                            if ($action == 'js') {
                                $path = $module_name."/assets/js";
                                $file_name = $module_name.".js";
                            }

                            // put the public views into the main views folder
                            if ($view_context == $module_name)
                            {
                                $path = $module_name."/".$type;
                            }
                            @mkdir($this->options['output_path']."{$path}",0777);
                            if ( ! write_file($this->options['output_path']."{$path}/{$file_name}", $value))
                            {
                                log_message('error', "failed to write file ./forms/{$path}/{$file_name}/");
                                $ret_val['status'] = FALSE;
                                $ret_val['error'] = $error_msg. " " .$this->options['output_path']."{$path}/{$file_name}/";
                                break;
                            }
                        }
                    }//end foreach
                }
                else {
                    // check if the content is blank
                    if($value != '') {
                        $ext = 'php';
                        $file_name = $module_name;
                        $path = $this->options['output_path']."{$module_name}/{$type}s";
                        switch ($type)
                        {
                            case 'acl_migration':
                                $file_name = "001_Install_".$file_name."_permissions";
                                $path = $this->options['output_path']."{$module_name}/migrations";
                                break;
                            case 'db_migration':
                                $file_name = "002_Install_".$table_name;
                                $path = $this->options['output_path']."{$module_name}/migrations";
                                break;
                            case 'model':
                                $file_name .= "_model";
                                break;
                            case 'lang':
                                $file_name .= "_lang";
                                $path = $this->options['output_path']."{$module_name}/language/english";
                                break;
                            case 'config':
                                $file_name = "config";
                                $path = $this->options['output_path']."{$module_name}/config";
                                break;

                            default:
                                break;
                        }

                        if( !is_dir($path) ) {
                            $path = $this->options['output_path']."{$module_name}";
                        }

                        if ( ! write_file($path."/{$file_name}." . $ext, $value))
                        {
                            log_message('error', "failed to write file $path/{$file_name}/");
                            $ret_val['status'] = FALSE;
                            $ret_val['error'] = $error_msg. " " .$path;
                            break;
                        }
                    }//end if
                }//end if
            }//end foreach
        }//end if

        return $ret_val;

    }//end _write_files()

    //--------------------------------------------------------------------

    /**
     * Generate the content for a view file
     *
     * @access private
     *
     * @param int    $field_total           The number of fields to add to the table
     * @param string $module_name           The name given to the module
     * @param string $controller_name       The name of the controller class
     * @param string $action_name           The name of the controller method which will use the view
     * @param string $action_label          The value used on the submit button
     * @param string $primary_key_field     The name of the primary key
     *
     * @return mixed FALSE on error/A string containing the content of the view file
     */
    private function build_view($field_total, $module_name, $controller_name, $action_name, $action_label, $primary_key_field)
    {
        if ($field_total == NULL)
        {
              return FALSE;
        }

        $data['field_total'] 		= $field_total;
        $data['module_name'] 		= $module_name;
        $data['module_name_lower'] 	= preg_replace("/[ -]/", "_", strtolower($module_name));
        $data['controller_name'] 	= $controller_name;
        $data['action_name'] 		= $action_name;
        $data['primary_key_field'] 	= $primary_key_field;
        $data['action_label'] 		= $action_label;
        $data['textarea_editor'] 	= $this->CI->input->post('textarea_editor');
        $data['use_soft_deletes'] 	= $this->CI->input->post('use_soft_deletes');
        $data['use_created'] 		= $this->CI->input->post('use_created');
        $data['use_modified'] 		= $this->CI->input->post('use_modified');

        $id_val = '';
        if($action_name != 'insert' && $action_name != 'add') {
            $id_val = '$id';
        }

        $data['id_val'] = $id_val;

        switch ($action_name)
        {
            case 'list':
            case 'index':
                $view_name = 'index';
                break;
            case 'index_front':
                $view_name = 'index_front';
                break;
            case 'delete':
                $view_name = 'delete';
                break;
            case 'js':
                $view_name = 'js';
                break;
            case 'sub_nav':
                $view_name = 'sub_nav';
                break;
            default:
                $view_name = 'default';
                break;
        }

        $view = $this->CI->load->view('files/view_'.$view_name, $data, TRUE);

        return $view;

    }//end build_view()


	//--------------------------------------------------------------------

    /**
     * Generate the content of a controller file
     *
     * @access private
     *
     * @param int    $field_total           The number of fields to add to the table
     * @param string $module_name           The name given to the module
     * @param string $controller_name       The name of the controller class
     * @param array  $action_names          An array of the controller actions (methods) required
     * @param string $primary_key_field     The name of the primary key
     * @param string $db_required           The database requirement setting (new, existing or none)
     * @param array  $form_error_delimiters An array with the html delimiters for error messages
     * @param string $table_name            The name of the table in the database
     *
     * @return mixed FALSE on error/A string containing the content of the controller file
     */
    private function build_controller($field_total, $module_name, $controller_name, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $table_name, $table_as_field_prefix)
    {
        if (is_null($field_total))
        {
            return FALSE;
        }

        $data['field_total'] = $field_total;
        $data['module_name'] = $module_name;
        $data['table_name'] = $table_name;
        $data['module_name_lower'] = preg_replace("/[ -]/", "_", strtolower($module_name));
        $data['controller_name'] = $controller_name;
        $data['action_names'] = $action_names;
        $data['primary_key_field'] = $primary_key_field;
        $data['db_required'] = $db_required;
        $data['form_error_delimiters'] = $form_error_delimiters;
        $data['textarea_editor'] = $this->CI->input->post('textarea_editor');
        $data['table_as_field_prefix'] = $table_as_field_prefix;
        $controller = $this->CI->load->view('files/controller', $data, TRUE);
        return $controller;

    }//end build_controller()

    //--------------------------------------------------------------------

    /**
     * Generate the content of a model file
     *
     * @access private
     *
     * @param int    $field_total       The number of fields to add to the table
     * @param string $module_file_name  The name given to the module
     * @param array  $action_names      An array of the controller actions (methods) required
     * @param string $primary_key_field The name of the primary key
     * @param string $table_name        The name of the table in the database
     *
     * @return mixed FALSE on error/A string containing the content of the model file
     */
    private function build_model($field_total, $module_file_name, $action_names, $primary_key_field, $table_name)
    {
        if ($field_total == NULL)
        {
            return FALSE;
        }

        $data['field_total']        = $field_total;
        $data['controller_name']    = $module_file_name;
        $data['action_names']       = $action_names;
        $data['primary_key_field']  = $primary_key_field;
        $data['table_name']         = $table_name;

        $model = $this->CI->load->view('files/model', $data, TRUE);

        return $model;

    }//end build_model()

    //--------------------------------------------------------------------


    /**
     * Generate the content of a language file
     *
     * @access private
     *
     * @param string $module_name       The name given to the module
     * @param string $module_name_lower The name given to the module in lowercase
     *
     * @return string A string containing the content of the language file
     */
    private function build_lang($module_name, $module_name_lower)
    {
        $data['module_name'] = $module_name;
        $data['module_name_lower'] = $module_name_lower;
        $lang = $this->CI->load->view('files/lang', $data, TRUE);

        return $lang;

    }//end build_lang()

    //--------------------------------------------------------------------


    /**
     * Generate the content of the module config file
     *
     * @access private
     *
     * @param string $module_name        The name given to the module
     * @param string $module_description The description text for the module
     *
     * @return string A string containing the content of the config file
     */
    private function build_config($module_name, $module_description)
    {
        $data['module_name'] = $module_name;
        $data['module_description'] = $module_description;

        // Load our current logged in user so we can access it anywhere.
        $current_user = $this->CI->user_model->find($this->CI->auth->user_id());

        $data['username'] = $current_user->username;
        $lang = $this->CI->load->view('files/config', $data, TRUE);

        return $lang;

    }//end build_config()

    //--------------------------------------------------------------------

    /**
     * Generate the acl (permissions) migration file
     *
     * @access private
     *
     * @param int    $field_total  The number of fields to add to the table
     * @param string $module_name  The name given to the module
     * @param array  $contexts     An array of contexts selected
     * @param array  $action_names An array of the controller actions (methods) required
     * @param int    $role_id      The id of the role which receives full access to the module
     *
     * @return string A string containing the content of the permission migration file
     */
    private function build_acl_sql($field_total, $module_name, $contexts, $action_names, $role_id)
    {
        $data['field_total'] = $field_total;
        $data['module_name'] = preg_replace("/[ -]/", "_", $module_name);
        $data['module_name_lower'] = preg_replace("/[ -]/", "_", strtolower($module_name));
        $data['contexts'] = $contexts;
        $data['action_names'] = $action_names;
        $data['role_id'] = $role_id;

        $acl_migration = $this->CI->load->view('files/acl_migration', $data, TRUE);

        return $acl_migration;

    }//end build_acl_sql()

    //--------------------------------------------------------------------


    /**
     * Generate the module migration file which creates the database table
     *
     * @access private
     *
     * @param int    $field_total       The number of fields to add to the table
     * @param string $module_name       The name given to the module
     * @param string $primary_key_field The name of the primary key
     * @param string $table_name        The name of the table in the database
     *
     * @return string A string containing the content of the database migration file
     */
    private function build_db_sql($field_total, $module_name, $primary_key_field, $table_name, $table_as_field_prefix)
    {
        if ($field_total == NULL)
        {
            return FALSE;
        }

        $data['field_total'] = $field_total;
        $data['module_name'] = preg_replace("/[ -]/", "_", $module_name);
        $data['module_name_lower'] = preg_replace("/[ -]/", "_", strtolower($module_name));
        $data['primary_key_field'] = $primary_key_field;
        $data['table_name']         = $table_name;
        $data['table_as_field_prefix']   = $table_as_field_prefix;

        $db_migration = $this->CI->load->view('files/db_migration', $data, TRUE);

        return $db_migration;

    }//end build_db_sql()

    //--------------------------------------------------------------------

    /**
     * Custom Form Validation Callback Rule
     *
     * Checks that one field doesn't match all the others.
     * This code is not really portable. Would of been nice to create a rule that accepted an array
     *
     * @access protected
     *
     * @param string $str     Name of the field
     * @param int    $fieldno The position number of this field
     *
     * @return bool
     */
    protected function no_match($str, $fieldno)
    {
        for($counter=1; $this->field_total >= $counter; $counter++)
        {
            // nothing has been entered into this field so we don't need to check
            // or the field being checked is the same as the field we are checking from
            if ($_POST["view_field_name$counter"] == '' || $fieldno == $counter)
            {
                continue;
            }

            if ($str == $_POST["view_field_name$counter"])
            {
                $this->CI->form_validation->set_message('no_match', "Field names must be unique!");
                return FALSE;
            }
        }

        return TRUE;

    }//end no_match

    //--------------------------------------------------------------------

    /**
     * Makes directory, returns TRUE if exists or made
     *
     * @access private
     *
     * @param string $pathname The directory path.
     * @param string $mode     The unix permissions on the directory eg (0775)
     *
     * @return bool TRUE if exists or made or FALSE on failure.
     */
    private function mkdir_recursive($pathname, $mode)
    {
        is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname), $mode);
        return is_dir($pathname) || @mkdir($pathname, $mode);

    }//end mkdir_recursive()


    //--------------------------------------------------------------------

}//end Modulebuilder
