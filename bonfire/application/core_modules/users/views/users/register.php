<section id="register">
	<div class="page-header">
		<h1><?php echo lang('us_login'); ?></h1>
	</div>

<?php if (auth_errors() || validation_errors()) : ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="alert alert-error fade in">
						<a data-dismiss="alert" class="close">&times;</a>
					<?php echo auth_errors() . validation_errors(); ?>
				</div>
			</div>
		</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span10 offset2">
		<div class="alert alert-info fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<h4 class="alert-heading"><?php echo lang('bf_required_note'); ?></h4>
			<?php if (isset($password_hints) ) echo $password_hints; ?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">

<?php echo form_open('register', 'class="form-horizontal"'); ?>


	<div class="control-group <?php echo iif( form_error('email') , 'error'); ?>">
		<label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>
		<div class="controls">
		 <input class="span6" type="text" name="email" id="email"  value="<?php echo set_value('email'); ?>"  placeholder="email" />
		</div>
	</div>

	<div class="control-group <?php echo iif( form_error('display_name') , 'error') ;?>">
		<label class="control-label" for="display_name"><?php echo lang('bf_display_name'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="display_name" value="<?php echo set_value('display_name'); ?>" />
		</div>
	</div>

	<?php if ( $this->settings_lib->item('auth.login_type') !== 'email' OR $this->settings_lib->item('auth.use_usernames') == 1): ?>

	<div class="control-group <?php echo iif( form_error('username') , 'error'); ?>">
		<label class="control-label required" for="username"><?php echo lang('bf_username'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="username" id="username" value="<?php echo set_value('username') ?>" placeholder="username" />
		</div>
	</div>

	<?php endif; ?>
	<br/>

		<div class="control-group <?php echo iif( form_error('password') , 'error'); ?>">
			<label class="control-label required" for="password"><?php echo lang('bf_password'); ?></label>
			<div class="controls">
				<input class="span6" type="password" name="password" id="password" value="" placeholder="password" />
				<p class="help-block"><?php echo lang('us_password_mins'); ?></p>
			</div>
		</div>

		<div class="control-group <?php echo iif( form_error('pass_confirm') , 'error'); ?>">
			<label class="control-label required" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
			<div class="controls">
				<input class="span6" type="password" name="pass_confirm" id="pass_confirm" value="" placeholder="<?php echo lang('bf_password_confirm'); ?>" />
			</div>
		</div>

		<div class="control-group <?php echo form_error('language') ? 'error' : '' ?>">
			<label class="control-label required" for="language"><?php echo lang('bf_language') ?></label>
			<div class="controls">
				<select name="language" id="language" class="chzn-select">
				<?php if (isset($languages) && is_array($languages) && count($languages)) : ?>
					<?php foreach ($languages as $language) : ?>
						<option value="<?php echo $language ?>" <?php echo set_select('language', $language, config_item('language') == $language ? TRUE : FALSE) ?>>
							<?php echo ucfirst($language) ?>
						</option>

					<?php endforeach; ?>
				<?php endif; ?>
				</select>
				<?php if (form_error('language')) echo '<span class="help-inline">'. form_error('language') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('timezone') ? 'error' : '' ?>">
			<label class="control-label required" for="timezones"><?php echo lang('bf_timezone') ?></label>
			<div class="controls">
				<?php echo timezone_menu(set_value('timezones')); ?>
				<?php if (form_error('timezones')) echo '<span class="help-inline">'. form_error('timezones') .'</span>'; ?>
			</div>
		</div>

		<?php
			// Allow modules to render custom fields
			Events::trigger('render_user_form');
		?>

		<!-- Start of User Meta -->
		<?php
			foreach ($meta_fields as $field):

			if (!(isset($field['frontend']) && $field['frontend'] === FALSE)):

				if ($field['form_detail']['type'] == 'dropdown'):

					echo form_dropdown($field['form_detail']['settings'], $field['form_detail']['options'], set_value($field['name']), $field['label']);


				elseif ($field['form_detail']['type'] == 'state_select') : ?>

				<div class="control-group <?php echo iif( form_error($field['name']) , 'error'); ?>">
					<label class="control-label" for="<?= $field['name'] ?>"><?php echo lang('user_meta_state'); ?></label>
					<div class="controls">

					<?php echo state_select(set_value($field['name']), 'SC', 'US', $field['name'], 'span6 chzn-select'); ?>

					</div>
				</div>

				<?php elseif ($field['form_detail']['type'] == 'country_select') : ?>

				<div class="control-group <?php echo iif( form_error('country') , 'error'); ?>">
					<label class="control-label" for="country"><?php echo lang('user_meta_country'); ?></label>
					<div class="controls">

					<?php echo country_select(set_value($field['name']), 'US', 'country', 'span6 chzn-select'); ?>

					</div>
				</div>

				<?php else:


					$form_method = 'form_' . $field['form_detail']['type'];
					echo $form_method($field['form_detail']['settings'], set_value($field['name']), $field['label']);

				endif;
			endif;

			endforeach;
		?>

	<!-- End of User Meta -->

	<div class="control-group">
		<label class="control-label" for="submit">&nbsp;</label>
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" id="submit" value="<?php echo lang('us_register'); ?>"  />
		</div>
	</div>

<?php echo form_close(); ?>

<p style="text-align: center">
	<?php echo lang('us_already_registered'); ?> <?php echo anchor('/login', lang('bf_action_login')); ?>
</p>

	</div>
</div>
</section>