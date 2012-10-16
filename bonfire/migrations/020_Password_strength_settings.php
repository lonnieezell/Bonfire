<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Password_strength_settings extends Migration
{

	/**
	 * Removing the '/' from the Role login_destination field in the DB so that
	 * the user will be brought to the last requested url when they login
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('auth.password_min_length', 'core', '8'),
			 ('auth.password_force_numbers','core',0),
			 ('auth.password_force_symbols','core',0),
			 ('auth.password_force_mixed_case','core',0);
		";

		if ($this->db->query($default_settings))
		{
			return TRUE;
		}

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		// remove the keys
		$this->db->query("DELETE FROM {$prefix}settings WHERE (name = 'auth.password_min_length'
			OR name ='auth.password_force_numbers'
			OR name ='auth.password_force_symbols'
			OR name ='auth.password_force_mixed_case'
		)");

	}

	//--------------------------------------------------------------------

}