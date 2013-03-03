<?php $this->load->view('header'); ?>

<div class="notification">
	<?php echo lang('in_installed') . ' <a href="'. installed_url() .'">'. lang('in_continue') .'</a>.'; ?>
</div>

<form action="<?php echo site_url('rename') ?>" method="post" class="form-inline">
    <label for="rename"><?php echo lang('in_rename_msg'); ?> <input type="submit" id="rename" value="<?php echo lang('in_click') ?>"></label>
</form>

<?php $this->load->view('footer'); ?>