<?php

$errorClass   = empty($errorClass) ? ' has-error' : $errorClass;
$labelClass = empty($labelClass) ? '' : $labelClass;
$wrapClass = empty($wrapClass) ? '' : $wrapClass;
$controlClass = empty($controlClass) ? 'form-control' : $controlClass;
$fieldData = array(
    'errorClass'    => $errorClass,
    'labelClass'  => $labelClass,
    'wrapClass'     => $wrapClass,
    'controlClass'  => $controlClass
);

?>
<style scoped='scoped'>
#register p.already-registered {
    text-align: center;
}
</style>
<section id="register">
    <h1 class="page-header"><?php echo lang('us_sign_up'); ?></h1>
    <?php if (validation_errors()) : ?>
    <div class="alert alert-danger fade in">
        <?php echo validation_errors(); ?>
    </div>
    <?php endif; ?>
    <div class="alert alert-info fade in">
        <h4 class="alert-heading"><?php echo lang('bf_required_note'); ?></h4>
        <?php
        if (isset($password_hints)) {
            echo $password_hints;
        }
        ?>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php echo form_open(site_url(REGISTER_URL), array('autocomplete' => 'off')); ?>
                <fieldset>
                    <?php Template::block('user_fields', 'user_fields', $fieldData); ?>
                </fieldset>
                <fieldset>
                    <?php
                    // Allow modules to render custom fields. No payload is passed
                    // since the user has not been created, yet.
                    Events::trigger('render_user_form');
                    ?>
                    <!-- Start of User Meta -->
                    <?php $this->load->view('users/user_meta', array('frontend_only' => true)); ?>
                    <!-- End of User Meta -->
                </fieldset>
                <fieldset>
                    <div class="form-group">
                                    <div class="<?php echo $wrapClass; ?>">
                            <input class="btn btn-primary" type="submit" name="register" id="submit" value="<?php echo lang('us_register'); ?>" />
                        </div>
                    </div>
                </fieldset>
            <?php echo form_close(); ?>
            <p class='already-registered'>
                <?php echo lang('us_already_registered'); ?>
                <?php echo anchor(LOGIN_URL, lang('bf_action_login')); ?>
            </p>
        </div>
    </div>
</section>