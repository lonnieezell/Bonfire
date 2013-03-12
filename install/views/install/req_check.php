<?php $this->load->view('header'); ?>

<?php $this->load->view('install/menu'); ?>

<?php echo lang('in_intro'); ?>

<div class="well">
	
	<table>
		<tbody>
			<!-- PHP Version -->
			<tr>
				<td><?php echo lang('in_php_version') .' <b>'. $php_min_version ?>+</b></td>
				<td style="width: 10em"><?php echo $php_acceptable ? '<span class="good">' : '<span class="bad">'; ?><?php echo $php_version ?></span></td>
			</tr>
			<tr>
				<td><?php echo lang('in_curl_enabled') ?></td>
				<td><?php echo $curl_enabled ? '<span class="good">'. lang('in_enabled') .'</span>' : '<span class="bad">'. lang('in_disabled') .'</span>'; ?></td>
			</tr>
			
			<!-- Folders -->
			<tr><td colspan="2" style="text-align: center"><b><?php echo lang('in_folders') ?></b></td></tr>
			
			<?php foreach ($folders as $folder => $perm) :?>
			<tr>
				<td><?php echo $folder ?></td>
				<td><?php echo $perm ? '<span class="good">'. lang('in_writeable') .'</span>' : '<span class="bad">'. lang('in_not_writeable') .'</span>' ?></td>
			</tr>
			<?php endforeach; ?>
			
			<!-- Files -->
			<tr><td colspan="2" style="text-align: center"><b><?php echo lang('in_files') ?></b></td></tr>
			
			<?php foreach ($files as $file => $perm) :?>
			<tr>
				<td><?php echo $file ?></td>
				<td><?php echo $perm ? '<span class="good">'. lang('in_writeable') .'</span>' : '<span class="bad">'. lang('in_not_writeable') .'</span>' ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
</div>

<div class="form-actions">
	<?php if ($step_passed) : ?>
		<a href="<?php echo site_url('install/database') ?>" class="button"><?php echo lang('in_continue') ?></a>
	<?php else : ?>	
		<p><?php echo lang('in_bad_permissions') ?></p>
	<?php endif; ?>
</div>

<?php $this->load->view('footer'); ?>