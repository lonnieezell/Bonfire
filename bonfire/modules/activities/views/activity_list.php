<?php

if ( ! function_exists('relative_time')) {
    $this->load->helper('date');
}

?>
<h3><?php echo lang('us_access_logs'); ?></h3>
<?php if (isset($activities) && is_array($activities) && count($activities)) : ?>
<ul class="clean">
	<?php
    foreach ($activities as $activity) :
        $identity = $this->settings_lib->item('auth.login_type') == 'email' ? $activity->email : $activity->username;
    ?>
    <li>
        <span class="small"><?php echo relative_time(strtotime($activity->created_on)); ?></span><br/>
        <strong><?php e($identity); ?></strong> <?php echo $activity->activity; ?>
    </li>
	<?php endforeach; ?>
</ul>
<?php else :
    echo lang('us_no_access_message');
endif;
?>