<ul class="nav nav-pills">
    <li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .'/settings/emailer') ?>"><span class="fa fa-cogs"></span> <?php echo lang('bf_context_settings'); ?></a>
    </li>
    <li <?php echo $this->uri->segment(4) == 'template' ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .'/settings/emailer/template') ?>"><span class="fa fa-code"></span> <?php echo lang('emailer_email_template') ?></a>
    </li>
    <li <?php echo $this->uri->segment(4) == 'queue' ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .'/settings/emailer/queue') ?>"><span class="fa fa-list"></span> <?php echo lang('emailer_emailer_queue') ?></a>
    </li>
    <li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .'/settings/emailer/create')?>"><span class="fa fa-envelope"></span> <?php echo lang('emailer_create_email'); ?></a>
    </li>
</ul>
