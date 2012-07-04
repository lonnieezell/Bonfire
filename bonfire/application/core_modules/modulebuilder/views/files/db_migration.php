<?php

 // There are no doubt more types where a value/length isn't possible - needs investigating
$no_length = array('TEXT', 'BOOL', 'DATE', 'DATETIME', 'TIME', 'TIMESTAMP', 'BLOB', 'TINYBLOB', 'TINYTEXT', 'MEDIUMBLOB', 'MEDIUMTEXT', 'LONGBLOB', 'LONGTEXT');

if(!$table_as_field_prefix)
{
	$module_name_lower = '';
}
else
{
	$module_name_lower = $module_name_lower .'_';
}

$db_migration = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class Migration_Install_'.$table_name.' extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			\''.$primary_key_field.'\' => array(
				\'type\' => \'INT\',
				\'constraint\' => 11,
				\'auto_increment\' => TRUE,
			),';

	for($counter=1; $field_total >= $counter; $counter++)
	{
		//Due to the requiredif rule if the first field is set the the others must be
		if (set_value("view_field_label$counter") == NULL)
		{
			continue; 	// move onto next iteration of the loop
		}

		$db_migration .= "
			'".$module_name_lower.set_value("view_field_name$counter")."' => array(
				'type' => '".addcslashes(set_value("db_field_type$counter"),'"')."',
				";

		if (!in_array(set_value("db_field_type$counter"), $no_length))
		{
			$db_migration .= "'constraint' => ".addcslashes($this->input->post("db_field_length_value$counter"),'"').',
				';
		}

		// NOT NULL is the default when using the fields array,
		// but should probably be set based on user input rather than assumed,
		// replace FALSE with a proper conditional
		if (FALSE) {
			$db_migration .= "'null' => TRUE,
				";
		}

		// set defaults for certain field types
		switch (set_value("db_field_type$counter"))
		{
			case 'DATE':
				$db_migration .= "'default' => '0000-00-00',
				";
				break;
			case 'DATETIME':
				$db_migration .= "'default' => '0000-00-00 00:00:00',
				";
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
		$db_migration .= "
			'deleted' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => '0',
			),";
	}

	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_created') == 'true')
	{
		$created_field = ($this->input->post('created_field')) ? $this->input->post('created_field') : 'created_on';
		$db_migration .= "
			'".$created_field."' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00',
			),";
	}

	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_modified') == 'true')
	{
		$modified_field = ($this->input->post('modified_field')) ? $this->input->post('modified_field') : 'modified_on';
		$db_migration .= "
			'".$modified_field."' => array(
				'type' => 'datetime',
				'default' => '0000-00-00 00:00:00',
			),";
	}

	$db_migration .= '
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key(\''.$primary_key_field.'\', true);
		$this->dbforge->create_table(\''.$table_name.'\');

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table(\''.$table_name.'\');

	}

	//--------------------------------------------------------------------

}';

echo $db_migration;
?>
