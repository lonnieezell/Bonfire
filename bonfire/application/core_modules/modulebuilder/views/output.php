<?php
	$cur_url = uri_string();
	$tot = $this->uri->total_segments();
	$last_seg = $this->uri->segment( $tot);

	if( is_numeric($last_seg) ) {
		$cur_url = str_replace('/'.$last_seg, '', $cur_url);
	}
?>
	<p>Below you will find the code for the Controller and View files. Model and SQL files will be included if you selected the DB Required option and a Javascript file if you so required.</p>

	<p><b>NOTE: Please add extra user input validation as you require.  This code is to be used as a starting point only.</b></p>

<p class="important">
<?php if (!isset($error)): ?>
MVC+SQL as .zip - <a href="/<?php echo $cur_url."/download/{$module_name}"?>">Download</a>
<?php else: // user isn't given the option to downoad the files if they were not successfully written to disk ?>
<?php echo $error?>
<?php endif; ?> 
</p>

	<h4>Controller files</h4>
	<p>
	<?php  foreach($controllers as $controller_name => $val): ?>
	controllers/<?php echo $controller_name;?>.php<br />
	<?php endforeach; ?>
	</p>

<?php if($model): ?>
	<h4>Model file</h4>
	<p><?php echo $module_name_lower;?>_model.php</p>
<?php endif; ?>

	<h4>View files</h4>
	<p>
	<?php foreach($views as $context_name => $context_views): ?>
		<?php  foreach($context_views as $view_name => $val): ?>
		views/<?php echo $context_name."/".$context_name."_".$view_name;?>.php<br />
		<?php endforeach; ?>
	<?php endforeach; ?>
	</p>

<?php if($javascript): ?>
	<h4>Javascript file</h4>
	<p><?php echo $module_name_lower;?>.js</p>
<?php endif; ?>

<?php if($sql): ?>
	<h4>SQL file</h4>
	<p><?php echo $module_name_lower;?>.sql</p>
<?php endif; ?>
