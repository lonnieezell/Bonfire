<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$view =<<<END
<?php

\$num_columns	= {cols_total};
\$can_delete	= \$this->auth->has_permission('{delete_permission}');
\$can_edit		= \$this->auth->has_permission('{edit_permission}');
\$has_records	= isset(\$records) && is_array(\$records) && count(\$records);

?>
<div class="admin-box">
	<h3>{$module_name}</h3>
	<?php echo form_open(\$this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if (\$can_delete && \$has_records) : ?>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>
					{table_header}
				</tr>
			</thead>
			<?php if (\$has_records) : ?>
			<tfoot>
				<?php if (\$can_delete) : ?>
				<tr>
					<td colspan="<?php echo \$num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('{$module_name_lower}_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if (\$has_records) :
					foreach (\$records as \$record) :
				?>
				<tr>
					<?php if (\$can_delete) : ?>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo \$record->{$primary_key_field}; ?>" /></td>
					<?php endif;?>
					{table_records}
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="<?php echo \$num_columns; ?>">No records found that match your selection.</td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
</div>
END;

$headers = '';
for ($counter = 1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	// Due to the required if rule if the first field is set the others must be
	if (set_value("view_field_label$counter") == NULL)
	{
		continue; 	// move onto next iteration of the loop
	}

	$headers .= '
					<th>' . set_value("view_field_label$counter") . '</th>';
}

$field_prefix = '';

// only add maintenance columns to view when module is creating a new db table
// (columns should already be present and handled below when existing table is used)
if ($db_required == 'new')
{
	if ($use_soft_deletes == 'true')
	{
		$headers .= '
					<th><?php echo lang("' . $module_name_lower . '_column_deleted"); ?></th>';
	}
	if ($use_created == 'true')
	{
		$headers .= '
					<th><?php echo lang("' . $module_name_lower . '_column_created"); ?></th>';
	}
	if ($use_modified == 'true')
	{
		$headers .= '
					<th><?php echo lang("' . $module_name_lower . '_column_modified"); ?></th>';
	}
    if ($table_as_field_prefix === TRUE)
    {
        $field_prefix = $module_name_lower . '_';
    }
}

$table_records = '';
$pencil_icon   = "'<span class=\"icon-pencil\"></span>' . ";
for ($counter = 1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	//Due to the requiredif rule if the first field is set then the others must be

	if (set_value("view_field_name$counter") == NULL || set_value("view_field_name$counter") == $primary_key_field)
	{
		continue; 	// move onto next iteration of the loop
	}

	$field_name = $field_prefix . set_value("view_field_name$counter");

	if ($counter == 1)
	{
		$table_records .= "
				<?php if (\$can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/" . $controller_name . "/" . $module_name_lower . "/edit/' . \$record->" . $primary_key_field . ", {$pencil_icon} \$record->" . $field_name . "); ?></td>
				<?php else : ?>
					<td><?php e(\$record->" . $field_name . "); ?></td>
				<?php endif; ?>";
	}
	else
	{
		$field_name = set_value("view_field_name$counter");

		// when building from existing table, modify output of the 'deleted' maintenance column
		if  ($db_required == 'existing' && $field_name == $this->input->post("soft_delete_field"))
		{
			$table_records .= '
					<td><?php echo $record->'.$field_name.' > 0 ? lang(\''.$module_name_lower.'_true\') : lang(\''.$module_name_lower.'_false\')?></td>';
		}
		else
		{
			$table_records .= '
					<td><?php e($record->'.$field_name.') ?></td>';
		}
	}
	
}

// only add maintenance columns to view when module is creating a new db table
// (columns should already be present and handled above when existing table is used)
if($db_required == 'new')
{
	if ($use_soft_deletes == 'true')
	{
		$table_records .= '
					<td><?php echo $record->'.set_value("soft_delete_field").' > 0 ? lang(\''.$module_name_lower.'_true\') : lang(\''.$module_name_lower.'_false\')?></td>';
		$field_total++;
	}
	if ($use_created == 'true')
	{
		$table_records .= '
					<td><?php e($record->'.set_value("created_field").') ?></td>';
		$field_total++;
	}
	if ($use_modified == 'true')
	{
		$table_records .= '
					<td><?php e($record->'.set_value("modified_field").') ?></td>';
		$field_total++;
	}
}

$view = str_replace('{cols_total}', $field_total + 1 , $view);
$view = str_replace('{table_header}', $headers, $view);
$view = str_replace('{table_records}', $table_records, $view);
$view = str_replace('{delete_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Delete', $view);
$view = str_replace('{edit_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Edit', $view);

echo $view;

unset($view, $headers);