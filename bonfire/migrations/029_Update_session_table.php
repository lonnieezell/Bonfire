<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Update_session_table extends Migration
{
	//--------------------------------------------------------------------

	/**
	 * Updates Database Session table for CodeIgniter 2.1
	 * see {@link http://codeigniter.com/user_guide/libraries/sessions.html}
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'user_agent' => array(
				'type' => 'VARCHAR',
				'constraint' => 120,
				'null' => FALSE,
			),
		);

		$this->dbforge->modify_column('sessions', $fields);

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'user_agent' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'null' => FALSE,
			),
		);

		$this->dbforge->modify_column('sessions', $fields);
	}

	//--------------------------------------------------------------------

}