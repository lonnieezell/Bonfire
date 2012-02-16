<?php if (validation_errors()) : ?>
<div class="notification error">
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string(), 'class="constrained"'); ?>
	<input type="hidden" name="remove_action" id="remove_action" />
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
		<?php $count = 1; ?>
		<ul id="shortcut_keys" class="clean">
			<li id="heading">
				<span class="required medium"><?php echo lang('ui_action') ?></span>
				<span class="required medium"><?php echo lang('ui_shortcut') ?></span>
			</li>
			<li id="shortcut<?php echo $count;?>">
				<select id="action<?php echo $count;?>" name="action<?php echo $count;?>" class="medium">
			<?php foreach ($current as $name => $detail): ?>
			<?php if (!array_key_exists($name, $settings)):?>
			<option value="<?php echo $name;?>"><?php echo $detail['description'];?></option>
			<?php endif;?>
			<?php endforeach?>
				</select> 
				<input type="text" id="shortcut<?php echo $count;?>" name="shortcut<?php echo $count;?>" class="medium" value="<?php echo isset($shortcut) ? $shortcut : set_value('shortcuts['.$count.']') ?>" />
				<input type="submit" name="add_shortcut" id="add_shortcut<?php echo $count;?>" value="<?php echo lang('ui_add_shortcut') ?>" class="button" />
			</li>
		<?php foreach ($settings as $action => $shortcut): ?>
			<?php $count++; ?>
			<li id="shortcut<?php echo $count;?>">
				<input type="text" id="action<?php echo $count;?>" name="action<?php echo $count;?>" class="medium" value="<?php echo isset($action) ? $action : set_value('actions['.$count.']') ?>" />
				<input type="text" id="shortcut<?php echo $count;?>" name="shortcut<?php echo $count;?>" class="medium" value="<?php echo isset($shortcut) ? $shortcut : set_value('shortcuts['.$count.']') ?>" />
				<input type="button" name="remove_shortcut" id="remove_shortcut<?php echo $count;?>" value="<?php echo lang('ui_remove_shortcut') ?>" class="button" />
			</li>
		<?php endforeach; ?>
		</ul>
	</div>

<?php echo form_close(); ?>