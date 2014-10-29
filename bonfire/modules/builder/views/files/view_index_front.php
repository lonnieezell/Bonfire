<?php

$pager = '';
if ($usePagination) {
    $pager = "
    echo \$this->pagination->create_links();";
}

//------------------------------------------------------------------------------
// Setup the fields to be displayed in the view
//------------------------------------------------------------------------------
$headers = '';
for ($counter = 1; $field_total >= $counter; $counter++) {
	// Only build on fields that have data entered.
	if (set_value("view_field_label$counter") == null) {
		continue; 	// move onto next iteration of the loop
	}

	$headers .= '
            <th>' . set_value("view_field_label$counter") . '</th>';
}

$hiddenFields = "array('{$primary_key_field}',";
if ($useSoftDeletes) {
    $hiddenFields .= " '{$soft_delete_field}',";
    if ($logUser) {
        $hiddenFields .= " '{$deleted_by_field}',";
    }
}
if ($useCreated) {
	$headers .= "
            <th><?php echo lang('{$module_name_lower}_column_created'); ?></th>";
    if ($logUser) {
        $hiddenFields .= " '{$created_by_field}',";
    }
}
if ($useModified) {
	$headers .= "
            <th><?php echo lang('{$module_name_lower}_column_modified'); ?></th>";
    if ($logUser) {
        $hiddenFields .= " '{$modified_by_field}',";
    }
}

$hiddenFields .= ")";

//------------------------------------------------------------------------------
// Output the view
//------------------------------------------------------------------------------
echo "<?php

\$hiddenFields = {$hiddenFields};
?>
<h1 class='page-header'>
    <?php echo lang('{$module_name_lower}_area_title'); ?>
</h1>
<?php if (isset(\$records) && is_array(\$records) && count(\$records)) : ?>
<table class='table table-striped table-bordered'>
    <thead>
        <tr>
            {$headers}
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (\$records as \$record) :
        ?>
        <tr>
            <?php
            foreach(\$record as \$field => \$value) :
                if ( ! in_array(\$field, \$hiddenFields)) :
            ?>
            <td>
                <?php
                if (\$field == 'deleted') {
                    e((\$value > 0) ? lang('{$module_name_lower}_true') : lang('{$module_name_lower}_false'));
                } else {
                    e(\$value);
                }
                ?>
            </td>
            <?php
                endif;
            endforeach;
            ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
{$pager}
endif; ?>";