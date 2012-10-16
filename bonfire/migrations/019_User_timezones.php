<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_User_timezones extends Migration {
	
	public function up() 
	{
		$this->load->dbforge();
		
		$field = array(
			'timezone' => array(
				'type'			=> 'char',
				'constraint'	=> 4,
				'default'		=> 'UM6'
			)
		);
		
		$this->dbforge->add_column('users', $field);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{ 
		$this->load->dbforge();
		
		$this->dbforge->drop_column('users', 'timezone');
	}
	
	//--------------------------------------------------------------------
	
}