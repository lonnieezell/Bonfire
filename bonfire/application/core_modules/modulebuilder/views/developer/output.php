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

<div class="notification attention">
	<?php printf(lang('mb_out_roles'), anchor(SITE_AREA .'/settings/roles', 'Roles')) ;?>
</div>

<p class="important">
<?php
	if (!isset($error)) {
		echo $error;
	};
?> 
</p>

<?php if($build_config): ?>
<h4><?php echo lang('mb_out_config'); ?></h4>
<p><?php echo lang('mb_out_config_path'); ?></p>
<?php endif; ?>

<h4><?php echo lang('mb_out_controller'); ?></h4>
<p>
<?php
foreach($controllers as $controller_name => $val) {
	echo sprintf(lang('mb_out_controller_path'),$controller_name).'<br />';
}
?>
</p>

<?php if($model): ?>
<h4><?php echo lang('mb_out_model'); ?></h4>
<p><?php echo sprintf(lang('mb_out_model_path'),$module_name_lower); ?></p>
<?php endif; ?>

<?php if($lang): ?>
<h4><?php echo lang('mb_out_lang'); ?></h4>
<p><?php echo sprintf(lang('mb_out_lang_path'),$module_name_lower); ?></p>
<?php endif; ?>

<h4><?php echo lang('mb_out_view'); ?></h4>
<p>
<?php
foreach($views as $context_name => $context_views){
	foreach($context_views as $view_name => $val){
		echo sprintf(lang('mb_out_view_path'),( $context_name == $module_name_lower ? $view_name : $context_name."/".$view_name)).'<br />';
	}
}
?>
</p>

<?php if($migration): ?>
<h4><?php echo lang('mb_out_migration'); ?></h4>
<p><?php echo sprintf(lang('mb_out_migration_path'),$module_name_lower); ?></p>
</p>
<?php endif; ?>