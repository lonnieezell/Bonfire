<?php echo modules::run('update/update/update_check'); ?>

<div class="notification information">
	<p class="text-center">The tools available in the Developer's section are advanced tools, designed to make a developer's life a little easier.<br/>If used without the proper knowledge, they can be very dangerous. Use with care.</p>
</div>

<h2><a href="<?php echo site_url('unit_test.php') ?>" target="_blank">Unit Tests</a></h2>

<h2>System Information</h2>

	<table cellspacing="0">
		<tbody>
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


<h2>Installed Modules</h2>

<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Version</th>
				<th>Description</th>
				<th>Author</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($modules as $module => $config) : ?>
			<tr>
				<td><?php echo $config['name'] ?></td>
				<td><?php echo isset($config['version']) ? $config['version'] : '---'; ?></td>
				<td><?php echo isset($config['description']) ? $config['description'] : '---'; ?></td>
				<td><?php echo isset($config['author']) ? $config['author'] : '---'; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
