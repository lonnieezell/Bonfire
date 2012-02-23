<?php

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
class Modulebuilder
{
	public $CI;
	public $options = array();
	public $field_numbers = array(6,10,20,40);
	private $field_total = 0;
	private $files = array();
	

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
	}
	
	//--------------------------------------------------------------------
	
	public function build_files($field_total, $module_name, $contexts, $action_names, $primary_key_field, $db_required, $form_input_delimiters, $form_error_delimiters, $module_description, $role_id, $table_name) {
		
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
		$field_total = (empty($field_total) && $db_required) ? 1 : $field_total;

        // build the files
		$module_file_name = str_replace(" ", "_", strtolower($module_name));
		foreach( $contexts as $key => $context_name) {
			// controller
			$public_context = FALSE;
			if($context_name == 'public') {
				$context_name = $module_file_name;
				$public_context = TRUE;
			}
			$content['controllers'][$context_name] = $this->build_controller($field_total, $module_name, $context_name, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $table_name);

			// view files
			if ($public_context === TRUE)
			{
				// only build this view in the Public context
				$content['views'][$context_name]['index'] = $this->build_view($field_total, $module_name, $context_name, 'index_front', 'Index', $primary_key_field, $form_input_delimiters);
			}
			else {
				// only build these views for the Admin contexts
				foreach($action_names as $key => $action_name) {
					if ($action_name != 'delete' ) {
						$content['views'][$context_name][$action_name] = $this->build_view($field_total, $module_name, $context_name, $action_name, $this->options['form_action_options'][$action_name], $primary_key_field, $form_input_delimiters);
					}
				}
				$content['views'][$context_name]['index_alt'] = $this->build_view($field_total, $module_name, $context_name, 'index_alt', $this->options['form_action_options'][$action_name], $primary_key_field, $form_input_delimiters);
				$content['views'][$context_name]['js'] = $this->build_view($field_total, $module_name, $context_name, 'js', $this->options['form_action_options'][$action_name], $primary_key_field, $form_input_delimiters);
			}
		}

		// build the config file
		$content['config'] = $this->build_config($module_name, $module_description);

		// build the lang file
		$content['lang'] = $this->build_lang($module_name, $module_file_name);

		// build the permissions migration file
		$content['acl_migration'] = $this->build_acl_sql($field_total, $module_name, $contexts, $action_names, $role_id, $table_name);

		if ($field_total) {
    	   // build the model file
        	$content['model'] = $this->build_model($field_total, $module_file_name, $action_names, $primary_key_field, $table_name);
    		
    		// db based files - migrations
    		if( $db_required ) {
    			$content['db_migration'] =  $this->build_db_sql($field_total, $module_name, $primary_key_field, $table_name);
    		}
        }

		if ($content['acl_migration'] == FALSE || $content['config'] == FALSE || $content['controllers'] == FALSE || $content['views'] == FALSE || ($db_required && (($content['model'] == FALSE || $content['db_migration'] == FALSE) ) ) ) 
		{
			// something went wrong when trying to build the form
			log_message('error', "The form was not built. There was an error with one of the build_() functions. Probably caused by total fields variable not being set");
			$this->CI->session->set_flashdata('error', 'Wow! There was a problem igniting your form. It would be great if you could let me know what happened. Thanks.');
			redirect();
		}

		// we need something unique to build the file directory. unix timestamp seemed like a good choice
		$id = '';
		// write to files to disk
		$write_status = $this->_write_files($module_file_name, $content, $table_name);

		$data['error'] = FALSE;
		if( $write_status['status'] ) {

		}
		else {
			// write failed
			$data['error'] = TRUE;
			$data['error_msg'] = $write_status['error'];
		}

		// make the variables available to the view file
		$data['acl_migration'] = $content['acl_migration'];
		$data['build_config'] = $content['config'];		
		$data['controllers'] = $content['controllers'];
		$data['db_migration'] = $content['db_migration'];
		$data['lang'] = $content['lang'];
		$data['model'] = $content['model'];
		$data['views'] = $content['views'];
		$data['db_table'] = $table_name;

		return $data;
	}

	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// PRIVATE METHODS
	//--------------------------------------------------------------------

	private function _write_files($module_name, $content, $table_name) {
		
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
			@mkdir($this->options['output_path']."{$module_name}/models/",0777);
			@mkdir($this->options['output_path']."{$module_name}/views/",0777);
			@mkdir($this->options['output_path']."{$module_name}/language/",0777);
			@mkdir($this->options['output_path']."{$module_name}/language/english/",0777);
			@mkdir($this->options['output_path']."{$module_name}/migrations/",0777);

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
							$path = $module_name."/".$type."/".$view_context;
							// put the public views into the main views folder
							if ($view_context == $module_name)
							{
								$path = $module_name."/".$type;
							}
							@mkdir($this->options['output_path']."{$path}",0777);
							if ( ! write_file($this->options['output_path']."{$path}/{$action}.php", $value))
							{
								log_message('error', "failed to write file ./forms/{$path}/{$action}/");
								$ret_val['status'] = FALSE;
								$ret_val['error'] = $error_msg. " " .$this->options['output_path']."{$path}/{$action}/";
								break;
							}
						}
					}
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
					}
				}
			}

		
		}
	
		return $ret_val;
	}
	
	//--------------------------------------------------------------------

   /** 
    * function build_view()
    *
    * write view file
    * @access private
    * @param	integer $field_total
    * @return string
    *
    */
	private function build_view($field_total, $module_name, $controller_name, $action_name, $action_label, $primary_key_field, $form_input_delimiters)
	{
		if ($field_total == NULL)
		{
			  return FALSE;
		}
		  
		$data['field_total'] = $field_total;
		$data['module_name'] = $module_name;
		$data['module_name_lower'] = str_replace(" ", "_", strtolower($module_name));
		$data['controller_name'] = $controller_name;
		$data['action_name'] = $action_name;
		$data['primary_key_field'] = $primary_key_field;
		$data['action_label'] = $action_label;
		$data['form_input_delimiters'] = $form_input_delimiters;
		$data['textarea_editor'] = $this->CI->input->post('textarea_editor');
		$data['use_soft_deletes'] = $this->CI->input->post('use_soft_deletes');
		$data['use_created'] = $this->CI->input->post('use_created');
		$data['use_modified'] = $this->CI->input->post('use_modified');

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
			case 'index_alt':
				$view_name = 'index_alt';
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
			default:
				$view_name = 'default';
				break;
		}
	
		$view = $this->CI->load->view('files/view_'.$view_name, $data, TRUE);

        return $view;

	}

	
	//--------------------------------------------------------------------

   /** 
    * function build_controller()
    *
    * write view file
    * @access private
    * @param integer $field_total
    * @return string
 	*
	*/
	private function build_controller($field_total, $module_name, $controller_name, $action_names, $primary_key_field, $db_required, $form_error_delimiters, $table_name)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}
		  
		$data['field_total'] = $field_total;
		$data['module_name'] = $module_name;
		$data['table_name'] = $table_name;
		$data['module_name_lower'] = str_replace(" ", "_", strtolower($module_name));
		$data['controller_name'] = $controller_name;
		$data['action_names'] = $action_names;
		$data['primary_key_field'] = $primary_key_field;
		$data['db_required'] = $db_required;
		$data['form_error_delimiters'] = $form_error_delimiters;
		$data['textarea_editor'] = $this->CI->input->post('textarea_editor');
		$controller = $this->CI->load->view('files/controller', $data, TRUE);
		return $controller;            
	}

	//--------------------------------------------------------------------

   /** 
    * function build_model()
    *
    * write model file
    * @access private
    * @param integer $field_total
    * @return string
    */

	private function build_model($field_total, $module_file_name, $action_names, $primary_key_field, $table_name)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}

		$data['field_total']		= $field_total;
		$data['controller_name']	= $module_file_name;
		$data['action_names']		= $action_names;
		$data['primary_key_field']	= $primary_key_field;
		$data['table_name']			= $table_name;
		
		$model = $this->CI->load->view('files/model', $data, TRUE);

		return $model;
	}
	
	//--------------------------------------------------------------------

	
   /** 
    * function build_lang()
    *
    * write language file
    * @access private
    * @param string $module_name	Module Name to use in the language file
    * @return string
    */

	private function build_lang($module_name, $module_name_lower)
	{
		$data['module_name'] = $module_name;
		$data['module_name_lower'] = $module_name_lower;
		$lang = $this->CI->load->view('files/lang', $data, TRUE);

		return $lang;
	}
	
	//--------------------------------------------------------------------

	
   /** 
    * function build_config()
    *
    * write config file
    * @access private
    * @param string $module_name		Module Name to use in the config file
    * @param string $module_description	Module Description to use in the config file
    * @return string
    */

	private function build_config($module_name, $module_description)
	{
		$data['module_name'] = $module_name;
		$data['module_description'] = $module_description;
		$lang = $this->CI->load->view('files/config', $data, TRUE);

		return $lang;
	}
	
	//--------------------------------------------------------------------    
    
    /** 
    * function build_acl_sql()
    *
    * write acl (permissions) migration file
    * @access private
    * @param string $module_name
    * @return string
    */

	private function build_acl_sql($field_total, $module_name, $contexts, $action_names, $role_id)
	{
		$data['field_total'] = $field_total;
		$data['module_name'] = str_replace(" ", "_", $module_name);
		$data['module_name_lower'] = str_replace(" ", "_", strtolower($module_name));
		$data['contexts'] = $contexts;
		$data['action_names'] = $action_names;
		$data['role_id'] = $role_id;
        
		$acl_migration = $this->CI->load->view('files/acl_migration', $data, TRUE);
		
		return $acl_migration;
	}
	
	//--------------------------------------------------------------------

	
   /** 
    * function build_db_sql()
    *
    * write view file
    * @access private
    * @param integer $field_total
    * @return string
    */

	private function build_db_sql($field_total, $module_name, $primary_key_field, $table_name)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}
		
		$data['field_total'] = $field_total;
		$data['module_name'] = str_replace(" ", "_", $module_name);
		$data['module_name_lower'] = str_replace(" ", "_", strtolower($module_name));
		$data['primary_key_field'] = $primary_key_field;
		$data['table_name']			= $table_name;
		
		$db_migration = $this->CI->load->view('files/db_migration', $data, TRUE);
		
		return $db_migration;
	}
	
	//--------------------------------------------------------------------
    
	/** Custom Form Validation Callback Rule
	 *
	 * Checks that one field doesn't match all the others.
	 * This code is not really portable. Would of been nice to create a rule that accepted an array
	 *
	 * @access	public
	 * @param	string
	 * @param	fields array
	 * @return	bool
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
	}

	//--------------------------------------------------------------------

   	/**
   	* Makes directory, returns TRUE if exists or made
   	*
   	* @param string $pathname The directory path.
   	* @return boolean returns TRUE if exists or made or FALSE on failure.
   	* http://uk2.php.net/manual/en/function.mkdir.php#81656
   	*/

   	private function mkdir_recursive($pathname, $mode)
   	{
		is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname), $mode);
		return is_dir($pathname) || @mkdir($pathname, $mode);
   	}

	
	//--------------------------------------------------------------------
    
}
