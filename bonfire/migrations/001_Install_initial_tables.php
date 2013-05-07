<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install the initial tables:
 *	Email Queue
 *	Login Attempts
 *	Permissions
 *	Roles
 *	Sessions
 *	States
 *	Users
 *	User Cookies
 */
class Migration_Install_initial_tables extends Migration
{
	/****************************************************************
	 * Table Names
	 */
	/**
	 * @var string The name of the Email Queue table
	 */
	private $email_table = 'email_queue';

	/**
	 * @var string The name of the Login Attempts table
	 */
	private $login_table = 'login_attempts';

	/**
	 * @var string The name of the Permissions table
	 */
	private $permissions_table = 'permissions';

	/**
	 * @var string The name of the Roles table
	 */
	private $roles_table = 'roles';

	/**
	 * @var string The name of the Sessions table
	 */
	private $sessions_table = 'sessions';

	/**
	 * @var string The name of the States table
	 */
	private $states_table = 'states';

	/**
	 * @var string The name of the Users table
	 */
	private $users_table = 'users';

	/**
	 * @var string The name of the User Cookies table
	 */
	private $cookies_table = 'user_cookies';

	/****************************************************************
	 * Field Definitions
	 */
	/**
	 * @var array Fields for the Email table
	 */
	private $email_fields = array(
		'id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'auto_increment' => TRUE,
		),
		'to_email' => array(
			'type' => 'VARCHAR',
			'constraint' => 128,
		),
		'subject' => array(
			'type' => 'VARCHAR',
			'constraint' => 255,
		),
		'message' => array(
			'type' => 'TEXT',
		),
		'alt_message' => array(
			'type' => 'TEXT',
			'null' => true,
		),
		'max_attempts' => array(
			'type' => 'INT',
			'constraint' => 11,
			'default' => 3,
		),
		'attempts' => array(
			'type' => 'INT',
			'constraint' => 11,
			'default' => 0,
		),
		'success' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'date_published' => array(
			'type' => 'DATETIME',
			'null' => true,
		),
		'last_attempt' => array(
			'type' => 'DATETIME',
			'null' => true,
		),
		'date_sent' => array(
			'type' => 'DATETIME',
			'null' => true,
		),
	);

	/**
	 * @var array Fields for the Login table
	 */
	private $login_fields = array(
		'id' => array(
			'type' => 'BIGINT',
			'constraint' => 20,
			'auto_increment' => TRUE,
		),
		'ip_address' => array(
			'type' => 'VARCHAR',
			'constraint' => 40,
		),
		'login' => array(
			'type' => 'VARCHAR',
			'constraint' => 50,
		),
        /* This will probably cause an error outside MySQL and may not
         * be cross-database compatible for reasons other than
         * CURRENT_TIMESTAMP
         */
		'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
	);

	/**
	 * @var array Fields for the Permissions table
	 */
	private $permission_fields = array(
		'permission_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'auto_increment' => TRUE,
		),
		'role_id' => array(
			'type' => 'INT',
			'constraint' => 11,
		),
		"`Site.Signin.Allow` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Site.Signin.Allow' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Site.Content.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Site.Content.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Site.Statistics.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Site.Statistics.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Site.Appearance.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Site.Appearance.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Site.Settings.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Site.Settings.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Site.Developer.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Site.Developer.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Roles.Manage` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Roles.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Users.Manage` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Users.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Users.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Users.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Users.Add` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Users.Add' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Database.Manage` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Database.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Emailer.Manage` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Emailer.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Logs.View` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Logs.View' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
		"`Bonfire.Logs.Manage` tinyint(1) NOT NULL DEFAULT '0'",
