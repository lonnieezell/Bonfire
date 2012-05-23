<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Default_context_setting extends Migration
{

	/**
	 * Removing the '/' from the Role login_destination field in the DB so that
	 * the user will be brought to the last requested url when they login
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES('site.default_context','core','content');";

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
		$this->db->query("DELETE FROM {$prefix}settings WHERE (name = name ='site.default_context')");

	}

	//--------------------------------------------------------------------

}