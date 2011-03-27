<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_initial_tables extends Migration {

	var $migration_type = 'sql';
	
	public function up() 
	{
		$sql =<<<SQL
-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net

--
-- Table structure for table `bf_email_queue`
--

CREATE TABLE `bf_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_email` varchar(128) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `alt_message` text,
  `max_attempts` int(11) NOT NULL DEFAULT '3',
  `attempts` int(11) NOT NULL DEFAULT '0',
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `date_published` datetime DEFAULT NULL,
  `last_attempt` datetime DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bf_email_queue`
--


-- --------------------------------------------------------

--
-- Table structure for table `bf_login_attempts`
--

CREATE TABLE `bf_login_attempts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `bf_permissions`
--

CREATE TABLE `bf_permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `Site.Signin.Allow` tinyint(1) NOT NULL DEFAULT '0',
  `Site.Content.View` tinyint(1) NOT NULL DEFAULT '0',
  `Site.Statistics.View` tinyint(1) NOT NULL DEFAULT '0',
  `Site.Appearance.View` tinyint(1) NOT NULL DEFAULT '0',
  `Site.Settings.View` tinyint(1) NOT NULL DEFAULT '0',
  `Site.Developer.View` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Roles.Manage` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Users.Manage` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Users.View` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Users.Add` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Database.Manage` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Emailer.Manage` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Logs.View` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Logs.Manage` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Articles.View` tinyint(1) NOT NULL DEFAULT '0',
  `Bonfire.Articles.Manage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`permission_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bf_permissions`
--

INSERT INTO `bf_permissions` VALUES(1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1);
INSERT INTO `bf_permissions` VALUES(2, 2, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `bf_permissions` VALUES(3, 6, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO `bf_permissions` VALUES(4, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bf_roles`
--

CREATE TABLE `bf_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `bf_roles`
--

INSERT INTO `bf_roles` VALUES(1, 'Administrator', 'Has full control over every aspect of the site.', 0, 0);
INSERT INTO `bf_roles` VALUES(2, 'Editor', 'Can handle day-to-day management, but does not have full power.', 0, 1);
INSERT INTO `bf_roles` VALUES(3, 'Banned', 'Banned users are not allowed to sign into your site.', 0, 0);
INSERT INTO `bf_roles` VALUES(6, 'Developer', 'Developers typically are the only ones that can access the developer tools. Otherwise identical to Administrators, at least until the site is handed off.', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bf_sessions`
--

CREATE TABLE `bf_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bf_states`
--

CREATE TABLE `bf_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL,
  `abbrev` char(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `bf_states`
--

INSERT INTO `bf_states` VALUES(1, 'Alaska', 'AK');
INSERT INTO `bf_states` VALUES(2, 'Alabama', 'AL');
INSERT INTO `bf_states` VALUES(3, 'American Samoa', 'AS');
INSERT INTO `bf_states` VALUES(4, 'Arizona', 'AZ');
INSERT INTO `bf_states` VALUES(5, 'Arkansas', 'AR');
INSERT INTO `bf_states` VALUES(6, 'California', 'CA');
INSERT INTO `bf_states` VALUES(7, 'Colorado', 'CO');
INSERT INTO `bf_states` VALUES(8, 'Connecticut', 'CT');
INSERT INTO `bf_states` VALUES(9, 'Delaware', 'DE');
INSERT INTO `bf_states` VALUES(10, 'District of Columbia', 'DC');
INSERT INTO `bf_states` VALUES(12, 'Florida', 'FL');
INSERT INTO `bf_states` VALUES(13, 'Georgia', 'GA');
INSERT INTO `bf_states` VALUES(14, 'Guam', 'GU');
INSERT INTO `bf_states` VALUES(15, 'Hawaii', 'HI');
INSERT INTO `bf_states` VALUES(16, 'Idaho', 'ID');
INSERT INTO `bf_states` VALUES(17, 'Illinois', 'IL');
INSERT INTO `bf_states` VALUES(18, 'Indiana', 'IN');
INSERT INTO `bf_states` VALUES(19, 'Iowa', 'IA');
INSERT INTO `bf_states` VALUES(20, 'Kansas', 'KS');
INSERT INTO `bf_states` VALUES(21, 'Kentucky', 'KY');
INSERT INTO `bf_states` VALUES(22, 'Louisiana', 'LA');
INSERT INTO `bf_states` VALUES(23, 'Maine', 'ME');
INSERT INTO `bf_states` VALUES(24, 'Marshall Islands', 'MH');
INSERT INTO `bf_states` VALUES(25, 'Maryland', 'MD');
INSERT INTO `bf_states` VALUES(26, 'Massachusetts', 'MA');
INSERT INTO `bf_states` VALUES(27, 'Michigan', 'MI');
INSERT INTO `bf_states` VALUES(28, 'Minnesota', 'MN');
INSERT INTO `bf_states` VALUES(29, 'Mississippi', 'MS');
INSERT INTO `bf_states` VALUES(30, 'Missouri', 'MO');
INSERT INTO `bf_states` VALUES(31, 'Montana', 'MT');
INSERT INTO `bf_states` VALUES(32, 'Nebraska', 'NE');
INSERT INTO `bf_states` VALUES(33, 'Nevada', 'NV');
INSERT INTO `bf_states` VALUES(34, 'New Hampshire', 'NH');
INSERT INTO `bf_states` VALUES(35, 'New Jersey', 'NJ');
INSERT INTO `bf_states` VALUES(36, 'New Mexico', 'NM');
INSERT INTO `bf_states` VALUES(37, 'New York', 'NY');
INSERT INTO `bf_states` VALUES(38, 'North Carolina', 'NC');
INSERT INTO `bf_states` VALUES(39, 'North Dakota', 'ND');
INSERT INTO `bf_states` VALUES(40, 'Northern Mariana Islands', 'MP');
INSERT INTO `bf_states` VALUES(41, 'Ohio', 'OH');
INSERT INTO `bf_states` VALUES(42, 'Oklahoma', 'OK');
INSERT INTO `bf_states` VALUES(43, 'Oregon', 'OR');
INSERT INTO `bf_states` VALUES(44, 'Palau', 'PW');
INSERT INTO `bf_states` VALUES(45, 'Pennsylvania', 'PA');
INSERT INTO `bf_states` VALUES(46, 'Puerto Rico', 'PR');
INSERT INTO `bf_states` VALUES(47, 'Rhode Island', 'RI');
INSERT INTO `bf_states` VALUES(48, 'South Carolina', 'SC');
INSERT INTO `bf_states` VALUES(49, 'South Dakota', 'SD');
INSERT INTO `bf_states` VALUES(50, 'Tennessee', 'TN');
INSERT INTO `bf_states` VALUES(51, 'Texas', 'TX');
INSERT INTO `bf_states` VALUES(52, 'Utah', 'UT');
INSERT INTO `bf_states` VALUES(53, 'Vermont', 'VT');
INSERT INTO `bf_states` VALUES(54, 'Virgin Islands', 'VI');
INSERT INTO `bf_states` VALUES(55, 'Virginia', 'VA');
INSERT INTO `bf_states` VALUES(56, 'Washington', 'WA');
INSERT INTO `bf_states` VALUES(57, 'West Virginia', 'WV');
INSERT INTO `bf_states` VALUES(58, 'Wisconsin', 'WI');
INSERT INTO `bf_states` VALUES(59, 'Wyoming', 'WY');
INSERT INTO `bf_states` VALUES(60, 'Armed Forces Africa', 'AE');
INSERT INTO `bf_states` VALUES(62, 'Armed Forces Canada', 'AE');
INSERT INTO `bf_states` VALUES(63, 'Armed Forces Europe', 'AE');
INSERT INTO `bf_states` VALUES(64, 'Armed Forces Middle East', 'AE');
INSERT INTO `bf_states` VALUES(65, 'Armed Forces Pacific', 'AP');

-- --------------------------------------------------------

--
-- Table structure for table `bf_users`
--

CREATE TABLE `bf_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '4',
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `password_hash` varchar(40) NOT NULL,
  `temp_password_hash` varchar(40) DEFAULT NULL,
  `salt` varchar(7) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_ip` varchar(40) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `street_1` varchar(255) DEFAULT NULL,
  `street_2` varchar(255) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `zipcode` int(7) DEFAULT NULL,
  `zip_extra` int(5) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `bf_user_cookies`
--

CREATE TABLE `bf_user_cookies` (
  `user_id` bigint(20) NOT NULL,
  `token` varchar(128) NOT NULL,
  `created_on` datetime NOT NULL,
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$sql =<<<SQL
DROP TABLE `bf_email_queue`, `bf_login_attempts`, `bf_pages`, `bf_permissions`, `bf_roles`, `bf_schema_version`, `bf_sessions`, `bf_states`, `bf_users`, `bf_user_cookies`;
SQL;

		return $sql;
	}
	
	//--------------------------------------------------------------------
	
}