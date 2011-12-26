<div class="view split-view">
	
	<!-- Profiles List -->
	<div class="view">
	
	<?php if (isset($records) && is_array($records) && count($records)) : ?>
		<div class="scrollable">
			<div class="list-view" id="role-list">
				<?php foreach ($records as $record) : ?>
					<?php $record = (array)$record;?>
					<div class="list-item" data-id="<?php echo $record['profile_id']; ?>">
						<p>
							<b><?php echo (empty($record['profile_name']) ? $record['profile_id'] : $record['profile_name']); ?></b><br/>
							<span class="small"><?php if($record["default"]) echo "Default" ?></span>
						</p>
					</div>
				<?php endforeach; ?>
			</div>	<!-- /list-view -->
		</div>
	
	<?php else: ?>
	
	<div class="notification attention">
		<p><?php echo lang('package_no_records'); ?> <?php echo anchor(SITE_AREA .'/settings/package/create', lang('package_create_new'), array("class" => "ajaxify")) ?></p>
	</div>
	
	<?php endif; ?>
	</div>

	
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
			<div class="box create rounded">
            
				<a class="button good ajaxify" href="<?php echo site_url(SITE_AREA .'/settings/emailer/create')?>"><?php echo "Create New Profile";?></a>

				<h3><?php echo "Create a New Profile" ?></h3>

				<p><?php echo "Create new emailer profile"; ?></p>
			</div>
			<br />

<!-- Start Queue -->
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

<!-- End Queue -->


		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>
