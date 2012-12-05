<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/builder') ?>"><?php echo lang('bf_action_list'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create_module' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/developer/builder/create_module') ?>" id="create_new"><?php echo lang('mb_new_module'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create_context' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/developer/builder/create_context') ?>" id="create_new_context"><?php echo lang('mb_new_context'); ?></a>
	</li>
</ul>