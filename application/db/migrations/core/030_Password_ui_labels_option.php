<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Password_ui_labels_option extends Migration
{

	/**
	 * Removing the '/' from the Role login_destination field in the DB so that
	 * the user will be brought to the last requested url when they login
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES('auth.password_show_labels','core',0);";

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
		$this->db->query("DELETE FROM {$prefix}settings WHERE (name = name ='auth.password_show_labels')");

	}

	//--------------------------------------------------------------------

}