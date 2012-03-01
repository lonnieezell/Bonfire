<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$view =<<<END
<div class="admin-box">
	<h3>{$module_name}</h3>
	<?php echo form_open(\$this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					{table_header}
				</tr>
			</thead>
			<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
			<tfoot>
				<tr>
					<td colspan="{cols_total}">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="submit" class="btn danger" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php echo lang('{$module_name_lower}_delete_confirm'); ?>')">
					</td>
				</tr>
			</tfoot>
			<?php endif; ?>
			<tbody>
			<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
			<?php foreach (\$records as \$record) : ?>
				<tr>
					<td><input type="checkbox" name="checked[]" value="<?php echo \$record->{$primary_key_field} ?>" /></td>
					{table_records}
				</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="{cols_total}">No records found that match your selection.</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
</div>
END;

$headers = '';
for($counter=1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	//Due to the required if rule if the first field is set the the others must be

	if (set_value("view_field_label$counter") == NULL)
	{
		continue; 	// move onto next iteration of the loop
	}
	$headers .= '
		<th>'. set_value("view_field_label$counter").'</th>';
}
if ($use_soft_deletes == 'true')
{
	$headers .= '
		<th>Deleted</th>';
}
if ($use_created == 'true')
{
	$headers .= '
		<th>Created</th>';
}
if ($use_modified == 'true')
{
	$headers .= '
		<th>Modified</th>';
}

$table_records = '';
$pencil_icon   = '<i class="icon pencil">&nbsp;</i>';
for($counter=1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_name$counter") == NULL || set_value("view_field_name$counter") == $primary_key_field)
	{
		continue; 	// move onto next iteration of the loop
	}
	if ($counter == 1) {
		$table_records .= "
				<td><?php echo anchor(SITE_AREA .'/".$controller_name."/".$module_name_lower."/edit/'. \$record->".$primary_key_field.", {$pencil_icon} \$record->".$module_name_lower."_".set_value("view_field_name$counter").") ?></td>
			";
	}
	else {
		$table_records .= '
				<td><?php echo $record->'.$module_name_lower.'_'.set_value("view_field_name$counter").'?></td>';
	}
}
if ($use_soft_deletes == 'true')
{
	$table_records .= '
				<td><?php echo $record->deleted > 0 ? lang(\''.$module_name_lower.'_true\') : lang(\''.$module_name_lower.'_false\')?></td>';
}
if ($use_created == 'true')
{
	$table_records .= '
				<td><?php echo $record->'.set_value("created_field").'?></td>';
}
if ($use_modified == 'true')
{
	$table_records .= '
				<td><?php echo $record->'.set_value("modified_field").'?></td>';
}



$view = str_replace('{cols_total}', $field_total + 2 , $view);
$view = str_replace('{table_header}', $headers, $view);
$view = str_replace('{table_records}', $table_records, $view);

echo $view;

unset($view, $headers);
