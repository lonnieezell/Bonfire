<?php echo $this->load->view('developer/sub_nav.php', null, true); ?>


<?php if ($log_threshold == 0) : ?>
<div class="notification attention">
	<p>Logging is not enabled for this application. Would you like to <?php echo anchor('admin/developer/logs/enable', 'enable logging?'); ?>?</p>
</div>
<?php endif; ?>

<p>Logging currently set to log: <b>
<?php 
	switch ($log_threshold)
	{
		case 0:
			echo 'Nothing';
			break;
		case 1:
			echo 'Only Errors (including PHP)';
			break;
		case 2:
			echo 'Debug and Error messages';
			break;
		case 3:
			echo 'Information, Debug and Error messages';
			break;
		case 4:
			echo 'All messages';
			break;
	}
?>
</b>.</p>


<?php if (isset($log_files) && is_array($log_files) && count($log_files) > 1) : ?>
<table cellspacing="0" class="constrained">
	<thead>
		<th style="width: 2em">
			<input type="checkbox" name="select_all" id="select_all" />
		</th>
		<th style="width: 8em">Date</th>
		<th>Filename</th>
	</thead>
	<tbody>
	<?php foreach ($log_files as $file) : ?>
		<?php if ($file != 'index.html') :?>
		<tr>
			<td>
				<input type="checkbox" name="actionable[]" value="<?php echo $file ?>" />
			</td>
			<td>
				<?php echo date('M d, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $file)))); ?>
			</td>
			<td><?php echo anchor('admin/developer/logs/view/'. str_replace('.php', '', $file), $file) ?></td>
		</tr>
		<?php endif; ?>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>

<div class="notification information">
	<p>There are currently no logs available.</p>
</div>

<?php endif; ?>




<script>
head.ready(function() {
	$('#select_all').click(function() {
		var is_checked = $(this).attr('checked');
	
		$('#action_form input[type=checkbox]').attr('checked', is_checked);
	});
});
</script>
