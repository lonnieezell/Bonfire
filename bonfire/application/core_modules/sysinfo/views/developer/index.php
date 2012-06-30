<?php echo modules::run('update/update/update_check'); ?>

<br/>

<div class="admin-box">
	<h3><?php echo lang('si_system_info'); ?></h3>

	<table class="table table-striped">
		<tbody>
			<tr>
				<td><?php echo lang('si_bonfire_version'); ?></td>
				<td><?php echo BONFIRE_VERSION ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_codeIgniter_version'); ?></td>
				<td>
					<?php 
						echo CI_CORE == true ? 'Core ' : 'Reactor ';
						echo CI_VERSION;
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo lang('si_php_version'); ?></td>
				<td><?php echo phpversion(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_server_time'); ?></td>
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
				<td><?php echo lang('si_local_time'); ?></td>
				<td><?php echo date('h:i a'); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_db_name'); ?></td>
				<td><?php echo $this->db->database; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_db_server'); ?></td>
				<td><?php echo $this->db->platform(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_db_version'); ?></td>
				<td><?php echo $this->db->version(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_db_charset'); ?></td>
				<td><?php echo $this->db->char_set; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_db_collation_charset'); ?></td>
				<td><?php echo $this->db->dbcollat; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_base_path'); ?></td>
				<td><?php echo BASEPATH; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_app_path'); ?></td>
				<td><?php echo APPPATH ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_site_url'); ?></td>
				<td><?php echo site_url(); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('si_environment'); ?></td>
				<td><?php echo ENVIRONMENT; ?></td>
			</tr>
		</tbody>
	</table>
</div>