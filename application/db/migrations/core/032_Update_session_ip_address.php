<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Update_session_ip_address extends Migration
{
	//--------------------------------------------------------------------

	/**
	 * Updates Database Session table for CodeIgniter 2.1.1
	 * see {@link http://codeigniter.com/user_guide/libraries/sessions.html}
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 45,
				'null' => FALSE,
				'default' => 0
			),
		);

		$this->dbforge->modify_column('sessions', $fields);

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		$fields = array(
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 16,
				'null' => FALSE,
				'default' => 0,
			),
		);

		$this->dbforge->modify_column('sessions', $fields);
	}

	//--------------------------------------------------------------------

}