<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adding_mailtype_setting extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('mailtype', 'email', 'text');
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

		$default_settings = "
			DELETE FROM `{$prefix}settings` WHERE `name` = 'mailtype';
		";

		$this->db->query($default_settings);
	}

	//--------------------------------------------------------------------

}