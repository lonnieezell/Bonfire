<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$view =<<<END
<div>
	<h1 class="page-header"><?php echo \$toolbar_title; ?></h1>
</div>

<br />

<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
				
	<table class="table table-striped table-bordered">
		<thead>
		
			{table_header}
		
		</thead>
		<tbody>
		
		<?php foreach (\$records as \$record) : ?>
			<?php \$record = (array)\$record;?>
			<tr>
			<?php foreach(\$record as \$field => \$value) : ?>
				
				<?php if (\$field != '{$primary_key_field}') : ?>
					<td><?php echo (\$field == 'deleted') ? ((\$value > 0) ? lang('{$module_name_lower}_true') : lang('{$module_name_lower}_false')) : \$value; ?></td>
				<?php endif; ?>
				
			<?php endforeach; ?>

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
		<th><?php echo lang(\''. $module_name_lower . '_field_' . set_value("view_field_name$counter").'\'); ?></th>';
}

if ($use_soft_deletes == 'true')
{
	$headers .= '
					<th><?php echo lang(\''. $module_name_lower . '_field_deleted\'); ?></th>';
}
if ($use_created == 'true')
{
	$headers .= '
					<th><?php echo lang(\''. $module_name_lower . '_field_created\'); ?></th>';
}
if ($use_modified == 'true')
{
	$headers .= '
					<th><?php echo lang(\''. $module_name_lower . '_field_modified\'); ?></th>';
}

$view = str_replace('{table_header}', $headers, $view);

echo $view;

unset($view, $headers);