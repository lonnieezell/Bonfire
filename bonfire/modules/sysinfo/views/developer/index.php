<?php

$dateFormat = 'g:i a';

?>
<div class="admin-box">
	<table class="table table-striped">
		<tbody>
			<tr>
				<th><?php echo lang('sysinfo_version_bf'); ?></th>
				<td><?php echo BONFIRE_VERSION; ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_version_ci'); ?></th>
				<td><?php echo CI_VERSION; ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_version_php'); ?></th>
				<td><?php echo phpversion(); ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_time_server'); ?></th>
				<td><?php echo date($dateFormat); ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_time_local'); ?></th>
				<td><?php echo user_time(time(), false, $dateFormat); ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_db_name'); ?></th>
				<td><?php echo $this->db->database; ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_db_server'); ?></th>
				<td><?php echo $this->db->platform(); ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_db_version'); ?></th>
				<td><?php echo $this->db->version(); ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_db_charset'); ?></th>
				<td><?php echo $this->db->char_set; ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_db_collation'); ?></th>
				<td><?php echo $this->db->dbcollat; ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_basepath'); ?></th>
				<td><?php echo BASEPATH; ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_apppath'); ?></th>
				<td><?php echo APPPATH ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_site_url'); ?></th>
				<td><?php echo site_url(); ?></td>
			</tr>
			<tr>
				<th><?php echo lang('sysinfo_environment'); ?></th>
				<td><?php echo ENVIRONMENT; ?></td>
			</tr>
		</tbody>
	</table>
</div>