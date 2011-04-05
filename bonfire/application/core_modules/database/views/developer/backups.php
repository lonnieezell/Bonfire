<br/>
<?php if (isset($backups) && is_array($backups) && count($backups) > 0) : ?>
	<?php echo form_open('/admin/developer/database/delete', array('style' => 'padding: 0')); ?>
		<table cellspacing="0">
			<thead>
				<tr>
					<th id="cb" class="column-check" style="width: 2em">
						<input class="check-all" type="checkbox" />
					</th>
					<th>Filename</th>
					<th style="width: 6.5em">Size</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">
						Delete selected backup files: 
						<button type="submit" name="submit" class="button">Delete</button>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($backups as $file => $atts) :?>
				<tr class="hover-toggle">
					<td class="column-check">
						<input type="checkbox" value="<?php echo $file ?>" name="checked[]" />
					</td>
					<td>
						<?php echo $file ?>
						<div class="hover-item small">
							<a href="/admin/developer/database/get_backup/<?php echo $file ?>" title="Download this file">Download</a> | 
							<a href="/admin/developer/database/restore/<?php echo $file ?>" title="Restore this file">Restore</a>
						</div>
					</td>
					<td><?php echo round($atts['size'] / 1024 , 3) ?> KB</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		
		</form>
	
	<?php else : ?>
		<div class="notification attention">
			<p>No previous backups were found.</p>
		</div>
	<?php endif; ?>
