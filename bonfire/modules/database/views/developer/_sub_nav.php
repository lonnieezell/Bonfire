<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' && $this->uri->segment(3) != 'migrations' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/database') ?>"><?php echo lang('db_maintenance'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'backups' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/database/backups') ?>"><?php echo lang('db_backups'); ?></a>
	</li>
	<li <?php echo $this->uri->segment(3) == 'migrations' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/developer/migrations') ?>"><?php echo lang('db_migrations'); ?></a>
	</li>
</ul>
