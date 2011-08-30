<div class="view split-view">
	<div class="view">
		<div class="scrollable">
			<div class="list-view" id="user-list">
				<?php if (has_permission('Activities.Own.View')): ?>
				<div class="list-item with-icon" data-id="activity_user/1">
					<img src="<?php echo Template::theme_url('images/activity-user.png') ?>" />
					
					<p><b><?php echo lang('activity_own'); ?></b><br/>
					<span><?php echo lang('activity_own_description'); ?></span>
					</p>
				</div>
				<?php endif; ?>
				
				<?php if (has_permission('Activities.User.View')): ?>
				<div class="list-item with-icon" data-id="activity_user">
					<img src="<?php echo Template::theme_url('images/customers.png') ?>" />
					
					<p><b><?php echo lang('activity_users'); ?></b><br/>
					<span><?php echo lang('activity_users_description'); ?></span>
					</p>
				</div>
				<?php endif; ?>


				<?php if (has_permission('Activities.Module.View')): ?>
				<div class="list-item with-icon" data-id="activity_module">
					<img src="<?php echo Template::theme_url('images/product.png') ?>" />
					
					<p><b><?php echo lang('activity_modules'); ?></b><br/>
					<span><?php echo lang('activity_module_description'); ?></span>
					</p>
				</div>
				<?php endif; ?>

				<?php if (has_permission('Activities.Date.View')): ?>
				<div class="list-item with-icon" data-id="activity_date">
					<img src="<?php echo Template::theme_url('images/calendar.png') ?>" />
					
					<p><b><?php echo lang('activity_date'); ?></b><br/>
					<span><?php echo lang('activity_date_description'); ?></span>
					</p>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>	
		
	<!-- Activities -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
			<h2><?php echo lang('activity_actions'); ?></h2>
			
			<?php if (has_permission('Activities.Own.Delete')): ?>	
			<div class="box delete rounded">
				<a class="button" id="delete-activity_own"><?php echo lang('activity_own_delete'); ?></a>				
				<?php echo lang('activity_delete_own_note'); ?>
				<select id="activity_own_select">
					<option value="<?php echo $this->auth->user_id(); ?>"><?php echo $this->auth->username(); ?></option>))
				</select>
			</div>
			<?php endif; ?>
			
			<?php if (has_permission('Activities.User.Delete')): ?>	
			<div class="box delete rounded">
				<a class="button" id="delete-activity_user"><?php echo lang('activity_user_delete'); ?></a>				
				<?php echo lang('activity_delete_user_note'); ?>
				<select id="activity_user_select">
					<option value="all"><?php echo lang('activity_all_users'); ?></option>
				<?php foreach ($users as $au) : ?>
					<option value="<?php echo $au->id; ?>"><?php echo $au->username; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>

			<?php if (has_permission('Activities.Module.Delete')): ?>			
			<div class="box delete rounded">
				<a class="button" id="delete-activity_module"><?php echo lang('activity_module_delete'); ?></a>			
				<?php echo lang('activity_delete_module_note'); ?>
				<select id="activity_module_select">
					<option value="all"><?php echo lang('activity_all_modules'); ?></option>
					<option value="core"><?php echo lang('activity_core'); ?></option>
				<?php foreach ($modules as $mod) : ?>
					<option value="<?php echo $mod; ?>"><?php echo $mod; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>

			<?php if (has_permission('Activities.Date.Delete')): ?>			
			<div class="box delete rounded">
				<a class="button" id="delete-activity_date"><?php echo lang('activity_date_delete'); ?></a>			
				<?php echo lang('activity_delete_date_note'); ?>
				<select id="activity_date_select">
					<option value="all"><?php echo lang('activity_all_dates'); ?></option>
				<?php foreach ($activities as $activity) : ?>
					<option value="<?php echo $activity->activity_id; ?>"><?php echo $activity->created_on; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>