<?php

 // There are no doubt more types where a value/length isn't possible - needs investigating
$no_length = array(
	'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT',
	'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB',
	'BOOL',
	'DATE', 'DATETIME', 'TIME', 'TIMESTAMP',
);

// types where a value/length is optional, will not output a constraint if the field is empty
$optional_length = array(
	'INT', 'TINYINT', 'MEDIUMINT', 'BIGINT',
	'YEAR',
);

$decimal_types = array(
	'DECIMAL', 'DOUBLE', 'FLOAT',
);

if ( ! $table_as_field_prefix)
{
	$module_name_lower = '';
}
else
{
	$module_name_lower = $module_name_lower . '_';
}

$db_migration = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_' . $table_name . ' extends Migration
{
	/**
	 * The name of the database table
	 *
	 * @var String
	 */
	private $table_name = \'' . $table_name . '\';

	/**
	 * The table\'s fields
	 *
	 * @var Array
	 */
	private $fields = array(
		\'' . $primary_key_field . '\' => array(
			\'type\' => \'INT\',
			\'constraint\' => 11,
			\'auto_increment\' => TRUE,
		),';

	for ($counter = 1; $field_total >= $counter; $counter++)
	{
		//Due to the requiredif rule if the first field is set the the others must be
		if (set_value("view_field_label$counter") == NULL)
		{
			continue; 	// move onto next iteration of the loop
		}

		$db_migration .= '
		\'' . $module_name_lower . set_value("view_field_name$counter") . '\' => array(
			\'type\' => \'' . addcslashes(set_value("db_field_type$counter"), '"') . '\',';

		if ( ! in_array(set_value("db_field_type$counter"), $no_length))
		{
			$escaped_constraint_val = $this->input->post("db_field_length_value$counter");

			// ENUM or SET
			if (in_array(set_value("db_field_type$counter"), array("ENUM", "SET")))
			{
				$escaped_constraint_val = '\'' . addcslashes($this->input->post("db_field_length_value$counter"), "'") . '\'';
			}
			elseif (in_array(set_value("db_field_type$counter"), $decimal_types))
			{
				$escaped_constraint_val = '\'' . $escaped_constraint_val . '\'';
			}

			if (in_array(set_value("db_field_type$counter"), $optional_length) && empty($escaped_constraint_val) && ! is_numeric($escaped_constraint_val))
			{
				$constraint = '';
			}
			else
			{
				$constraint = '
			\'constraint\' => ' . $escaped_constraint_val . ',';
			}
			$db_migration .= $constraint;

			unset($escaped_constraint_val, $constraint);
		}

		// should probably be set based on user input rather than assumed,
		// replace TRUE with a proper conditional
		if (TRUE)
		{
			$db_migration .= '
			\'null\' => FALSE,';
		}

		// set defaults for certain field types
		switch (set_value("db_field_type$counter"))
		{
			case 'DATE':
				$db_migration .= '
			\'default\' => \'0000-00-00\',';
				break;

			case 'DATETIME':
				$db_migration .= '
			\'default\' => \'0000-00-00 00:00:00\',';
				break;

			default:
				break;
		}

		$db_migration .= '
		),';
	}

	// use soft deletes? Add deleted field.
	if ($this->input->post('use_soft_deletes') == 'true')
	{
		$delete_field = ($this->input->post('soft_delete_field')) ? $this->input->post('soft_delete_field') : 'deleted';
		$db_migration .= "
			'".$delete_field."' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => '0',
			),";
	}

	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_created') == 'true')
	{
		$created_field = ($this->input->post('created_field')) ? $this->input->post('created_field') : 'created_on';
		$db_migration .= '
		\'' . $created_field . '\' => array(
			\'type\' => \'datetime\',
			\'default\' => \'0000-00-00 00:00:00\',
		),';
	}

	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_modified') == 'true')
	{
		$modified_field = ($this->input->post('modified_field')) ? $this->input->post('modified_field') : 'modified_on';
		$db_migration .= '
		\'' . $modified_field . '\' => array(
			\'type\' => \'datetime\',
			\'default\' => \'0000-00-00 00:00:00\',
		),';
	}

	$db_migration .= '
	);

	/**
	 * Install this migration
	 *
	 * @return void
	 */
	public function up()
	{
		$this->dbforge->add_field($this->fields);
		$this->dbforge->add_key(\'' . $primary_key_field . '\', true);
		$this->dbforge->create_table($this->table_name);
	}

	//--------------------------------------------------------------------

	/**
	 * Uninstall this migration
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dbforge->drop_table($this->table_name);
	}

	//--------------------------------------------------------------------

}';

echo $db_migration;