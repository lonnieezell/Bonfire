<br/>
<div class="row">
	<div class="column size1of3">
		<p><b><?php echo lang('em_total_in_queue'); ?></b> <?php echo $total_in_queue ? $total_in_queue : '0' ?></p>
	</div>

	<div class="column size1of3">
		<p><b><?php echo lang('em_total_sent'); ?></b> <?php echo $total_sent ? $total_sent : '0' ?></p>
	</div>
	<div class="column size1of3 last-column text-right">
		<?php echo form_open($this->uri->uri_string(), array('class' => 'form-inline')); ?>
		<input type="submit" name="action_force_process" class="btn btn-primary" value="Process Now">
		<input type="submit" name="action_insert_test" class="btn btn-warning" value="Insert Test Email">
		<?php echo form_close(); ?>
	</div>
</div>

<br/>
<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>
	<?php echo form_open($this->uri->uri_string()); ?>

<?php if (isset($emails) && is_array($emails) && count($emails)) : ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th style="width: 2em"><?php echo lang('em_id'); ?></th>
				<th style="width: 10em"><?php echo lang('em_to'); ?></th>
				<th><?php echo lang('em_subject'); ?></th>
				<th style="width: 6em"># <?php echo lang('em_attempts'); ?></th>
				<th style="width: 3em"><?php echo lang('em_sent'); ?>?</th>
				<th style="width: 6em"></th>
			</tr>
		</thead>

		<tfoot>
		<tr>
			<td colspan="7">
			<?php if (isset($emails) && count($emails)) : ?>

				<?php echo lang('bf_with_selected') ?>
				<button type="submit" name="action_delete" id="delete-me" class="btn btn-danger" onclick="return confirm('<?php echo lang('em_delete_confirm'); ?>')">
					<i class="icon-white icon-trash"></i> <?php echo lang('bf_action_delete') ?>
				</button>

			<?php endif;?>

			</td>
		</tr>
			<tr>
				<td colspan="7" class="text-left"><?php echo $this->pagination->create_links() ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($emails as $email) :?>
			<tr>
				<td><input type="checkbox" name="checked[]" value="<?php echo $email->id ?>" /></td>
				<td><?php echo $email->id; ?></td>
				<td><?php e($email->to_email) ?></td>
				<td><?php e($email->subject) ?></td>
				<td class="text-center"><?php echo $email->attempts ?></td>
				<td class="text-center"><?php echo $email->success ? lang('bf_yes') : lang('bf_no') ?></td>
				<td class="text-center">
					<?php echo anchor(SITE_AREA .'/settings/emailer/preview/'. $email->id, lang('bf_action_preview'), array('target'=>'_blank')); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>


<?php else : ?>

	<div class="alert alert-warning">
		<p><?php echo lang('em_stat_no_queue'); ?></p>
	</div>

<?php endif; ?>

	<?php echo form_close(); ?>
</div>

<?php if (isset($email_debug)) :?>

<h3>Email Debugger</h3>

<div class="notification attention">
	<p>There was an error sending emails from the queue. The results appear below.</p>
</div>

<div class="box">
<?php echo $email_debug; ?>
</div>

<?php endif; ?>
