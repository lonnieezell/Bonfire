<?php

$lang = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";

$lang .= <<<EOT
_lang['{$module_name_lower}_list_front']			= '{$module_name}';
_lang['{$module_name_lower}_manage']			= 'Manage {$module_name}';
_lang['{$module_name_lower}_edit']				= 'Edit';
_lang['{$module_name_lower}_true']				= 'True';
_lang['{$module_name_lower}_false']				= 'False';
_lang['{$module_name_lower}_create']			= 'Create';
_lang['{$module_name_lower}_list']				= 'List';
_lang['{$module_name_lower}_new']				= 'New';
_lang['{$module_name_lower}_edit_text']			= 'Edit this to suit your needs';
_lang['{$module_name_lower}_no_records']			= 'There aren\\'t any {$module_name_lower} in the system.';
_lang['{$module_name_lower}_create_new']			= 'Create a new {$module_name}.';
_lang['{$module_name_lower}_create_success']			= '{$module_name} successfully created.';
_lang['{$module_name_lower}_create_failure']			= 'There was a problem creating the {$module_name_lower}: ';
_lang['{$module_name_lower}_create_new_button']			= 'Create New {$module_name}';
_lang['{$module_name_lower}_invalid_id']			= 'Invalid {$module_name} ID.';
_lang['{$module_name_lower}_edit_success']			= '{$module_name} successfully saved.';
_lang['{$module_name_lower}_edit_failure']			= 'There was a problem saving the {$module_name_lower}: ';
_lang['{$module_name_lower}_delete_success']			= 'record{s} successfully deleted.';
_lang['{$module_name_lower}_delete_failure']			= 'We could not delete the record: ';
_lang['{$module_name_lower}_delete_error']			= 'You have not selected any records to delete.';
_lang['{$module_name_lower}_actions']			= 'Actions';
_lang['{$module_name_lower}_cancel']			= 'Cancel';
_lang['{$module_name_lower}_delete_record']			= 'Delete this {$module_name}';
_lang['{$module_name_lower}_delete_confirm']			= 'Are you sure you want to delete this {$module_name_lower}?';
_lang['{$module_name_lower}_create_heading']			= 'Create {$module_name}';
_lang['{$module_name_lower}_edit_heading']			= 'Edit {$module_name}';

// Activities
_lang['{$module_name_lower}_act_create_record']			= 'Created record with ID';
_lang['{$module_name_lower}_act_edit_record']			= 'Updated record with ID';
_lang['{$module_name_lower}_act_delete_record']			= 'Deleted record with ID';
EOT;

if ($field_total > 0)
{

	$fields = "// Fields\n";
	for($counter=1; $field_total >= $counter; $counter++)
	{
		// only build on fields that have data entered.

		if (set_value("view_field_label$counter") == NULL)
		{
			continue; 	// move onto next iteration of the loop
		}
		
		$field_label = set_value("view_field_label$counter");
		$field_name = $module_name_lower . '_field_' . set_value("view_field_name$counter");
	
		$fields .= <<<EOT
_lang['{$field_name}']			= "{$field_label}";\n
EOT;
	}

	if ($use_soft_deletes == 'true')
	{
		$fields .= <<<EOT
_lang['{$module_name_lower}_field_deleted']			= "Delete";\n
EOT;
	}
	if ($use_created == 'true')
	{
		$fields .= <<<EOT
_lang['{$module_name_lower}_field_created']			= "Created";\n
EOT;
	}
	if ($use_modified == 'true')
	{
		$fields .= <<<EOT
_lang['{$module_name_lower}_field_modified']			= "Modified";\n
EOT;
	}

	$fields .= "\n// Activities";

	// Activities
	$lang = str_replace('// Activities', $fields , $lang);
}

$lang = str_replace('_lang[', '$lang[' , $lang);

echo $lang;
?>
