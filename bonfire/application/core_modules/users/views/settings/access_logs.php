<h3>Access Logs</h3>
						
<?php if (isset($access_logs) && is_array($access_logs) && count($access_logs)) : ?>

	<ol>
	<?php foreach ($access_logs as $log) : ?>
		<li><b><?php echo $log->email ?></b> logged in on <?php echo date('j-m-Y H:i:s',strtotime($log->last_login)) ?></li>
	<?php endforeach; ?>
	</ol>
<?php else : ?>
	<p>Congratulations!</p>
	
	<p>All of your users have good memories!</p>
<?php endif; ?>