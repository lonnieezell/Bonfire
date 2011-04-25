<h3><?php echo lang('us_failed_login_attempts'); ?></h3>
						
<?php if (isset($login_attempts) && is_array($login_attempts) && count($login_attempts)) : ?>

	<ol>
	<?php foreach ($login_attempts as $attempt) : ?>
		<li><b><?php echo $attempt->login; ?></b> on <?php echo date('j-m-Y H:i:s',strtotime($attempt->time)) ?></li>
	<?php endforeach; ?>
	</ol>

<?php else : ?>
	<?php echo lang('us_failed_logins_note'); ?>
<?php endif; ?>