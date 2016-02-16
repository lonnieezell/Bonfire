<div class="page-header">
	<h1><?php echo lang('us_activate'); ?></h1>
</div>

<?php if (validation_errors()) { ?>
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="alert alert-danger fade in">
		  <a data-dismiss="alert" class="close">&times;</a>
			<?php echo validation_errors(); ?>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="well shallow-well">
			<?php echo lang('us_user_activate_note'); ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="row">
	<div class="col-sm-8 col-sm-offset-2">

        <?php echo form_open($this->uri->uri_string(), array('autocomplete' => 'off')); ?>

        <div class="form-group <?php echo iif(form_error('code'), 'has-error'); ?>">
            <label class="required" for="code"><?php echo lang('us_activate_code'); ?></label>
            <input class="form-control" type="text" id="code" name="code" value="<?php echo set_value('code') ?>"/>
        </div>

        <div class="form-group">
            <input class="btn btn-primary" type="submit" name="activate"
                   value="<?php echo lang('us_confirm_activate_code') ?>"/>
        </div>

	<?php echo form_close(); ?>

	</div>
</div>
