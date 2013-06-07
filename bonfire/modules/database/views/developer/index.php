<style>
.admin-box > form { padding: 0; }
th.records { width: 5.5em; }
td.records { text-align: center; }
input[type='submit'] { margin-left: 1em; }
</style>
<div class="admin-box">
	<?php 
	if (isset($tables) && is_array($tables) && count($tables) > 0) :

		echo form_open(SITE_AREA . '/developer/database/'); 
	?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<th><?php echo lang('db_table_name'); ?></th>
					<th class='records'># <?php echo lang('db_records'); ?></th>
					<th><?php echo lang('db_data_size'); ?></th>
					<th><?php echo lang('db_index_size'); ?></th>
					<th><?php echo lang('db_data_free'); ?></th>
					<th><?php echo lang('db_engine'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo lang('bf_with_selected'); ?>:
						<select name="action" class="span2" style="margin-bottom:0; ">
							<option value="backup"><?php echo lang('db_backup'); ?></option>
							<option value="repair"><?php echo lang('db_repair'); ?></option>
							<option value="optimize"><?php echo lang('db_optimize'); ?></option>
							<option>------</option>
							<option value="drop"><?php echo lang('db_drop'); ?></option>
						</select>
						<input type="submit" value="<?php echo lang('db_apply')?>" class="btn btn-primary" />
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($tables as $table) : ?>
				<tr>
					<td class="column-check">
						<input type="checkbox" value="<?php e($table->Name); ?>" name="checked[]" />
					</td>
					<td>
						<a href="<?php e(site_url(SITE_AREA . '/developer/database/browse/' . $table->Name)); ?>">
							<?php e($table->Name); ?>
						</a>
					</td>
					<td class='records'><?php echo $table->Rows; ?></td>
					<td><?php echo byte_format($table->Data_length); ?></td>
					<td><?php echo byte_format($table->Index_length); ?></td>
					<td><?php echo byte_format($table->Data_free); ?></td>
					<td><?php echo $table->Engine; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php 
		echo form_close();
	
	else :
	 ?>
	<div class="notification info">
		<p><?php echo lang('db_no_tables'); ?></p>
	</div>
	<?php endif; ?>
</div>