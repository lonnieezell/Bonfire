<h3><?php echo lang('us_access_logs'); ?></h3>
						
<?php if (isset($access_logs) && is_array($access_logs) && count($access_logs)) : ?>

	<ol>
	<?php foreach ($access_logs as $log) : ?>
		<li><b><?php echo $log->email ?></b> <?php echo lang('us_logged_in_on'); ?> <?php echo date('j-m-Y H:i:s',strtotime($log->last_login)) ?></li>
	<?php endforeach; ?>
	</ol>
<?php else : ?>
	<?php echo lang('us_no_access_message'); ?>
<?php endif; ?>