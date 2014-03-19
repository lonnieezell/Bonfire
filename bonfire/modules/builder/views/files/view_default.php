<?php

$view = '<?php

$validation_errors = validation_errors();

if ($validation_errors) :
?>
<div class="alert alert-block alert-danger fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<h4 class="alert-heading">
		<?php echo lang("'.$module_name_lower.'_errors_message"); ?>
	</h4>
	<?php echo $validation_errors; ?>
</div>
<?php
endif;

if (isset($' . $module_name_lower . '))
{
	$' . $module_name_lower . ' = (array) $' . $module_name_lower . ';
}
$id = isset($' . $module_name_lower . '[\'' . $primary_key_field . '\']) ? $' . $module_name_lower . '[\'' . $primary_key_field . '\'] : \'\';

?>
<div class="admin-box">
	<h3>' . $module_name . '</h3>
	<?php echo form_open($this->uri->uri_string(), \'class="form-horizontal"\'); ?>
		<fieldset>';

$on_click = '';
$xinha_names = '';

for ($counter = 1; $field_total >= $counter; $counter++) {
	$maxlength = null;

	// Only build on fields that have data entered.
	if (set_value("view_field_label$counter") == null) {
		continue;
	}

    // This should be in the controller
	$validation_rules = $this->input->post('validation_rules'.$counter);

	$field_label = set_value("view_field_label$counter");
	$form_name   = "{$module_name_lower}_" . set_value("view_field_name$counter");
	$field_name  = set_value("view_field_name$counter");
	$field_type  = set_value("view_field_type$counter");

	$required = '';

    // Validation rules for this fieldset
	if (is_array($validation_rules)) {
		foreach ($validation_rules as $key => $value) {
			if ($value == 'required') {
				$required = ". lang('bf_form_label_required')";
			}
		}
	}

	// Type of field
	switch ($field_type) {
		// Some consideration has gone into how these should be implemented
		// I came to the conclusion that it should just setup a mere framework
		// and leave helpful comments for the developer
		// Modulebuilder is meant to have a minimium amount of features.
		// It sets up the parts of the form that are repitive then gets the hell
        // out of the way.
        //
		// This approach maintains these aims/goals

		case 'textarea':
			if ( ! empty($textarea_editor)) {
				// Setup the editor for textareas
				if ($textarea_editor == 'xinha') {
					if ($xinha_names != '') {
						$xinha_names .= ', ';
					}
					$xinha_names .= "'{$field_name}'";
				}
			}

			$view .= PHP_EOL . "
			<div class=\"form-group<?php echo form_error('{$field_name}') ? ' error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<?php echo form_textarea(array('name' => '{$form_name}', 'id' => '{$form_name}', 'rows' => '5', 'cols' => '80', 'value' => set_value('$form_name', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''))); ?>
					<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
				</div>
			</div>";
			break;

		case 'radio':
			$view .= PHP_EOL . "
			<div class=\"form-group<?php echo form_error('{$field_name}') ? ' error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '', array('class' => 'control-label', 'id' => '{$form_name}_label') ); ?>
				<div class='controls' aria-labelled-by='{$form_name}_label'>
					<label class='radio' for='{$form_name}_option1'>
						<input id='{$form_name}_option1' name='{$form_name}' type='radio' class='' value='option1' <?php echo set_radio('{$form_name}', 'option1', TRUE); ?> />
						Radio option 1
					</label>
					<label class='radio' for='{$form_name}_option2'>
						<input id='{$form_name}_option2' name='{$form_name}' type='radio' class='' value='option2' <?php echo set_radio('{$form_name}', 'option2'); ?> />
						Radio option 2
					</label>
					<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
				</div>
			</div>";
			break;

		case 'select':
			// Use CI form helper here as it makes selects/dropdowns easier
			$select_options = array();
			if (set_value("db_field_length_value$counter") != null) {
				$select_options = explode(',', set_value("db_field_length_value$counter"));
			}
			$view .= PHP_EOL . '
			<?php // Change the values in this array to populate your dropdown as required
				$options = array(';
			foreach ($select_options as $key => $option) {
				$view .= '
					' . strip_slashes($option) . ' => ' . strip_slashes($option) . ',';
			}
			$view .= "
				);
				echo form_dropdown('{$form_name}', \$options, set_value('{$form_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''), '{$field_label}'{$required});
			?>";
			break;

		case 'checkbox':
			$view .= PHP_EOL . "
			<div class=\"form-group<?php echo form_error('{$field_name}') ? ' error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<label class='checkbox' for='{$form_name}'>
						<input type='checkbox' id='{$form_name}' name='{$form_name}' value='1' <?php echo (isset(\${$module_name_lower}['{$field_name}']) && \${$module_name_lower}['{$field_name}'] == 1) ? 'checked=\"checked\"' : set_checkbox('{$form_name}', 1); ?>>
						<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
					</label>
				</div>
			</div>";
			break;

		case 'input':
            // no break;
		case('password'):
            // no break;
		default:
            $type = $field_type == 'input' ? 'text' : 'password';
			$db_field_type = set_value("db_field_type$counter");
            $max = set_value("db_field_length_value$counter");
			if ($max != null) {
				if (in_array($db_field_type, $realNumberTypes)) {
					$len = explode(',', $max);
					$max = $len[0];
                    if ( ! empty($len[1])) {
                        $max++; // Add 1 to allow for the decimal point
                    }
				}
                $maxlength = "maxlength='{$max}'";
			}

			$view .= PHP_EOL . "
			<div class=\"form-group<?php echo form_error('{$field_name}') ? ' error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<input id='{$form_name}' type='{$type}' name='{$form_name}' {$maxlength} value=\"<?php echo set_value('{$form_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''); ?>\" />
					<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
				</div>
			</div>";
			break;
	}
}

if ( ! empty($on_click)) {
	$on_click .= '"';
}

$delete = '';
if ($action_name != 'create') {
	$delete_permission = preg_replace("/[ -]/", "_", ucfirst($module_name)) . '.' . ucfirst($controller_name) . '.Delete';
	$delete = '
			<?php if ($this->auth->has_permission(\'' . $delete_permission . '\')) : ?>
				<?php echo lang(\'bf_or\'); ?>
				<button type="submit" name="delete" class="btn btn-danger" id="delete-me" onclick="return confirm(\'<?php e(js_escape(lang(\''.$module_name_lower.'_delete_confirm\'))); ?>\'); ">
					<span class="icon-trash icon-white"></span>&nbsp;<?php echo lang(\'' . $module_name_lower . '_delete_record\'); ?>
				</button>
			<?php endif; ?>';
}

$view .= PHP_EOL . '
        </fieldset>
		<fieldset class="form-actions">
			<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang(\''.$module_name_lower.'_action_'.$action_name.'\'); ?>" '.$on_click.' />
			<?php echo lang(\'bf_or\'); ?>
			<?php echo anchor(SITE_AREA .\'/' . $controller_name . '/' . $module_name_lower . '\', lang(\'' . $module_name_lower . '_cancel\'), \'class="btn btn-warning"\'); ?>
			' . $delete . '
		</fieldset>
    <?php echo form_close(); ?>';

if ($xinha_names != '') {
	$view .= PHP_EOL . '
	<script type="text/javascript">
		var xinha_plugins = [ \'Linker\' ],
			xinha_editors = [ ' . $xinha_names . ' ];

		function xinha_init() {
			if ( ! Xinha.loadPlugins(xinha_plugins, xinha_init)) {
				return;
			}

			var xinha_config = new Xinha.Config();

			xinha_editors = Xinha.makeEditors(xinha_editors, xinha_config, xinha_plugins);
			Xinha.startEditors(xinha_editors);
		}
		xinha_init();
	</script>';
}

$view .= '
</div>';

echo $view;