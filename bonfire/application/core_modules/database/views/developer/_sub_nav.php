<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' && $this->uri->segment(3) != 'migrations' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/database', lang('db_s_maintenance')); ?>
	</li>
	<li <?php echo $this->uri->segment(4) == 'backups' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/database/backups', lang('db_s_backups')); ?>
	</li>
	<li <?php echo $this->uri->segment(3) == 'migrations' ? 'class="active"' : '' ?>>
		<?php echo anchor(SITE_AREA . '/developer/migrations', lang('db_s_migrations')); ?>
	</li>
</ul>
