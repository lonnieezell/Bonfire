<br/>
<?php if (isset($tables) && is_array($tables) && count($tables) > 0) : ?>
	<?php echo form_open('admin/developer/database/', array('style' => 'padding: 0')) ?>
	<table cellspacing="0">
		<thead>
			<tr>
				<th style="width: 2em">
					<input class="check-all" type="checkbox" />
				</th>
				<th>Table Name</th>
				<th style="width: 5.5em"># Records</th>
				<th>Data Size</th>
				<th>Index Size</th>
				<th>Data Free</th>
				<th>Engine</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					With selected: 
					
					<select name="action">
						<option>Backup</option>
						<option>Repair</option>
						<option>Optimize</option>
						<option>------</option>
						<option>Drop</option>
					</select> 
					&nbsp;&nbsp;
					<input type="submit" namve="submit" value="Apply" />
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($tables as $table) : ?>
			<tr>
				<td class="column-check">
					<input type="checkbox" value="<?php echo $table->Name ?>" name="checked[]" />
				</td>
				<td><?php echo $table->Name ?></td>
				<td style="text-align: center"><?php echo $table->Rows?></td>
				<td><?php echo byte_format($table->Data_length) ?></td>
				<td><?php echo byte_format($table->Index_length) ?></td>
				<td><?php echo byte_format($table->Data_free) ?></td>
				<td><?php echo $table->Engine ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo form_close(); ?>
	<?php else : ?>
		<div class="notification info">
			<p>No tables were found for the current database.</p>
		</div>
		
	<?php endif; ?>

<script>
head.ready(function(){
	// Attach our check all function
	$(".check-all").click(function(){
		$("table input[type=checkbox]").attr('checked', $(this).is(':checked'));
	});
});
</script>