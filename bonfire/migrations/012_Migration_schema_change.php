<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This Migration file should not be used in an install situation
 * because the migration table schema will already be setup correctly
 * and the old table will not exist
 */

/**
 * Update the Migration Schema (schema_version table)
 */
class Migration_Migration_schema_change extends Migration
{
	/****************************************************************
	 * Table names
	 */
	/**
	 * @var string The name of the Schema_version table
	 */
	private $table_name = 'schema_version';

	/**
	 * @var string The name of the backup Schema_version table
	 */
	private $backup_table = 'schema_version_old';

	/****************************************************************
	 * Field definitions
	 */
	/**
	 * @var array Fields for the new schema_version table
	 */
	private $fields = array(
		'type' => array(
			'type' => 'VARCHAR',
			'constraint' => 20,
			'null' => false,
		),
		'version' => array(
			'type' => 'INT',
			'constraint' => '4',
			'default' => 0,
			'null' => false,
		),
	);

	/**
	 * @var string Name of new key field to be added to the table
	 */
	private $new_key = 'type';

	/****************************************************************
	 * Migration methods
	 */
	/**
	 * Install this migration
	 */
	public function up()
	{
		// check if the table is in the old format
		if ( ! $this->db->field_exists($this->new_key, $this->table_name))
		{
			// the table is in the old format

			// backup the schema_version table
			$this->dbforge->rename_table($this->table_name, $this->backup_table);

			// modify the schema_version table
			$this->dbforge->add_field($this->fields);
			$this->dbforge->add_key($this->new_key, TRUE);
			$this->dbforge->create_table($this->table_name);

			// add records for each of the old permissions
			$permission_records = array();
			foreach ($version_array as $type => $version_num)
			{
				if ($type == 'version')
				{
					$type_field = 'core';
					$version_num++;
				}
				else
				{
					$type_field = str_replace('version', '', $type);
				}

				$permission_records[] = array(
					'type' => $type_field,
					'version' => $version_num,
				);
			}

			if ( ! empty($permission_records))
			{
				$this->db->insert_batch($this->table_name, $permission_records);
			}
		}
	}

	/**
	 * Install this migration
	 */
	public function down()
	{
		// check if the old schema exists
		if ($this->db->table_exists($this->backup_table))
		{
			// Reverse the schema_version table changes
			$this->dbforge->drop_table($this->table_name);

			$this->dbforge->rename_table($this->backup_table, $this->table_name);
		}
	}
}