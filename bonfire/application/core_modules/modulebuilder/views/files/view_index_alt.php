<?php

$view = '
			<div class="box create rounded">
				<a class="button good" href="<?php echo site_url(\'/admin/'.$controller_name.'/'.$module_name_lower.'/create\')?>"><?php echo lang(\''.$module_name_lower.'_create_new_button\');?></a>

				<h3><?php echo lang(\''.$module_name_lower.'_create_new\');?></h3>

				<p><?php echo lang(\''.$module_name_lower.'_edit_text\'); ?></p>
			</div>
			<br />
				<?php if (isset($records) && is_array($records) && count($records)) : ?>
				
					<h2>'.$module_name.'</h2>
	<table>
		<thead>';

for($counter=1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered. 

	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_label$counter") == NULL)
	{
		continue; 	// move onto next iteration of the loop
	}
	$view .= '
		<th>'. set_value("view_field_label$counter").'</th>';
}

$view .= '<th><?php echo lang(\''.$module_name_lower.'_actions\'); ?></th>
		</thead>
		<tbody>
<?php
foreach ($records as $record) : ?>
<?php $record = (array)$record;?>
			<tr>
<?php
	foreach($record as $field => $value)
	{
		if($field != "'.$primary_key_field.'") {
?>
				<td><?php echo $value;?></td>

<?php
		}
	}
?>
				<td><?php echo anchor(\'admin/'.$controller_name.'/'.$module_name_lower.'/edit/\'. $record[\''.$primary_key_field.'\'], \'Edit\', \'\') ?></td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
				<?php endif; ?>
';

	echo $view;
?>