<br/>
<div class="row">
	<div class="column size1of2">
		<p><b><?php echo lang('em_total_in_queue'); ?></b> <?php echo $total_in_queue ? $total_in_queue : '0' ?></p>
	</div>
	
	<div class="column size1of2">
		<p><b><?php echo lang('em_total_sent'); ?></b> <?php echo $total_sent ? $total_sent : '0' ?></p>
	</div>
</div>

<div class="padded text-right">
	<a href="<?php echo site_url(SITE_AREA . '/settings/emailer/force_process'); ?>" class="button">Process Now</a> 
	<a href="<?php echo site_url(SITE_AREA . '/settings/emailer/insert_test'); ?>" class="button">Insert Test Email</a>
</div>

<?php if (isset($emails) && is_array($emails) && count($emails)) : ?>

	<table>
		<thead>
			<tr>
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
				<td colspan="6" class="text-left"><?php echo $this->pagination->create_links() ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($emails as $email) :?>
			<tr>
				<td><?php echo $email->id; ?></td>
				<td><?php echo $email->to_email ?></td>
				<td><?php echo $email->subject ?></td>
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

	<div class="notification information">
		<p><?php echo lang('em_stat_no_queue'); ?></p>
	</div>

<?php endif; ?>

<?php if (isset($email_debug)) :?>

<h3>Email Debugger</h3>

<div class="notification attention">
	<p>There was an error sending emails from the queue. The results appear below.</p>
</div>

<div class="box">
<?php echo $email_debug; ?>
</div>

<?php endif; ?>
