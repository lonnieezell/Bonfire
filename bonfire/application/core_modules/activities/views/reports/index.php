<div class="view split-view">
	<!-- Users List -->
	<div class="view">	
		
		<div class="panel-header list-search">			
			<strong>Users</strong> <?php render_search_box(); ?>
		</div>
		<?php if (isset($users) && is_array($users)) : ?>
		
		<div class="scrollable">
			<div class="list-view" id="user-list">
			<?php foreach ($users as $user) : ?>
				<div class="list-item with-icon" data-id="<?php echo $user->id ?>">
					<?php echo gravatar_link($user->email, 32,'',$user->first_name.'&nbsp;'.$user->last_name); ?>
				
					<p>
						<b><?php echo config_item('auth.use_usernames') ? $user->username : $user->email; ?></b><br/>
						<span><?php echo $user->role_name ?></span>
					</p>
				</div>
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
			<h2><?php echo lang('activity_actions'); ?></h2>
			
			<div class="box delete rounded">
				<a class="button" id="delete-user-activity"><?php echo lang('activity_delete'); ?></a>				
				<?php echo lang('activity_delete_user_note'); ?>
				<select id="user_select">
				<?php foreach ($users as $au) : ?>
					<option value="<?php echo $au->id; ?>"><?php echo $au->username; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			
			<div class="box delete rounded">
				<a class="button" id="delete-module-activity"><?php echo lang('activity_delete'); ?></a>			
				<?php echo lang('activity_delete_module_note'); ?>
				<select id="module_select">
					<option value="core">Bonfire Core</option>
				<?php foreach ($modules as $mod) : ?>
					<option value="<?php echo $mod; ?>"><?php echo $mod; ?></option>
				<?php endforeach; ?>
				</select>
			</div>			
		</div>
	</div>
</div>