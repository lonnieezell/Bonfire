<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Move_settings_into_db extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;

		$this->dbforge->drop_table('settings');

		$settings = "
			CREATE TABLE `{$prefix}settings` (
			  `name` varchar(30) collate utf8_unicode_ci NOT NULL,
			  `module` varchar(50) collate utf8_unicode_ci NOT NULL,
			  `value` varchar(255) collate utf8_unicode_ci NOT NULL,
			PRIMARY KEY  (`name`),
			UNIQUE KEY `unique - name` (`name`),
			KEY `index - name` (`name`)
			) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";

		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('site.title', 'core', ''),
			 ('site.system_email', 'core', ''),
			 ('site.status', 'core', '1'),
			 ('site.list_limit', 'core', '25'),
			 ('site.show_profiler', 'core', '1'),
			 ('site.show_front_profiler', 'core', '1'),
			 ('updates.do_check', 'core', '1'),
			 ('updates.bleeding_edge', 'core', '1'),
			 ('auth.allow_register', 'core', '1'),
			 ('auth.login_type', 'core', 'email'),
			 ('auth.use_usernames', 'core', '1'),
			 ('auth.allow_remember', 'core', '1'),
			 ('auth.remember_length', 'core', '1209600'),
			 ('auth.do_login_redirect', 'core', '1'),
			 ('auth.use_extended_profile', 'core', '0'),
			 ('sender_email', 'email', ''),
			 ('protocol', 'email', 'mail'),
			 ('mailpath', 'email', '/usr/sbin/sendmail'),
			 ('smtp_host', 'email', ''),
			 ('smtp_user', 'email', ''),
			 ('smtp_pass', 'email', ''),
			 ('smtp_port', 'email', ''),
			 ('smtp_timeout', 'email', '');
		";

		if ($this->db->query($settings) && $this->db->query($default_settings))
		{
			return TRUE;
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$this->dbforge->drop_table('settings');
	}
	
	//--------------------------------------------------------------------
	
}