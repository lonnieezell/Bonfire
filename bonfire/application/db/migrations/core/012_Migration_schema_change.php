<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Migration_schema_change extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		// get the current schema versions
		$sql = "SELECT * FROM {$prefix}schema_version";
		$schema_version_query = $this->db->query($sql);
		$version_array = $schema_version_query->row_array();

		// backup the schema_version table
		$this->dbforge->rename_table($prefix.'schema_version', $prefix.'schema_version_old');

		// modify the schema_version table
		$fields = array(
						'type' => array(
							'type' => 'VARCHAR',
							'constraint' => 20, 
							'null' => FALSE,
						),
						'version_num' => array(
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
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		// Reverse the schema_version table changes
		$this->dbforge->drop_table('schema_version');
		
		$this->dbforge->rename_table($prefix.'schema_version_old', $prefix.'schema_version');

	}
	
	//--------------------------------------------------------------------
	
}