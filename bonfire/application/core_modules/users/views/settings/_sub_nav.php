<?php if (has_permission('Bonfire.Users.Manage')):?>
<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/users') ?>"><?php echo lang('us_s_users'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/settings/users/create') ?>" id="create_new"><?php echo lang('us_s_new_user'); ?></a>
	</li>
</ul>
<?php endif;?>