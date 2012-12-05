<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$view =<<<END
<div class="admin-box">
	<h3>{$module_name}</h3>
	<?php echo form_open(\$this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php \$tblcols_total = {tblcols_total}; ?>
					<?php if (\$this->auth->has_permission('{delete_permission}') && isset(\$records) && is_array(\$records) && count(\$records)) : ?>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>
					<?php if ( isset(\$records) && is_array(\$records) && count(\$records) && ( \$this->auth->has_permission('{edit_permission}') || \$this->auth->has_permission('{view_permission}') ) ) : ?>
					<th class="column-check">&nbsp;</th>
					<?php \$tblcols_total += 1; ?>
					<?php endif;?>
					{table_header}
				</tr>
			</thead>
			<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
			<tfoot>
				<?php if (\$this->auth->has_permission('{delete_permission}') or \$this->auth->has_permission('{create_permission}')) : ?>
				<tr>
					<td colspan="{cols_total}">
					<?php if (\$this->auth->has_permission('{delete_permission}')) : ?>
						<?php // echo lang('bf_with_selected') ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('{$module_name_lower}_delete_selected') ?>" onclick="return confirm('<?php e(js_escape(lang('{$module_name_lower}_delete_confirm'))); ?>')">
					<?php endif; ?>
					<?php if (\$this->auth->has_permission('{create_permission}')) : ?>
						<a class="btn btn-primary" href="<?php echo site_url(SITE_AREA.'/content/{$module_name_lower}/create?') . http_build_query(\$_GET) ;?>"><?php echo lang('{$module_name_lower}_new') ?> {$module_name}</a>
						<?php endif; ?>
					</td>
				</tr>
				<?php endif;?>
			</tfoot>
			<?php elseif (\$this->auth->has_permission('{create_permission}')) : ?>
			<tfoot>
				<tr>
					<td colspan="{cols_total}">
						<a class="btn btn-primary" href="<?php echo site_url(SITE_AREA.'/content/{$module_name_lower}/create?') . http_build_query(\$_GET) ;?>"><?php echo lang('{$module_name_lower}_new') ?> {$module_name}</a>
					</td>
				</tr>
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
					<td colspan="{cols_total}"><?php echo lang('{$module_name_lower}_no_records_found');?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php if ( \$this->pagination->total_rows > \$this->pagination->per_page ) echo \$this->pagination->create_links(); ?>
	<?php echo form_close(); ?>

</div>
END;

$columns = array();
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
	$columns[set_value("view_field_name$counter")] = set_value("view_field_name$counter");
}

if ($use_soft_deletes == 'true')
{
	$headers .= '
					<th>Deleted</th>';
}
if ($use_created == 'true' && NULL != set_value("created_field") && !isset( $columns[ set_value("created_field") ] ) )
{
	$headers .= '
					<th>Created</th>';
}
if ($use_modified == 'true' && NULL != set_value("modified_field") && !isset( $columns[ set_value("modified_field") ] ) )
{
	$headers .= '
					<th>Modified</th>';
}

$table_records = '';
$pencil_icon   = "'<i class=\"icon-pencil\">&nbsp;</i>'";
$edit_icon     = "'<i class=\"icon-pencil\">&nbsp;</i>&nbsp;'";
$view_icon     = "'<i class=\"icon-book\">&nbsp;</i>&nbsp;'";
$delete_icon   = "'<i class=\"icon-remove\">&nbsp;</i>&nbsp;'";	// remove	remove-circle
$field_counter = 0;
for($counter=1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_name$counter") == NULL || set_value("view_field_name$counter") == $primary_key_field)
	{
		continue; 	// move onto next iteration of the loop
	}

	$field_counter++;

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

// Enhanced Parent-Child Builder - Add Reference column
if ( $ref = $this->input->post( "view_field_reference$counter" ) ) {
	$ref = explode( '.', $ref );
	$field_name = $ref[0] . '_' . $ref[2];
}
// Enhanced Parent-Child Builder - End of Refference column

	if ($counter == 1) {
		$table_records .= "
				<?php // Add Permitted Actions
					if (\$this->auth->has_permission('{edit_permission}'))
						echo '<td>'.anchor(SITE_AREA .'/".$controller_name."/".$module_name_lower."/edit/'. \$record->".$primary_key_field.", {$edit_icon}).'</td>';
					elseif (\$this->auth->has_permission('{view_permission}'))
						echo '<td>'.anchor(SITE_AREA .'/".$controller_name."/".$module_name_lower."/edit/'. \$record->".$primary_key_field.", {$view_icon}).'</td>';
				?>
				<td><?php e(\$record->".$field_name.") ?></td>
			";
	}
	else {
		$table_records .= '
				<td><?php e($record->'.$field_name.') ?></td>';
	}
}
if ($use_soft_deletes == 'true')
{
	$table_records .= '
				<td><?php echo $record->deleted > 0 ? lang(\''.$module_name_lower.'_true\') : lang(\''.$module_name_lower.'_false\')?></td>';
	$field_total++;
}
if ($use_created == 'true' && NULL != set_value("created_field") && !isset( $columns[ set_value("created_field") ] ) )
{
	$table_records .= '
				<td><?php e($record->'.set_value("created_field").') ?></td>';
	$field_total++;
}
if ($use_modified == 'true' && NULL != set_value("modified_field") && !isset( $columns[ set_value("modified_field") ] ) )
{
	$table_records .= '
				<td><?php e($record->'.set_value("modified_field").') ?></td>';
	$field_total++;
}



$view = str_replace('{tblcols_total}', $field_counter + 1 , $view);
$view = str_replace('{cols_total}', '<?php echo $tblcols_total; ?>' , $view);
$view = str_replace('{cols_total_minus_one}', '<?php echo $tblcols_total-1; ?>' , $view);
$view = str_replace('{table_header}', $headers, $view);
$view = str_replace('{table_records}', $table_records, $view);
$view = str_replace('{delete_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Delete', $view);
$view = str_replace('{edit_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Edit', $view);
$view = str_replace('{view_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.View', $view);
$view = str_replace('{create_permission}', preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Create', $view);

echo $view;

unset($view, $headers);
