<div class="box create rounded">
	<a class="button good" href="/admin/'.$controller_name.'/'.$module_name_lower.'/insert">Create New <?php echo $module_name ?></a>

	<h3>Create A New <?php echo $module_name ?></h3>

	<p>Every user needs a <?php echo $module_name ?>. Make sure you have all that you need.</p>
</div>
<div>
	<h2></h2>
	<table>
		<thead>
		<th>ID</th>
		
		<?php for ($counter=1; $field_total >= $counter; $counter++) :?>
			<?php 
			if (set_value("view_field_label". $counter) == null) 
			{
				continue;
			}
			?>
			<th><?php echo set_value("view_field_label$counter") ?></th>
		<?php endfor; ?>
		
		<th>Actions</th>
	</thead>
	<tbody>
	<?php foreach ($records as $row) : ?>
		<tr>
		<?php foreach ($row as $field => $value) : ?>
			<td><?php echo $value; ?></td>
		<?php endforeach; ?>
			<td>
				<a href="/admin/<?php echo $controller_name .'/'. $module_name_lower ?>/edit/<?php echo $row['id'] ?>">Edit</a> | 
				<a href="/admin/<?php echo $controller_name .'/'. $module_name_lower ?>/delete/<?php echo $row['id'] ?>">Update</a> 
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
