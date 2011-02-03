<div class="notification attention">
	<p>Performing migrations <b>WILL</b> change your database structure, possibly ending in disaster. If you are not comfortable with your migrations, please verify them before continuing.</p>
</div>

<!-- Migration Confirmation -->
<h2>Migrate database to version <?php echo $latest_version ?>?</h2>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>

	<p>
		Or choose another migration: 
		<select name="migration">
		<?php foreach ($migrations as $migration) :?>
			<option value="<?php echo (int)substr($migration, 0, 3) ?>" <?php echo ((int)substr($migration, 0, 3) == $this->uri->segment(5)) ? 'selected="selected"' : '' ?>><?php echo $migration ?></option>
		<?php endforeach; ?>
		</select>
	</p>

	<div class="submits">
		<input type="submit" name="submit" value="Migrate Database" /> or <?php echo anchor('admin/developer/migrations', 'Cancel'); ?>
	</div>
<?php echo form_close(); ?>