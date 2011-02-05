<h3>Failed Login Attempts</h3>
						
<?php if (isset($login_attempts) && is_array($login_attempts) && count($login_attempts)) : ?>

	<ol>
	<?php foreach ($login_attempts as $attempt) : ?>
		<li><b><?php echo $attempt->login; ?></b> on <?php echo date('j-m-Y H:i:s',strtotime($attempt->time)) ?></li>
	<?php endforeach; ?>
	</ol>

<?php else : ?>
	<p>Congratulations!</p>
	
	<p>All of your users have good memories!</p>
<?php endif; ?>