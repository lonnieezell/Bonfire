<?php
$ucModuleName = ucfirst($module_name_lower);

$logUserString = $logUser ? 'true' : 'false';
$useCreatedString = $useCreated ? 'true' : 'false';
$useModifiedString = $useModified ? 'true' : 'false';
$useSoftDeletesString = $useSoftDeletes ? 'true' : 'false';

$fields = "protected \$table_name	= '{$table_name}';
	protected \$key			= '{$primary_key_field}';
	protected \$date_format	= 'datetime';

	protected \$log_user 	= {$logUserString};
	protected \$set_created	= {$useCreatedString};
	protected \$set_modified = {$useModifiedString};
	protected \$soft_deletes	= {$useSoftDeletesString};
";

// Use the created field? Add field and custom name if chosen.
if ($useCreated) {
    $fields .= "
	protected \$created_field     = '{$created_field}';";
    if ($logUser) {
        $fields .= "
    protected \$created_by_field  = '{$created_by_field}';";
    }
}

// Use the modified field? Add field and custom name if chosen.
if ($useModified) {
    $fields .= "
	protected \$modified_field    = '{$modified_field}';";
    if ($logUser) {
        $fields .= "
    protected \$modified_by_field = '{$modified_by_field}';";
    }
}

if ($useSoftDeletes) {
    $fields .= "
    protected \$deleted_field     = '{$soft_delete_field}';";
    if ($logUser) {
        $fields .= "
    protected \$deleted_by_field  = '{$deleted_by_field}';";
    }
}

//--------------------------------------------------------------------
// Validation Rules
//--------------------------------------------------------------------

$dbPrefix = $this->db->dbprefix;
$field_prefix = '';
$rules = '';

if ($db_required == 'new' && $table_as_field_prefix === true) {
    $field_prefix = "{$module_name_lower}_";
}

for ($counter = 1; $field_total >= $counter; $counter++) {
	// Only build on fields that have data entered.
	if (set_value("view_field_label$counter") == null) {
		continue;
	}

    $field_name = set_value("view_field_name$counter");
	$form_name = $field_prefix . $field_name;

	$rules .= "
		array(
			'field' => '{$form_name}',
			'label' => 'lang:{$module_name_lower}_field_{$field_name}',
			'rules' => '";

	$validation_rules = $this->input->post("validation_rules{$counter}");
	$rule_counter = 0;
    $tempRules = array();

	// Rules have been selected for this fieldset
	if (is_array($validation_rules)) {
		// Add rules such as trim|required
		foreach ($validation_rules as $key => $value) {
            if ($value == 'none_of_the_above') {
                continue;
            }

			if ($value == 'unique') {
				$value .= "[{$dbPrefix}{$table_name}.{$field_name},{$dbPrefix}{$table_name}.{$primary_key_field}]";
			}
            $tempRules[] = $value;
		}
	}

	$db_field_type = set_value("db_field_type{$counter}");

    $max = set_value("db_field_length_value$counter");
	if ( ! in_array($db_field_type, $listTypes) && $max != null) {
		if (in_array($db_field_type, $realNumberTypes)) {
			$len = explode(',', $max);
			$max = $len[0];
            if ( ! empty($len[1])) {
                $max++; // Add 1 to allow for the decimal point
            }
		}

        $tempRules[] = "max_length[{$max}]";
	}

    $rules .= implode('|', $tempRules);

    // End the validation rules definition and close the array for this field
	$rules .= "',
		),";
}

if ( ! empty($rules)) {
    // Minor formatting to close the array on the next line
    $rules .= '
	';
}

//------------------------------------------------------------------------------
// Output the model
//------------------------------------------------------------------------------
echo "<?php defined('BASEPATH') || exit('No direct script access allowed');

class {$ucModuleName}_model extends BF_Model
{
    {$fields}

	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected \$before_insert 	= array();
	protected \$after_insert 	= array();
	protected \$before_update 	= array();
	protected \$after_update 	= array();
	protected \$before_find 	    = array();
	protected \$after_find 		= array();
	protected \$before_delete 	= array();
	protected \$after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected \$return_insert_id = true;

	// The default type for returned row data.
	protected \$return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected \$protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// \$insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected \$validation_rules 		= array({$rules});
	protected \$insert_validation_rules  = array();
	protected \$skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
}";