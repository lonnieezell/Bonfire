<br/>
<p>Tests can be run for either a single module, multiple modules, or all tests in the application. Select the module(s) to run below.</p>

<?php if (isset($test_map) && is_array($test_map) && count($test_map)) : ?>

	<?php echo form_open(SITE_AREA .'/developer/tester/run'); ?>
	
		<div class="fancy-text">
			<label>Available Modules</label>
		</div>
	
		<select name="tests[]" multiple="multiple" class="multiple-select" size="15">
		<?php foreach ($test_map as $module => $tests) :?>
			<option value="<?php echo $module ?>"><?php echo ucwords(str_replace('_', ' ', $module)) ?></option>
		<?php endforeach; ?>
		</select>
		
		<div class="submits">
			<input type="submit" name="submit" class="button" value="Run Tests" />
		</div>
	
	<?php echo form_close(); ?>

<?php else : ?>
	
	<div class="notification information">
		<p>No tests were found for any modules. To add tests, you must put them in a tests folder in your module.</p>
	</div>

<?php endif; ?>