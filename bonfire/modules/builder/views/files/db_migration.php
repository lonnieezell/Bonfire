<?php

$fieldPrefix = $table_as_field_prefix ? "{$module_name_lower}_" : '';

$migrationFields = "
		'{$primary_key_field}' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),";

for ($counter = 1; $field_total >= $counter; $counter++) {
    if (set_value("view_field_label$counter") == null) {
        continue; 	// move onto next iteration of the loop
    }

    $dbFieldType = set_value("db_field_type$counter");

    $migrationFields .= "
        '{$fieldPrefix}" . set_value("view_field_name$counter") . "' => array(
            'type'       => '" . addcslashes($dbFieldType, "'") . "',";

    if ( ! in_array($dbFieldType, $no_length)) {
        $escaped_constraint_val = $this->input->post("db_field_length_value$counter");

        // ENUM or SET
        if (in_array($dbFieldType, $listTypes)) {
            $escaped_constraint_val = "'" . addcslashes($this->input->post("db_field_length_value$counter"), "'") . "'";
        } elseif (in_array($dbFieldType, $realNumberTypes)) {
            $escaped_constraint_val = "'{$escaped_constraint_val}'";
        }

        // Optional length and the constraint is empty, but not 0
        if (in_array($dbFieldType, $optional_length)
            && empty($escaped_constraint_val) && ! is_numeric($escaped_constraint_val)
           ) {
            $constraint = '';
        }
        // A constraint was supplied or required
        else {
            $constraint = "
            'constraint' => {$escaped_constraint_val},";
        }

        $migrationFields .= $constraint;
        unset($escaped_constraint_val, $constraint);
    }

    // If the required field validation has been set, don't allow null values
    $validation_rules = $this->input->post("validation_rules{$counter}");
    if (is_array($validation_rules) && in_array('required', $validation_rules)) {
        $migrationFields .= "
            'null'       => false,";
    } else {
        $migrationFields .= "
            'null'       => true,";
    }

    // Set defaults for certain field types
    switch ($dbFieldType) {
        case 'DATE':
            $migrationFields .= "
            'default'    => '0000-00-00',";
            break;

        case 'DATETIME':
            $migrationFields .= "
            'default'    => '0000-00-00 00:00:00',";
            break;

        default:
            break;
    }

    $migrationFields .= "
        ),";
}

// Use soft deletes? Add deleted field.
if ($useSoftDeletes) {
    $migrationFields .= "
        '{$soft_delete_field}' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),";
    if ($logUser) {
        $migrationFields .= "
        '{$deleted_by_field}' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),";
    }
}

// Use the created field? Add field and custom name if chosen.
if ($useCreated) {
    $migrationFields .= "
        '{$created_field}' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),";
    if ($logUser) {
        $migrationFields .= "
        '{$created_by_field}' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => false,
        ),";
    }
}

// Use the modified field? Add field and custom name if chosen.
if ($useModified) {
    $migrationFields .= "
        '{$modified_field}' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),";
    if ($logUser) {
        $migrationFields .= "
        '{$modified_by_field}' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),";
    }
}

echo "<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_{$table_name} extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private \$table_name = '{$table_name}';

	/**
	 * @var array The table's fields
	 */
	private \$fields = array({$migrationFields}
	);

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		\$this->dbforge->add_field(\$this->fields);
		\$this->dbforge->add_key('{$primary_key_field}', true);
		\$this->dbforge->create_table(\$this->table_name);
	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
		\$this->dbforge->drop_table(\$this->table_name);
	}
}";