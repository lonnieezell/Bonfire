<div class="container">
    <div class="row">
        <div class="col-xs-9 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">
            <div class="well well-sm">
                <?php echo form_open($this->uri->uri_string(), array('class' => "form-horizontal", 'autocomplete' => 'off')); ?>
                    <fieldset>
                        <legend class="text-center"><?php echo lang('us_reset_password'); ?></legend>

                        <?php if (validation_errors()) : ?>
                        <div class="alert alert-danger fade in">
                            <?php echo validation_errors(); ?>
                        </div>
                        <?php endif; ?>

                        <div class="alert alert-info fade in">
                            <?php echo lang('us_reset_note'); ?>
                        </div>

                        <!-- Email input -->
                        <div class="form-group<?php echo iif(form_error('email') , ' error'); ?>">
                            <label class="col-sm-3 control-label required" for="email"><?php echo lang('bf_email'); ?></label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" />
                            </div>
                        </div>
                        <!-- Form actions -->
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <input class="btn btn-primary btn-lg" type="submit" name="send" value="<?php e(lang('us_send_password')); ?>" />
                            </div>
                        </div>
                    </fieldset>
                <?php echo form_close(); ?>
            </div>
        </div>
	</div>
</div>