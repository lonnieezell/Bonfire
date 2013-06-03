<?php if (validation_errors()) : ?>
<div class="alert alert-error fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<div class="admin-box">

	<?php echo form_open($this->uri->uri_string(), array('class' => "form-horizontal", 'id' => 'shortcut_form')); ?>
		<div class="alert alert-info fade in">
			<a class="close" data-dismiss="alert">&times;</a>
			<?php echo lang('ui_keyboard_shortcuts'); ?>
		</div>

		<table class="table table-striped table-condensed">
			<thead>
				<tr>
					<th><?php echo lang('ui_action') ?></th>
					<th colspan="2"><?php echo lang('ui_shortcut') ?> <span class="help-inline"><?php echo lang('ui_shortcut_help') ?></span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<select name="new_action" class="medium">
						<?php foreach ($current as $name => $detail): ?>
							<?php if (!array_key_exists($name, $settings)):?>
								<option value="<?php echo $name;?>" <?php echo set_select('new_action', $name);?>><?php echo $detail['description'];?></option>
							<?php endif; ?>
						<?php endforeach; ?>
						</select>
					</td>
					<td>
						<input type="text" name="new_shortcut" class="medium" value="<?php echo set_value('new_shortcut') ?>" />
					</td>
					<td>
						<input type="submit" name="add_shortcut" class="btn" value="<?php echo lang('ui_add_shortcut') ?>"/>
					</td>
				</tr>
				<?php foreach ($settings as $action => $shortcut): ?>
					<tr>
						<td>
							<?php echo $current[$action]['description'] ?>
						</td>
						<td>
							<input type="text" name="shortcut_<?php echo $action;?>" value="<?php echo set_value("shortcut_$action", $shortcut) ?>" />
						</td>
						<td>
							<input type="submit" name="remove_shortcut[<?php echo $action;?>]" value="<?php echo lang('ui_remove_shortcut') ?>" class="btn btn-danger" />
						</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('ui_update_shortcuts') ?>" /></td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

<?php echo form_close(); ?>

</div>
