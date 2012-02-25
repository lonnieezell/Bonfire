<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Migration_schema_change extends Migration {

	/**
	 * This Migration file should not be used in an install situation
	 * because the migration table schema will already be setup correctly
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

		// get the current schema versions
		$sql = "SELECT * FROM {$prefix}schema_version";
		$schema_version_query = $this->db->query($sql);
		$version_array = $schema_version_query->row_array();

		// check if the table is in the old format
		if (!isset($version_array['type']))
		{
			// the table is in the old format

			// backup the schema_version table
			$this->dbforge->rename_table('schema_version', $prefix.'schema_version_old');

			// modify the schema_version table
			$fields = array(
							'type' => array(
								'type' => 'VARCHAR',
								'constraint' => 20,
								'null' => FALSE,
							),
							'version' => array(
								'type' => 'INT',
								'constraint' => '4',
								'default'    => 0,
							),
					);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('type', TRUE);
			$this->dbforge->create_table('schema_version');

			// add records for each of the old permissions
			foreach ($version_array as $type => $version_num)
			{
				$type_field = $type == 'version' ? 'core' : str_replace('version', '', $type);

				if ($type_field == 'core')
				{
					$version_num++;
				}

				$this->db->query("INSERT INTO {$prefix}schema_version VALUES ('{$type_field}', ".$version_num.");");
			}
		}
	}

	//--------------------------------------------------------------------

	/**
	 * This Migration file should not be used in an install situation
	 * because the migration table schema will already be setup correctly
	 * and the old table won't exist
	 */
	public function down()
	{
		$prefix = $this->db->dbprefix;

		// check if the old schema exists
		if ($this->db->table_exists('schema_version_old')) {
			// Reverse the schema_version table changes
			$this->dbforge->drop_table('schema_version');

			$this->dbforge->rename_table('schema_version_old', $prefix.'schema_version');
		}

	}

	//--------------------------------------------------------------------

}