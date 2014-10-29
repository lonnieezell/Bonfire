<?php

/**
 * @var bool[] $currentPerms Local cache of the Manage permissions.
 * For large permission sets, reduces the loading time of the page by ~1/3 or
 * better with no obvious increase in memory used.
 */
$currentPerms = array();
$cols = array();

?>
<script type="text/javascript">
	window.g_permission = '<?php e(lang('matrix_permission')); ?>';
	window.g_role       = '<?php e(lang('matrix_role')); ?>';
	window.g_url		= '<?php echo site_url(SITE_AREA . '/settings/roles/matrix_update'); ?>';
</script>
<div id="permission_table_result" class="alert alert-info fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<?php echo lang('matrix_note'); ?>
</div>
<div class="admin-box">
	<table class="table table-striped" id="permission_table">
		<thead>
			<tr>
				<th><?php echo lang('matrix_permission');?></th>
				<?php
                // Only display roles the current user is permitted to manage
                foreach($matrix_roles as $matrix_role ) :
                    // Cache the Manage permissions
                    $currentPerms["Permissions.{$matrix_role->role_name}.Manage"] = has_permission("Permissions.{$matrix_role->role_name}.Manage");
                    if ($currentPerms["Permissions.{$matrix_role->role_name}.Manage"]) :
                ?>
                <th class="text-center"><?php echo $matrix_role->role_name; ?></th>
				<?php
                    endif;
                    $cols[] = array(
                        'role_id'   => $matrix_role->role_id,
                        'role_name' => $matrix_role->role_name,
                    );
                endforeach;
                ?>
			</tr>
		</thead>
		<tbody>
    		<?php
            $currentUserRoleId = $this->auth->role_id();
            foreach ($matrix_permissions as $matrix_perm) :
                // If the user is admin or has this permission, most of these
                // permissions are only used once, so we don't cache them all,
                // but checking the cache doesn't seem to hurt
                if ($currentUserRoleId == 1
                    || ! empty($currentPerms[$matrix_perm->name])
                    || has_permission($matrix_perm->name)
                   ) :
            ?>
			<tr title="<?php echo $matrix_perm->name; ?>">
				<td><?php echo $matrix_perm->name; ?></td>
				<?php // For each role
				for ($i = 0; $i < count($cols); $i++) :
                    // If the current Manage permission isn't cached, cache it
                    if ( ! isset($currentPerms["Permissions.{$cols[$i]['role_name']}.Manage"])) {
                        $currentPerms["Permissions.{$cols[$i]['role_name']}.Manage"] = has_permission("Permissions.{$cols[$i]['role_name']}.Manage");
                    }
                    // If the user has the permission to manage the role, set
                    // the current value and display the checkbox
					if ($currentPerms["Permissions.{$cols[$i]['role_name']}.Manage"]) :
						$checkbox_value = "{$cols[$i]['role_id']},{$matrix_perm->permission_id}";
						$checked = in_array($checkbox_value, $matrix_role_permissions) ? ' checked="checked"' : '';
                ?>
                <td class="text-center" title="<?php echo $cols[$i]['role_name']; ?>">
                    <input type="checkbox" value="<?php echo $checkbox_value; ?>"<?php echo $checked; ?> title="<?php echo lang('matrix_role') . ": {$cols[$i]['role_name']}, " . lang('matrix_permission') . ": {$matrix_perm->name}"; ?>" />
                </td>
				<?php
                    endif;
                endfor;
                ?>
			</tr>
			<?php
                endif;
            endforeach;
            ?>
		</tbody>
	</table>
</div>