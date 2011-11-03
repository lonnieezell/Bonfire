<?php foreach ($shortcuts as $name => $detail): ?>
<?php if (isset($shortcut_keys[$name])):?>
jwerty.key('<?php echo $shortcut_keys[$name];?>', function () { <?php echo $detail['action']; ?> return false;});
<?php endif;?>
<?php endforeach; ?>
