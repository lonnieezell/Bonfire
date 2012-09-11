<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">

	    <div class="container">
			<!-- .btn-navbar is used as the toggle for collapsible content -->
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<a href="<?php site_url('/'); ?>" class="brand">
				<?php e($this->settings_lib->item('site.title')); ?>
			</a>

			<!-- Everything you want hidden at 940px or less, place within here -->
			<div class="nav-collapse collapse">
				<ul class="nav pull-right">
					<li class="divider-vertical"></li>
<?php //style="height:40px" ?>
					<?php if (isset($current_user->email)) : ?>
					<li class="dropdown" >
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php echo $current_user->user_img; ?>
						<b class="caret"></b></a>

						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo site_url(); ?>">
									<?php echo lang('bf_home') ?>
								</a>
							</li>

							<?php if (has_permission('Site.Content.View')) : ?>
							<li class="divider"></li>
							<li>
								<?php echo anchor(SITE_AREA, 'Control Panel'); ?>
							</li>

							<?php endif; ?>
							<li class="divider"></li>
							<li>
								<a href="<?php echo site_url('users/profile');?>">
									<?php echo lang('bf_user_settings') ?>
								</a>
							</li>

							<li class="divider"></li>
							<li>
								<a href="<?php echo site_url('logout');?>">
									<?php echo lang('bf_action_logout') ?>
								</a>
							</li>
						</ul>
					</li>

					<?php else :  ?>

						<li>
							<a href="<?php echo site_url('register');?>">
								<?php echo lang('bf_action_register') ?>
							</a>
						</li>
						<li>
							<a href="<?php echo site_url('login');?>" class="login-btn">
								<?php echo lang('bf_action_login') ?>
							</a>
						</li>

					<?php endif; ?>
				</ul>

			</div><!--/.nav-collapse -->
		</div>	<!-- /.container -->
	</div>	<!-- /.navbar-inner -->
</div>	<!-- /.navbar -->
<!-- End of Navbar Template -->

