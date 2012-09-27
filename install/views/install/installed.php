<?php $this->load->view('header'); ?>

<div class="notification">
	<?php echo lang('in_installed') . ' <a href="'. preg_replace('{install/$}', '', base_url()) .'">'. lang('continue') .'</a>.'; ?>
</div>

<form action="<?php echo site_url('rename') ?>" method="post" class="form-inline">
    <label for="rename"><?php echo lang('in_rename_msg'); ?> <input type="submit" id="rename" value="<?php echo lang('click') ?>"></label>
</form>

<?php $this->load->view('footer'); ?>