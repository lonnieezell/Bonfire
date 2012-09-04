<div class="page-header">
	<h1><?php echo lang('us_activate_resend'); ?></h1>
</div>

<?php if (auth_errors() || validation_errors()) { ?>
<div class="row-fluid">
	<div class="span8 offset2">
		<div class="alert alert-error fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo auth_errors() . validation_errors(); ?>
		</div>
	</div>
</div>
<?php } else { ?>

<div class="row-fluid">
	<div class="span8 offset2">
		<div class="well shallow-well">
			<?php echo lang('us_activate_resend_note'); ?>
		</div>
	</div>
</div>
<?php } ?>
<div class="row-fluid">
	<div class="span8 offset2">

<?php echo form_open($this->uri->uri_string(), array('class' => "form-horizontal", 'autocomplete' => 'off')); ?>

	<div class="control-group <?php echo iif( form_error('email') , 'error') ;?>">
		<label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>
		<div class="controls">
			<input class="span6" type="text" name="email" id="email" value="<?php echo set_value('email') ?>" />
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" value="<?php echo lang('us_activate_code_send') ?>"  />
		</div>
	</div>

<?php echo form_close(); ?>

	</div>
</div>
