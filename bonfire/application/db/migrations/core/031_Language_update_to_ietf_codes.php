<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Language_update_to_ietf_codes extends Migration
{
	private $language_array = array(
		'english' => 'en-us',
		'persian' => 'fa',
		'portuguese' => 'pt-pt',
	);

	public function up()
	{
		$prefix = $this->db->dbprefix;

		foreach ($this->language_array as $name => $code) {
			$this->db->query("UPDATE `{$prefix}users` set `language` = '{$code}' where `language` = '{$name}'");
		}
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		foreach ($this->language_array as $name => $code) {
			$this->db->query("UPDATE `{$prefix}users` set `language` = '{$name}' where `language` = '{$code}'");
		}
	}
}
