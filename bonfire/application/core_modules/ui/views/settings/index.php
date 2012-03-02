<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
	<input type="hidden" name="remove_action" id="remove_action" />
	
	<fieldset>
		<legend><?php echo lang('ui_actions') ?></legend>
		
		<div class="alert alert-info">
			<?php echo lang('ui_keyboard_shortcuts'); ?>
		</div>
		
		<?php if (isset($current) && is_array($current)): ?>
		<table class="table table-striped table-condensed">
			<tbody>
			<?php foreach ($current as $name => $detail): ?>
			<tr>
				<td style="width: 25%"><b><?php echo $name;?></b></td>
				<td><?php echo $detail['description'];?></td>
			</tr>
			<?php endforeach?>
			</tbody>
		</table>
		<?php else: ?>
			
			<div class="alert alert-warning">
				<?php echo lang('ui_no_shortcuts');?>
			</div>
			
		<?php endif;?>
		
		<br/>
	</fieldset>
	
	<fieldset>
		<legend><?php echo lang('ui_shortcuts') ?></legend>
	
		<?php $count = 1; ?>
		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th><?php echo lang('ui_action') ?></th>
					<th><?php echo lang('ui_shortcut') ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<select id="action<?php echo $count;?>" name="action<?php echo $count;?>" class="medium">
						<?php foreach ($current as $name => $detail): ?>
							<?php if (!array_key_exists($name, $settings)):?>
								<option value="<?php echo $name;?>"><?php echo $detail['description'];?></option>
							<?php endif; ?>
						<?php endforeach; ?>
						</select>
					</td>
					<td>
						<input type="text" id="shortcut<?php echo $count;?>" name="shortcut<?php echo $count;?>" class="medium" value="<?php echo isset($shortcut) ? $shortcut : set_value('shortcuts['.$count.']') ?>" />
					</td>
					<td>
						<input type="submit" name="add_shortcut" class="btn" id="add_shortcut<?php echo $count;?>" value="<?php echo lang('ui_add_shortcut') ?>" class="button" />
					</td>
				</tr>
				<?php foreach ($settings as $action => $shortcut): ?>
					<?php $count++; ?>
					<tr id="shortcut<?php echo $count; ?>">
						<td id="shortcut<?php echo $count; ?>">
							<input type="text" id="action<?php echo $count;?>" name="action<?php echo $count;?>"  value="<?php echo isset($action) ? $action : set_value('actions['.$count.']') ?>" />
						</td>
						<td>
							<input type="text" id="shortcut<?php echo $count;?>" name="shortcut<?php echo $count;?>"  value="<?php echo isset($shortcut) ? $shortcut : set_value('shortcuts['.$count.']') ?>" />
						</td>
						<td>
							<input type="button" name="remove_shortcut" id="remove_shortcut<?php echo $count;?>" value="<?php echo lang('ui_remove_shortcut') ?>" class="btn btn-danger" />
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

<?php echo form_close(); ?>