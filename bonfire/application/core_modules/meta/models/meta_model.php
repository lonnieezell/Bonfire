<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: Meta_model
*/
class Meta_model extends BF_Model {

	protected $table		= 'meta';
	protected $field_table	= 'fields';
	protected $key			= 'meta_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_created	= true;
	protected $set_modified = true;
	
	//--------------------------------------------------------------------
	
	/*
		Method: insert()
		
		Inserts a meta key/value pair into the module's meta table.
		
		Parameters:
			$key		- The key name to save.
			$value		- The value to assign to $key.
			$module		- The module name. This is the basis for the foreign_key field and table name.
			$fkey_value	- An INT with the foreign_key value.
			$field_id	- An INT with the id of the field_type from $module_fields table.
			
		Returns:
			An INT with the meta_id on success. FALSE on failure.
	*/
	public function insert($key=null, $value=null, $module=null, $fkey_value=null, $field_id=null) 
	{
		if (empty($key) || empty($value) || empty($module) || empty($fkey_value) || empty($field_id))
		{
			return false;
		}
		
		$this->prep_module($module);
		
		$data = array(
			'field_id'		=> $field_id,
			$module .'_id'	=> $fkey_value,
			'meta_key'		=> $key,
			'meta_value'	=> $value
		);
		
		if (parent::insert($data))
		{
			return $this->db->insert_id();
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: update()
		
		Updates a meta_value in the module's meta table.
		
		Parameters:
			$id		- An INT with the meta_id to update.
			$value	- The value to assign.
			$module	- The module name.
			
		Returns:
			true/false
	*/
	public function update($id=null, $value=null, $module=null) 
	{
		$data = array(
			'meta_value'	=> $value
		);
		
		$this->prep_module($module);
		
		return parent::update($id, $data);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: delete()
		
		Deletes a meta entry from the module's meta table.
		
		Parameters:
			$id		- An INT with the meta id.
			$module	- A string with the module name.
			$purge	- If TRUE, will perform hard delete. 
					  If FALSE, will perform a soft delete.
					  
		Returns:
			true/false
	*/
	public function delete($id=null, $module=null, $purge=true) 
	{
		if (!is_numeric($id) || empty($mdoule))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
	
		if ($purge == true)
		{
			$this->soft_deletes = false;
		}
		
		return parent::delete($id);
	}
	
	//--------------------------------------------------------------------
	
	public function find($id=null, $module=null) 
	{
		if (!is_numeric($id) || empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		return parent::find($id);
	}
	
	//--------------------------------------------------------------------
	
	public function find_all($module=null) 
	{
		if (empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		return parent::find_all();
	}
	
	//--------------------------------------------------------------------
	
	public function find_all_by($field=null, $value=null, $module=null) 
	{
		if (empty($field) || empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		return parent::find_all_by($field, $value);
	}
	
	//--------------------------------------------------------------------
	
	public function find_by($field=null, $value=null, $module=null, $type='and') 
	{
		if (empty($field) || empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		return parent::find_by($field, $value);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_by_key()
		
		Finds an individual meta record by the given key. Returns all associated
		object information. Can be filtered by table, foreign_key name or value, 
		individually or jointly.
		
		Parameters:
			$key	- The name of the key to retrieve.
			$module	- The name of the module to retrieve from.
			$value	- Optional. If set, will filter results where meta_value = $value.
			$fkey_value	- Optional. If set, will filter results where $module_id = $fkey_value
			
		Returns:
			An array of results, or FALSE.
	*/
	public function find_by_key($key=null, $module=null, $value=null, $fkey_value=null) 
	{
		if (empty($key) || empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
	
		$this->prepare_module($module);
	
		$fields = array(
			'meta_key'	=> $key
		);
		
		if (!empty($value))
		{
			$fields['meta_value'] = $value;
		}
		
		if (!empty($fkey_value))
		{
			$fields[$this->key]	= $fkey_value;
		}
		
		return parent::find_by($fields, null, 'and');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_all_by_key()
		
		Finds all meta records by the given key. 
		
		Parameters:
			$key
	*/
	public function find_all_by_key($key=null, $module=null, $value=null, $fkey_value=null) 
	{
		if (empty($key) || empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
	
		$this->prepare_module($module);
	
		$fields = array(
			'meta_key'	=> $key
		);
		
		if (!empty($value))
		{
			$fields['meta_value'] = $value;
		}
		
		if (!empty($fkey_value))
		{
			$fields[$this->key]	= $fkey_value;
		}
		
		return parent::find_all_by($fields, null, 'and');
	}
	
	//--------------------------------------------------------------------

	/*
		Method: find_all_for()
		
		Finds all meta records by the given foreign key. Useful for pulling
		all of the user's meta keys from their user_id and similar queries.
		
		Examples: 
			Given a user_meta table with a foreign key of 'user_id'...
			
			$this->meta_module->find_all_for($user_id, 'users');
		
		Parameters: 
			$foreign_key	- The foreign key to search for.
			$module			- The name of the module to search in.
			
		Returns: 
			An object with the full meta array.
	*/
	public function find_all_for($foreign_key=null, $module=null) 
	{
		if (!is_numeric($foreign_key) || empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		$fields = array(
			$this->key	=> $foreign_key
		);

		return parent::find_all_by($fields, null, 'and');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: save_all_for()
		
		Saves an array of key/value pairs to the table for a single foreign key. 
		This is especially handy when you need to save a number of items for a 
		single, consistent, key, like a user. 
		
		Example:
			$data = array(
				'interests'	=> 'This is my interests string,
				'location'	=> 'Some City, Katmandu'
			);
			$this->meta_model->save_all_for($user_id, 'user', $data);
		
		Parameters:
			$foreign_key	- The value of the foreign key that describes our object
			$module			- The name of the module
			$data			- The array of data to save.
	*/
	public function save_all_for($foreign_key=null, $module=null, $data=array()) 
	{
		if (is_null($foreign_key) || is_null($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		/*
			It's a little bit clunky and needs to be looked at again
			in the future - but for now, simply loop through each item
			checking to see if it exists. If it does, do an update, otherwise
			insert it.
		*/
		foreach ($data as $key => $value)
		{
			// Does it exist? 
			$this->db->where('foreign_id', $);
		}
	}
	
	//--------------------------------------------------------------------
	
	
	public function count_all($module=null) 
	{
		if (empty($module))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prep_module($module);
		
		return parent::count_all();
	}
	
	//--------------------------------------------------------------------
	
	public function count_by($field=null, $value=null, $module=null) 
	{
		if (empty($module) || empty($field))
		{
			$this->error = 'Insufficient data.';
			return false;
		}
		
		$this->prepare_module($module);
		
		return parent::count_by($field, $value);
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !Custom Fields
	//--------------------------------------------------------------------
	
	/*
		Method: insert_custom_field()
		
		Creates a new custom field entry for the specified module.
		
		The params array should be formatted as a series of key/value pairs
		that match the following...
		
		$params = array(
			'field_name'		=> '',	// The system name of the field. Required. No spaces.
			'field_label'		=> '',	// The display name of the field. Required.
			'field_order'		=> '',	// The display order. INT. Optional. Defaults to 0.
			'field_desc'		=> '',	// Description of field. Used as a help string. Optional.
			'field_type'		=> '',	// The type of field, ie 'text', 'dropdown', 'checkbox', etc.
			'field_options'		=> '',	// Only needed for selects. A serialized set of options/values.
			'field_width'		=> '',	// A string with the input width. Used for CSS display.
			'field_default'		=> '',	// A default value. Optional.
			'field_required'	=> '',	// Either 0 or 1 for not required/required. Defaults to 0.
			'field_validators	=> ''	// A string with pipe-delimited validation rules. ie 'trim|xss_clean'.
		);
		
		Parameters: 
			$params	- An array of key/value pairs with the entries.
			$module	- The module name.
			
		Returns: 
			An int with the ID of the field or FALSE.
	*/
	public function insert_custom_field($params=null, $module=null) 
	{
		if (!is_array($params))
		{
			$this->error = 'No parameters found.';
			return false;
		}
		
		if (empty($module))
		{
			$this->error = 'No module found.';
			return false;
		}
		
		$this->prep_module($module);
		
		if ($this->db->insert($this->field_table, $params))
		{
			return $this->db->insert_id();
		}
		
		return false;		
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_all_fields()
		
		Finds all custom fields for the current module.
		
		Parameters:
			$module	- The name of the module to get information about.
			
		Returns:
			An array of field information, or FALSE.
	*/
	public function find_all_fields($module=null)
	{
		if (empty($module))
		{
			$this->error = 'No module found.';
			return false;
		}
		
		$this->prep_module($module);
		
		$this->set_selects();
		
		$this->db->from($this->field_table);
		
		$query = $this->db->get();
		
		if (!empty($query) && $query->num_rows() > 0)
		{
			return $query->result();
		}
		
		$this->error = $this->lang->line('bf_model_bad_select');
		$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_bad_select'));
		return false;
	}
	
	//--------------------------------------------------------------------
	

	//--------------------------------------------------------------------
	// !Module Setup/Teardown methods
	//--------------------------------------------------------------------
	
	/*
		Method: setup_module_meta()
		
		Sets up a new module to have custom field information usable.
		This sets up 2 new tables: 
			
			'*_fields'	- Holds the fields and their display information.
			'*_meta'	- Holds the actual custom data.
			
		Parameters:
			$module	- A string with the name of the module. This is the
					  name that will be used for the table names. 
					  
		Returns:
			true/false
	*/
	public function setup_module_meta($module=null) 
	{
		if (empty($module))
		{
			$this->error = lang('meta_no_module');
			return false;
		}
		
		$this->load->dbforge();
		
		$this->prep_module($module);

		// Fields table
		if (!$this->db->table_exists($module .'_fields'))
		{ 
			$fields = array(
				'id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 4,
					'unsigned'		=> true,
					'auto_increment'	=> true
				),
				'name'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 32,
				),
				'label'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 50
				),
				'order'	=> array(
					'type'			=> 'int',
					'constraint'	=> 11,
					'default'		=> 0
				),
				'desc'	=> array(
					'type'		=> 'text',
					'null'		=> true,
				),
				'type'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 50
				),
				'options'	=> array(
					'type'		=> 'text',
					'null'		=> true,
				),
				'width'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 20,
					'null'			=> true,
				),
				'default'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 255,
					'null'			=> true,
				),
				'placeholder'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 255,
					'null'			=> true,
				),
				'required'	=> array(
					'type'			=> 'tinyint',
					'constraint'	=> 1,
					'default'		=> 0
				),
				'validators'	=> array(
					'type'		=> 'text',
					'null'		=> true,
				),
				'created_on'	=> array(
					'type'		=> 'datetime',
					'null'		=> true
				),
				'modified_on'	=> array(
					'type'		=> 'datetime',
					'null'		=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', true);
	
			$this->dbforge->create_table($module .'_fields');
		}
		
		// Meta table
		if (!$this->db->table_exists($module .'_meta'))
		{	
			$fields = array(
				'meta_id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 20,
					'unsigned'		=> true,
					'auto_increment'	=> true
				),
				'field_id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 4,
					'unsigned'		=> true,
					'default'		=> 0
				),
				$module .'_id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 20,
					'unsigned'		=> true,
					'default'		=> 0
				),
				'meta_key'	=> array(
					'type'			=> 'varchar',
					'constraint'	=> 255,
					'default'		=> ''
				),
				'meta_value' => array(
					'type'		=> 'text',
					'null'		=> true,
				),
				'created_on'	=> array(
					'type'		=> 'datetime',
					'null'		=> true
				),
				'modified_on'	=> array(
					'type'		=> 'datetime',
					'null'		=> true
				)		
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('meta_id', TRUE);
			
			$this->dbforge->create_table($module .'_meta');
		}
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: remove_module_meta()
		
		Removes any meta/field tables from the database for a given module.
		Intended to be used during migrations.
		
		Parameters:
			$module	- A string with the module name
		
		Returns:
			true/false
	*/
	public function remove_module_meta($module=null) 
	{
		if (empty($module))
		{
			$this->error = lang('meta_no_module');
			return false;
		}
		
		$this->load->dbforge();
		
		$this->prep_module($module);
		
		$this->dbforge->drop_table($module .'_fields');
		$this->dbforge->drop_table($module .'_meta');
		
		return true;
	}
	
	//--------------------------------------------------------------------
	
	public function prep_module(&$module) 
	{	
		// Prep the module name for use in table
		$module = url_title($module, 'underscore', true);
		
		// Setup the table to use
		$this->table 		= $module .'_meta';
		$this->field_table	= $module .'_fields';

		$this->key = $module .'_id';
	}
	
	//--------------------------------------------------------------------
	
}