<?php echo modules::run('update/update/update_check'); ?>

<br/>

<div class="admin-box">
	<h3><?php echo lang('si.system_info') ?></h3>

	<table class="table table-striped">
		<tbody>
			<tr>
				<td><?php echo lang('sys_bonfire_ver'); ?></td>
				<td><?php echo BONFIRE_VERSION ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_codeigniter_ver'); ?></td>
				<td>
					<?php 
						echo CI_CORE == true ? 'Core ' : 'Reactor ';
						echo CI_VERSION;
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo lang('sys_codeigniter_ver'); ?></td>
				<td><?php echo phpversion(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_server_time'); ?></td>
				<td>
				<?php 
	
			        $thetimeis = getdate(time()); 
			            $thehour = $thetimeis['hours']; 
			            $theminute = $thetimeis['minutes']; 
			        if($thehour > 12){ 
			            $thehour = $thehour - 12; 
			            $dn = "pm"; 
			        }else{ 
			            $dn = "am"; 
			        } 
			        
					echo "$thehour:$theminute $dn"; 
				?>   
				</td>
			</tr>
			<tr>
				<td><?php echo lang('sys_local_time'); ?></td>
				<td><?php echo date('h:i a'); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_databse_name'); ?></td>
				<td><?php echo $this->db->database; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_databse_server'); ?></td>
				<td><?php echo $this->db->platform(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_databse_ver'); ?></td>
				<td><?php echo $this->db->version(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_databse_char'); ?></td>
				<td><?php echo $this->db->char_set; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_databse_col_char'); ?></td>
				<td><?php echo $this->db->dbcollat; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_base_path'); ?></td>
				<td><?php echo BASEPATH; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_app_path'); ?></td>
				<td><?php echo APPPATH ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_app_url'); ?></td>
				<td><?php echo site_url(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('sys_app_environment'); ?></td>
				<td><?php echo ENVIRONMENT; ?></td>
			</tr>
		</tbody>
	</table>
</div>