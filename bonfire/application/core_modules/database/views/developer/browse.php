

<?php if (isset($rows) && is_array($rows) && count($rows)) :?>

	<div class="alert alert-info">
		<p><b><?php e(lang('db_sql_query')); ?>:</b></p>

		<p><?php e($query); ?></p>
	</div>

	<p><?php echo e(lang('db_total_results')); ?>: <?php echo count($rows); ?></p>

	<div class="admin-box">
		<h3><?php echo $toolbar_title ?></h3>

		<table class="table table-striped">
			<thead>
				<tr>
				<?php
					$heads = $rows[0];

					foreach ($heads as $field => $value)
					{
						echo "<th>$field</th>\n";
					}
				?>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($rows as $row) : ?>
				<tr>
					<?php foreach ($row as $key => $value) :?>
					<td><?php echo $value; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

	</div>
<?php else: ?>
	<div class="admin-box">
		<h3><?php echo $toolbar_title ?></h3>
	<br/>
		<div class="alert alert-warning">
			<?php e(lang('db_no_rows')); ?>
		</div>
	</div>
<?php endif; ?>
