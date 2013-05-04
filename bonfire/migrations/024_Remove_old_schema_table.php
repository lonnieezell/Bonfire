<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Remove_old_schema_table extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table($prefix.'schema_version_old');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'version' => array(
				'type' => 'int',
				'constraint' => 4,
				'null' => FALSE,
				'default' => 0,
			),
			'app_version' => array(
				'type' => 'int',
				'constraint' => 4,
				'null' => FALSE,
				'default' => 0,
			),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table($prefix.'schema_version_old');

		$this->db->query("INSERT INTO {$prefix}bf_schema_version_old VALUES(11, 0)");
	}

	//--------------------------------------------------------------------

}