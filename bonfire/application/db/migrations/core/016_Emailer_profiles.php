<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Emailer_Profiles extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		$this->dbforge->add_field("`profile_id` int(11) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`profile_name` varchar(255) NOT NULL");
		$this->dbforge->add_field("`mailtype` varchar(255) DEFAULT 'text' NOT NULL");
		$this->dbforge->add_field("`mailpath` varchar(255) DEFAULT '/usr/sbin/sendmail' NOT NULL");
		$this->dbforge->add_field("`protocol` varchar(255) DEFAULT 'mail' NOT NULL");
		$this->dbforge->add_field("`sender_email` varchar(255) DEFAULT 'name@domain' NOT NULL");
		$this->dbforge->add_field("`sender_name` VARCHAR( 255 ) DEFAULT '' NOT NULL");
		$this->dbforge->add_field("`smtp_host` varchar(255) DEFAULT 'localhost' NOT NULL");
		$this->dbforge->add_field("`smtp_pass` varchar(255) DEFAULT '' NOT NULL");
		$this->dbforge->add_field("`smtp_port` varchar(255) DEFAULT '25' NOT NULL");
		$this->dbforge->add_field("`smtp_timeout` varchar(255) DEFAULT '' NOT NULL");
		$this->dbforge->add_field("`smtp_user` varchar(255) DEFAULT 'user@domain' NOT NULL");
		$this->dbforge->add_field("`template_header` longtext NOT NULL");
		$this->dbforge->add_field("`template_footer` longtext NOT NULL");
		$this->dbforge->add_field("`default` TINYINT(1) DEFAULT 0 NOT NULL");
		$this->dbforge->add_key('profile_id', true);
		$this->dbforge->create_table('email_profiles');
		
		$this->db->query("INSERT INTO {$prefix}email_profiles VALUES(1,'Default','text','/usr/sbin/sendmail','mail','','','','','','','','','','1')");
		
		$sql = "ALTER TABLE `{$prefix}email_queue` ADD `profile_id` INT NOT NULL AFTER `id` ";
		$this->db->query($sql);

		
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		$this->dbforge->drop_table('email_profiles');
		
	}
	
	//--------------------------------------------------------------------
	
}