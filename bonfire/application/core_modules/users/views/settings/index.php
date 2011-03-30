<div class="view split-view">
	<!-- Users List -->
	<div class="view">
		
		<div class="panel-header list-search">
		
			<select id="role-filter" style="display: inline; max-width: 40%;">
				<option value="0">Show role...</option>
			<?php foreach ($roles as $role) : ?>
				<option><?php echo $role->role_name ?></option>
			<?php endforeach; ?>
			</select>
			
			<?php render_search_box(); ?>
		</div>
	
		<?php if (isset($users) && is_array($users)) : ?>
		
		<div class="scrollable">
			<div class="list-view" id="user-list">
			<?php foreach ($users as $user) : ?>
				<div class="list-item with-icon" data-id="<?php echo $user->id ?>" data-role="<?php echo $user->role_name ?>">
					<?php echo gravatar_link($user->email, 32); ?>
				
					<p>
						<b><?php echo config_item('auth.login_type') == 'username' ? $user->username : $user->email; ?></b><br/>
						<span><?php echo $user->role_name ?></span>
					</p>
				</div>
			<?php endforeach; ?>
			</div>	<!-- /list -->
		</div>
		
		<?php else : ?>
		
			<div class="notification information">
				<p>No users found.</p>
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
					
					<div class="column size1of2">
						<img src="<?php echo Template::theme_url('images/user.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />	
						
						<span class="big-text"><b><?php echo $deleted_users ?></b></span> &nbsp; <?php echo anchor('admin/settings/users/deleted', 'deleted users', 'class="ajaxify"') ?>
					</div>
				</div>
			
			
				<div class="box create rounded">
					<a class="button good ajaxify" href="<?php echo site_url('admin/settings/users/create'); ?>">Create New User</a>
				
					<h3>Create A New User</h3>
					
					<p>Create new accounts for other users in your circle.</p>
				</div>	
				
				<div class="row" style="margin-top: 3em">
					<!-- Access Logs -->
					<div class="column size1of2">
						<?php echo modules::run('users/settings/access_logs'); ?>
					</div>
					
					<!-- Access Logs -->
					<div class="column size1of2">
						<?php echo modules::run('users/settings/login_attempts'); ?>
					</div>
				</div>
				
			</div>	<!-- /inner -->
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div> <!-- /v-split -->