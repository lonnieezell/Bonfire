<div class="view split-view">
	<!-- Users List -->
	<div class="view">
		
		<div class="panel-header list-search">
		
			<select id="role-filter" style="display: inline-block; max-width: 40%;">
				<option value="0"><?php echo lang('bf_action_show') .' '. lang('us_role'); ?>...</option>
			<?php foreach ($roles as $role) : ?>
				<?php if (isset($role) && has_permission('Permissions.'.$role->role_name.'.Manage')) : ?>
				<option><?php echo $role->role_name ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</select>
			
			<?php render_search_box(); ?>
		</div>
	
		<?php if (isset($users) && is_array($users)) : ?>
		
		<div class="scrollable">
			<div class="list-view" id="user-list">
			<?php foreach ($users as $user) : ?>
				<?php if (isset($user) && has_permission('Permissions.'.$user->role_name.'.Manage')) : ?>
				<div class="list-item with-icon" data-id="<?php echo $user->id ?>" data-role="<?php echo $user->role_name ?>">
					<?php echo gravatar_link($user->email, 32,'',$user->first_name.'&nbsp;'.$user->last_name); ?>
				
					<p>
						<?php 
							if (config_item('auth.use_own_names'))
							{
								$name = trim($this->auth->user_name());
								
								if (empty($name))
								{
									if (!empty($user->username))
									{
										$name = $user->username;
									}
									else
									{
										$name = $user->email;
									}
								}
							} 
							else if (config_item('auth.use_usernames') && ($user->username))
							{
								$name = $user->username;
							}
							else 
							{
								$name = $user->email;
							}
						?>
						
						<b><?php echo $name; ?></b><br/>
						<span><?php echo $user->role_name ?></span>
					</p>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div>	<!-- /list -->
		</div>
		
		<?php else : ?>
		
			<div class="notification information">
				<p><?php echo lang('no_users'); ?></p>
			</div>
		
		<?php endif; ?>
	</div>	<!-- /users-list -->
	
	<!-- User Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
			<div class="padded">
			
				<div class="row" style="margin-bottom: 2.5em">
					<div class="column size1of2">
						<img src="<?php echo Template::theme_url('images/user.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />	
						
						<span class="big-text"><b><?php echo $user_count ?></b></span> &nbsp; users
					</div>
					
					<div class="column size1of2 last-column">
						<img src="<?php echo Template::theme_url('images/user.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />	
						
						<span class="big-text"><b><?php echo $deleted_users ?></b></span> &nbsp; <?php echo anchor(SITE_AREA .'/settings/users/deleted', 'deleted users', 'class="ajaxify"') ?>
					</div>
				</div>
			
			
				<div class="box create rounded">
					<a class="button good ajaxify" href="<?php echo site_url(SITE_AREA .'/settings/users/create'); ?>"><?php echo lang('us_create_user'); ?></a>
				
					<?php echo lang('us_create_user_note'); ?>
				</div>	
				
				<div class="row" style="margin-top: 3em">
					<!-- Access Logs -->
					<div class="column size1of2">
						<?php echo modules::run('activities/activities/activity_list', 'users'); ?>
					</div>
					
					<!-- Login Attempts -->
					<div class="column size1of2 last-column">
						<h3><?php echo lang('us_failed_login_attempts'); ?></h3>
						
						<?php if (isset($login_attempts) && is_array($login_attempts) && count($login_attempts)) : ?>
						
							<ol>
							<?php foreach ($login_attempts as $attempt) : ?>
								<li><b><?php echo $attempt->login; ?></b> on <?php echo date('j-m-Y H:i:s',strtotime($attempt->time)) ?></li>
							<?php endforeach; ?>
							</ol>
						
						<?php else : ?>
							<?php echo lang('us_failed_logins_note'); ?>
						<?php endif; ?>
					</div>
				</div>
				
			</div>	<!-- /inner -->
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div> <!-- /v-split -->