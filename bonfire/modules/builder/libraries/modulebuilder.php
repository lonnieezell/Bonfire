<?php
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Module Builder library
 *
 * Performs the heavy-lifting while creating new modules for Bonfire.
 *
 * Originally based on Ollie Rattue's http://formigniter.org/ project
 *
 * @package    Bonfire\Modules\Builder\Libraries\Modulebuilder
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/builder
 */
class Modulebuilder
{
    /**
     * @var object A pointer to the CodeIgniter instance.
     */
    public $CI;

    /**
     * @var array Various settings from the modulebuilder config file
     */
    public $options = array();

    /**
     * @todo Not used?
     */
    public $field_numbers = array(6, 10, 20, 40);

    protected $databaseTypes = array(
        'BIGINT'        => array('numeric', 'integer'),
        'BINARY'        => array('binary'),
        'BIT'           => array('numeric', 'integer', 'bit'),
        'BLOB'          => array('binary', 'object'),
        'BOOL'          => array('numeric', 'integer', 'boolean'),
        'BOOLEAN'       => array('numeric', 'integer', 'boolean'),
        'CHAR'          => array('string'),
        'DATE'          => array('date'),
        'DATETIME'      => array('date', 'time'),
        'DEC'           => array('numeric', 'real'),
        'DECIMAL'       => array('numeric', 'real'),
        'DOUBLE'        => array('numeric', 'real'),
        'ENUM'          => array('string', 'list'),
        'FLOAT'         => array('numeric', 'real'),
        'INT'           => array('numeric', 'integer'),
        'INTEGER'       => array('numeric', 'integer'),
        'LONGBLOB'      => array('binary', 'object'),
        'LONGTEXT'      => array('string', 'object'),
        'MEDIUMBLOB'    => array('binary', 'object'),
        'MEDIUMINT'     => array('numeric', 'integer'),
        'MEDIUMTEXT'    => array('string', 'object'),
        'NUMERIC'       => array('numeric', 'real'),
        'REAL'          => array('numeric', 'real'),
        'SET'           => array('string', 'list'),
        'SMALLINT'      => array('numeric', 'integer'),
        'TIME'          => array('time'),
        'TIMESTAMP'     => array('date', 'time'),
        'TINYBLOB'      => array('binary', 'object'),
        'TINYINT'       => array('numeric', 'integer'),
        'TINYTEXT'      => array('string', 'object'),
        'TEXT'          => array('string', 'object'),
        'VARBINARY'     => array('binary'),
        'VARCHAR'       => array('string'),
        'YEAR'          => array('year', 'integer'),
    );

    protected $booleanTypes = array();
    protected $dateTypes = array();
    protected $integerTypes = array();
    protected $listTypes = array();
    protected $objectTypes = array();
    protected $realNumberTypes = array();
    protected $stringTypes = array();
    protected $textTypes = array();
    protected $timeTypes = array();

    /**
     * @var int Total number of fields being used in this module
     */
    private $field_total = 0;

    /**
     * @var array The files being output for the current module
     */
    private $files = array();

    /**
     * @var string[] The language files to be used when building the modules
     */
    private $languages_available = array('english', 'portuguese_br', 'spanish_am');


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

        if ( ! empty($this->options['languages_available'])
            && is_array($this->options['languages_available'])
           ) {
            $this->languages_available = $this->options['languages_available'];
        }

        if ( ! empty($this->options['database_types'])) {
            $this->databaseTypes = $this->options['database_types'];
        }

