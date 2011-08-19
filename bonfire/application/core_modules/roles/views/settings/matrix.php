<?php if (isset($domains) && is_array($domains) && count($domains)) : ?>

	<?php foreach ($domains as $domain_name => $fields) : ?>
	<table cellspacing="0" style="width: auto !important" class="matrix">
		<thead>
			<tr>
				<th style="width: 150px"><b style="color: #222"><?php echo $domain_name ?></b></th>
				<?php $index = 0; ?>
				<?php foreach ($fields['actions'] as $action) : ?>
					<th cellIndex="<?php echo $index ?>"><a href="#"><?php echo $action ?></a></th>
					<?php ++$index; ?>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($fields as $field_name => $field_actions) : ?>
				<?php if ($field_name != 'actions') : ?>
				<tr>
					<td class="matrix-title"><b><a href="#"><?php echo $field_name ?></a></b></td>
					<?php foreach ($fields['actions'] as $action) : ?>
						<td>
							<?php if (array_key_exists($action, $field_actions)) : ?>
								<?php 
									$perm_name = $domain_name .'.'. $field_name .'.'. $action;
								?>
								<input type="checkbox" name="role_permissions[]" class="" value="<?php echo $domains[$domain_name][$field_name][$action]['perm_id'] ?>" 
								<?php 
									if (isset($domains[$domain_name][$field_name][$action]['value']) && $domains[$domain_name][$field_name][$action]['value'] == 1)
									{
										echo 'checked="checked"';
									}
								?>
								/>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endforeach; ?>
	
	<?php else: ?>

	<div class="notification attention"><?php echo $authentication_failed; ?></div>

<?php endif; ?>

<script>
head.ready(function() {
	// Horizontal Check-all
	$('.matrix-title a').click(function(){
		var rows 		= $(this).parents('tr');
		var checked 	= false;
		var found		= false;
		var checkbox	= false;
		
		for (i=0; i < rows.length; i++)
		{
		
			checkbox = $(rows[i]).find(":checkbox");
			
			if(!found && checkbox.length > 0){
				found=true;
				checked = $(checkbox).attr('checked');
			}
			if(checked) {
				checkbox.removeAttr('checked');
			}
			else
			{
				checkbox.attr('checked','checked');
			}
		}
		
		return false;
	});
	
	//--------------------------------------------------------------------
	
	// Vertical Check All
	$('.matrix th a').click(function(){
		var rows 		= $(this).parents('table').find('tbody tr');;
		var checked 	= false;
		var found		= false;
		var checkbox	= false;
		var columnIndex	= $(this).parent('th').attr('cellIndex');
				
		for (i=0; i < rows.length; i++)
		{
			checkbox = $(rows[i]).find('td:eq('+(columnIndex)+')').find(":checkbox");
			
			if(!found && checkbox.length > 0){
				found=true;
				checked = $(checkbox).attr('checked');
			}
			if(checked) {
				checkbox.removeAttr('checked');
			}
			else
			{
				checkbox.attr('checked','checked');
			}
		}
		
		return false;
	});
	
	//--------------------------------------------------------------------
	
});
</script>