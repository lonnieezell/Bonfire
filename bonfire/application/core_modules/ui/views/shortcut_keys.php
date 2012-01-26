<?php if (isset($shortcuts) && is_array($shortcuts)): ?>
<?php foreach ($shortcuts as $name => $detail): ?>
<?php if (isset($shortcut_keys) && is_array($shortcut_keys) && isset($shortcut_keys[$name])):?>
jwerty.key('<?php echo $shortcut_keys[$name];?>', function () { <?php echo $detail['action']; ?> return false;});
<?php endif;?>
<?php endforeach; ?>
$( "#shortkeys_dialog" ).dialog({
	autoOpen: false,
	width: 500,
	modal: true
});

$( "#shortkeys_show" ).click(function() {
	$( "#shortkeys_dialog" ).dialog( "open" );
	return false;
});
<?php endif;?>