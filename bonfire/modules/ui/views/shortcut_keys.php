<?php if (isset($shortcuts) && is_array($shortcuts)): ?>
<?php foreach ($shortcuts as $name => $detail): ?>
<?php if (isset($shortcut_keys) && is_array($shortcut_keys) && isset($shortcut_keys[$name])):?>
jwerty.key('<?php echo js_escape($shortcut_keys[$name]);?>', function () { <?php echo $detail['action']; ?> });
<?php endif;?>
<?php endforeach; ?>
<?php endif;?>