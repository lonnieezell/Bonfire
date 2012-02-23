<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$view =<<<END
<div class="box create rounded">

	<a class="button good" href="<?php echo site_url(SITE_AREA .'/{$controller_name}/{$module_name_lower}/create'); ?>">
		<?php echo lang('{$module_name_lower}_create_new_button'); ?>
	</a>

	<h3><?php echo lang('{$module_name_lower}_create_new'); ?></h3>

	<p><?php echo lang('{$module_name_lower}_edit_text'); ?></p>

</div>

<br />

<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
				
	<h2>{$module_name}</h2>
	<table>
		<thead>
			<tr>
			{table_header}
		
			<th><?php echo lang('{$module_name_lower}_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		
		<?php foreach (\$records as \$record) : ?>
			<tr>
				{table_records}
				<td><?php echo anchor(SITE_AREA .'/{$controller_name}/{$module_name_lower}/edit/'. \$record->{$primary_key_field}, lang('{$module_name_lower}_edit'), '') ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
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
for($counter=1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered. 

	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_name$counter") == NULL || set_value("view_field_name$counter") == $primary_key_field)
	{
		continue; 	// move onto next iteration of the loop
	}
	$table_records .= '
				<td><?php echo $record->'.$module_name_lower.'_'.set_value("view_field_name$counter").'?></td>';
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



$view = str_replace('{table_header}', $headers, $view);
$view = str_replace('{table_records}', $table_records, $view);

echo $view;

unset($view, $headers);