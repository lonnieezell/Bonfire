<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Remove the backup permissions table left behind by 003_Permission_system_upgrade
 */
class Migration_Remove_old_permissions_table extends Migration
{
	/****************************************************************
	 * Table names
	 */
	/**
	 * @var string Table to remove
	 */
	private $table = 'permissions_old';

	/****************************************************************
	 * Field definitions
	 */
	/**
	 * @var array Fields for the table for use by down() method
	 */
	private $fields = array(
		'permission_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
		'role_id' => array(
			'type' => 'INT',
			'constraint' => 11,
		),
		'Site.Signin.Allow' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Site.Signin.Offline' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Site.Content.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Site.Reports.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Site.Settings.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Site.Developer.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Roles.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Users.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Users.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Users.Add' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Database.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Emailer.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Emailer.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Logs.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'Bonfire.Logs.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
	);

	/****************************************************************
	 * Data for Insert
	 */
	/**
	 * @var array Permissions data for restoring the table
	 */
	private $data = array(
		array(
			'role_id' => 1,
			'Site.Signin.Allow' => 1,
			'Site.Signin.Offline' => 1,
			'Site.Content.View' => 1,
			'Site.Reports.View' => 1,
			'Site.Settings.View' => 1,
			'Site.Developer.View' => 1,
			'Bonfire.Roles.Manage' => 1,
			'Bonfire.Users.Manage' => 1,
			'Bonfire.Users.View' => 1,
			'Bonfire.Users.Add' => 1,
			'Bonfire.Database.Manage' => 1,
			'Bonfire.Emailer.Manage' => 1,
			'Bonfire.Emailer.View' => 1,
			'Bonfire.Logs.View' => 1,
			'Bonfire.Logs.Manage' => 1,
		),
		array(
			'role_id' => 2,
			'Site.Signin.Allow' => 1,
			'Site.Signin.Offline' => 1,
			'Site.Content.View' => 1,
			'Site.Reports.View' => 0,
			'Site.Settings.View' => 0,
			'Site.Developer.View' => 0,
			'Bonfire.Roles.Manage' => 0,
			'Bonfire.Users.Manage' => 0,
			'Bonfire.Users.View' => 0,
			'Bonfire.Users.Add' => 0,
			'Bonfire.Database.Manage' => 0,
			'Bonfire.Emailer.Manage' => 0,
			'Bonfire.Emailer.View' => 0,
			'Bonfire.Logs.View' => 0,
			'Bonfire.Logs.Manage' => 0,
		),
		array(
			'role_id' => 6,
			'Site.Signin.Allow' => 1,
			'Site.Signin.Offline' => 1,
			'Site.Content.View' => 1,
			'Site.Reports.View' => 1,
			'Site.Settings.View' => 1,
			'Site.Developer.View' => 1,
			'Bonfire.Roles.Manage' => 1,
			'Bonfire.Users.Manage' => 1,
			'Bonfire.Users.View' => 1,
			'Bonfire.Users.Add' => 1,
			'Bonfire.Database.Manage' => 1,
			'Bonfire.Emailer.Manage' => 1,
			'Bonfire.Emailer.View' => 1,
			'Bonfire.Logs.View' => 0,
			'Bonfire.Logs.Manage' => 0,
		),
		array(
			'role_id' => 3,
			'Site.Signin.Allow' => 0,
			'Site.Signin.Offline' => 0,
			'Site.Content.View' => 0,
			'Site.Reports.View' => 0,
			'Site.Settings.View' => 0,
			'Site.Developer.View' => 0,
			'Bonfire.Roles.Manage' => 0,
			'Bonfire.Users.Manage' => 0,
			'Bonfire.Users.View' => 0,
			'Bonfire.Users.Add' => 0,
			'Bonfire.Database.Manage' => 0,
			'Bonfire.Emailer.Manage' => 0,
			'Bonfire.Emailer.View' => 0,
			'Bonfire.Logs.View' => 0,
			'Bonfire.Logs.Manage' => 0,
		),
		array(
			'role_id' => 4,
			'Site.Signin.Allow' => 1,
			'Site.Signin.Offline' => 0,
			'Site.Content.View' => 0,
			'Site.Reports.View' => 0,
			'Site.Settings.View' => 0,
			'Site.Developer.View' => 0,
			'Bonfire.Roles.Manage' => 0,
			'Bonfire.Users.Manage' => 0,
			'Bonfire.Users.View' => 0,
			'Bonfire.Users.Add' => 0,
			'Bonfire.Database.Manage' => 0,
			'Bonfire.Emailer.Manage' => 0,
			'Bonfire.Emailer.View' => 0,
			'Bonfire.Logs.View' => 0,
			'Bonfire.Logs.Manage' => 0,
		),
	);

	/****************************************************************
	 * Migration methods
	 */
	/**
	 * Install this migration
	 */
	public function up()
	{
		$this->dbforge->drop_table($this->table);
	}

	/**
	 * Uninstall this migration
	 */
	public function down()
	{
		// Permissions
		$this->dbforge->add_field($this->fields);
		$this->dbforge->add_key('permission_id', true);
		$this->dbforge->add_key('role_id');
		$this->dbforge->create_table($this->table);

		$this->db->insert_batch($this->table, $this->data);
	}
}