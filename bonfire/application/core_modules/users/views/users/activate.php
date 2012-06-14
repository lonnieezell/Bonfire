<div class="page-header">
	<h1><?php echo lang('us_activate'); ?></h1>
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
			<?php echo lang('us_user_activate_note'); ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="row-fluid">
	<div class="span8 offset2">

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<div class="control-group <?php echo iif( form_error('code') , 'error') ;?>">
		<?php echo form_simple_label('code', lang('us_activate_code'), TRUE); ?>
		<div class="controls">
			<input class="span6" type="text" id="code" name="code" value="<?php echo set_value('code') ?>" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="submit">&nbsp;</label>
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="submit" value="<?php echo lang('us_confirm_activate_code') ?>"  />
		</div>
	</div>

	<?php echo form_close(); ?>

	</div>
</div>
