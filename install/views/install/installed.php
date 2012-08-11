<?php $this->load->view('header'); ?>

<div class="notification">
	<?php echo lang('in_installed') . ' <a href="'. str_replace('install/', '', site_url()) .'">'. lang('continue') .'</a>.'; ?>
</div>

<p><?php echo lang('in_rename_msg'); ?> <a href="<?php echo site_url('rename') ?>"><?php echo lang('click') ?></a></p>

<?php $this->load->view('footer'); ?>