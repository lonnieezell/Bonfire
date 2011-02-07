<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<p class="small">Required fields are in <b>bold</b>.</p>

<?php if (isset($user) && $user->role_name == 'Banned') : ?>
<div class="notification attention">
	<p>This user has been banned from the site.</p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

	<div>
		<label>First Name</label>
		<input type="text" name="first_name" value="<?php echo isset($user) ? $user->first_name : set_value('first_name') ?>" />
	</div>

	<div>
		<label>Last Name</label>
		<input type="text" name="last_name" value="<?php echo isset($user) ? $user->last_name : set_value('last_name') ?>" />
	</div>
	
	<div>
		<label class="required">Email</label>
		<input type="text" name="email" class="medium" value="<?php echo isset($user) ? $user->email : set_value('email') ?>" />
	</div>
	
	<br />
	
	<div>
		<label class="required">Password</label>
		<input type="password" name="password" value="" />
	</div>
	<div>
		<label class="required">Password (again)</label>
		<input type="password" name="pass_confirm" value="" />
	</div>
	
	<fieldset>
		<legend>Role</legend>
		
		<div>
			<label>User Role</label>
			<select name="role_id">
			<?php if (isset($roles) && is_array($roles) && count($roles)) : ?>
				<?php foreach ($roles as $role) : ?>
				<option value="<?php echo $role->role_id ?>" <?php echo isset($user) && $user->role_id == $role->role_id ? 'selected="selected"' : '' ?> <?php echo !isset($user) && $role->default == 1 ? 'selected="selected"' : ''; ?>>
					<?php echo $role->role_name ?>
				</option>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Address</legend>
		
		<div>
			<label>Street 1</label>
			<input type="text" name="street_1" class="medium" value="<?php echo isset($user) ? $user->street_1 : set_value('street_1') ?>" />
		</div>
		<div>
			<label>Street 2</label>
			<input type="text" name="street_2" class="medium" value="<?php echo isset($user) ? $user->street_2 : set_value('street_2') ?>" />
		</div>
		<div>
			<label>City</label>
			<input type="text" name="city" value="<?php echo isset($user) ? $user->city : set_value('city') ?>" />
		</div>
		<div>
			<label>State</label>
			<?php echo state_select(isset($user) ? $user->state_id : 0, 'FL'); ?>
		</div>
		<div>
			<label>Zipcode</label>
			<input type="text" name="zipcode" size="7" maxlength="7" style="width: 6em; display: inline;" value="<?php echo isset($user) ? $user->zipcode : set_value('zipcode') ?>"  /> - 
			<input type="text" name="zip_extra" size="5" maxlength="5" style="width: 4em; display: inline;" value="<?php echo isset($user) && $user->zip_extra ? $user->zip_extra : set_value('zip_extra') ?>"  /> 
		</div>

	</fieldset>
	
	<div class="submits">
		<input type="submit" name="submit" value="Save User" /> or <?php echo anchor('admin/settings/users', 'Cancel'); ?>
	</div>

	<?php if (isset($user)) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url('admin/settings/users/delete/'. $user->id); ?>" onclick="return confirm('Are you sure you want to delete this user account?')">Delete this Account</a>
		
		<h3>Delete this Account</h3>
		
		<p>Deleting this account will revoke all of their privileges on the site.</p>
	</div>
	<?php endif; ?>

<?php echo form_close(); ?>