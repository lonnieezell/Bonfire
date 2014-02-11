<?php
if (isset($domains) && is_array($domains) && count($domains)) :
    foreach ($domains as $domain_name => $fields) :
?>
<table class="matrix table table-striped">
    <thead>
        <tr>
            <th class='domain'><?php echo $domain_name; ?></th>
            <?php foreach ($fields['actions'] as $action) : ?>
            <th class="text-center"><a href="#"><?php echo $action ?></a></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($fields as $field_name => $field_actions) :
            if ($field_name != 'actions') :
        ?>
        <tr>
            <td class="matrix-title"><a href="#"><?php echo $field_name; ?></a></td>
            <?php foreach ($fields['actions'] as $action) : ?>
            <td class="text-center">
                <?php
                if (array_key_exists($action, $field_actions)) :
                    $perm_name = "{$domain_name}.{$field_name}.{$action}";
                    $currentRolePermission = $domains[$domain_name][$field_name][$action];
                ?>
                <input type="checkbox" name="role_permissions[]" class="" value="<?php echo $currentRolePermission['perm_id']; ?>"<?php if (isset($currentRolePermission['value']) && $currentRolePermission['value'] == 1) { echo ' checked="checked"'; } ?> />
                <?php else: ?>
                    <span class="help-inline small"><?php echo lang('role_not_used') ?></span>
                <?php endif; ?>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php
            endif;
        endforeach;
        ?>
    </tbody>
</table>
<?php
    endforeach;
else:
?>
<div class="notification attention"><?php echo $authentication_failed; ?></div>
<?php endif; ?>