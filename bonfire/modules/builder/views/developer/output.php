<?php

$cur_url = uri_string();
$tot = $this->uri->total_segments();
$last_seg = $this->uri->segment( $tot);

if (is_numeric($last_seg))
{
	$cur_url = str_replace('/' . $last_seg, '', $cur_url);
}

// $error should never be non-empty on this page...
if (empty($error)) :
?>
<div class="alert alert-success">
	<button type='button' class='close' data-dismiss='alert'>&times;</button>
	<p><?php echo lang('mb_out_success'); ?></p>
	<p><b><?php echo lang('mb_out_success_note'); ?></b></p>
</div>
<?php
else : ?>
<div class="alert alert-error">
	<button type='button' class='close' data-dismiss='alert'>&times;</button>
	<p><?php echo $error; ?></p>
</div>
<?php
endif;
?>
<div class="alert">
	<button type='button' class='close' data-dismiss='alert'>&times;</button>
	<?php printf(lang($mb_migration_result), anchor(SITE_AREA .'/developer/migrations#mod-tab', 'Migrations')) ;?>
</div>
<?php
if ($acl_migration) : ?>
<h4><?php echo lang('mb_out_acl'); ?></h4>
<p><?php echo sprintf(lang('mb_out_acl_path'), $module_name_lower); ?></p>
<?php
endif;

if ($build_config) : ?>
<h4><?php echo lang('mb_out_config'); ?></h4>
<p><?php echo lang('mb_out_config_path'); ?></p>
<?php
endif;
?>
<h4><?php echo lang('mb_out_controller'); ?></h4>
<p>
<?php
foreach ($controllers as $controller_name => $val)
{
	echo sprintf(lang('mb_out_controller_path'), $controller_name) . '<br />';
}
?>
</p>
<?php if ($lang) : ?>
<h4><?php echo lang('mb_out_lang'); ?></h4>
<p><?php echo sprintf(lang('mb_out_lang_path'), $module_name_lower); ?></p>
<?php
endif;

if ($db_migration) : ?>
<h4><?php echo lang('mb_out_migration'); ?></h4>
<p><?php echo sprintf(lang('mb_out_migration_path'),$db_table); ?></p>
<?php
endif;

if ($model) : ?>
<h4><?php echo lang('mb_out_model'); ?></h4>
<p><?php echo sprintf(lang('mb_out_model_path'), $module_name_lower); ?></p>
<?php
endif;
?>
<h4><?php echo lang('mb_out_view'); ?></h4>
<p>
<?php
foreach ($views as $context_name => $context_views)
{
	foreach ($context_views as $view_name => $val)
	{
		echo sprintf(lang('mb_out_view_path'), ( $context_name == $module_name_lower ? $view_name : $context_name."/".$view_name)) . '<br />';
	}
}
?>
</p>