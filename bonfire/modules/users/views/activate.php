<div class="page-header">
	<h1><?php echo lang('us_activate'); ?></h1>
</div>

<?php if (validation_errors()) { ?>
<div class="row-fluid">
	<div class="span8 offset2">
		<div class="alert alert-error fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo validation_errors(); ?>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="row-fluid">
	<div class="span8 offset2">
		<div class="well shallow-well">
			<?php echo lang('us_user_activate_note'); ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="row-fluid">
	<div class="span8 offset2">

	<?php echo form_open($this->uri->uri_string(), array('class' => "form-horizontal", 'autocomplete' => 'off')); ?>

	<div class="control-group <?php echo iif( form_error('code') , 'error') ;?>">
		<label class="control-label required" for="code"><?php echo lang('us_activate_code'); ?></label>
		<div class="controls">
			<input class="span6" type="text" id="code" name="code" value="<?php echo set_value('code') ?>" />
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="activate" value="<?php echo lang('us_confirm_activate_code') ?>"  />
		</div>
	</div>

	<?php echo form_close(); ?>

	</div>
</div>
