<?php echo modules::run('update/update/update_check'); ?>

<br/>

<div class="admin-box">
	<h3><?php echo lang('si.system_info') ?></h3>

	<table class="table table-striped">
		<tbody>
			<tr>
				<td>Bonfire Version</td>
				<td><?php echo BONFIRE_VERSION ?></td>
			</tr>
			<tr>
				<td>CodeIgniter Version</td>
				<td>
					<?php 
						echo CI_CORE == true ? 'Core ' : 'Reactor ';
						echo CI_VERSION;
					?>
				</td>
			</tr>
			<tr>
				<td>PHP Version</td>
				<td><?php echo phpversion(); ?></td>
			</tr>
			<tr>
				<td>Server Time</td>
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
				<td>Local Time</td>
				<td><?php echo date('h:i a'); ?></td>
			</tr>
			<tr>
				<td>Database Name</td>
				<td><?php echo $this->db->database; ?></td>
			</tr>
			<tr>
				<td>Database Server</td>
				<td><?php echo $this->db->platform(); ?></td>
			</tr>
			<tr>
				<td>Database Version</td>
				<td><?php echo $this->db->version(); ?></td>
			</tr>
			<tr>
				<td>Database Charset</td>
				<td><?php echo $this->db->char_set; ?></td>
			</tr>
			<tr>
				<td>Database Collation Charset</td>
				<td><?php echo $this->db->dbcollat; ?></td>
			</tr>
			<tr>
				<td>BASE PATH</td>
				<td><?php echo BASEPATH; ?></td>
			</tr>
			<tr>
				<td>APP PATH</td>
				<td><?php echo APPPATH ?></td>
			</tr>
			<tr>
				<td>SITE_URL</td>
				<td><?php echo site_url(); ?></td>
			</tr>
			<tr>
				<td>ENVIRONMENT</td>
				<td><?php echo ENVIRONMENT; ?></td>
			</tr>
		</tbody>
	</table>
</div>