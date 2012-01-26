<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
	
	<div>
		<p><?php echo lang('ui_keyboard_shortcuts') ?></p>
		<?php if (isset($current) && is_array($current)): ?>
		<ul>
			<?php foreach ($current as $name => $detail): ?>
			<li><?php echo $name;?>: <?php echo $detail['description'];?></li>
			<?php endforeach?>
		</ul>
		<?php else: ?>
			<?php echo lang('ui_no_shortcuts');?>
		<?php endif;?>
		<br/>
		<input type="button" name="add_shortcut" id="add_shortcut" value="<?php echo lang('ui_add_shortcut') ?>" class="button"/>
		<ul id="shortcut_keys" class="clean">
		<?php $count = 1; ?>
		<?php foreach ($settings as $action => $shortcut): ?>
			<li id="shortcut<?php echo $count;?>">
				<?php echo lang('ui_action') ?> <input type="text" name="actions[]" class="medium" value="<?php echo isset($action) ? $action : set_value('actions[0]') ?>" />
				<?php echo lang('ui_shortcut') ?> <input type="text" name="shortcuts[]" class="medium" value="<?php echo isset($shortcut) ? $shortcut : set_value('shortcuts[0]') ?>" />
				<input type="button" name="remove_shortcut" value="<?php echo lang('ui_remove_shortcut') ?>" class="button" onClick="$('#shortcut<?php echo $count;?>').remove(); return false;" />
			</li>
			<?php $count++; ?>
		<?php endforeach; ?>
		</ul>
	</div>

	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') .' '. lang('bf_context_settings') ?>" />
	</div>

<?php echo form_close(); ?>