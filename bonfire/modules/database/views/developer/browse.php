<div class="alert alert-info">
	<p><strong><?php e(lang('db_sql_query')); ?></strong>:</p>
	<p><?php e($query); ?></p>
</div>
<?php if (isset($rows) && is_array($rows) && count($rows)) :?>
<p><?php echo e(lang('db_total_results')); ?>: <?php echo count($rows); ?></p>
<div class="admin-box">
	<table class="table table-striped">
		<thead>
			<tr>
				<?php
				$heads = $rows[0];
			
				foreach ($heads as $field => $value) :
				?>
				<th><?php e($field); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) : ?>
			<tr>
				<?php foreach ($row as $key => $value) : ?>
				<td><?php e($value); ?></td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<div class="alert alert-warning">
	<?php e(lang('db_no_rows')); ?>
</div>
<?php endif; ?>