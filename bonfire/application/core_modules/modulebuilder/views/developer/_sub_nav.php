<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/modulebuilder') ?>"><?php echo lang('bf_action_list'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/developer/modulebuilder/create') ?>" id="create_new"><?php echo lang('mb_new_module'); ?></a>
	</li>
</ul>