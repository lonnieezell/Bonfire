<?php

$view = '
<div class="box create rounded">
	<a class="button good" href="/admin/'.$controller_name.'/'.$module_name_lower.'/insert">Create New '.$module_name.'</a>

	<h3>Create A New '.$module_name.'</h3>

	<p>Every user needs a '.$module_name.'. Make sure you have all that you need.</p>
</div>
<div>
	<h2></h2>
	<table>
		<thead>
		<th>ID</th>';

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

$view .= '<th>Actions</th>
		</thead>
		<tbody>
<?php
foreach ($records_array as $row)
{
	$last_field = 0;
	?>
			<tr>
<?php
	foreach($row as $field => $value)
	{
?>
				<td><?php echo $value;?></td>

<?php
	}
?>
				<td><a href="/admin/'.$controller_name.'/'.$module_name_lower.'/update/<?php echo $row["id"];?>">Update</a> | <a href="/admin/'.$controller_name.'/'.$module_name_lower.'/delete/<?php echo $row["id"];?>">Delete</a></td>
			</tr>
<?php
}
?>
		</tbody>
	</table>
</div>';

	echo $view;
?>