<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_initial_tables extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
	
		// Email Queue
		$this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field('`to_email` varchar(128) NOT NULL');
		$this->dbforge->add_field('`subject` varchar(255) NOT NULL');
		$this->dbforge->add_field('`message` text NOT NULL');
		$this->dbforge->add_field('`alt_message` text');
		$this->dbforge->add_field("`max_attempts` int(11) NOT NULL DEFAULT '3'");
		$this->dbforge->add_field("`attempts` int(11) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`success` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`date_published` datetime DEFAULT NULL");
		$this->dbforge->add_field("`last_attempt` datetime DEFAULT NULL");
		$this->dbforge->add_field("`date_sent` datetime DEFAULT NULL");
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('email_queue');
	
		// Login Attempts
		$this->dbforge->add_field("`id` bigint(20) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`ip_address` varchar(40) NOT NULL");
		$this->dbforge->add_field("`login` varchar(50) NOT NULL");
		$this->dbforge->add_field("`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('login_attempts');
		
		// Permissions
		$this->dbforge->add_field("`permission_id` int(11) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`role_id` int(11) NOT NULL");
		$this->dbforge->add_field("`Site.Signin.Allow` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Content.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Statistics.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Appearance.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Settings.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Site.Developer.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Roles.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Users.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Users.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Users.Add` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Database.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Emailer.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Logs.View` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`Bonfire.Logs.Manage` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_key('permission_id', true);
		$this->dbforge->add_key('role_id');
		$this->dbforge->create_table('permissions');
		
		$this->db->query("INSERT INTO {$prefix}permissions VALUES(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)");
		$this->db->query("INSERT INTO {$prefix}permissions VALUES(2, 2, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
		$this->db->query("INSERT INTO {$prefix}permissions VALUES(3, 6, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)");
		$this->db->query("INSERT INTO {$prefix}permissions VALUES(4, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
		$this->db->query("INSERT INTO {$prefix}permissions VALUES(5, 4, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
		
		// Roles
		$this->dbforge->add_field("`role_id` int(11) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`role_name` varchar(60) NOT NULL");
		$this->dbforge->add_field("`description` varchar(255) DEFAULT NULL");
		$this->dbforge->add_field("`default` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`can_delete` tinyint(1) NOT NULL DEFAULT '1'");
		$this->dbforge->add_key('role_id', true);
		$this->dbforge->create_table('roles');
		
		$this->db->query("INSERT INTO {$prefix}roles VALUES(1, 'Administrator', 'Has full control over every aspect of the site.', 0, 0)");
		$this->db->query("INSERT INTO {$prefix}roles VALUES(2, 'Editor', 'Can handle day-to-day management, but does not have full power.', 0, 1)");
		$this->db->query("INSERT INTO {$prefix}roles VALUES(3, 'Banned', 'Banned users are not allowed to sign into your site.', 0, 0)");
		$this->db->query("INSERT INTO {$prefix}roles VALUES(4, 'User', 'This is the default user with access to login.', 1, 0)");
		$this->db->query("INSERT INTO {$prefix}roles VALUES(6, 'Developer', 'Developers typically are the only ones that can access the developer tools. Otherwise identical to Administrators, at least until the site is handed off.', 0, 1)");
		
		// Sessions
		$this->dbforge->add_field("`session_id` varchar(40) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`ip_address` varchar(16) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`user_agent` varchar(50) NOT NULL");
		$this->dbforge->add_field("`last_activity` int(10) unsigned NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`user_data` text");
		$this->dbforge->add_key('session_id', true);
		$this->dbforge->create_table('sessions');
		
		// States
		$this->dbforge->add_field("`id` int(11) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`name` char(40) NOT NULL");
		$this->dbforge->add_field("`abbrev` char(2) NOT NULL");
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('states');
		
		$this->db->query("INSERT INTO {$prefix}states VALUES(1, 'Alaska', 'AK')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(2, 'Alabama', 'AL')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(3, 'American Samoa', 'AS')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(4, 'Arizona', 'AZ')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(5, 'Arkansas', 'AR')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(6, 'California', 'CA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(7, 'Colorado', 'CO')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(8, 'Connecticut', 'CT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(9, 'Delaware', 'DE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(10, 'District of Columbia', 'DC')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(12, 'Florida', 'FL')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(13, 'Georgia', 'GA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(14, 'Guam', 'GU')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(15, 'Hawaii', 'HI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(16, 'Idaho', 'ID')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(17, 'Illinois', 'IL')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(18, 'Indiana', 'IN')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(19, 'Iowa', 'IA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(20, 'Kansas', 'KS')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(21, 'Kentucky', 'KY')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(22, 'Louisiana', 'LA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(23, 'Maine', 'ME')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(24, 'Marshall Islands', 'MH')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(25, 'Maryland', 'MD')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(26, 'Massachusetts', 'MA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(27, 'Michigan', 'MI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(28, 'Minnesota', 'MN')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(29, 'Mississippi', 'MS')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(30, 'Missouri', 'MO')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(31, 'Montana', 'MT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(32, 'Nebraska', 'NE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(33, 'Nevada', 'NV')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(34, 'New Hampshire', 'NH')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(35, 'New Jersey', 'NJ')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(36, 'New Mexico', 'NM')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(37, 'New York', 'NY')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(38, 'North Carolina', 'NC')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(39, 'North Dakota', 'ND')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(40, 'Northern Mariana Islands', 'MP')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(41, 'Ohio', 'OH')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(42, 'Oklahoma', 'OK')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(43, 'Oregon', 'OR')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(44, 'Palau', 'PW')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(45, 'Pennsylvania', 'PA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(46, 'Puerto Rico', 'PR')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(47, 'Rhode Island', 'RI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(48, 'South Carolina', 'SC')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(49, 'South Dakota', 'SD')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(50, 'Tennessee', 'TN')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(51, 'Texas', 'TX')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(52, 'Utah', 'UT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(53, 'Vermont', 'VT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(54, 'Virgin Islands', 'VI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(55, 'Virginia', 'VA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(56, 'Washington', 'WA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(57, 'West Virginia', 'WV')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(58, 'Wisconsin', 'WI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(59, 'Wyoming', 'WY')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(60, 'Armed Forces Africa', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(62, 'Armed Forces Canada', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(63, 'Armed Forces Europe', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(64, 'Armed Forces Middle East', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(65, 'Armed Forces Pacific', 'AP')");
		
		// Users
		$this->dbforge->add_field("`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`role_id` int(11) NOT NULL DEFAULT '4'");
		$this->dbforge->add_field("`first_name` varchar(20) DEFAULT NULL");
		$this->dbforge->add_field("`last_name` varchar(20) DEFAULT NULL");
		$this->dbforge->add_field("`email` varchar(120) NOT NULL");
		$this->dbforge->add_field("`username` varchar(30) NOT NULL DEFAULT ''");
		$this->dbforge->add_field("`password_hash` varchar(40) NOT NULL");
		$this->dbforge->add_field("`temp_password_hash` varchar(40) DEFAULT NULL");
		$this->dbforge->add_field("`salt` varchar(7) NOT NULL");
		$this->dbforge->add_field("`last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->dbforge->add_field("`last_ip` varchar(40) NOT NULL DEFAULT ''");
		$this->dbforge->add_field("`created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->dbforge->add_field("`street_1` varchar(255) DEFAULT NULL");
		$this->dbforge->add_field("`street_2` varchar(255) DEFAULT NULL");
		$this->dbforge->add_field("`city` varchar(40) DEFAULT NULL");
		$this->dbforge->add_field("`state_id` int(11) DEFAULT NULL");
		$this->dbforge->add_field("`zipcode` int(7) DEFAULT NULL");
		$this->dbforge->add_field("`zip_extra` int(5) DEFAULT NULL");
		$this->dbforge->add_field("`country_id` int(11) DEFAULT NULL");
		$this->dbforge->add_field("`deleted` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('email');
		$this->dbforge->create_table('users');
		
		// User Cookies
		$this->dbforge->add_field("`user_id` bigint(20) NOT NULL");
		$this->dbforge->add_field("`token` varchar(128) NOT NULL");
		$this->dbforge->add_field("`created_on` datetime NOT NULL");
		$this->dbforge->add_key('token');
		$this->dbforge->create_table('user_cookies');
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$this->dbforge->drop_table('email_queue');
		$this->dbforge->drop_table('login_attempts');
		$this->dbforge->drop_table('permissions');
		$this->dbforge->drop_table('roles');
		$this->dbforge->drop_table('schema_version');
		$this->dbforge->drop_table('sessions');
		$this->dbforge->drop_table('states');
		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('user_cookies');
	}
	
	//--------------------------------------------------------------------
	
}
