<div id="sub-nav">
	
	<?php echo form_open(current_url()); ?>
	
		<?php foreach ($test_names as $test) : ?>
			<input type="hidden" name="tests[]" value="<?php echo $test ?>" />
		<?php endforeach; ?>
	
		<a href="/admin/developer/tester">&laquo; Back</a> &nbsp;
		
		<input type="submit" name="submit" class="button" value="Rerun Tests" />
	
	<?php echo form_close(); ?>
</div>
