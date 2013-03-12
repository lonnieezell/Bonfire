<?php $this->load->view('header'); ?>

<?php $this->load->view('install/menu'); ?>

<h2><?php echo lang('in_complete_heading'); ?></h2>

<p><?php echo lang('in_complete_intro'); ?></p>

<h3><?php echo lang('in_complete_next') ?></h3>

<ul>
	<li><?php echo lang('in_complete_visit') ?> <a href="<?php echo installed_url() .'admin' ?>"><?php echo lang('in_admin_area') ?></a></li>
	<li><?php echo lang('in_complete_visit') ?> <a href="<?php echo installed_url() ?>"><?php echo lang('in_site_front') ?></a></li>
	<li><?php echo lang('in_read') ?> <a href="https://github.com/ci-bonfire/Bonfire/wiki" target="_blank"><?php echo lang('in_bf_docs') ?></a></li>
	<li><?php echo lang('in_read') ?> <a href="http://ellislab.com/codeigniter/user-guide/" target="_blank"><?php echo lang('in_ci_docs') ?></a></li>
</ul>

<br/>
<p><?php echo lang('in_happy_coding') ?></p>

<?php $this->load->view('footer'); ?>
