<?php
	$cur_url = uri_string();
	$tot = $this->uri->total_segments();
	$last_seg = $this->uri->segment( $tot);

	if( is_numeric($last_seg) ) {
		$cur_url = str_replace('/'.$last_seg, '', $cur_url);
	}
?>
<div class="notification success">
	<p><?php echo lang('mb_out_success'); ?></p>
	<br />
	<p><b><?php echo lang('mb_out_success_note'); ?></b></p>
</div>

<?php if($migration): ?>
<div class="notification information">
	The database tables are <strong>NOT</strong> automatically installed for you. You still need to go to the <?php echo anchor(SITE_AREA .'/developer/migrations#mod-tab', 'Migrations') ?> section and migrate your database table(s) before you can work with them.
</div>
<?php endif; ?>

<div class="notification attention">
	<p><b>You <em>MUST</em> go to the <?php echo anchor(SITE_AREA .'/settings/roles', 'Roles') ?> area and assign permissions for your new module to the roles before you can access any of the pages.</b></p>
</div>

<p class="important">
<?php if (!isset($error)): ?>
<?php echo $error?>
<?php endif; ?> 
</p>

<?php if($build_config): ?>
<h4>Config file</h4>
<p>config/config.php</p>
<?php endif; ?>

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
<p>migrations/001_Install_<?php echo $module_name_lower;?>.php
</p>
<?php endif; ?>