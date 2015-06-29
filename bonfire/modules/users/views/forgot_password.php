<?php
$labelClass = 'control-label';
$wrapClass = 'controls';
$controlClass = 'form-control';
?>
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
        <div class="col-sm-8 col-md-6 col-lg-5 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">

<?php echo form_open($this->uri->uri_string(), array('class' => "form-vertical", 'autocomplete' => 'off')); ?>

	<div class="form-group <?php echo iif( form_error('email') , 'error'); ?>">
		<label class="<?php echo $labelClass; ?> required" for="email"><?php echo lang('bf_email'); ?></label>
		<div class="<?php echo $wrapClass; ?>">
			<input class="<?php echo $controlClass; ?>" type="text" name="email" id="email" value="<?php echo set_value('email') ?>" />
		</div>
	</div>

	<div class="form-group">
        <div class="<?php echo $wrapClass; ?>">
           	<input class="btn btn-primary" type="submit" name="send" value="<?php e(lang('us_send_password')); ?>" />
	    </div>
    </div>
	<?php echo form_close(); ?>
            </div>
         </div>
	</div>
</div>
