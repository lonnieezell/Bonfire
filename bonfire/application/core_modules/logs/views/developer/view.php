<h2><span style="font-weight: normal">Viewing:</span> <?php echo str_replace(EXT, '', $log_file) ?></h2>

<?php if (!isset($log_content) || empty($log_content)) : ?>
<div class="notification attention">
	<p>Either the log file could not be located, or it was empty.</p>
</div>
<?php else : ?>

	<p>View &nbsp;&nbsp;
		<select id="filter">
			<option value="all">All entries</option>
			<option value="error">Errors only</option>
		</select>
	</p>

	<div id="log">
		<?php foreach ($log_content as $row) : ?>
		<?php 
			$class = '';
			
			if (strpos($row, 'ERROR') !== false)
			{
				$class="error";
			} else
			if (strpos($row, 'DEBUG') !== false)
			{
				$class="attention";
			}		
		?>
		<div style="border-bottom: 1px solid #999; padding: 5px 18px; color: #222;" <?php echo 'class="'. $class .'"' ?>>
			<?php echo $row; ?>
		</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>

<script>
	// Filter Hook
	$('#filter').change(function(){
		// Are we filtering at all? 
		var filter = $(this).val();
	
		$('#log div').each(function(){
		
			switch (filter)
			{
				case 'all':
					$(this).css('display', 'block');
					break;
				case 'error':
					if ($(this).hasClass('error'))
					{
						$(this).css('display', 'block');
					} else
					{
						$(this).css('display', 'none');
					}
			}
		});
	});
</script>