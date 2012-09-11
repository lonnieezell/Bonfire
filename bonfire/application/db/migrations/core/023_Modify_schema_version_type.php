<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_schema_version_type extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'type' => array(
				'name' => 'type',
				'type' => 'VARCHAR',
				'constraint' => 40,
				'null' => FALSE,
			),
		);
		$this->dbforge->modify_column('schema_version', $fields);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'type' => array(
				'name' => 'type',
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => FALSE,
			),
		);
		$this->dbforge->modify_column('schema_version', $fields);
	}
	
	//--------------------------------------------------------------------
	
}