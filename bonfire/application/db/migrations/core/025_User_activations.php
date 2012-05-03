<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_User_activations extends Migration {

	/**
	 * Removing the '/' from the Role login_destination field in the DB so that
	 * the user will be brought to the last requested url when they login
	 */
	public function up()
	{
        $prefix = $this->db->dbprefix;

        $this->dbforge->add_column('users', array(
			'active'	=> array(
				'type'			=> 'tinyint',
				'constraint'	=> 1,
				'default'		=> '0'
			)
		));
		$this->dbforge->add_column('users', array(
			'activate_hash' => array(
                'type'	=> 'TEXT',
				'type'	=> 'VARCHAR',
				'constraint'	=> 40,
                'default' => ''
			)
		));

        $this->db->query("UPDATE `{$prefix}users` set `active` = '1'");

        if ($this->db->query("INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
    ('auth.user_activation_method', 'core', '0')"))
        {
            return TRUE;
        }
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $prefix = $this->db->dbprefix;

		$this->dbforge->drop_column("users","active");
		$this->dbforge->drop_column("users","activate_hash");
		$this->db->query("DELETE FROM `{$prefix}settings` WHERE name = 'auth.user_activation_method'");
	}

	//--------------------------------------------------------------------

}