<div class="alert alert-info fade in">
  <a class="close" data-dismiss="alert">&times;</a>

	<?php echo isset($update_message) ? $update_message : lang((isset($curl_disabled) ? 'up_curl_disabled_message' : 'up_update_off_message')) ?>
</div>

<div class="admin-box">
	<h3><?php echo lang('up_edge_commits'); ?></h3>

<?php if (isset($commits) && is_array($commits) && count($commits)) : ?>

	<fieldset>
		<legend><?php echo lang('up_branch'); ?></legend>

	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 8em"><?php echo lang('up_author'); ?></th>
				<th style="width: 8em"><?php echo lang('up_committed'); ?></th>
				<th><?php echo lang('up_message'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($commits as $commit) : ?>
			<?php if ($commit->id > $this->settings_lib->item('updates.last_commit')) :?>
			<tr>
				<td><?php echo anchor('http://github.com/'. $commit->author->login, $commit->author->name, array('target' => '_blank')) ?></td>
				<td><?php echo relative_time(strtotime($commit->committed_date)) ?></td>
				<td><?php echo $commit->message ?></td>
			</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</tbody>
	</table>

	</fieldset>

<?php endif; ?>
</div>
