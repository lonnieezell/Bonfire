<div class="page-header">
	<h1><?php echo lang('us_reset_password'); ?></h1>
</div>

<?php if (validation_errors()) : ?>
	<div class="alert alert-danger fade in">
		<?php echo validation_errors(); ?>
	</div>
<?php endif; ?>

<div class="alert alert-info fade in">
	<?php echo lang('us_reset_note'); ?>
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-9 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">

<?php echo form_open($this->uri->uri_string(), array('class' => "form-vertical", 'autocomplete' => 'off')); ?>

	<div class="form-group <?php echo iif( form_error('email') , 'error'); ?>">
		<label class="control-label required" for="email"><?php echo lang('bf_email'); ?></label>
		<div class="controls col-sm-12">
			<input class="form-control" type="text" name="email" id="email" value="<?php echo set_value('email') ?>" />
		</div>
	</div>

	<input class="btn btn-primary" type="submit" name="send" value="<?php e(lang('us_send_password')); ?>" />
	
	<?php echo form_close(); ?>
            </div>
         </div>
	</div>
</div>
