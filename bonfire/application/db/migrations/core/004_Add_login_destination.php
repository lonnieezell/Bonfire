<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_login_destination extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		$this->dbforge->add_column('roles', array(
				'login_destination'	=> array(
					'type'			=> 'VARCHAR',
					'constraint'	=> 255,
					'null'			=> false,
					'default'		=> '/'
				)
			)
		);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_column('roles', 'login_destination');
	}
	
	//--------------------------------------------------------------------
	
}