        foreach ($this->databaseTypes as $key => $dataTypes) {
            foreach ($dataTypes as $typeVal) {
                // The order below is based on the number of occurrences of each
                // type in the default set of values, from highest to lowest
                switch ($typeVal) {
                    case 'integer':
                        $this->integerTypes[] = $key;
                        break;

                    case 'string':
                        $this->stringTypes[] = $key;
                        break;

                    case 'object':
                        $this->objectTypes[] = $key;
                        break;

                    case 'real':
                        $this->realNumberTypes[] = $key;
                        break;

                    case 'date':
                        $this->dateTypes[] = $key;
                        break;

                    case 'time':
                        $this->timeTypes[] = $key;
                        break;

                    case 'boolean':
                        $this->booleanTypes[] = $key;
                        break;

                    case 'list':
                        $this->listTypes[] = $key;
                        break;
                }
            }
        }

        $this->textTypes = array_intersect($this->objectTypes, $this->stringTypes);

        $this->files = array(
            'model'      => 'myform_model',
            'view'       => 'myform_view',
            'controller' => 'myform',
            'migration'  => 'migration'
        );
    }

    /**
     * Generate the files required for the module
     *
     * @param array $data The data required to build the module
     *  int    'field_total'           The number of fields to add to the table
     *  string 'module_name'           The name given to the module
     *  array  'contexts'              An array of contexts selected
     *  array  'action_names'          An array of the controller actions (methods) required
     *  string 'primary_key_field'     The name of the primary key
     *  string 'db_required'           The database requirement setting (new, existing or none)
     *  array  'form_error_delimiters' An array with the html delimiters for error messages
     *  string 'module_description'    A description for the module which appears in the config file
     *  int    'role_id'               The id of the role which receives full access to the module
     *  string 'table_name'            The name of the table in the database
     *  int    'table_as_field_prefix' Use table name as field prefix
     *
     * @return array An array with the content for the generated files
     */
    public function buildFiles($data)
    {
        $action_names           = $data['action_names'];
        $contexts               = $data['contexts'];
        $db_required            = $data['db_required'];
        $field_total            = $data['field_total'];
        $form_error_delimiters  = $data['form_error_delimiters'];
        $module_description     = $data['module_description'];
        $module_name            = $data['module_name'];
        $primary_key_field      = $data['primary_key_field'];
        $role_id                = $data['role_id'];
        $table_as_field_prefix  = $data['table_as_field_prefix'];
        $table_name             = $data['table_name'];

        $controller_name        = isset($data['controller_name']) ? $data['controller_name'] : preg_replace("/[ -]/", "_", $module_name);
        $module_name_lower      = isset($data['module_name_lower']) ? $data['module_name_lower'] : strtolower($controller_name);

        $logUser                = isset($data['logUser']) ? $data['logUser'] : false;
        $useCreated             = isset($data['useCreated']) ? $data['useCreated'] : false;
        $useModified            = isset($data['useModified']) ? $data['useModified'] : false;
        $usePagination          = isset($data['usePagination']) ? $data['usePagination'] : false;
        $useSoftDeletes         = isset($data['useSoftDeletes']) ? $data['useSoftDeletes'] : false;

        $created_field          = isset($data['created_field']) ? $data['created_field'] : 'created_on';
        $created_by_field       = isset($data['created_by_field']) ? $data['created_by_field'] : 'created_by';
        $soft_delete_field      = isset($data['soft_delete_field']) ? $data['soft_delete_field'] : 'deleted';
        $deleted_by_field       = isset($data['deleted_by_field']) ? $data['deleted_by_field'] : 'deleted_by';
        $modified_field         = isset($data['modified_field']) ? $data['modified_field'] : 'modified_on';
        $modified_by_field      = isset($data['modified_by_field']) ? $data['modified_by_field'] : 'modified_by';

        $textarea_editor        = isset($data['textarea_editor']) ? $data['textarea_editor'] : '';

        // Used by buildConfig()
        $current_user = $this->CI->user_model->find($this->CI->auth->user_id());

        // Used by buildController()
        $data['textTypes']          = $this->textTypes;

        // Used by buildModel() and buildDbSQL()
        $data['realNumberTypes']    = $this->realNumberTypes; // also buildView()
        $data['listTypes']          = $this->listTypes;

        $data['controller_name'] = $controller_name;
        $data['module_name_lower'] = $module_name_lower;
        $data['logUser'] = $logUser;
        $data['useCreated'] = $useCreated;
        $data['useModified'] = $useModified;
        $data['usePagination'] = $usePagination;
        $data['useSoftDeletes'] = $useSoftDeletes;
        $data['created_field'] = $created_field;
        $data['created_by_field'] = $created_by_field;
        $data['soft_delete_field'] = $soft_delete_field;
        $data['deleted_by_field'] = $deleted_by_field;
        $data['modified_field'] = $modified_field;
        $data['modified_by_field'] = $modified_by_field;
        $data['textarea_editor'] = $textarea_editor;
        $data['username'] = $current_user->username;

        $this->CI->load->helper('inflector');
        $this->files = array(
            'model'     => singular($module_name) . '_model',
            'migration' => 'migration',
        );

        $content = array(
            'acl_migration' => false,
            'config'        => false,
            'controllers'   => false,
            'db_migration'  => false,
            'lang'          => false,
            'model'         => false,
            'views'         => false,
        );

        // If the db is required there is at least one field, the primary ID,
        // so $field_total is at least 1
        $field_total = empty($field_total) && $db_required != '' ? 1 : $field_total;

        // Build the files
        $module_file_name = $module_name_lower;

        // Each context has a controller and a set of views
        foreach ($contexts as $key => $context_name) {
            // Controller
            $public_context = false;
            if ($context_name == 'public') {
                $context_name   = $module_file_name;
                $public_context = true;
            }
            $data['controller_name'] = $context_name;
            $content['controllers'][$context_name] = $this->buildController($data);

            // Views
            if ($public_context === true) {
                // Only build this view in the Public context
                $data['action_name'] = 'index_front';
                $data['action_label'] = 'Index';
                $content['views'][$context_name]['index'] = $this->buildView($data);
            } else {
                // Only build these views for the Admin contexts
                foreach ($action_names as $key => $action_name) {
                    if ($action_name != 'delete' ) {
                        $data['action_name'] = $action_name;
                        $data['action_label'] = $this->options['form_action_options'][$action_name];
                        $content['views'][$context_name][$action_name] = $this->buildView($data);
                    }
                }
                $data['action_name'] = 'js';
                $data['action_label'] = $this->options['form_action_options'][$action_name];
                $content['views'][$context_name]['js'] = $this->buildView($data);

                $data['action_name'] = 'sub_nav';
                $content['views'][$context_name]['_sub_nav'] = $this->buildView($data);
            }
        }

        // Build the config file
        $content['config'] = $this->buildConfig($data);

        // Build the lang file
        $content['lang'] = $this->build_lang($field_total, $module_name, $module_file_name);

        // Build the permissions migration file
        $content['acl_migration'] = $this->buildAclSql($data);

        // If the DB is required and there are fields, build a model and migration
        if ($field_total && $db_required != '') {
           // Build the model file
            $content['model'] = $this->buildModel($data);

            // DB migration
            if ($db_required == 'new') {
                $content['db_migration'] = $this->buildDbSql($data);
            }
        }

        // Did everything build correctly?
        if ($content['acl_migration'] == false || $content['config'] == false
            || $content['controllers'] == false || $content['views'] == false
            || ($db_required != '' && $content['model'] == false)
            || ($db_required == 'new' && $content['db_migration'] == false)
           ) {
            $data['error']  = true;
            $data['error_msg'] = "The form was not built. There was an error with one of the build_() functions. Probably caused by total fields variable not being set";
        } else {
            // Write the files to disk
            $write_status = $this->_write_files($module_file_name, $content, $table_name, $db_required);

            $data['error'] = false;
            if ( ! $write_status['status']) {
                // Write failed
                $data['error']      = true;
                $data['error_msg']  = $write_status['error'];
            }
        }

        // Make the variables available to the view file
        $data['acl_migration'] 	= $content['acl_migration'];
        $data['build_config'] 	= $content['config'];
        $data['controllers'] 	= $content['controllers'];
        $data['db_migration'] 	= $content['db_migration'];
        $data['lang'] 			= $content['lang'];
        $data['model'] 			= $content['model'];
        $data['views'] 			= $content['views'];
        $data['db_table'] 		= $table_name;

        return $data;
    }

    /**
     * Generate the files required for the module
     *
     * @deprecated since 0.7.1
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
        return $this->buildFiles(array(
            'field_total'            => $field_total,
            'module_name'            => $module_name,
            'contexts'               => $contexts,
            'action_names'           => $action_names,
            'primary_key_field'      => $primary_key_field,
            'db_required'            => $db_required,
            'form_error_delimiters'  => $form_error_delimiters,
            'module_description'     => $module_description,
            'role_id'                => $role_id,
            'table_name'             => $table_name,
            'table_as_field_prefix'  => $table_as_field_prefix
        ));
    }

    /**
     * Get the list of boolean data types supported by the database
     *
     * @return string[]    The names of the boolean data types
     */
    public function getBooleanTypes()
    {
        return $this->booleanTypes;
    }

    /**
     * Get the list of data types supported by the database
     *
     * @return array    An array in which the keys are the names of the data
     * types and the values are an array of generic type information ('integer',
     * 'numeric', 'binary', 'object', etc.)
     */
    public function getDatabaseTypes()
    {
        return $this->databaseTypes;
    }

    /**
     * Get the list of date data types supported by the database
     *
     * @return string[]    The names of the date data types
     */
    public function getDateTypes()
    {
        return $this->dateTypes;
    }

    /**
     * Get the list of integer data types supported by the database
     *
     * @return string[]    The names of the integer data types
     */
    public function getIntegerTypes()
    {
        return $this->integerTypes;
    }

    /**
     * Get the list of the list data types supported by the database
     *
     * @return string[]    The names of the list data tpes
     */
    public function getListTypes()
    {
        return $this->listTypes;
    }

    /**
     * Get the list of the object data types supported by the database
     *
     * Object data types include string objects (text fields) and binary objects
     * (blob, or binary large object, fields)
     *
     * @return string[]    The names of the object data types
     */
    public function getObjectTypes()
    {
        return $this->objectTypes;
    }

    /**
     * Get the list of the real number data types supported by the database
     *
     * @return string[]    The names of the real number data types
     */
    public function getRealNumberTypes()
    {
        return $this->realNumberTypes;
    }

    /**
     * Get the list of the string data types supported by the database
     *
     * @return string[]    The names of the string data types
     */
    public function getStringTypes()
    {
        return $this->stringTypes;
    }

    /**
     * Get the list of the text data types supported by the database
     *
     * Usually these are the types which are both string and object types
     *
     * @return string[]    The names of the text data types
     */
    public function getTextTypes()
    {
        return $this->textTypes;
    }

    /**
     * Get the list of the time data types supported by the database
     *
     * @return string[]    The names of the time data types
     */
    public function getTimeTypes()
    {
        return $this->timeTypes;
    }

    //--------------------------------------------------------------------
    // PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     * Generate the ACL (permissions) migration file
     *
     * @param array $data The data required to build the permissions migration
     *  int    'field_total'  The number of fields to add to the table
     *  string 'module_name'  The name given to the module
     *  array  'contexts'     An array of contexts selected
     *  array  'action_names' An array of the controller actions (methods) required
     *  int    'role_id'      The id of the role which receives full access to the module
     *
     * @return string A string containing the content of the permission migration file
     */
    private function buildAclSql($data)
    {
        return $this->CI->load->view('files/acl_migration', $data, true);
    }

    /**
     * Generate the content of the module config file
     *
     * @param array $data The data used to generate the config file's content
     *  string 'module_name'        The name given to the module
     *  string 'module_description' The description text for the module
     *  string 'username'           The user name for the current user
     *  string 'module_name_lower'  Cleaned/lowercase version of module_name
     *
     * @return string A string containing the content of the config file
     */
    private function buildConfig($data)
    {
        return $this->CI->load->view('files/config', $data, true);
    }

    /**
     * Generate the content of a controller file
     *
     * @param array $data The data required to build the controller
     *  int    'field_total'           The number of fields to add to the table
     *  string 'module_name'           The name given to the module
     *  string 'controller_name'       The name of the controller class
     *  array  'action_names'          An array of the controller actions (methods) required
     *  string 'primary_key_field'     The name of the primary key
     *  string 'db_required'           The database requirement setting (new, existing or none)
     *  array  'form_error_delimiters' An array with the html delimiters for error messages
     *  string 'table_name'            The name of the table in the database
     *
     * @return string|bool The content of the controller file or false on error
     */
    private function buildController($data)
    {
        if (is_null($data['field_total'])) {
            return false;
        }

        return $this->CI->load->view('files/controller', $data, true);
    }

    /**
     * Generate the module migration file which creates the database table
     *
     * @param array $data The data required to build the migration
     *  int    'field_total'       The number of fields to add to the table
     *  string 'module_name'       The name given to the module
     *  string 'primary_key_field' The name of the primary key
     *  string 'table_name'        The name of the table in the database
     *  bool   'table_as_field_prefix'  Whether the table name is used as a
     *  prefix for field names
     *
     * @return string A string containing the content of the database migration file
     */
    private function buildDbSql($data)
    {
        $field_total = $data['field_total'];
        if (is_null($field_total)) {
            return false;
        }

        // There are no doubt more types where a value/length isn't possible
        // - needs investigating
        $data['no_length'] = array_merge(
            $this->objectTypes,
            $this->booleanTypes,
            $this->dateTypes,
            $this->timeTypes
        );

        // Types where a value/length is optional, will not output a constraint
        // if the field is empty
        $data['optional_length'] = array_diff($this->integerTypes, $this->booleanTypes);

        return $this->CI->load->view('files/db_migration', $data, true);
    }

    /**
     * Generate the content of a model file
     *
     * @param array $data The data to use when building the model:
     *  int    'field_total'       The number of fields to add to the table
     *  string 'module_name_lower' The name given to the module
     *  array  'action_names'      An array of the controller actions (methods) required
     *  string 'primary_key_field' The name of the primary key
     *  string 'table_name'        The name of the table in the database
     *
     * @return string|bool The content of the model file or false on error
     */
    private function buildModel($data)
    {
        $field_total = $data['field_total'];
        if (is_null($field_total)) {
            return false;
        }

        return $this->CI->load->view('files/model', $data, true);
    }

    /**
     * Generate the content for a view file
     *
     * @param array $data   The data required to build the view
     *  int    'field_total'        The number of fields to add to the table
     *  string 'module_name'        The name given to the module
     *  string 'controller_name'    The controller class name
     *  string 'action_name'        The controller method which will use the view
     *  string 'action_label'       The value used on the submit button
     *  string 'primary_key_field'  The name of the primary key
     *
     * @return string|bool The content of the view file or false on error
     */
    private function buildView($data)
    {
        if ($data['field_total'] == null) {
              return false;
        }

        $action_label = $data['action_label'];
        $action_name  = $data['action_name'];
        $data['id_val'] = $action_name != 'insert' && $action_name != 'add' ? '$id' : '';

        switch ($action_name) {
            case 'list':
                $view_name = 'index';
                break;

            case 'index':
                // no break
            case 'index_front':
                // no break
            case 'delete':
                // no break
            case 'js':
                // no break
            case 'sub_nav':
                // no break
                $view_name = $action_name;
                break;

            default:
                $view_name = 'default';
                break;
        }

        return $this->CI->load->view("files/view_{$view_name}", $data, true);
    }

    /**
     * Write the files for the module to the server
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
        // Load the constants config if DIR_WRITE_MODE is undefined
        defined('DIR_WRITE_MODE') || $this->CI->load->config('constants');

        $error_msg = 'Module Builder:';
        $modulePath = $this->options['output_path'] . "{$module_name}/";

        // Make the $modulePath directory if it does not exist
        if ( ! is_dir($modulePath) && ! @mkdir($modulePath, DIR_WRITE_MODE)) {
            $errorMessage = "failed to make directory $modulePath";
            log_message('error', $errorMessage);

            return array(
                'status' => false,
                'error'  => "{$error_msg} {$errorMessage}",
            );
        }

        $ret_val = array('status' => true);

        // Make all of the directories required within the $modulePath
        @mkdir("{$modulePath}/assets/", DIR_WRITE_MODE);
        @mkdir("{$modulePath}/assets/css/", DIR_WRITE_MODE);
        @mkdir("{$modulePath}/assets/js/", DIR_WRITE_MODE);
        @mkdir("{$modulePath}/config/", DIR_WRITE_MODE);
        @mkdir("{$modulePath}/controllers/", DIR_WRITE_MODE);
        @mkdir("{$modulePath}/views/", DIR_WRITE_MODE);
        @mkdir("{$modulePath}/migrations/", DIR_WRITE_MODE);

        // Language directories
        @mkdir("{$modulePath}/language/", DIR_WRITE_MODE);
        foreach ($this->languages_available as $language_folder) {
            @mkdir("{$modulePath}/language/{$language_folder}/", DIR_WRITE_MODE);
        }

        // Create the models folder if the db is required
        if ($db_required != '') {
            @mkdir("{$modulePath}/models/", DIR_WRITE_MODE);
        }

        // Load the file helper (used for write_file() calls in the loop)
        $this->CI->load->helper('file');

        // Loop to save all the files to disk - considered using a db but
        // this makes things more portable and easier for a user to install

        // @todo revise the interior loops for clarity
        foreach ($content as $type => $value) {
            if ($type == 'controllers') {
                // @todo $content[$type] == $value, and $value shouldn't be
                // redefined here
                foreach ($content[$type] as $name => $value) {
                    if ($value != '') {
                        if ( ! write_file("{$modulePath}/{$type}/{$name}.php", $value)) {
                            $errorMessage = "failed to write file {$modulePath}/{$type}/{$name}.php";
                            log_message('error', $errorMessage);
                            $ret_val['status']  = false;
                            $ret_val['error']   = "{$error_msg} {$errorMessage}";
                            unset($errorMessage);
                            break;
                        }
                    }
                }
            } elseif ($type == 'views') {
                // @todo $content['views'] == $value
                $view_files = $content['views'];
                foreach ($view_files as $view_context => $context_views) {
                    // @todo $value shouldn't be redefined here
                    foreach ($context_views as $action => $value) {
                        if ($action == 'display') {
                            $action = 'index';
                        }

                        $file_name = "{$action}.php";
                        $path = "{$module_name}/{$type}/{$view_context}";
                        if ($action == 'js') {
                            $path = "{$module_name}/assets/js";
                            $file_name = "{$module_name}.js";
                        }

                        // Put the public views into the main views folder
                        if ($view_context == $module_name) {
                            $path = "{$module_name}/{$type}";
                        }

                        $viewPath = $this->options['output_path'] . $path;
                        if ( ! is_dir($viewPath)) {
                            @mkdir($viewPath, DIR_WRITE_MODE);
                        }
                        if ( ! write_file("{$viewPath}/{$file_name}", $value)) {
                            $errorMessage = "failed to write file {$viewPath}/{$file_name}";
                            log_message('error', $errorMessage);
                            $ret_val['status']  = false;
                            $ret_val['error']   = "{$error_msg} {$errorMessage}";
                            unset($errorMessage);
                            break;
                        }
                    }
                }
            } elseif ($type == 'lang') {
                $ext = 'php';
                foreach ($value as $lang_name => $lang_file_contents) {
                    $file_name = "{$module_name}_lang";
                    $path = "{$modulePath}/language/{$lang_name}";

                    if ( ! write_file("{$path}/{$file_name}.{$ext}", $lang_file_contents)) {
                        $errorMessage = "failed to write language file {$path}/{$file_name}.{$ext}";
                        log_message('error', $errorMessage);
                        $ret_val['status']  = false;
                        $ret_val['error']   = "{$error_msg} {$errorMessage}";
                        break;
                    }
                }
            }
            // Check whether the content is blank
            elseif ($value != '') {
                $file_name = $module_name;
                $path = "{$modulePath}/{$type}s";

                switch ($type) {
                    case 'acl_migration':
                        $file_name = "001_Install_{$file_name}_permissions";
                        $path = "{$modulePath}/migrations";
                        break;

                    case 'db_migration':
                        $file_name = "002_Install_{$table_name}";
                        $path = "{$modulePath}/migrations";
                        break;

                    case 'model':
                        $file_name .= "_model";
                        break;

                    case 'config':
                        $file_name = "config";
                        $path = "{$modulePath}/config";
                        break;

                    default:
                        break;
                }

                if ( ! is_dir($path) ) {
                    $path = "{$modulePath}";
                }

                $ext = 'php';
                if ( ! write_file("{$path}/{$file_name}.{$ext}", $value)) {
                    $errorMessage = "failed to write file {$path}/{$file_name}.{$ext}";
                    log_message('error', $errorMessage);
                    $ret_val['status']  = false;
                    $ret_val['error']   = "{$error_msg} {$errorMessage}";
                    break;
                }
            }
        }

        return $ret_val;
    }

    /**
     * Generate the content of a language file
     *
     * @param string $module_name       The name given to the module
     * @param string $module_name_lower The name given to the module in lowercase
     *
     * @return string A string containing the content of the language file
     */
    private function build_lang($field_total, $module_name, $module_name_lower)
    {
        $data['field_total'] = $field_total;
        $data['module_name'] = $module_name;
        $data['module_name_lower'] = $module_name_lower;

        $lang = array();
        foreach ($this->languages_available as $language_file) {
            $lang[$language_file] = $this->CI->load->view("files/languages/{$language_file}", $data, true);
        }

        return $lang;
    }

    /**
     * Custom Form Validation Callback Rule
     *
     * Checks that one field doesn't match all the others.
     *
     * This code is not really portable. Would have been nice to create a rule
     * that accepted an array.
     *
     * @param string $str     Name of the field
     * @param int    $fieldno The position number of this field
     *
     * @return bool
     */
    protected function no_match($str, $fieldno)
    {
        for ($counter = 1; $this->field_total >= $counter; $counter++) {
            // Nothing has been entered into this field or the field being
            // checked is the same as the field it will be checked against
            if ($_POST["view_field_name$counter"] == '' || $fieldno == $counter) {
                continue;
            }

            if ($str == $_POST["view_field_name$counter"]) {
                $this->CI->form_validation->set_message('no_match', "Field names must be unique!");
                return false;
            }
        }

        return true;
    }

    /**
     * Makes directory, returns TRUE if exists or made
     *
     * @deprecated since 0.7.1 use the third parameter for mkdir instead
     *
     * @param string $pathname The directory path.
     * @param string $mode     The unix permissions on the directory eg (0775)
     *
     * @return bool TRUE if exists or made or FALSE on failure.
     */
    private function mkdir_recursive($pathname, $mode)
    {
        return is_dir($pathname) || @mkdir($pathname, $mode, true);
    }
}
//end Modulebuilder