/*		'Bonfire.Logs.Manage' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
 */
	);

	/**
	 * @var array Fields for the roles table
	 */
	private $roles_fields = array(
		'role_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'auto_increment' => TRUE,
		),
		'role_name' => array(
			'type' => 'VARCHAR',
			'constraint' => 60,
		),
		'description' => array(
			'type' => 'VARCHAR',
			'constraint' => 255,
			'null' => true,
		),
		'default' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
		'can_delete' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 1,
		),
	);

	/**
	 * @var array Fields for the Sessions table
	 */
	private $sessions_fields = array(
		'session_id' => array(
			'type' => 'VARCHAR',
			'constraint' => 40,
			'default' => '0',
		),
		'ip_address' => array(
			'type' => 'VARCHAR',
			'constraint' => 16,
			'default' => '0',
		),
		'user_agent' => array(
			'type' => 'VARCHAR',
			'constraint' => 50,
		),
		'last_activity' => array(
			'type' => 'INT',
			'constraint' => 10,
			'unsigned' => true,
			'default' => 0,
		),
		'user_data' => array(
			'type' => 'TEXT',
		),
	);

	/**
	 * @var array Fields for the States table
	 */
	private $states_fields = array(
		'id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'auto_increment' => TRUE,
		),
		'name' => array(
			'type' => 'CHAR',
			'constraint' => 40,
		),
		'abbrev' => array(
			'type' => 'CHAR',
			'constraint' => 2,
		),
	);

	/**
	 * @var array Fields for the users table
	 */
	private $users_fields = array(
		'id' => array(
			'type' => 'BIGINT',
			'constraint' => 20,
			'unsigned' => true,
			'auto_increment' => true,
		),
		'role_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'default' => 4,
		),
		'first_name' => array(
			'type' => 'VARCHAR',
			'constraint' => 20,
			'null' => true,
		),
		'last_name' => array(
			'type' => 'VARCHAR',
			'constraint' => 20,
			'null' => true,
		),
		'email' => array(
			'type' => 'VARCHAR',
			'constraint' => 120,
		),
		'username' => array(
			'type' => 'VARCHAR',
			'constraint' => 30,
			'default' => '',
		),
		'password_hash' => array(
			'type' => 'VARCHAR',
			'constraint' => 40,
		),
		'temp_password_hash' => array(
			'type' => 'VARCHAR',
			'constraint' => 40,
			'null' => true,
		),
		'salt' => array(
			'type' => 'VARCHAR',
			'constraint' => 7,
		),
		'last_login' => array(
			'type' => 'DATETIME',
			'default' => '0000-00-00 00:00:00',
		),
		'last_ip' => array(
			'type' => 'VARCHAR',
			'constraint' => 40,
			'default' => '',
		),
		'created_on' => array(
			'type' => 'DATETIME',
			'default' => '0000-00-00 00:00:00',
		),
		'street_1' => array(
			'type' => 'VARCHAR',
			'constraint' => 255,
			'null' => true,
		),
		'street_2' => array(
			'type' => 'VARCHAR',
			'constraint' => 255,
			'null' => true,
		),
		'city' => array(
			'type' => 'VARCHAR',
			'constraint' => 40,
			'null' => true,
		),
		'state_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'null' => true,
		),
		'zipcode' => array(
			'type' => 'INT',
			'constraint' => 7,
			'null' => true,
		),
		'zip_extra' => array(
			'type' => 'INT',
			'constraint' => 5,
			'null' => true,
		),
		'country_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'null' => true,
		),
		'deleted' => array(
			'type' => 'TINYINT',
			'constraint' => 1,
			'default' => 0,
		),
	);

	/**
	 * @var array Fields for the Cookies table
	 */
	private $cookies_fields = array(
		'user_id' => array(
			'type' => 'BIGINT',
			'constraint' => 20,
		),
		'token' => array(
			'type' => 'VARCHAR',
			'constraint' => 128,
		),
		'created_on' => array(
			'type' => 'DATETIME',
		),
	);

	/****************************************************************
	 * Data to Insert
	 */
	/**
	 * @var array Default Permissions
	 */
	private $permissions_data = array(
		array(
			'role_id' => 1,
			'`Site.Signin.Allow`' => 1,
			'`Site.Content.View`' => 1,
			'`Site.Statistics.View`' => 1,
			'`Site.Appearance.View`' => 1,
			'`Site.Settings.View`' => 1,
			'`Site.Developer.View`' => 1,
			'`Bonfire.Roles.Manage`' => 1,
			'`Bonfire.Users.Manage`' => 1,
			'`Bonfire.Users.View`' => 1,
			'`Bonfire.Users.Add`' => 1,
			'`Bonfire.Database.Manage`' => 1,
			'`Bonfire.Emailer.Manage`' => 1,
			'`Bonfire.Logs.View`' => 1,
			'`Bonfire.Logs.Manage`' => 1,
		),
		array(
			'role_id' => 2,
			'`Site.Signin.Allow`' => 1,
			'`Site.Content.View`' => 1,
			'`Site.Statistics.View`' => 1,
			'`Site.Appearance.View`' => 1,
			'`Site.Settings.View`' => 0,
			'`Site.Developer.View`' => 0,
			'`Bonfire.Roles.Manage`' => 0,
			'`Bonfire.Users.Manage`' => 0,
			'`Bonfire.Users.View`' => 0,
			'`Bonfire.Users.Add`' => 0,
			'`Bonfire.Database.Manage`' => 0,
			'`Bonfire.Emailer.Manage`' => 0,
			'`Bonfire.Logs.View`' => 0,
			'`Bonfire.Logs.Manage`' => 0,
		),
		array(
			'role_id' => 6,
			'`Site.Signin.Allow`' => 1,
			'`Site.Content.View`' => 1,
			'`Site.Statistics.View`' => 1,
			'`Site.Appearance.View`' => 1,
			'`Site.Settings.View`' => 1,
			'`Site.Developer.View`' => 1,
			'`Bonfire.Roles.Manage`' => 1,
			'`Bonfire.Users.Manage`' => 1,
			'`Bonfire.Users.View`' => 1,
			'`Bonfire.Users.Add`' => 1,
			'`Bonfire.Database.Manage`' => 1,
			'`Bonfire.Emailer.Manage`' => 1,
			'`Bonfire.Logs.View`' => 1,
			'`Bonfire.Logs.Manage`' => 1,
		),
		array(
			'role_id' => 3,
			'`Site.Signin.Allow`' => 0,
			'`Site.Content.View`' => 0,
			'`Site.Statistics.View`' => 0,
			'`Site.Appearance.View`' => 0,
			'`Site.Settings.View`' => 0,
			'`Site.Developer.View`' => 0,
			'`Bonfire.Roles.Manage`' => 0,
			'`Bonfire.Users.Manage`' => 0,
			'`Bonfire.Users.View`' => 0,
			'`Bonfire.Users.Add`' => 0,
			'`Bonfire.Database.Manage`' => 0,
			'`Bonfire.Emailer.Manage`' => 0,
			'`Bonfire.Logs.View`' => 0,
			'`Bonfire.Logs.Manage`' => 0,
		),
		array(
			'role_id' => 4,
			'`Site.Signin.Allow`' => 1,
			'`Site.Content.View`' => 0,
			'`Site.Statistics.View`' => 0,
			'`Site.Appearance.View`' => 0,
			'`Site.Settings.View`' => 0,
			'`Site.Developer.View`' => 0,
			'`Bonfire.Roles.Manage`' => 0,
			'`Bonfire.Users.Manage`' => 0,
			'`Bonfire.Users.View`' => 0,
			'`Bonfire.Users.Add`' => 0,
			'`Bonfire.Database.Manage`' => 0,
			'`Bonfire.Emailer.Manage`' => 0,
			'`Bonfire.Logs.View`' => 0,
			'`Bonfire.Logs.Manage`' => 0,
		),
	);

	/**
	 * @var array Default Roles
	 */
	private $roles_data = array(
		array(
			'role_name' => 'Administrator',
			'description' => 'Has full control over every aspect of the site.',
			'default' => 0,
			'can_delete' => 0,
		),
		array(
			'role_name' => 'Editor',
			'description' => 'Can handle day-to-day management, but does not have full power.',
			'default' => 0,
			'can_delete' => 1,
		),
		array(
			'role_name' => 'Banned',
			'description' => 'Banned users are not allowed to sign into your site.',
			'default' => 0,
			'can_delete' => 0,
		),
		array(
			'role_name' => 'User',
			'description' => 'This is the default user with access to login.',
			'default' => 1,
			'can_delete' => 0,
		),
		array(
			'role_name' => 'To Delete', /* because role_id is an auto-increment field */
			'description' => 'N/A',
			'default' => 0,
			'can_delete' => 1,
		),
		array(
			'role_name' => 'Developer',
			'description' => 'Developers typically are the only ones that can access the developer tools. Otherwise identical to Administrators, at least until the site is handed off.',
			'default' => 0,
			'can_delete' => 1,
		),
	);

	/**
	 * @var array States name/abbreviation pairs
	 */
	private $states_data = array(
		array(
			'name' => 'Alaska',
			'abbrev' => 'AK',
		),
		array(
			'name' => 'Alabama',
			'abbrev' => 'AL',
		),
		array(
			'name' => 'American Samoa',
			'abbrev' => 'AS',
		),
		array(
			'name' => 'Arizona',
			'abbrev' => 'AZ',
		),
		array(
			'name' => 'Arkansas',
			'abbrev' => 'AR',
		),
		array(
			'name' => 'California',
			'abbrev' => 'CA',
		),
		array(
			'name' => 'Colorado',
			'abbrev' => 'CO',
		),
		array(
			'name' => 'Connecticut',
			'abbrev' => 'CT',
		),
		array(
			'name' => 'Delaware',
			'abbrev' => 'DE',
		),
		array(
			'name' => 'District of Columbia',
			'abbrev' => 'DC',
		),
		array(
			'name' => 'Florida',
			'abbrev' => 'FL',
		),
		array(
			'name' => 'Georgia',
			'abbrev' => 'GA',
		),
		array(
			'name' => 'Guam',
			'abbrev' => 'GU',
		),
		array(
			'name' => 'Hawaii',
			'abbrev' => 'HI',
		),
		array(
			'name' => 'Idaho',
			'abbrev' => 'ID',
		),
		array(
			'name' => 'Illinois',
			'abbrev' => 'IL',
		),
		array(
			'name' => 'Indiana',
			'abbrev' => 'IN',
		),
		array(
			'name' => 'Iowa',
			'abbrev' => 'IA',
		),
		array(
			'name' => 'Kansas',
			'abbrev' => 'KS',
		),
		array(
			'name' => 'Kentucky',
			'abbrev' => 'KY',
		),
		array(
			'name' => 'Louisiana',
			'abbrev' => 'LA',
		),
		array(
			'name' => 'Maine',
			'abbrev' => 'ME',
		),
		array(
			'name' => 'Marshall Islands',
			'abbrev' => 'MH',
		),
		array(
			'name' => 'Maryland',
			'abbrev' => 'MD',
		),
		array(
			'name' => 'Massachusetts',
			'abbrev' => 'MA',
		),
		array(
			'name' => 'Michigan',
			'abbrev' => 'MI',
		),
		array(
			'name' => 'Minnesota',
			'abbrev' => 'MN',
		),
		array(
			'name' => 'Mississippi',
			'abbrev' => 'MS',
		),
		array(
			'name' => 'Missouri',
			'abbrev' => 'MO',
		),
		array(
			'name' => 'Montana',
			'abbrev' => 'MT',
		),
		array(
			'name' => 'Nebraska',
			'abbrev' => 'NE',
		),
		array(
			'name' => 'Nevada',
			'abbrev' => 'NV',
		),
		array(
			'name' => 'New Hampshire',
			'abbrev' => 'NH',
		),
		array(
			'name' => 'New Jersey',
			'abbrev' => 'NJ',
		),
		array(
			'name' => 'New Mexico',
			'abbrev' => 'NM',
		),
		array(
			'name' => 'New York',
			'abbrev' => 'NY',
		),
		array(
			'name' => 'North Carolina',
			'abbrev' => 'NC',
		),
		array(
			'name' => 'North Dakota',
			'abbrev' => 'ND',
		),
		array(
			'name' => 'Northern Mariana Islands',
			'abbrev' => 'MP',
		),
		array(
			'name' => 'Ohio',
			'abbrev' => 'OH',
		),
		array(
			'name' => 'Oklahoma',
			'abbrev' => 'OK',
		),
		array(
			'name' => 'Oregon',
			'abbrev' => 'OR',
		),
		array(
			'name' => 'Palau',
			'abbrev' => 'PW',
		),
		array(
			'name' => 'Pennsylvania',
			'abbrev' => 'PA',
		),
		array(
			'name' => 'Puerto Rico',
			'abbrev' => 'PR',
		),
		array(
			'name' => 'Rhode Island',
			'abbrev' => 'RI',
		),
		array(
			'name' => 'South Carolina',
			'abbrev' => 'SC',
		),
		array(
			'name' => 'South Dakota',
			'abbrev' => 'SD',
		),
		array(
			'name' => 'Tennessee',
			'abbrev' => 'TN',
		),
		array(
			'name' => 'Texas',
			'abbrev' => 'TX',
		),
		array(
			'name' => 'Utah',
			'abbrev' => 'UT',
		),
		array(
			'name' => 'Vermont',
			'abbrev' => 'VT',
		),
		array(
			'name' => 'Virgin Islands',
			'abbrev' => 'VI',
		),
		array(
			'name' => 'Virginia',
			'abbrev' => 'VA',
		),
		array(
			'name' => 'Washington',
			'abbrev' => 'WA',
		),
		array(
			'name' => 'West Virginia',
			'abbrev' => 'WV',
		),
		array(
			'name' => 'Wisconsin',
			'abbrev' => 'WI',
		),
		array(
			'name' => 'Wyoming',
			'abbrev' => 'WY',
		),
		array(
			'name' => 'Armed Forces Africa',
			'abbrev' => 'AE',
		),
		array(
			'name' => 'Armed Forces Canada',
			'abbrev' => 'AE',
		),
		array(
			'name' => 'Armed Forces Europe',
			'abbrev' => 'AE',
		),
		array(
			'name' => 'Armed Forces Middle East',
			'abbrev' => 'AE',
		),
		array(
			'name' => 'Armed Forces Pacific',
			'abbrev' => 'AP',
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
		// Email Queue
        if ( ! $this->db->table_exists($this->email_table))
        {
            $this->dbforge->add_field($this->email_fields);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($this->email_table);
        }

		// Login Attempts
        if ( ! $this->db->table_exists($this->login_table))
        {
            $this->dbforge->add_field($this->login_fields);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($this->login_table);
        }

		// Permissions
        if ( ! $this->db->table_exists($this->permissions_table))
        {
            $this->dbforge->add_field($this->permission_fields);
            $this->dbforge->add_key('permission_id', true);
            $this->dbforge->add_key('role_id');
            $this->dbforge->create_table($this->permissions_table);

//          $this->db->insert_batch($this->permissions_table, $this->permissions_data);
            $prefix = $this->db->dbprefix;
            $this->db->query("INSERT INTO {$prefix}permissions VALUES(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)");
            $this->db->query("INSERT INTO {$prefix}permissions VALUES(2, 2, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
            $this->db->query("INSERT INTO {$prefix}permissions VALUES(3, 6, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)");
            $this->db->query("INSERT INTO {$prefix}permissions VALUES(4, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
            $this->db->query("INSERT INTO {$prefix}permissions VALUES(5, 4, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
        }

		// Roles
        if ( ! $this->db->table_exists($this->roles_table))
        {
            $this->dbforge->add_field($this->roles_fields);
            $this->dbforge->add_key('role_id', true);
            $this->dbforge->create_table($this->roles_table);

            $this->db->insert_batch($this->roles_table, $this->roles_data);
            $this->db->where('role_id', 5)->delete($this->roles_table);
        }

		// Sessions
        if ( ! $this->db->table_exists($this->sessions_table))
        {
            $this->dbforge->add_field($this->sessions_fields);
            $this->dbforge->add_key('session_id', true);
            $this->dbforge->create_table($this->sessions_table);
        }

		// States
        if ( ! $this->db->table_exists($this->states_table))
        {
            $this->dbforge->add_field($this->states_fields);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($this->states_table);

            $this->db->insert_batch($this->states_table, $this->states_data);
        }

		// Users
        if ( ! $this->db->table_exists($this->users_table))
        {
            $this->dbforge->add_field($this->users_fields);
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('email');
            $this->dbforge->create_table($this->users_table);
        }

		// User Cookies
        if ( ! $this->db->table_exists($this->cookies_table))
        {
            $this->dbforge->add_field($this->cookies_fields);
            $this->dbforge->add_key('token');
            $this->dbforge->create_table($this->cookies_table);
        }
	}

	/**
	 * Uninstall this migration
	 */
	public function down()
	{
		$this->dbforge->drop_table($this->email_table);
		$this->dbforge->drop_table($this->login_table);
		$this->dbforge->drop_table($this->permissions_table);
		$this->dbforge->drop_table($this->roles_table);

		// Since we didn't add this table in this migration,
		// check to see if it exists before removing it
		if ($this->db->table_exists('schema_version'))
		{
			$this->dbforge->drop_table('schema_version');
		}

		$this->dbforge->drop_table($this->sessions_table);
		$this->dbforge->drop_table($this->states_table);
		$this->dbforge->drop_table($this->users_table);
		$this->dbforge->drop_table($this->cookies_table);
	}
}