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
		$this->CI->load->library('form_validation');
		$this->CI->load->library('zip');
		$this->CI->load->library('session');
		$this->CI->load->helper('url');
		$this->CI->load->helper('file');
		$this->CI->load->helper('download');
		$this->CI->load->helper('security');
		$this->options = $this->CI->config->item( 'modulebuilder' );

		// filenames 
		$this->files = array(
	                        'model' => 'myform_model',
	                        'view' => 'myform_view',
	                        'controller' => 'myform',
	                        'sql'  => 'sql'
	                        );
	

	}
	
	public function build_files($field_total, $module_name, $main_context, $contexts, $action_names, $db_required, $ajax_processing, $form_input_delimiters, $form_error_delimiters) {
		
		// filenames 
		$this->files = array(
							'model' => $module_name.'_model',
							'controller' => $main_context,
							'javascript'  => $module_name,
							'sql'  => 'sql',
							);

		$content = array();
		$content['views'] = FALSE;
		$content['controllers'] = FALSE;
		$content['model'] = FALSE;
		$content['javascript'] = FALSE;
		$content['sql'] = FALSE;

		// Build the files
		if( $field_total ) {

			$module_file_name = strtolower($module_name);
			foreach( $contexts as $key => $context_name) {
				// controller
				if($context_name == 'public') {
					$context_name = $module_file_name;
				}
				$content['controllers'][$context_name] = $this->build_controller($field_total, $module_name, $context_name, $action_names, $db_required, $form_error_delimiters);

				// view files
				foreach($action_names as $key => $action_name) {

					$content['views'][$context_name][$action_name] = $this->build_view($field_total, $module_name, $context_name, $action_name, $this->options['form_action_options'][$action_name], $form_input_delimiters);
				}
			}
			// db based files - model and sql
			if( $db_required ) {
				$content['sql'] =  $this->build_sql($field_total, $module_file_name);
				$content['model'] = $this->build_model($field_total, $module_file_name, $action_names);
			}
			// javascript
			if( $ajax_processing ) {

				$content['javascript'] = $this->build_javascript($field_total, $module_file_name, $action_names);
			}
		}

		if ($content['views'] == FALSE || $content['controllers'] == FALSE || ($db_required && ($content['model'] == FALSE || $content['sql'] == FALSE) ) ) // not correct syntax
		{
			// something went wrong when trying to build the form
			log_message('error', "The form was not built. There was an error with one of the build_() functions. Probably caused by total fields variable not being set");
			$this->session->set_flashdata('error', 'Wow! There was a problem igniting your form. It would be great if you could let me know what happened. Thanks.');
			redirect();
		}

		// we need something unique to build the file directory. unix timestamp seemed like a good choice
		$id = '';
		// write to files to disk
		$write_status = $this->_write_files($module_file_name, $content);

		$data['error'] = FALSE;
		if( $write_status['status'] ) {

		}
		else {
			// write failed
			$data['error'] = TRUE;
			$data['error_msg'] = $write_status['error'];
		}


		// make the variables available to the view file		
		$data['views'] = $content['views'];
		$data['controllers'] = $content['controllers'];
		$data['model'] = $content['model'];
		$data['javascript'] = $content['javascript'];
		$data['sql'] = $content['sql'];

		return $data;
	}


	// --------------------------------------------------------------------

	private function _write_files($module_name, $content) {
		
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
			@mkdir($this->options['output_path']."{$module_name}/controllers/",0777);
			@mkdir($this->options['output_path']."{$module_name}/models/",0777);
			@mkdir($this->options['output_path']."{$module_name}/views/",0777);

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
					$view_files = $content['views'];
					foreach($view_files as $view_context => $context_views)
					{
						foreach($context_views as $action => $value)
						{
							if($action == 'display') {
								$action = 'index';
							}
							$path = $module_name."/".$type."/".$view_context;
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
					$ext = 'php';
					$file_name = $module_name;
					switch ($type)
					{
						case 'javascript':
							$ext = 'js';
							break;
						case 'sql':
							$file_name = "Migrations_Install_".$file_name;
							break;
						case 'model':
							$file_name .= "_model";
							break;

						default:
							break;
					}

					$path = $this->options['output_path']."{$module_name}/{$type}s";
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
	
		return $ret_val;
	}
	
	// --------------------------------------------------------------------

   /** 
    * function build_view()
    *
    * write view file
    * @access private
    * @param	integer $field_total
    * @return string
    *
    */
	private function build_view($field_total, $module_name, $controller_name, $action_name, $action_label, $form_input_delimiters)
	{
		if ($field_total == NULL)
		{
			  return FALSE;
		}
		  
		$data['field_total'] = $field_total;
		$data['module_name'] = $module_name;
		$data['module_name_lower'] = strtolower($module_name);
		$data['controller_name'] = $controller_name;
		$data['action_name'] = $action_name;
		$data['action_label'] = $action_label;
		$data['form_input_delimiters'] = $form_input_delimiters;

		$id_val = '';
		if($action_name != 'insert' && $action_name != 'add') {
			$id_val = '/$id';
		}
		$data['id_val'] = $id_val;
		
		$view_name = 'default';
		if( $action_name == 'list' OR $action_name == 'index') {
			$view_name = 'index';
		}
		elseif( $action_name == 'delete' ) {
			$view_name = 'delete';
		}
	
		$view = $this->CI->load->view('files/view_'.$view_name, $data, TRUE);

        return $view;

	}

	
	// --------------------------------------------------------------------

   /** 
    * function build_controller()
    *
    * write view file
    * @access private
    * @param integer $field_total
    * @return string
 	*
	*/
	private function build_controller($field_total, $module_name, $controller_name, $action_names, $db_required, $form_error_delimiters)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}
		  
		$data['field_total'] = $field_total;
		$data['module_name'] = $module_name;
		$data['module_name_lower'] = strtolower($module_name);
		$data['controller_name'] = $controller_name;
		$data['db_required'] = $db_required;
		$data['action_names'] = $action_names;
		$data['form_error_delimiters'] = $form_error_delimiters;
		$controller = $this->CI->load->view('files/controller', $data, TRUE);
		return $controller;            
	}

	// --------------------------------------------------------------------

   /** 
    * function build_model()
    *
    * write view file
    * @access private
    * @param integer $field_total
    * @return string
    */

	private function build_model($field_total, $module_name, $action_names)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}

		$data['field_total'] = $field_total;
		$data['controller_name'] = $module_name;
		$data['action_names'] = $action_names;
		$model = $this->CI->load->view('files/model', $data, TRUE);

		return $model;
	}
	
	// --------------------------------------------------------------------

   /** 
    * function build_javascript()
    *
    * write view file
    * @access private
    * @param integer $field_total
    * @return string
 	*
	*/

	private function build_javascript($field_total, $controller_name, $action_names)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}
		  
		$data['field_total'] = $field_total;
		$data['controller_name'] = $controller_name;
		$data['action_names'] = $action_names;
		$javascript = $this->CI->load->view('files/javascript', $data, TRUE);
		
		return $javascript;
	}
	
   /** 
    * function build_sql()
    *
    * write view file
    * @access private
    * @param integer $field_total
    * @return string
    */

	private function build_sql($field_total, $module_name)
	{
		if ($field_total == NULL)
		{
			return FALSE;
		}
		$data['field_total'] = $field_total;
		$data['module_name'] = $module_name;
		$data['module_name_lower'] = strtolower($module_name);
		$sql = $this->CI->load->view('files/migrations', $data, TRUE);
/*
		$sql = 'CREATE TABLE IF NOT EXISTS  `'.$controller_name.'` (
 id int(40) NOT NULL auto_increment,';
		
		for($counter=1; $field_total >= $counter; $counter++)
		{
			//Due to the requiredif rule if the first field is set the the others must be
			if (set_value("view_field_label$counter") == NULL)
			{
				continue; 	// move onto next iteration of the loop
			}

		$sql .= '
 '.set_value("view_field_name$counter").' '.set_value("db_field_type$counter");
		
			if (!in_array(set_value("db_field_type$counter"), array('TEXT', 'DATETIME'))) // There are no doubt more types where a value/length isn't possible - needs investigating
			{
				$sql .= '('.set_value("db_field_length_value$counter").')';
			}
		

		$sql .= ' NOT NULL,';
		
		}
		
		$sql .= '
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
 * 
 */
		
		return $sql;
		
		
		// ip_address varchar(16) DEFAULT '0' NOT NULL,
		// user_agent varchar(50) NOT NULL,
		// last_activity int(10) unsigned DEFAULT 0 NOT NULL,
		// user_data text NOT NULL,
	}
	
	// --------------------------------------------------------------------
	
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

	function no_match($str, $fieldno)
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

	// --------------------------------------------------------------------
	
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
   
	// --------------------------------------------------------------------

    /**
     * Read a directory and add it to the zip.
     *
     * This is a customised version of the standard zip library function
     * The directory structure is removed and a readmefile is included if it exists
     * 
     * This function recursively reads a folder and everything it contains (including
     * sub-folders) and creates a zip based on it.  Whatever directory structure
     * is in the original file path will be recreated in the zip file.
     *
     * @access	public
     * @param	string	path to source
     * @return	bool
     */	

	private function read_dir($orig_path, $new_path = '')
	{
		$dir_path = $this->options['output_path'].$orig_path.$new_path;

		if ($fp = @opendir($dir_path))
		{
			while (FALSE !== ($file = readdir($fp)))
        	{
				if (@is_dir($this->options['output_path'].$orig_path.$new_path.$file) && substr($file, 0, 1) != '.')
				{
					$this->read_dir($orig_path.$new_path, $file."/");
        		}
				elseif (substr($file, 0, 1) != ".")
        		{
					if (FALSE !== ($data = file_get_contents($this->options['output_path'].$orig_path."/".$new_path.$file)))
        			{
						$this->CI->zip->add_data($orig_path.$new_path.$file, $data);
        			}
        		}
        	}
    
            return TRUE;
        }
	}

	// --------------------------------------------------------------------
}
