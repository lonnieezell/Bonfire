<div class="subnav rounded-5">
	<a href="<?php echo site_url(SITE_AREA .'/settings/users') ?>" <?php echo $this->uri->segment(4) == '' ? 'class="current"' : '' ?> ><?php echo lang('bf_users'); ?></a>
	<a href="<?php echo site_url(SITE_AREA .'/settings/users/create') ?>" <?php echo $this->uri->segment(4) == 'create' ? 'class="current"' : '' ?> ><?php echo lang('bf_new') .' '. lang('bf_user'); ?></a>
</div>