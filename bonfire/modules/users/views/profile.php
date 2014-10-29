<?php

$validation_errors = validation_errors();
$errorClass = ' error';
$controlClass = 'span6';
$fieldData = array(
    'errorClass'    => $errorClass,
    'controlClass'  => $controlClass,
);

?>
<section id="profile">
	<h1 class="page-header"><?php echo lang('us_edit_profile'); ?></h1>
    <?php if ($validation_errors) : ?>
    <div class="alert alert-error">
        <?php echo $validation_errors; ?>
    </div>
    <?php endif; ?>
    <?php if (isset($user) && $user->role_name == 'Banned') : ?>
    <div data-dismiss="alert" class="alert alert-error">
        <?php echo lang('us_banned_admin_note'); ?>
    </div>
    <?php endif; ?>
    <div class="alert alert-info">
        <h4 class="alert-heading"><?php echo lang('bf_required_note'); ?></h4>
        <?php if (isset($password_hints)) { echo $password_hints; } ?>
    </div>
    <div class="row-fluid">
    	<div class="span12">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
                <?php Template::block('user_fields', 'user_fields', $fieldData); ?>
                <?php
                // Allow modules to render custom fields
                Events::trigger('render_user_form', $user );
                ?>
                <!-- Start User Meta -->
                <?php $this->load->view('users/user_meta', array('frontend_only' => true));?>
                <!-- End of User Meta -->
                <div class="form-actions">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save') . ' ' . lang('bf_user'); ?>" />
                    <?php echo lang('bf_or') . ' ' . anchor('/', lang('bf_action_cancel')); ?>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>