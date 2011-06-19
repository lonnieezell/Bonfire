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

<?php if($lang): ?>
	<h4>Language file</h4>
	<p><?php echo $module_name_lower;?>_lang.php</p>
<?php endif; ?>

	<h4>View files</h4>
	<p>
	<?php foreach($views as $context_name => $context_views): ?>
		<?php  foreach($context_views as $view_name => $val): ?>
		views/<?php echo $context_name == $module_name_lower ? $view_name : $context_name."/".$view_name;?>.php<br />
		<?php endforeach; ?>
	<?php endforeach; ?>
	</p>

<?php if($migration): ?>
	<h4>Migration file</h4>
	<p>migrations/001_Install_<?php echo $module_name_lower;?>.php</p>
<?php endif; ?>
