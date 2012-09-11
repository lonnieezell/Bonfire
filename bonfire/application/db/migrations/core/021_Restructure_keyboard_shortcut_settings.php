<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Restructure_keyboard_shortcut_settings extends Migration {

	public function up()
	{
		$prefix = $this->db->dbprefix;

		// get the current keyboard shortcuts
		$query = $this->db->get_where('settings',array('name' =>'ui.shortcut_keys'));

		if ($query->num_rows() > 0)
		{
			// divide them up
			$setting_obj = $query->row();
			$keys = unserialize($setting_obj->value);

			$new_keys = array();
			foreach ($keys as $name => $shortcut)
			{
				$new_keys[] = array(
					'name'   => $name,
					'module' => 'core.ui',
					'value'  => $shortcut,
				);
			}

			// store them individually
			if (count($new_keys))
			{
				// insert the new keys into the db
				if ($this->db->insert_batch('settings', $new_keys))
				{
					// delete the old entry
					$this->db->delete('settings',array('name' =>'ui.shortcut_keys'));
				}

			}
		}
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$prefix = $this->db->dbprefix;

		// THIS MAY NOT WORK
		// depending on the number of shortcuts you have now the size of the "value" field
		// in the settings table might now be big enough to store all of your shortcuts
		// in one setting record and could give an sql error

		// get the current keyboard shortcuts
		$query = $this->db->get_where('settings',array('module' =>'core.ui'));

		if ($query->num_rows() > 0)
		{
			// combine them
			$new_keys = array();
			foreach ($query->result() as $key)
			{
				$new_keys[$key->name] = $key->value;
			}

			// store keys in one setting record
			if (count($new_keys))
			{
				$rec = array(
					'name'   => 'ui.shortcut_keys',
					'module' => 'core',
					'value'  => serialize($new_keys),
				);
				// insert the new keys into the db
				if ($this->db->insert('settings', $rec))
				{
					// delete the old entry
					$this->db->delete('settings',array('module' =>'core.ui'));
				}

			}
		}

	}

	//--------------------------------------------------------------------

}