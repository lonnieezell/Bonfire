<?php if (has_permission('Bonfire.Users.Manage')):?>
<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/users', lang('us_s_users')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/settings/users/create', lang('us_s_new_user'), ' id="create_new"'); ?>
	</li>
</ul>
<?php endif;?>