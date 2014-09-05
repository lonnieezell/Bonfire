<?php

if ( ! function_exists('relative_time')) {
    $this->load->helper('date');
}

?>
<h3><?php echo lang('us_access_logs'); ?></h3>
<?php if ( ! empty($activities) && is_array($activities)) : ?>
<ul class="clean">
	<?php
    // Determine which field is displayed for the user's identity.
    $identityField = $this->settings_lib->item('auth.login_type') == 'email' ? 'email' : 'username';
    foreach ($activities as $activity) :
    ?>
    <li>
        <span class="small"><?php echo relative_time(strtotime($activity->created_on)); ?></span><br/>
        <strong><?php e($activity->{$identityField}); ?></strong> <?php echo $activity->activity; ?>
    </li>
	<?php endforeach; ?>
</ul>
<?php
else :
    echo lang('us_no_access_message');
endif;