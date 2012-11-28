<?php
	$req_done = $this->session->userdata('step1_done') ? 'done' : '';
	$db_done = $this->session->userdata('step2_done') ? 'done' : '';
	$act_done = $this->session->userdata('step3_done') ? 'done' : '';
	
	$active = $this->router->fetch_method();

?>
<ul class="breadcrumb">
	<li class="<?php echo $req_done ?> <?php echo $active=='index' ? 'active' :''; ?>"><a href="/install/index.php">Requirements</a></li>
	<li class="<?php echo $db_done ?> <?php echo $active=='database' ? 'active' :''; ?>"><a href="/install/index.php/install/database">Database</a></li>
	<li class="<?php echo $act_done ?> <?php echo $active=='account' ? 'active' :''; ?>"><a href="/install/index.php/install/account">Account</a></li>
</ul>