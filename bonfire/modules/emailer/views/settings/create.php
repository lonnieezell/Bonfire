<?php if (validation_errors()) : ?>
<div class="alert alert-block alert-error fade in ">
  <a class="close" data-dismiss="alert">&times;</a>
  <h4 class="alert-heading">Please fix the following errors :</h4>
 <?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">

	<?php echo form_open($this->uri->uri_string()) ;?>
	<h3><?php echo lang('em_create_setting'); ?></h3>
	
	<table class="table table-stripped">
		<tbody>
			<tr>
				<td>Subject:</td>
				<td><input type="text" size="50" name="email_subject" id="email_subject" value="<?php if (isset($email_subject)){e($email_subject);} ?>"></td>
			</tr>
			<tr>
				<td>Content:</td>
				<td><textarea name="email_content" id="email_content" rows="15" style="width:90%;"><?php 
					if(isset($email_content)){e($email_content);}
				?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	
	<h3><?php echo lang('bf_users') ?></h3>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th style="width: 3em"><?php echo lang('bf_id'); ?></th>
				<th><?php echo lang('bf_username'); ?></th>
				<th><?php echo lang('bf_display_name'); ?></th>
				<th><?php echo lang('bf_email'); ?></th>
				<th style="width: 11em"><?php echo lang('us_last_login'); ?></th>
				<th style="width: 10em"><?php echo lang('us_status'); ?></th>
			</tr>
		</thead>
		<?php if (isset($users) && is_array($users) && count($users)) : ?>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo lang('bf_with_selected') ?>
					<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('em_create_email') ?>">
					<?php echo anchor(SITE_AREA .'/settings/emailer/queue', lang('em_create_email_cancel'), 'class="btn btn-warning"'); ?>
				</td>
			</tr>
		</tfoot>
		<?php endif; ?>
		<tbody>

		<?php if (isset($users) && is_array($users) && count($users)) : ?>
			<?php foreach ($users as $user) : ?>
			<tr>
				<td>
					<input type="checkbox" name="checked[]" value="<?php echo $user->id ?>" 
					<?php if (isset($checked) && (is_array($checked)) && (count($checked)) && (in_array($user->id, $checked))) : ?>
						checked="checked"
					<?php endif; ?>
					/>
				</td>
				<td><?php echo $user->id ?></td>
				<td>
					<a href="<?php echo site_url(SITE_AREA .'/settings/users/edit/'. $user->id); ?>"><?php echo $user->username; ?></a>
					<?php if ($user->banned) echo '<span class="label label-warning">Banned</span>'; ?>
				</td>
				<td><?php echo $user->display_name ?></td>
				<td>
					<a href="mailto:<?php echo $user->email ?>"><?php echo $user->email ?></a>
				</td>
				<td>
					<?php
						if ($user->last_login != '0000-00-00 00:00:00')
						{
							echo date('M j, y g:i A', strtotime($user->last_login));
						}
						else
						{
							echo '---';
						}
					?>
				</td>
				<td><?php
					$class = '';
					switch ($user->active)
					{
						case 1:
							$class = " label-success";
							break;
						case 0:
						default:
							$class = " label-warning";
							break;

					}
					?>
					<span class="label<?php echo($class); ?>">
					<?php
						if ($user->active == 1)
						{
							echo(lang('us_active'));
						}
						else
						{
							echo(lang('us_inactive'));
						}
						?>
					</span>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6">No users found that match your selection.</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo form_close(); ?>
</div>
