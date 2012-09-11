<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_User_meta_move extends Migration {

	private $core_user_fields = array(
		'id',
		'role_id',
		'email',
		'username',
		'password_hash',
		'reset_hash',
		'salt',
		'last_login',
		'last_ip',
		'created_on',
		'deleted',
		'banned',
		'ban_message',
		'reset_by'
	);

	private $default_fields = array(
		'first_name',
		'last_name',
		'street_1',
		'street_2',
		'city',
		'zipcode',
		'state_code',
		'country_iso'
	);

	//--------------------------------------------------------------------

	/*
		Adding the table for user_meta and moving all current meta fields
		over to the new table.
	*/
	public function up()
	{
		$this->load->dbforge();

		$this->setup_module_meta();

		/*
			Backup our users table
		*/
		$this->load->dbutil();

		$filename  = defined('BFPATH') ? BFPATH : APPPATH;
		$filename .= '/db/backups/backup_meta_users_table.txt';

		$prefs = array(
			'tables'		=> $this->db->dbprefix .'users',
			'format'		=> 'txt',
			'filename'		=> $filename,
			'add_drop'		=> true,
			'add_insert'	=> true
		);
		$backup =& $this->dbutil->backup($prefs);

		$this->load->helper('file');
		write_file($filename, $backup);

		if (file_exists($filename))
		{
			log_message('info', 'Backup file successfully saved. It can be found at <a href="/'. $filename .'">'. $filename . '</a>.');
		}
		else
		{
			log_message('error', 'There was a problem saving the backup file.');
			die('There was a problem saving the backup file.');
		}

		/*
			Move User data to meta table
		*/

		// If there are users, loop through them and create meta entries
		// then remove all 'non-core' columns as they will now be in the meta table.
		if ($this->db->count_all_results('users'))
		{
			$query = $this->db->get('users');
			$rows = $query->result();

			foreach ($rows as $row)
			{
				foreach ($this->default_fields as $field)
				{
					// We don't want to store the field if it doesn't exist in the user profile.
					if (!empty($row->$field))
					{
						$data = array(
							'user_id'	=> $row->id,
							'meta_key'		=> $field,
							'meta_value'	=> $row->$field
						);

						$this->db->insert('user_meta', $data);

						unset($data);
					}
				}

				// Set a default display name
				//$this->user_model->update_display_name();
			}
		}

		/*
			Drop existing columns from users table.
		*/
		$fields = $this->db->list_fields('users');

		foreach($fields as $field)
		{
			if(!in_array($field, $this->core_user_fields)) {
				$this->dbforge->drop_column('users', $field);
			}
		}
		unset($fields);

		/*
			Create display_name field in users table
		*/
		$field = array(
			'display_name'	=> array(
				'type'			=> 'varchar',
				'constraint'	=> 255,
				'null'			=> true,
				'default'		=> ''
			)
		);
		$this->dbforge->add_column('users', $field);

		$field = array(
			'display_name_changed'	=> array(
				'type'			=> 'date',
				'null'			=> true,
			)
		);
		$this->dbforge->add_column('users', $field);

		// Add new settings
		$this->db->insert('settings', array('name'=>'auth.allow_name_change', 'module' => 'core', 'value' => 1));
		$this->db->insert('settings', array('name'=>'auth.name_change_frequency', 'module' => 'core', 'value' => 1));
		$this->db->insert('settings', array('name'=>'auth.name_change_limit', 'module' => 'core', 'value' => 1));
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->load->dbforge();

		$this->db->delete('settings', array('name'=>'auth.allow_name_change'));
		$this->db->delete('settings', array('name'=>'auth.name_change_frequency'));
		$this->db->delete('settings', array('name'=>'auth.name_change_limit'));

		// Copy the information back over to the users table.

		$this->remove_module_meta('User');
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !META FUNCTIONS
	//--------------------------------------------------------------------
	// These functions were taken from the meta_model to make
	// creating and removing the meta information simpler.
	//

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
	public function setup_module_meta()
	{
		$this->load->dbforge();

		// Meta table
		if (!$this->db->table_exists('user_meta'))
		{
			$fields = array(
				'meta_id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 20,
					'unsigned'		=> true,
					'auto_increment'	=> true
				),
				'user_id'	=> array(
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
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('meta_id', TRUE);

			$this->dbforge->create_table('user_meta');
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
	public function remove_module_meta()
	{
		$this->load->dbforge();

		$this->dbforge->drop_table('user_meta');

		return true;
	}

	//--------------------------------------------------------------------

}