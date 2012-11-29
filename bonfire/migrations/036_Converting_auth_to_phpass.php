<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	In 0.7, we are moving from the salted password generation that we had
	to use the phpass 0.3 Password Hasing algorithm.
	
	This does make it impossible to convert the passwords but we will 
	make it so that the users must change their password on next login.
*/
class Migration_Converting_auth_to_phpass extends Migration
{

	//--------------------------------------------------------------------

	public function up()
	{
		$this->load->dbforge();
		
		// We no longer need the 'salt' field
		$this->dbforge->drop_column('users', 'salt');
		
		// We do need to store the number of iterations used, though.
		$fields = array(
			'password_iterations' => array(
				'type'			=> 'int',
				'constraint'	=> 4,
				'null'			=> false
			)
		);
		$this->dbforge->add_column('users', $fields);
		
		// And we need to change the size of the password hash and reset hash columns
		$fields = array(
			'password_hash'	=> array(
				'type'			=> 'char',
				'constraint'	=> 60
			),
			'reset_hash'	=> array(
				'type'			=> 'char',
				'constraint'	=> 60
			),
		);
		$this->dbforge->modify_column('users', $fields);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		
	}

	//--------------------------------------------------------------------

}
