<div class="notification attention">
	<p><?php echo isset($update_message) ? $update_message : 'Update Checks are turned off in the config/application.php file.' ?></p>
</div>

<?php if (isset($commits) && is_array($commits) && count($commits)) : ?>
	<h3>New Bleeding Edge Commits</h3>

	<table>
		<thead>
			<tr>
				<th>Author</th>
				<th style="width: 8em">Committed</th>
				<th>Message</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($commits as $commit) : ?>
			<?php if ($commit->id > config_item('updates.last_commit')) :?>
			<tr>
				<td><?php echo anchor('http://github.com/'. $commit->author->name, $commit->author->name, array('target' => '_blank')) ?></td>
				<td><?php echo relative_time(strtotime($commit->committed_date)) ?></td>
				<td><?php echo $commit->message ?></td>
			</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>
