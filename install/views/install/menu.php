<?php
	$req_done = $this->session->userdata('step1_done') ? 'done' : '';
	$db_done = $this->session->userdata('step2_done') ? 'done' : '';
	$act_done = $this->session->userdata('step3_done') ? 'done' : '';
	
	$active = $this->router->fetch_method();
	
	$installed = $this->session->userdata('installed');

?>
<ul class="breadcrumb">
	<li class="<?php echo $req_done ?> <?php echo $active=='index' ? 'active' :''; ?>">
		<a href="<?php echo site_url('/install/index.php') ?>"><?php echo lang('in_requirements') ?></a>
	</li>
	<li class="<?php echo $db_done ?> <?php echo $active=='database' ? 'active' :''; ?>">
		<a href="<?php echo site_url('/install/index.php/install/database') ?>"><?php echo lang('in_database') ?></a>
	</li>
	<li class="<?php echo $act_done ?> <?php echo $active=='account' ? 'active' :''; ?>">
		<a href="<?php echo site_url('/install/index.php/install/account') ?>"><?php echo lang('in_account') ?></a>
	</li>
	<?php if ($installed) :?>
		<li class="active">
			<a href="#"><?php echo lang('in_complete') ?></a>
		</li>
	<?php endif; ?>
</ul>