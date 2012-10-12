<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Role_default_context_setting extends Migration
{

	/**
	 * Add a new column to the roles table to create the default context field. Update existing roles with the
     * default value as well.
	 */
	public function up()
	{
		$prefix = $this->db->dbprefix;

        $this->dbforge->add_column('roles', array(
            'default_context'	=> array(
                'type'			=> 'varchar',
                'constraint'	=> 255,
                'default'		=> 'content',
                'after'         => 'login_destination'
            )
        ));
        $update_roles = "
			UPDATE `{$prefix}roles` SET `default_context` = 'content';";

		if ($this->db->query($update_roles))
		{
			return TRUE;
		}

	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		// remove the default_context column
        $this->dbforge->drop_column("roles","default_context");


    }

	//--------------------------------------------------------------------

}