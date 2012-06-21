<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$view =<<<END
<div class="admin-box">
	<h3>{$module_name}</h3>
	<?php echo form_open(\$this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if (\$this->auth->has_permission('{delete_permission}') && isset(\$records) && is_array(\$records) && count(\$records)) : ?>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>
					{table_header}
				</tr>
			</thead>
			<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
			<tfoot>
				<?php if (\$this->auth->has_permission('{delete_permission}')) : ?>
				<tr>
					<td colspan="{cols_total}">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php echo lang('{$module_name_lower}_delete_confirm'); ?>')">
					</td>
				</tr>
				<?php endif;?>
			</tfoot>
			<?php endif; ?>
			<tbody>
			<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
			<?php foreach (\$records as \$record) : ?>
				<tr>
					<?php if (\$this->auth->has_permission('{delete_permission}')) : ?>
					<td><input type="checkbox" name="checked[]" value="<?php echo \$record->{$primary_key_field} ?>" /></td>
					<?php endif;?>
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
$pencil_icon   = "'<i class=\"icon-pencil\">&nbsp;</i>' . ";
for($counter=1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_name$counter") == NULL || set_value("view_field_name$counter") == $primary_key_field)
	{
		continue; 	// move onto next iteration of the loop
	}

	if($db_required == 'new' && $table_as_field_prefix === TRUE)
	{
		$field_name = $module_name_lower . '_' . set_value("view_field_name$counter");
	}
	elseif($db_required == 'new' && $table_as_field_prefix === FALSE)
	{
		$field_name = set_value("view_field_name$counter");
	}
	else
	{
		$field_name = set_value("view_field_name$counter");
	}

	if ($counter == 1) {
		$table_records .= "
				<?php if (\$this->auth->has_permission('{edit_permission}')) : ?>
				<td><?php echo anchor(SITE_AREA .'/".$controller_name."/".$module_name_lower."/edit/'. \$record->".$primary_key_field.", {$pencil_icon} \$record->".$field_name.") ?></td>
				<?php else: ?>
				<td><?php echo \$record->".$field_name." ?></td>
				<?php endif; ?>
			";
	}
	else {
		$table_records .= '
				<td><?php echo $record->'.$field_name.'?></td>';
	}
}
if ($use_soft_deletes == 'true')
{
	$table_records .= '
				<td><?php echo $record->deleted > 0 ? lang(\''.$module_name_lower.'_true\') : lang(\''.$module_name_lower.'_false\')?></td>';
	$field_total++;
}
if ($use_created == 'true')
{
	$table_records .= '
				<td><?php echo $record->'.set_value("created_field").'?></td>';
	$field_total++;
}
if ($use_modified == 'true')
{
	$table_records .= '
				<td><?php echo $record->'.set_value("modified_field").'?></td>';
	$field_total++;
}



$view = str_replace('{cols_total}', $field_total + 1 , $view);
$view = str_replace('{table_header}', $headers, $view);
$view = str_replace('{table_records}', $table_records, $view);
$view = str_replace('{delete_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Delete', $view);
$view = str_replace('{edit_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Edit', $view);

echo $view;

unset($view, $headers);
