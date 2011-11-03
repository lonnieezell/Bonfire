$('input#add_shortcut').click(function(id) {
	var current = $('ul#shortcut_keys').children().length;
	current++;
 	$('ul#shortcut_keys').append('<li id="shortcut' + current + '"><?php echo lang('ui_action') ?> <input type="text" name="actions[]" class="medium" value="" /> <?php echo lang('ui_shortcut') ?> <input type="text" name="shortcuts[]" class="medium" value="" /> <input type="button" name="remove_shortcut" value="<?php echo lang('ui_remove_shortcut') ?>" class="button" onClick=\'$("#shortcut' + current + '").remove(); return false;\'/></li>');
});

